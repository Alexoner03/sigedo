<template>
    <app-layout>
        <template #header>
            <h2 class="font-light text-4xl text-secondary leading-tight">
                Administrador de Documentos
            </h2>
            <p class="py-4">
                Tienes el documento {{ document.codigo }} para tu revisión
            </p>
        </template>

        <div>
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                                            <validation-errors />

                <div class="w-full flex justify-between flex-wrap">
                    <div class="w-full p-4 text-secondary">
                        <a :href="`/storage/${document.path}`" download>
                            Descargar el documento aquí
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
                                class="
                                    flex
                                    items-center
                                    cursor-pointer
                                    text-base
                                "
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
                                class="
                                    flex
                                    items-center
                                    cursor-pointer
                                    text-base
                                "
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
                        <p class="mb-2">
                            Carga el documento con tus observaciones
                        </p>
                        <jet-label
                            for="file"
                            value="Subir Documento"
                            class="mb-2"
                        />
                        <div
                            v-bind="getRootProps()"
                            class="
                                w-full
                                border-2 border-dashed
                                rounded-md
                                border-metalgray
                                flex
                                justify-center
                                items-center
                                h-40
                            "
                        >
                            <input
                                v-bind="getInputProps()"
                                type="file"
                                accept=".xlsx,.docx,.pptx,application/pdf,officedocument.presentationml.presentation,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.openxmlformats-officedocument.wordprocessingml.documents"
                            />
                            <div
                                class="
                                    w-full
                                    flex flex-col
                                    items-center
                                    justify-center
                                "
                            >
                                <UploadIcon
                                    class="w-8 h-8 mb-2 text-secondary"
                                />
                                <p class="text-secondary" v-if="isDragActive">
                                    Suelta tu archivo aquí
                                </p>
                                <p class="text-secondary" v-else>
                                    Arrastra tu documento o dale click aquí
                                </p>
                                <small>Peso máximo 3mb</small>
                                <small
                                    v-if="isDragReject"
                                    class="text-red-500 text-md"
                                    >Este Archivo no será admitido</small
                                >
                                <small
                                    v-if="isDragAccept"
                                    class="text-green-500 text-md"
                                    >Este Archivo será admitido</small
                                >
                            </div>
                        </div>
                        <div class="py-4" v-if="acceptedFiles.length > 0">
                            <p class="mb-2">Archivo a subir:</p>
                            <div class="flex">
                                {{ acceptedFiles[0].name }}
                                <XIcon
                                    class="
                                        ml-4
                                        h-6
                                        w-6
                                        text-red-500
                                        cursor-pointer
                                    "
                                    @click="clearFile"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="w-full py-4 flex justify-center">
                        <jet-button
                            class="text-lg"
                            @click="submitForm"
                            :disabled="!canSubmit"
                            >{{
                                form.obs
                                    ? "Enviar Observaciones"
                                    : "Aprobar Documento"
                            }}</jet-button
                        >
                    </div>
                </div>
            </div>
        </div>

        <transition-root appear :show="openModal" as="template">
            <modal-success @onClose="toggleModalSuccess" />
        </transition-root>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import JetLabel from "@/Jetstream/Label";
import JetInput from "@/Jetstream/Input";
import JetButton from "@/Jetstream/Button";
import ModalSuccess from "@/Pages/Document/components/ModalSuccess";
import {
    Listbox,
    ListboxLabel,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
    TransitionRoot,
} from "@headlessui/vue";
import { CheckIcon, SelectorIcon } from "@heroicons/vue/solid";
import { UploadIcon, XIcon } from "@heroicons/vue/outline";
import { useDropzone } from "vue3-dropzone";
import { ref, computed } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import ValidationErrors from '../../Jetstream/ValidationErrors.vue';


export default {
    name: "DocumentReview",
    components: {
        AppLayout,
        JetLabel,
        JetInput,
        JetButton,
        Listbox,
        ListboxLabel,
        ListboxButton,
        ListboxOptions,
        ListboxOption,
        CheckIcon,
        SelectorIcon,
        UploadIcon,
        XIcon,
        TransitionRoot,
        ModalSuccess,
        ValidationErrors
    },
    props: ["document"],
    setup(props) {
        const openModal = ref(false);
        const { getRootProps, getInputProps, ...rest } = useDropzone({
            accept: [
                ".xlsx",
                ".docx",
                ".pptx",
                "application/pdf",
                "officedocument.presentationml.presentation",
                "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                "application/vnd.openxmlformats-officedocument.presentationml.presentation",
                "application/vnd.openxmlformats-officedocument.wordprocessingml.documents",
                "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            ],
            onDropAccepted: (acceptedFiles) => (form.file = acceptedFiles[0]),
        });

        const clearFile = () => {
            rest.acceptedFiles.value = [];
            form.file = null;
        };

        const toggleModalSuccess = () => {
            openModal.value = !openModal.value;
        };

        const canSubmit = computed(() => {
            if (!form.obs) {
                return true;
            } else if (form.obs && form.file !== null) {
                return true;
            } else {
                return false;
            }
        });

        const form = useForm({
            obs: false,
            file: null,
        });

        const submitForm = async () => {

            form.post(route("document.update.reviewer",{document : props.document.id}), {
                onError: () => {
                    form.reset();
                    clearFile();
                },
            });
        };

        return {
            form,
            submitForm,
            openModal,
            canSubmit,
            toggleModalSuccess,
            getRootProps,
            getInputProps,
            clearFile,
            ...rest,
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
