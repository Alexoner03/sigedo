<template>
    <app-layout>
        <template #header>
            <h2 class="font-light text-4xl text-secondary leading-tight">
                Revisar Contrato
            </h2>
            <p class="py-4">
                Tienes el contrato {{ contract.code }} con observaciones
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
                            {{ document.is_main === 1 ? "PRINCIPAL" : "ANEXO" }}
                            : {{ document.file_name }}
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
                    <p class="mb-2">
                        Carga el documento <b>principal</b> con los ajustes aplicados
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
                            <UploadIcon class="w-8 h-8 mb-2 text-secondary" />
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
                    <small
                        >*El nombre del archivo se mantendrá como el
                        original</small
                    >
                    <div class="py-4" v-if="acceptedFiles.length > 0">
                        <p class="mb-2">Archivo a subir:</p>
                        <div class="flex">
                            {{ acceptedFiles[0].name }}
                            <XIcon
                                class="ml-4 h-6 w-6 text-red-500 cursor-pointer"
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
                        >Subir Correcciones</jet-button
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
import { computed } from "@vue/runtime-core";
import JetButton from "@/Jetstream/Button";
import JetLabel from "@/Jetstream/Label";
import { UploadIcon, XIcon } from "@heroicons/vue/outline";
import { useDropzone } from "vue3-dropzone";

export default {
    props: ["contract"],
    components: {
        AppLayout,
        ValidationErrors,
        JetButton,
        JetLabel,
        UploadIcon,
        XIcon,
    },
    setup(props) {
        const form = useForm({
            file: null,
        });

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

        const canSubmit = computed(() => !!form.file);

        const submitForm = async () => {
            form.post(
                route("contract.correct.update", {
                    contract: props.contract.id,
                }),
                {
                    onError: () => {
                        form.reset();
                        clearFile();
                    },
                }
            );
        };

        return {
            form,
            canSubmit,
            submitForm,
            getRootProps,
            getInputProps,
            clearFile,
            ...rest,
        };
    },
};
</script>
