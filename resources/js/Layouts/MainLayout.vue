<template>
    <div
        class="
            relative
            flex
            items-top
            justify-center
            min-h-screen
            bg-coral
            sm:items-start
            sm:pt-0
        "
    >
        <div
            class="
                fixed
                top-0
                px-6
                py-6
                block
                flex
                w-full
                bg-primary
                justify-between
                items-center
            "
        >
            <span class="text-coral md:text-lg"> SANABRIA & ASOCIADOS </span>

            <inertia-link
                v-if="$page.props.user"
                :href="route('contract.welcome')"
                class="text-coral"
            >
                Ingresar
            </inertia-link>

            <template v-else-if="canLogin">
                <inertia-link :href="route('login')" class="text-coral">
                    Iniciar Sesión
                </inertia-link>
            </template>

            <template v-else>
                <inertia-link href="/" class="text-coral">
                    Inicio
                </inertia-link>
            </template>
        </div>

        <div
            :class="[
                'pt-16',
                fullWidth
                    ? 'w-full h-screen'
                    : 'max-w-6xl w-full flex justify-center px-6 lg:px-8 mx-auto',
            ]"
        >
            <slot></slot>
        </div>
    </div>
</template>
<script>
export default {
    name: "MainLayout",
    props: {
        canLogin: Boolean,
        fullWidth: Boolean,
    },
};
</script>
