<template>
    <main-layout>
        <div class="flex flex-col w-1/2">
            <div class="mb-12">
                <p class="text-secondary text-center text-4xl pt-12">
                    SIGEDO
                </p>
                <p class="text-center">Administrador de documentos</p>
                <p class="text-center">Inicia Sesión</p>
            </div>

            <div>
                <jet-validation-errors
                    class="mb-4"
                    :is-in-background="forgotPasswordOpen"
                />
                <div
                    v-if="status && !forgotPasswordOpen"
                    class="mb-4 font-medium text-sm text-green-600"
                >
                    {{ status }}
                </div>

                <form @submit.prevent="submit">
                    <div>
                        <jet-label for="email" value="Correo" />

                        <jet-input
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            placeholder="correo@estudiosanabria.com"
                            required
                            autofocus
                        />
                    </div>

                    <div class="mt-4">
                        <jet-label for="password" value="Contraseña" />
                        <jet-input
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                        />
                    </div>

                    <div class="block mt-4">
                        <label class="flex items-center">
                            <jet-checkbox
                                name="remember"
                                v-model:checked="form.remember"
                            />
                            <span class="ml-2 text-sm text-gray-600"
                                >Recordarme</span
                            >
                        </label>
                    </div>

                    <div class="flex flex-col items-end justify-end mt-4">
                        <span
                            v-if="canResetPassword"
                            @click="toggleForgotPasswordModal"
                            class="
                                mb-8
                                text-secondary text-sm text-gray-600
                                hover:underline
                                cursor-pointer
                            "
                        >
                            Olvidé mi contraseña
                        </span>

                        <jet-button
                            class="self-center px-12 py-2 text-lg"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Ingresar
                        </jet-button>
                    </div>
                </form>
            </div>
        </div>
        <transition-root appear :show="forgotPasswordOpen" as="template">
            <forgot-password @onClose="toggleForgotPasswordModal" :status="status"/>
        </transition-root>
    </main-layout>
</template>

<script>
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from "@/Jetstream/Label";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import { TransitionRoot } from "@headlessui/vue";
import MainLayout from "@/Layouts/MainLayout";
import ForgotPassword from "@/Pages/Auth/ForgotPassword";

export default {
    components: {
        JetButton,
        JetInput,
        JetCheckbox,
        JetLabel,
        JetValidationErrors,
        MainLayout,
        TransitionRoot,
        ForgotPassword,
    },

    props: {
        canResetPassword: Boolean,
        status: String,
    },

    data() {
        return {
            form: this.$inertia.form({
                email: "",
                password: "",
                remember: false,
            }),
            forgotPasswordOpen: false,
        };
    },

    methods: {
        submit() {
            this.form
                .transform((data) => ({
                    ...data,
                    remember: this.form.remember ? "on" : "",
                }))
                .post(this.route("login"), {
                    onFinish: () => this.form.reset("password"),
                });
        },

        toggleForgotPasswordModal() {
            this.$page.props.errors = {}
            this.$page.props.status = null
            this.forgotPasswordOpen = !this.forgotPasswordOpen;
        },
    },
};
</script>
