<template>
    <app-layout>
        <template #header>
            <h2 class="font-light text-4xl text-secondary leading-tight">
                Crear nuevo Usuario
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="w-1/3 mx-auto">
                    <jet-validation-errors class="mb-4" />
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

                        <div>
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
                                @change="filteringRoles"
                                name="business_id"
                                id="business_id"
                                required
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
                                name="role_id"
                                id="role_id"
                                required
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
                                    v-for="(role, index) in filteredRoles"
                                    :key="index"
                                    :value="role.id"
                                >
                                    {{ role.description }}
                                </option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <jet-label for="position_id" value="Cargo" />
                            <select
                                name="position_id"
                                id="position_id"
                                required
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
                                    v-for="(position, index) in filterPositions"
                                    :key="index"
                                    :value="position.id"
                                >
                                    {{ position.description }}
                                </option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <jet-label for="password" value="Contraseña" />
                            <jet-input
                                id="password"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password"
                                required
                                autocomplete="new-password"
                            />
                        </div>

                        <div class="mt-4">
                            <jet-label
                                for="password_confirmation"
                                value="Confirmar Contraseña"
                            />
                            <jet-input
                                id="password_confirmation"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                            />
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
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import JetInput from "@/Jetstream/Input";
import JetButton from "@/Jetstream/Button";
import JetLabel from "@/Jetstream/Label";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import { reactive, toRefs, ref } from "vue";
import { Inertia } from "@inertiajs/inertia";

export default {
    components: {
        AppLayout,
        JetButton,
        JetInput,
        JetLabel,
        JetValidationErrors,
    },
    props: {
        roles: Array,
        businesses : Array,
        positions : Array
    },
    setup(props) {
        const state = reactive({
            form: Inertia.form({
                name: "",
                email: "",
                role_id: 1,
                password: "",
                password_confirmation: "",
                position_id : 1,
                business_id : 1,
                dni : null
            }),
        });

        const filteredRoles = ref(props.roles.filter(p => p.id !== 3))
        const filterPositions = ref(props.positions)

        const filteringRoles = () =>
        {
            if(state.form.business_id === 1 )
            {
                filteredRoles.value = props.roles.filter(p => p.id !== 3);
                state.form.role_id = 1
                filterPositions.value = props.positions;
                state.form.position_id = 1
            }else{
                filteredRoles.value = props.roles.filter(p => p.id === 3);
                state.form.role_id = 3

                filterPositions.value = props.positions.filter(p => p.id !== 1)
                state.form.position_id = props.positions[1].id
            }

        }

        const submit = () => {
            state.form.post(route("user.store"), {
                onFinish: () =>
                    state.form.reset("password", "password_confirmation"),
            });
        };

        const back = () => {
            Inertia.visit(route("user.index"));
        };

        return {
            filteredRoles,
            filterPositions,

            ...toRefs(state),
            submit,
            back,
            filteringRoles
        };
    },
};
</script>
