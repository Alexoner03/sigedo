<template>
    <main-layout>
        <div class="flex flex-col">
            <div class="mb-12">
                <p class="text-secondary text-center text-4xl pt-12">
                    Restaura tu contraseña
                </p>
            </div>
            <div>
                <jet-validation-errors class="mb-4" />

                <form @submit.prevent="submit">
                    <div>
                        <jet-label for="email" value="Correo" />
                        <jet-input
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
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
                            placeholder="correo@estudiosanabria.com"
                            autocomplete="new-password"
                        />
                    </div>

                    <div class="mt-4">
                        <jet-label
                            for="password_confirmation"
                            value="Confirma tu Contraseña"
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

                    <div class="flex items-center justify-center mt-8">
                        <jet-button
                            class="px-12 py-2 text-lg"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Restaurar Contraseña
                        </jet-button>
                    </div>
                </form>
            </div>
        </div>
    </main-layout>
</template>

<script>
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetLabel from "@/Jetstream/Label";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import MainLayout from "@/Layouts/MainLayout";

export default {
    components: {
        JetButton,
        JetInput,
        JetLabel,
        JetValidationErrors,
        MainLayout,
    },

    props: {
        email: String,
        token: String,
    },

    data() {
        return {
            form: this.$inertia.form({
                token: this.token,
                email: this.email,
                password: "",
                password_confirmation: "",
            }),
        };
    },

    methods: {
        submit() {
            this.form.post(this.route("password.update"), {
                onFinish: () =>
                    this.form.reset("password", "password_confirmation"),
            });
        },
    },
};
</script>
