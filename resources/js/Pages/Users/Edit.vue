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
                                <jet-label
                                    for="email"
                                    value="Correo Eléctronico"
                                />
                                <jet-input
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.email"
                                    required
                                />
                            </div>

                            <div class="mt-4">
                                <jet-label
                                    for="role_id"
                                    value="Rol de Usuario"
                                />
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
                                        v-for="(role, index) in roles"
                                        :key="index"
                                        :value="role.id"
                                        :selected="user.role_id === role.id"
                                    >
                                        {{ role.description }}
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
                                    class="ml-4"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Actualizar Usuario
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
import { reactive, toRefs } from "vue";
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
    },
    setup(props) {
        const state = reactive({
            message: false,
            messageContent: "",
            form: Inertia.form({
                name: props.user.name,
                email: props.user.email,
                role_id: props.user.role_id,
            }),
        });

        const submit = () => {
            state.form.put(route("user.update", { user: props.user }));
        };
        const back = () => {
            Inertia.visit(route("user.index"));
        };

        return {
            ...toRefs(state),
            submit,
            back,
        };
    },
};
</script>
