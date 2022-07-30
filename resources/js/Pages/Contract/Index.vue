<template>
    <app-layout>
        <template #header>
            <h2 class="font-light text-4xl text-secondary leading-tight">
                Mi Historial Documentos
            </h2>
        </template>

        <div class="pb-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <contract-table
                    :contracts="contracts"
                    @openModal="listenEvents"
                />
            </div>
        </div>

        <!-- modals -->
        <modal :show="open" @close="open = false">
            <template #title>
                {{ types[type] }}
            </template>

            <template #content>
                <div v-if="type === 'reviewers'" class="listDetails">
                    <p v-for="(user, index) in contract.users" :key="index">
                        {{ index + 1 }}.- {{ user.name }} -
                        {{ user.position.description }} -
                        {{ user.business.business_name }}
                        <span class="float-right">{{
                            user.pivot.check === 1 ? "Aprobado" : "Pendiente"
                        }}</span>
                    </p>
                </div>
                <div v-else-if="type === 'details'" class="listDetails">
                    <p>
                        <span>Cliente</span>
                        <span class="float-right">{{
                            contract.business.business_name
                        }}</span>
                    </p>
                    <p>
                        <span>Codigo</span>
                        <span class="float-right">{{ contract.code }}</span>
                    </p>
                    <p>
                        <span>Tipo de Contrato</span>
                        <span class="float-right">{{
                            contract.contract_type?.description ?? "No definido"
                        }}</span>
                    </p>
                    <p>
                        <span>Registrado por</span>
                        <span class="float-right">{{
                            contract.user_creator.name
                        }}</span>
                    </p>
                    <p class="flex justify-between">
                        <span>Observaciones</span>
                        <span class="float-right inline-block w-1/2 break-all text-right">{{
                            contract.observations ?? "No definido"
                        }}</span>
                    </p>
                    <p class="flex justify-between">
                        <span>Objetivo</span>
                        <span class="float-right inline-block w-1/2 break-all text-right">{{
                            contract.objective ?? "No definido"
                        }}</span>
                    </p>
                    <p class="flex justify-between">
                        <span>Causales de Resolución</span>
                        <span class="float-right inline-block w-1/2 break-all text-right">{{
                            contract.resolution ?? "No definido"
                        }}</span>
                    </p>
                    <p>
                        <span>Estado</span>
                        <span class="float-right">{{ contract.state }}</span>
                    </p>
                    <p>
                        <span>Registrado</span>
                        <span class="float-right">{{
                            contract.request_date
                        }}</span>
                    </p>
                    <p>
                        <span>Finalizado</span>
                        <span class="float-right">{{
                            contract.term_date ?? "Pendiente"
                        }}</span>
                    </p>
                </div>
                <div v-else-if="type === 'documents'">
                    <p
                        v-for="(document, index) in contract.documents"
                        :key="index"
                    >
                        <a :href="`/storage/${document.path}`" target="_blank" class="underline"
                            >{{document.file_name}}</a
                        >
                        <span class="float-right">{{ document.is_main === 1 ? 'Principal' : 'Anexo' }}</span>
                    </p>
                </div>
                <div v-else class="listDetails">
                    <p v-for="(record, index) in contract.records" :key="index">
                        {{ record.description }}

                        <span class="float-right">{{ record.created_at }}</span>
                    </p>
                </div>
            </template>

            <template #footer>
                <button
                    class="rounded bg-primary py-1 px-2 text-white"
                    @click="open = false"
                >
                    Cerrar
                </button>
            </template>
        </modal>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import ContractTable from "@/Pages/Contract/components/ContractTable";
import Modal from "@/Jetstream/DialogModal";
import { reactive, ref, toRefs } from "@vue/reactivity";

export default {
    props: ["contracts"],
    components: {
        AppLayout,
        ContractTable,
        Modal,
    },
    setup(props) {
        const types = ref({
            reviewers: "Lista de revisores",
            details: "Lista de Detalles",
            records: "Lista de Hitos",
            documents: "Lista de Documentos",
        });

        const modal = reactive({
            open: false,
            type: "",
            contract: null,
        });

        const listenEvents = (event) => {
            const { idContract, type } = event;

            const contract = props.contracts.find(
                (contract) => contract.id === idContract
            );
            modal.contract = contract;
            modal.type = type;
            modal.open = true;
        };

        return {
            ...toRefs(modal),
            types,

            listenEvents,
        };
    },
};
</script>

<style>
.listDetails p {
    border-bottom: 1px solid rgba(123, 123, 123, 0.535);
    margin-bottom: 1rem;
}
</style>
