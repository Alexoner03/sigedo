<template>
    <app-layout>
        <template #header>
            <h2 class="font-light text-4xl text-secondary leading-tight">
                Finalizar Contrato
            </h2>
        </template>
        

        <div class="pb-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <validation-errors />

                <p class="px-4 font-bold text-red-500 text-sm mb-4"  v-if="contract.state === 'en proceso'" >*ATENCIÓN : EL CONTRATO AUN NO HA SIDO REVISADO POR TODAS LOS RESPONSABLES <br> SI FINALIZA TODAS LAS REVISIONES SERÁN APROBADAS</p>
                <p class="px-4 font-bold">Información del Contrato:</p>

                <div class="w-full flex px-4 py-2">
                    <div class="w-1/2 font-bold">Codigo</div>
                    <div class="w-1/2">{{ contract.code }}</div>
                </div>
                <div class="w-full flex px-4 py-2">
                    <div class="w-1/2 font-bold">Cliente</div>
                    <div class="w-1/2">
                        {{ contract.business.business_name }}
                    </div>
                </div>
                <div class="w-full flex px-4 py-2">
                    <div class="w-1/2 font-bold">Registrado por</div>
                    <div class="w-1/2">{{ contract.user_creator.name }}</div>
                </div>
                <div class="w-full flex px-4 py-2">
                    <div class="w-1/2 font-bold">Fecha de Solicitud</div>
                    <div class="w-1/2">{{ contract.created_at }}</div>
                </div>
                <div class="w-full flex px-4 py-2">
                    <div class="w-1/2 font-bold">Estado</div>
                    <div class="w-1/2">{{ contract.state }}</div>
                </div>

                <div class="w-full p-4 mb-4">
                    <p class="mb-2">Selecciona un tipo de contrato</p>
                    <select
                        v-model="selectedCategory"
                        class="
                            form-select
                            w-full
                            bg-transparent
                            rounded
                            focus:border-secondary
                            focus:ring
                            focus:ring-secondary
                            focus:ring-opacity-50
                            uppercase
                        "
                    >
                        <option
                            :value="category"
                            v-for="(category, index) in categories"
                            :key="index"
                        >
                            {{ category.description }}
                        </option>
                    </select>
                </div>

                <div class="w-full p-4 mb-4">
                    <p class="mb-2">Objetivos del contrato</p>
                    <jet-text-area
                        class="w-full"
                        placeholder="Escribe aquí.."
                        v-model="form.objective"
                    />
                </div>
                <div class="w-full p-4 mb-4">
                    <p class="mb-2">Causales de resolución</p>
                    <jet-text-area
                        class="w-full"
                        placeholder="Escribe aquí.."
                        v-model="form.resolution"
                    />
                </div>
                <div class="w-full p-4 mb-4">
                    <p class="mb-2">Observaciones</p>
                    <jet-text-area
                        class="w-full"
                        placeholder="Escribe aquí.."
                        v-model="form.observations"
                    />
                </div>

                <div class="w-full py-4 flex justify-center">
                    <jet-button
                        v-if="contract.state === 'en proceso'"
                        class="text-lg"
                        @click="submitForm('contract.finalize.update.force')"
                        :disabled="!canSubmit"
                        >Forzar Finalizar Contrato</jet-button
                    >
                    <jet-button
                        v-else
                        class="text-lg"
                        @click="submitForm('contract.finalize.update')"
                        :disabled="!canSubmit"
                        >Finalizar Contrato</jet-button
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
import { computed, ref } from "@vue/runtime-core";
import JetButton from "@/Jetstream/Button";
import JetTextArea from "@/Jetstream/Textarea";

export default {
    props: ["contract", "categories"],
    components: {
        AppLayout,
        ValidationErrors,
        JetButton,
        JetTextArea,
    },
    setup(props) {
        const selectedCategory = ref(props.categories[0]);

        const form = useForm({
            objective: null,
            resolution: null,
            observations: null,
            contract_type_id: null,
        });

        const canSubmit = computed(() => {
            return (
                form.objective &&
                form.objective !== "" &&
                form.resolution &&
                form.resolution !== "" &&
                form.observations &&
                form.observations !== ""
            );
        });

        const submitForm = async (routeName) => {
            form.contract_type_id = selectedCategory.value.id;

            form.patch(
                route(routeName, {
                    contract: props.contract.id,
                }),
                {
                    onError: () => {
                        form.reset();
                    },
                }
            );
        };

        return {
            form,
            canSubmit,
            submitForm,
            selectedCategory,
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
