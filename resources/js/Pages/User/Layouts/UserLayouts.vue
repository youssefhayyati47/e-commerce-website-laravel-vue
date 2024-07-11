<script setup>
import Header from "@/Pages/User/Layouts/Header.vue";
import Hero from "@/Pages/User/Layouts/Hero.vue";
import Footer from "@/Pages/User/Layouts/Footer.vue";
import { onMounted } from "vue";
import { initFlowbite } from "flowbite";

onMounted(() => {
    initFlowbite();
});
</script>

<template>
    <!-- navbar starts -->
    <Header />
    <!-- navbar ends -->

    <!-- main starts -->
    <slot />
    <!-- main ends -->

    <!-- footer starts -->
    <Footer />
    <!-- footer ends -->
</template>

<style></style>
