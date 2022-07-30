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
        users: {
            type: Array,
            required: true,
        },
    },
    emits: ["onDelete","onRestore"],
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
                        id: "photo",
                        name: "Foto",
                        data: (row) => row.profile_photo_url,
                        formatter: (cell) =>
                            html(`<div class="flex items-center space-x-3">
                            <div class="inline-flex w-10 h-10">
                                <img class='w-10 h-10 object-cover rounded-full' alt='User avatar' src='${cell}'>
                            </div>
                        </div>`),
                    },
                    {
                        id: "name",
                        name: "Nombres",
                        data: (row) => row.name,
                        minWidth: 250,
                    },
                    {
                        id: "email",
                        name: "Correo Eléctronico",
                        data: (row) => row.email,
                    },
                    {
                        id: "empresa",
                        name: "Empresa",
                        data: (row) => row.business.business_name,
                    },
                    {
                        id: "cargo",
                        name: "Área",
                        data: (row) => row.position.description,
                    },
                    {
                        id: "created_at",
                        name: "Registrado",
                        data: (row) => row.created_at,
                    },
                    {
                        id: "dni",
                        name: "DNI",
                        data: (row) => row.dni,
                    },
                    {
                        id: "is_admin",
                        name: "Rol",
                        data: (row) => row.role.description,
                        formatter: (cell) => cell.toUpperCase(),
                    },
                    {
                        id: "is_active",
                        name: "Estado",
                        data: (row) => row.state,
                        formatter: (cell) => !!cell ? 'ACTIVO' : 'INACTIVO',
                    },
                    {
                        id: "supervisor",
                        name: "Supervisor",
                        data: (row) => row.supervisor_to_report?.name || 'No Aplica'
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
                                !!row.cell(9).data
                                ? h(
                                    "button",
                                    {
                                        onClick: () =>
                                            sendDelete(row.cell(0).data),
                                        className:
                                            "inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-600 disabled:opacity-25 transition",
                                    },
                                    "Dar de baja"
                                )
                                : h(
                                    "button",
                                    {
                                        onClick: () =>
                                            sendRestore(row.cell(0).data),
                                        className:
                                            "inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 active:bg-green-600 disabled:opacity-25 transition",
                                    },
                                    "Restaurar"
                                )
                            ];
                        },
                    },
                ],
                data: props.users,
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

        const sendDelete = (idUser) => {
            emit("onDelete", idUser);
        };

        const sendRestore = (idUser) => {
            emit("onRestore", idUser);
        };

        const toEdit = (idUser) => {
            Inertia.visit(route("user.edit", { user: idUser }));
        };

        return {
            wrapper,
            grid,
        };
    },
};
</script>
