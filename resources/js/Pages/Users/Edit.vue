<template>
    <app-layout>
        <template #header>
            <h2 class="font-light text-4xl text-secondary leading-tight">
                Editar Usuario
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="w-1/3 mx-auto">
                    <jet-validation-errors class="mb-4" />

                    <div class="container mx-auto">
                        <form @submit.prevent="submit">
                            <div>
                                <jet-label for="name" value="Nombres" />
                                <jet-input
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    autocomplete="name"
                                />
                            </div>

                            <div class="mt-4">
                                <jet-label for="dni" value="Dni" />
                                <jet-input
                                    id="dni"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.dni"
                                    required
                                    autofocus
                                    autocomplete="dni"
                                />
                            </div>

                            <div class="mt-4">
                                <jet-label for="email" value="Correo Eléctronico" />
                                <jet-input
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.email"
                                    required
                                />
                            </div>

                            <div class="mt-4">
                                <jet-label for="business_id" value="Empresa" />
                                <select
                                    @change="filteringSupervisors"
                                    id="business_id"
                                    v-model="form.business_id"
                                    class="
                                    mt-1
                                    block
                                    w-full
                                    border-metalgray
                                    bg-transparent
                                    focus:border-secondary
                                    focus:ring
                                    focus:ring-secondary
                                    focus:ring-opacity-50
                                    rounded-md
                                    shadow-sm
                                    capitalize
                                "
                                >
                                    <option
                                        v-for="(business, index) in businesses"
                                        :key="index"
                                        :value="business.id"
                                    >
                                        {{ business.business_name }}
                                    </option>
                                </select>
                            </div>

                            <div class="mt-4">
                                <jet-label for="role_id" value="Rol de Usuario" />
                                <select
                                    id="role_id"
                                    v-model="form.role_id"
                                    class="
                                    mt-1
                                    block
                                    w-full
                                    border-metalgray
                                    bg-transparent
                                    focus:border-secondary
                                    focus:ring
                                    focus:ring-secondary
                                    focus:ring-opacity-50
                                    rounded-md
                                    shadow-sm
                                    capitalize
                                "
                                >
                                    <option
                                        v-for="(role, index) in roles"
                                        :key="index"
                                        :value="role.id"
                                    >
                                        {{ role.description }}
                                    </option>
                                </select>
                            </div>

                            <div class="mt-4" v-if="form.business_id !== 1">
                                <jet-label for="position_id" value="Área" />
                                <select
                                    id="position_id"
                                    v-model="form.position_id"
                                    class="
                                    mt-1
                                    block
                                    w-full
                                    border-metalgray
                                    bg-transparent
                                    focus:border-secondary
                                    focus:ring
                                    focus:ring-secondary
                                    focus:ring-opacity-50
                                    rounded-md
                                    shadow-sm
                                    capitalize
                                "
                                >
                                    <option
                                        v-for="(position, index) in positions"
                                        :key="index"
                                        :value="position.id"
                                    >
                                        {{ position.description }}
                                    </option>
                                </select>
                            </div>

                            <div class="mt-4" v-if="form.role_id === 2">
                                <jet-label for="supervisor_to_report_id" value="Supervisor a reportar" />
                                <select
                                    name="supervisor_to_report_id"
                                    id="supervisor_to_report_id"
                                    v-model="form.supervisor_to_report"
                                    class="
                                    mt-1
                                    block
                                    w-full
                                    border-metalgray
                                    bg-transparent
                                    focus:border-secondary
                                    focus:ring
                                    focus:ring-secondary
                                    focus:ring-opacity-50
                                    rounded-md
                                    shadow-sm
                                    capitalize
                                "
                                >
                                    <option
                                        v-for="(supervisor, index) in filteredSupervisor"
                                        :key="index"
                                        :value="supervisor.id"
                                    >
                                        {{ supervisor.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <jet-button
                                    type="button"
                                    class="ml-4"
                                    :outline="true"
                                    :disabled="form.processing"
                                    @click="back"
                                >
                                    Regresar
                                </jet-button>
                                <jet-button
                                    type="submit"
                                    class="ml-4"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Registrar
                                </jet-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import JetActionMessage from "@/Jetstream/ActionMessage";
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from "@/Jetstream/Label";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import {reactive, ref, toRefs} from "vue";
import { Inertia } from "@inertiajs/inertia";

export default {
    components: {
        JetButton,
        JetInput,
        JetCheckbox,
        JetLabel,
        JetValidationErrors,
        JetActionMessage,
        AppLayout,
    },
    props: {
        user: Object,
        roles: Array,
        businesses: Array,
        positions: Array,
        supervisors : Array,
    },
    setup(props) {
        const state = reactive({
            message: false,
            messageContent: "",
            form: Inertia.form({
                name: props.user.name,
                email: props.user.email,
                role_id: props.user.role_id,
                position_id: props.user.position_id,
                business_id: props.user.business_id,
                dni: props.user.dni,
                supervisor_to_report : props.user.supervisor_to_report
            }),
        });

        const submit = () => {
            state.form.put(route("user.update", {user: props.user}));
        };
        const back = () => {
            Inertia.visit(route("user.index"));
        };

        const filteredSupervisor = ref(props.supervisors.filter(sv => sv.business_id === props.user.business_id))

        const filteringSupervisors = () => {
            state.form.supervisor_to_report = null
            filteredSupervisor.value = props.supervisors.filter(sv => sv.business_id === state.form.business_id)
        }

        return {
            ...toRefs(state),
            submit,
            back,
            filteringSupervisors,
            filteredSupervisor
        };
    },
};
</script>
