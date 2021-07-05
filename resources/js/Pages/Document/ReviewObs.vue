<template>
    <app-layout>
        <template #header>
            <h2 class="font-light text-4xl text-secondary leading-tight">
                Administrador de Documentos
            </h2>
            <p class="py-4">Tienes el documento @xxxx yyy zzzz con observaciones</p>
        </template>

        <div>
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="w-full flex justify-between flex-wrap">

                    <div class="w-full p-4 text-secondary">
                        <a href="#">
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
                        <p class="mb-2">Carga el documento con los ajustes aplicados</p>
                        <jet-label
                            for="file"
                            value="Subir Documento"
                            class="mb-2"
                        />
                        <div v-bind="getRootProps()" class="w-full border-2 border-dashed rounded-md border-metalgray flex justify-center items-center h-40">
                            <input v-bind="getInputProps()" type="file" accept=".xlsx,.docx,.pptx,application/pdf,officedocument.presentationml.presentation,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.openxmlformats-officedocument.wordprocessingml.documents">
                            <div class="w-full flex flex-col items-center justify-center">
                                <UploadIcon class="w-8 h-8 mb-2 text-secondary"/>
                                <p class="text-secondary" v-if="isDragActive">Suelta tu archivo aquí</p>
                                <p class="text-secondary" v-else>Arrastra tu documento o dale click aquí</p>
                                <small>Peso máximo 3mb</small>
                                <small v-if="isDragReject" class="text-red-500 text-md">Este Archivo no será admitido</small>
                                <small v-if="isDragAccept" class="text-green-500 text-md">Este Archivo será admitido</small>
                            </div>
                        </div>
                        <div class="py-4" v-if="acceptedFiles.length > 0">
                            <p class="mb-2">Archivo a subir: </p>
                            <div class="flex">{{acceptedFiles[0].name}} <XIcon class="ml-4 h-6 w-6 text-red-500 cursor-pointer" @click="clearFile"/> </div>
                        </div>
                    </div>

                    <div class="w-full py-4 flex justify-center">
                        <jet-button class="text-lg" @click="toggleModalSuccess">Devolver Documento</jet-button>
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
import { reactive, ref } from "vue";

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
    ModalSuccess
},
setup() {
    const date = reactive(new Date());
    const codigo = ref(null);
    const file = ref(null);
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
        ]
    });

    const clearFile = () => {
        rest.acceptedFiles.value = [];
    };

    const toggleModalSuccess = () => {
        openModal.value = !openModal.value;
    };

    return {
        openModal,
        toggleModalSuccess,
        date,
        codigo,
        file,
        getRootProps,
        getInputProps,
        clearFile,
        ...rest,
    };
}
};
</script>
