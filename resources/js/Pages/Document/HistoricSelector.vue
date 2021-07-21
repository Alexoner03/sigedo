<template>
    <app-layout>
        <template #header>
            <h2 class="font-light text-4xl text-secondary leading-tight">
                Historial de Documentos
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="w-full flex flex-col justify-center">
                    <div v-if="!($page.props.user.role_id === 3)">
                        Documentos creados :
                    </div>

                    <document-table
                        v-if="!($page.props.user.role_id === 3)"
                        :documents="documentsCreated"
                        :created="true"
                        @open-detail="openModalFn"
                    />

                    <div>Documentos que me asignaron :</div>

                    <document-table
                        :documents="documentsAssigned"
                        :assigned="true"
                        @open-detail="openModalFn"
                    />

                    <div class="flex justify-center">
                        <inertia-link :href="route('document.welcome')"
                            ><jet-button :outline="true" class="py-3">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-3 w-3 mr-3"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 19l-7-7 7-7"
                                    />
                                </svg>
                                Volver al Home
                            </jet-button>
                        </inertia-link>
                    </div>
                </div>
            </div>
        </div>
        <transition-root appear :show="openModal" as="template">
            <modal-detail @onClose="toggleModalSuccess" :document="document" />
        </transition-root>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import JetLabel from "@/Jetstream/Label";
import JetInput from "@/Jetstream/Input";
import JetButton from "@/Jetstream/Button";
import DocumentTable from "@/Pages/Document/components/DocumentTable";
import ModalDetail from "./components/ModalDetail.vue";
import { ref } from "@vue/reactivity";
import {
  TransitionRoot,
} from '@headlessui/vue'

export default {
    name: "DocumentHistoricSelector",
    components: {
        AppLayout,
        JetLabel,
        JetInput,
        JetButton,
        DocumentTable,
        ModalDetail,
        TransitionRoot
    },
    props: ["documentsCreated", "documentsAssigned"],
    setup() {
        const openModal = ref(false);
        const document = ref(null);

        const toggleModalSuccess = () => {
            openModal.value = !openModal.value;
        };
        const openModalFn = (_document) => {
            document.value = _document;
            toggleModalSuccess();
        };

        return {
            toggleModalSuccess,
            openModal,
            openModalFn,
            document,
        };
    },
};
</script>
