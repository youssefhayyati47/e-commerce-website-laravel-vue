<template>
    <div class="antialiased bg-gray-50 dark:bg-gray-900">
        <!-- Navbar Starts -->
        <Navbar />
        <!-- Navbar Ends -->

        <!-- Sidebar Starts -->
        <Sidebar />
        <!-- Sidebar Ends -->
        <main class="p-4 md:ml-64 h-auto pt-20">
            <slot />
        </main>
    </div>
</template>

<script setup>
import Navbar from "@/Pages/Admin/Components/Navbar.vue";
import Sidebar from "@/Pages/Admin/Components/Sidebar.vue";
import { onMounted } from "vue";
import { initFlowbite } from "flowbite";

onMounted(() => {
    initFlowbite();
});
</script>

<style></style>
