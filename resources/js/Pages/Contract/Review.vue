<template>
    <app-layout>
        <template #header>
            <h2 class="font-light text-4xl text-secondary leading-tight">
                Revisar Contrato
            </h2>
            <p class="py-4">
                Tienes el contrato {{ contract.code }} para tu revisión
            </p>
        </template>

        <div class="pb-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <validation-errors />

                <div class="w-full flex justify-between flex-wrap">
                    <div class="w-full p-4">
                        <p class="mb-4">Lista de Documentos :</p>

                        <a
                            v-for="(document, index) in contract.documents"
                            :key="index"
                            :href="`/storage/${document.path}`"
                            download
                            class="text-secondary underline"
                        >
                            {{ document.file_name }}
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 inline"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="w-full p-4">
                    <span class="mr-12">¿Tienes observaciones?</span>

                    <div class="inline-flex items-center mr-4">
                        <input
                            id="radio1"
                            type="radio"
                            name="obs"
                            class="hidden"
                            :value="true"
                            v-model="form.obs"
                        />
                        <label
                            for="radio1"
                            class="flex items-center cursor-pointer text-base"
                        >
                            Si
                            <span
                                class="
                                    w-5
                                    h-5
                                    inline-block
                                    ml-2
                                    rounded-full
                                    border border-black
                                    flex-no-shrink
                                "
                            ></span>
                        </label>
                    </div>

                    <div class="inline-flex items-center">
                        <input
                            id="radio2"
                            type="radio"
                            name="obs"
                            class="hidden"
                            :value="false"
                            v-model="form.obs"
                        />
                        <label
                            for="radio2"
                            class="flex items-center cursor-pointer text-base"
                        >
                            No
                            <span
                                class="
                                    w-5
                                    h-5
                                    inline-block
                                    ml-2
                                    rounded-full
                                    border border-black
                                    flex-no-shrink
                                "
                            ></span>
                        </label>
                    </div>
                </div>

                <div class="w-full p-4" v-show="form.obs">
                    <p class="mb-2">Escribe tus observaciones</p>
                    <jet-text-area class="w-full" placeholder="Escribe aqui.." v-model="form.message"/>
                </div>

                <div class="w-full py-4 flex justify-center">
                    <jet-button
                        class="text-lg"
                        @click="submitForm"
                        :disabled="!canSubmit"
                        >{{
                            form.obs
                                ? "Enviar Observaciones"
                                : "Aprobar Contrato"
                        }}</jet-button
                    >
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import ValidationErrors from "../../Jetstream/ValidationErrors.vue";
import { useForm } from "@inertiajs/inertia-vue3";
import { computed } from '@vue/runtime-core';
import JetButton from "@/Jetstream/Button";
import JetTextArea from "@/Jetstream/Textarea";


export default {
    props: ["contract"],
    components: {
        AppLayout,
        ValidationErrors,
        JetButton,
        JetTextArea
    },
    setup(props) {
        const form = useForm({
            obs: false,
            message: null,
        });

        
        const canSubmit = computed(() => {
            if (!form.obs) {
                return true;
            } else if (form.obs && (form.message && form.message !== "")) {
                return true;
            } else {
                return false;
            }
        });

         const submitForm = async () => {

            form.patch(route("contract.review.update",{contract : props.contract.id}), {
                onError: () => {
                    form.reset();
                },
            });
        };

        return {
            form,
            canSubmit,
            submitForm,
        };
    },
};
</script>
<style>
input[type="radio"]:checked + label span {
    background-color: #bc8158;
    box-shadow: 0px 0px 0px 2px white inset;
}
</style>
