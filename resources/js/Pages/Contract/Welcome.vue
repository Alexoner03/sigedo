<template>
    <app-layout>
        <template #header>
            <h2 class="font-light text-4xl text-secondary leading-tight">
                SIGEDO
            </h2>
            <p class="py-4">¡Bienvenido @{{ $page.props.user.name }}!</p>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="w-full flex justify-center">
                    <inertia-link
                        :href="route('contract.create')"
                        class="
                            px-8
                            py-2
                            bg-secondary
                            text-coral text-lg
                            rounded-lg
                            mr-4
                            hover:bg-opacity-75
                            transition
                        "
                        >Ingresar Documento</inertia-link
                    >
                    <inertia-link
                        :href="route('contract.index')"
                        class="
                            px-8
                            py-2
                            bg-secondary
                            text-coral text-lg
                            rounded-lg
                            ml-4
                            hover:bg-opacity-75
                            transition
                        "
                        >Historial de Documentos</inertia-link
                    >
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
    name : 'ContractWelcome',
    components: {
        AppLayout,
    },
};
</script>
