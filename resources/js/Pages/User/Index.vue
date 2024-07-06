<script setup>
import UserLayout from "@/Pages/User/Layouts/UserLayouts.vue";
import { router, usePage } from "@inertiajs/vue3";
import { ElNotification } from "element-plus";

defineProps({
    products: Array,
});

const page = usePage();

const addToCart = async (product) => {
    console.log(product);
    await router.post(route("cart.store", product.id), product, {
        onSuccess: () => {
            ElNotification({
                title: page.props.flash.success,
                type: "success",
            });
        },
    });
};
</script>

<template>
    <UserLayout>
        <div class="bg-white">
            <div
                class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8"
            >
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">
                    Latest Products List
                </h2>

                <div
                    class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8"
                >
                    <div
                        v-for="product in products"
                        :key="product.id"
                        class="relative hover:opacity-75"
                    >
                        <div
                            class="group aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80"
                        >
                            <img
                                v-if="product.product_images.length > 0"
                                :src="product.product_images[0].image"
                                alt="No image"
                                class="h-full w-full object-cover object-center lg:h-full lg:w-full"
                            />

                            <img
                                v-else
                                src="/No_Image_Available.jpg"
                                alt="No image"
                                class="h-full w-full object-cover object-center lg:h-full lg:w-full"
                            />

                            <!-- hover buttons -->
                            <div
                                class="absolute inset-0 bottom-0 flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 cursor-pointer z-10"
                            >
                                <a
                                    @click.prevent="addToCart(product)"
                                    class="bg-blue-600 p-2 rounded-full text-white"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="size-6"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"
                                        />
                                    </svg>
                                </a>
                                <a
                                    @click.prevent="addToCart(product)"
                                    class="bg-blue-600 p-2 rounded-full text-white"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="size-6"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                        />
                                    </svg>
                                </a>
                            </div>
                            <!-- hover buttons -->
                        </div>
                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-gray-700">
                                    <div>
                                        <span
                                            aria-hidden="true"
                                            class="inset-0"
                                        />
                                        {{ product.title }}
                                    </div>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ product.brand.name }}
                                </p>
                            </div>
                            <p class="text-sm font-medium text-gray-900">
                                ${{ product.price }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>

<style></style>
