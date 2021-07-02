<template>
    <main-layout :can-login="canLogin" :full-width="true">
        <img src="/images/main-bg.jpeg" alt="fondo de pantalla de dos personas entregandose un documento" 
        class="hidden md:block w-full h-full object-center object-fit object-cover">
        <img src="/images/movile-bg.jpg" alt="fondo de pantalla de dos personas entregandose un documento"
        class="block md:hidden w-full h-full object-center object-fit object-cover">
    </main-layout>
</template>
<script>
import MainLayout from "@/Layouts/MainLayout";

export default {
    components: {
        MainLayout,
    },
    props: {
        //Variable traída desde el controller
        canLogin: Boolean,
    },
};
</script>
