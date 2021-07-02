<template>
    <app-layout>
        <template #header>
            <h2 class="font-light text-4xl text-secondary leading-tight">
                Administrador de Usuarios
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between mb-8">
                    <inertia-link
                        :href="route('user.create')"
                        class="
                            inline-flex
                            items-center
                            px-4
                            py-2
                            bg-gray-800
                            border border-transparent
                            rounded-md
                            font-semibold
                            text-xs text-white
                            uppercase
                            tracking-widest
                            hover:bg-gray-700
                            active:bg-gray-900
                            focus:outline-none
                            focus:border-gray-900
                            focus:ring focus:ring-gray-300
                            disabled:opacity-25
                            transition
                        "
                    >
                        Nuevo Usuario
                    </inertia-link>
                </div>
                <users-table
                    :users="users"
                    @onDelete="confirmUserDeletion"
                    :key="reloadTable"
                />
                <!-- Eliminar Cuenta Confirmation Modal -->
                <jet-dialog-modal
                    :show="confirmingUserDeletion"
                    @close="closeModal"
                >
                    <template #title> Eliminar Cuenta </template>

                    <template #content>
                        ¿Estás seguro de que deseas eliminar esta cuenta?
                        <div class="mt-4">
                            <jet-input
                                type="password"
                                class="mt-1 block w-3/4"
                                placeholder="Password"
                                ref="password"
                                v-model="form.password"
                                @keyup.enter="deleteUser"
                            />

                            <jet-input-error
                                :message="form.errors.password"
                                class="mt-2"
                            />
                        </div>
                    </template>

                    <template #footer>
                        <jet-secondary-button @click="closeModal">
                            Cancelar
                        </jet-secondary-button>

                        <jet-danger-button
                            class="ml-2"
                            @click="deleteUser"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Eliminar Cuenta
                        </jet-danger-button>
                    </template>
                </jet-dialog-modal>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import UsersTable from "./components/UsersTable.vue";
import JetDialogModal from "@/Jetstream/DialogModal";
import JetDangerButton from "@/Jetstream/DangerButton";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetSecondaryButton from "@/Jetstream/SecondaryButton";
import { reactive, toRefs, ref } from "vue";
import { Inertia } from "@inertiajs/inertia";

export default {
    name: "UsersIndex",
    props: {
        users: Array,
    },
    components: {
        AppLayout,
        UsersTable,
        JetDangerButton,
        JetDialogModal,
        JetInput,
        JetInputError,
        JetSecondaryButton,
    },
    setup() {
        const state = reactive({
            confirmingUserDeletion: false,
            userSelected: -1,
            reloadTable: 0,

            form: Inertia.form({
                password: "",
            }),
        });

        const password = ref();

        const confirmUserDeletion = (user) => {
            state.userSelected = user;
            state.confirmingUserDeletion = true;

            setTimeout(() => password.value.focus(), 250);
        };

        const deleteUser = () => {
            state.form.delete(
                route("user.destroy", { user: state.userSelected }),
                {
                    preserveScroll: true,
                    onSuccess: () => closeModal(),
                    onError: () => password.value.focus(),
                    onFinish: () => {
                        state.form.reset();
                        state.reloadTable++;
                    },
                }
            );
        };

        const closeModal = () => {
            state.confirmingUserDeletion = false;
            state.form.reset();
        };

        return {
            ...toRefs(state),
            password,

            confirmUserDeletion,
            deleteUser,
            closeModal,
        };
    },
};
</script>
