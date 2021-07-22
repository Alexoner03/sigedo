<template>
    <div ref="wrapper"></div>
</template>

<script>
import { Grid, h } from "gridjs";
import { esES } from "gridjs/dist/gridjs.lang.es";
import { onMounted, ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { usePage } from '@inertiajs/inertia-vue3'


export default {
    components: {
        Grid,
    },
    props: {
        contracts: {
            type: Array,
            required: true,
        },
    },
    emits: ["openModal"],
    setup(props, { emit }) {
        const grid = ref(null);
        const wrapper = ref(null);

        const user = usePage().props.value.user

        onMounted(() => {
            grid.value = new Grid({
                columns: [
                    {
                        id: "id",
                        name: "Id",
                        hidden: true,
                        data: (row) => row.id,
                    },
                    {
                        id: "id_assigned",
                        name: "assigned",
                        hidden: true,
                        data: (row) => row.id_user_assigned,
                    },
                    {
                        id: "id_creator",
                        name: "creator",
                        hidden: true,
                        data: (row) => row.id_user_creator,
                    },
                    {
                        id: "code",
                        name: "Codigo",
                        data: (row) => row.code,
                    },
                    {
                        id: "state",
                        name: "Estado",
                        data: (row) => row.state,
                    },
                    {
                        id: "finalize",
                        hidden : true,
                        name: "Finalizado",
                        data: (row) => row.term_date,
                    },
                    {
                        id: "request_date",
                        name: "Fec. Solicitud",
                        data: (row) => row.request_date,
                    },
                    {
                        id: "business",
                        name: "Cliente",
                        data: (row) => row.business.business_name,
                    },
                    {
                        id: "user_creator",
                        name: "Registrado Por:",
                        data: (row) => row.user_creator.name,
                    },
                    {
                        id: "user_assigned",
                        name: "Asignado a:",
                        data: (row) => row.user_assigned.name,
                    },
                    {
                        id: "contract_type",
                        name: "Tipo Contrato",
                        data: (row) => row.contract_type?.description ?? 'No definido',
                    },
                    {
                        name: "Detalles",
                        formatter: (cell, row, col) => {
                            return [
                                h(
                                    "button",
                                    {
                                        onClick: () =>
                                            openModal(row.cell(0).data, "documents"),
                                        className: "contract-button",
                                    },
                                    "Ver Documentos"
                                ),
                                h(
                                    "button",
                                    {
                                        onClick: () =>
                                            openModal(row.cell(0).data, "reviewers"),
                                        className: "contract-button",
                                    },
                                    "Ver Revisores"
                                ),
                                h(
                                    "button",
                                    {
                                        onClick: () =>
                                            openModal(row.cell(0).data, "records"),
                                        className: "contract-button",
                                    },
                                    "Ver Hitos"
                                ),
                                h(
                                    "button",
                                    {
                                        onClick: () =>
                                            openModal(row.cell(0).data, "details"),
                                        className: "contract-button",
                                    },
                                    "Ver Detalles"
                                ),
                            ];
                        },
                    },
                    {
                        name: "Acciones",
                        formatter: (cell, row, col) => {
                            return [
                                user.id === row.cell(1).data && user.id !== row.cell(2).data ? 
                                h(
                                    "button",
                                    {
                                        onClick: () => review(row.cell(0).data),
                                        className: "contract-button-edit",
                                    },
                                    "Revisar"
                                ) : undefined,
                                user.id === row.cell(2).data && user.id === row.cell(1).data && row.cell(4).data !== 'archivado' ? 
                                h(
                                    "button",
                                    {
                                        onClick: () =>
                                            correct(row.cell(0).data),
                                        className: "contract-button-edit",
                                    },
                                    "Corregir"
                                ) : undefined,
                                user.business_id === 1 && row.cell(5).data === null ? 
                                h(
                                    "button",
                                    {
                                        onClick: () =>
                                            finalize(row.cell(0).data),
                                        className: "contract-button-delete",
                                    },
                                    "Finalizar"
                                ) : undefined,
                            ];
                        },
                    },
                ],
                data: props.contracts,
                style: {
                    table: {
                        "white-space": "nowrap",
                    },
                },
                fixedHeader: true,
                pagination: {
                    enabled: true,
                    limit: 5,
                    summary: false,
                },
                search: {
                    enabled: true,
                },
                language: esES,
            }).render(wrapper.value);
        });

        const openModal = (idContract, type) => {
            emit("openModal", { idContract, type });
        };

        const review = (contract) => {
            Inertia.visit(route("contract.review", { contract }));
        };
        const correct = (contract) => {
            Inertia.visit(route("contract.correct", { contract }));
        };

        const finalize = (contract) => {
            Inertia.visit(route("contract.finalize", { contract }));
        };

        return {
            wrapper,
            grid,
        };
    },
};
</script>
