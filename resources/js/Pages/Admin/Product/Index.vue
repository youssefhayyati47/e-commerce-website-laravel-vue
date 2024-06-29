<template>
    <AdminLayout>
        <ProductList :products="products"></ProductList>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Pages/Admin/Components/AdminLayout.vue";
import ProductList from "@/Pages/Admin/Product/ProductList.vue";

defineProps({
    products: Array,
});
</script>

<style></style>
