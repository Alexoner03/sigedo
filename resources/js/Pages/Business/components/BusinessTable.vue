<template>
    <div ref="wrapper"></div>
</template>

<script>
import { Grid, h, html } from "gridjs";
import { esES } from "gridjs/dist/gridjs.lang.es";
import { onMounted, ref } from "vue";
import { Inertia } from "@inertiajs/inertia";

export default {
    components: {
        Grid,
    },
    props: {
        businesses: {
            type: Array,
            required: true,
        },
    },
    emits: ["onDelete"],
    setup(props, { emit }) {
        const grid = ref(null);
        const wrapper = ref(null);

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
                        id: "business_name",
                        name: "Razón social",
                        data: (row) => row.business_name,
                    },
                    {
                        id: "ruc",
                        name: "RUC",
                        data: (row) => row.ruc,
                    },
                    {
                        id: "address",
                        name: "Dirección",
                        data: (row) => row.address,
                    },
                    {
                        id: "contact_number",
                        name: "Nro. Contacto",
                        data: (row) => row.contact_number,
                    },
                    {
                        id: "heading_id",
                        name: "Rubro",
                        data: (row) => row.heading.description,
                    },
                    {
                        id: "created_at",
                        name: "Registrado",
                        data: (row) => row.created_at,
                    },
                    {
                        name: "Acciones",
                        formatter: (cell, row, col) => {
                            return [
                                h(
                                    "button",
                                    {
                                        onClick: () => toEdit(row.cell(0).data),
                                        className:
                                            "md:mr-4 inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition",
                                    },
                                    "Editar"
                                ),
                                // h(
                                //     "button",
                                //     {
                                //         onClick: () =>
                                //             sendDelete(row.cell(0).data),
                                //         className:
                                //             "inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-600 disabled:opacity-25 transition",
                                //     },
                                //     "Eliminar"
                                // ),
                            ];
                        },
                    },
                ],
                data: props.businesses,
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

        const sendDelete = (idC) => {
            emit("onDelete", idC);
        };

        const toEdit = (business) => {
            Inertia.visit(route("business.edit", { business }));
        };

        return {
            wrapper,
            grid,
        };
    },
};
</script>
