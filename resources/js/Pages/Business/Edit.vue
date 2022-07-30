<template>
    <app-layout>
        <template #header>
            <h2 class="font-light text-4xl text-secondary leading-tight">
                Editar Cliente
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="w-1/3 mx-auto">
                    <jet-validation-errors class="mb-4" />

                    <div class="container mx-auto">
                        <form @submit.prevent="submit">
                            <div>
                                <jet-label
                                    for="business_name"
                                    value="Razón Social"
                                />
                                <jet-input
                                    id="business_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.business_name"
                                    required
                                    autofocus
                                    autocomplete="business_name"
                                />
                            </div>

                            <div class="mt-4">
                                <jet-label for="ruc" value="Ruc" />
                                <jet-input
                                    readonly
                                    id="ruc"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.ruc"
                                    required
                                />
                            </div>
                            <div class="mt-4">
                                <jet-label for="address" value="Dirección" />
                                <jet-input
                                    id="address"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.address"
                                    required
                                />
                            </div>

                            <div class="mt-4">
                                <jet-label for="heading_id" value="Rubro" />
                                <select
                                    name="heading_id"
                                    id="heading_id"
                                    required
                                    v-model="form.heading_id"
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
                                        v-for="(heading, index) in headings"
                                        :key="index"
                                        :value="heading.id"
                                    >
                                        {{ heading.description }}
                                    </option>
                                </select>
                            </div>

                            <div class="mt-4">
                                <jet-label
                                    for="contact_number"
                                    value="Nro Contacto"
                                />
                                <jet-input
                                    id="contact_number"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.contact_number"
                                    required
                                    autocomplete="contact_number"
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
                                    Editar
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
        business: Object,
        headings: Array,
    },
    setup(props) {
        const state = reactive({
            message: false,
            messageContent: "",
            form: Inertia.form({
                business_name: props.business.business_name,
                address: props.business.address,
                ruc: props.business.ruc,
                contact_number: props.business.contact_number,
                heading_id: props.business.heading_id,
            }),
        });

        const submit = () => {
            state.form.put(
                route("business.update", { business: props.business.id })
            );
        };
        const back = () => {
            Inertia.visit(route("business.index"));
        };

        return {
            ...toRefs(state),
            submit,
            back,
        };
    },
};
</script>
