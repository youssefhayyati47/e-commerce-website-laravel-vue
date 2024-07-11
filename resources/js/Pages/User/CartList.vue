<script setup>
import UserLayout from "@/Pages/User/Layouts/UserLayouts.vue";
import { router, usePage } from "@inertiajs/vue3";
import { computed, onMounted } from "vue";

const products = computed(() => usePage().props.cart.products);
const total = computed(() => usePage().props.cart.total);
const carts = computed(() => usePage().props.cart.items);

const itemId = (id) => carts.value.findIndex(function (item) {
    return item.product_id == id;
});

const update = async (product, quantity) => {
    console.log(product, quantity);
    await router.patch(route('cart.update', product), { quantity, })
};

const destroy = async (product, item_id) => {
    await router.delete(route('cart.destroy', product));
};

onMounted(() => {
    console.log(carts.value[itemId(45)]);
})
</script>

<template>
    <UserLayout>
        <section class="text-gray-600 body-font relative">
            <div
                class="container px-5 py-24 mx-auto flex sm:flex-nowrap flex-wrap"
            >
                <div class="lg:w-2/3 md:w-1/2 rounded-lg sm:mr-10 p-10">
                    <!-- list of cart items -->

                    <table
                        class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                    >
                        <thead
                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                        >
                            <tr>
                                <th scope="col" class="px-16 py-3">
                                    <span class="sr-only">Image</span>
                                </th>
                                <th scope="col" class="px-6 py-3">Product</th>
                                <th scope="col" class="px-6 py-3">Qty</th>
                                <th scope="col" class="px-6 py-3">Price</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(product, index) in products"
                                :key="index"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                            >
                                <td class="p-2">
                                    <img
                                        v-if="product.product_images.length > 0"
                                        :src="`/${product.product_images[0].image}`"
                                        class="w-10 md:w-20 max-w-full max-h-full"
                                        alt="Apple Watch"
                                    />
                                    <img
                                        v-else
                                        src="/No_Image_Available.jpg"
                                        class="w-10 md:w-20 max-w-full max-h-full"
                                        alt="no image"
                                    />
                                </td>
                                <td
                                    class="px-6 py-4 font-semibold text-gray-900 dark:text-white"
                                >
                                    {{ product.title }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <button
                                        @click.prevent="update(product, carts[itemId(product.id)].quantity - 1)"
                                            class="inline-flex items-center justify-center p-1 me-3 text-sm font-medium h-6 w-6 text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                                            type="button"
                                        >
                                            <span class="sr-only"
                                                >Quantity button</span
                                            >
                                            <svg
                                                class="w-3 h-3"
                                                aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 18 2"
                                            >
                                                <path
                                                    stroke="currentColor"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M1 1h16"
                                                />
                                            </svg>
                                        </button>
                                        <div>
                                            <input
                                            v-model="carts[itemId(product.id)].quantity"
                                                type="number"
                                                id="first_product"
                                                class="bg-gray-50 w-14 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-2.5 py-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                required
                                            />
                                        </div>
                                        <button
                                        @click.prevent="update(product, carts[itemId(product.id)].quantity + 1)"
                                            class="inline-flex items-center justify-center h-6 w-6 p-1 ms-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                                            type="button"
                                        >
                                            <span class="sr-only"
                                                >Quantity button</span
                                            >
                                            <svg
                                                class="w-3 h-3"
                                                aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 18 18"
                                            >
                                                <path
                                                    stroke="currentColor"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 1v16M1 9h16"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 font-semibold text-gray-900 dark:text-white"
                                >
                                    ${{ product.price }}
                                </td>
                                <td class="px-6 py-4">
                                    <a
                                        @click.prevent="destroy(product)"
                                        href="#"
                                        class="font-medium text-red-600 dark:text-red-500 hover:underline"
                                        >Remove</a
                                    >
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- end of list -->
                </div>
                <div
                    class="lg:w-1/3 md:w-1/2 bg-white flex flex-col md:ml-auto w-full md:py-8 mt-8 md:mt-0"
                >
                    <h2
                        class="text-gray-900 text-lg mb-1 font-medium title-font"
                    >
                        Summary
                    </h2>
                    <p class="leading-relaxed mb-5 text-gray-600">
                        Total : <p class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300 inline border border-red-400">{{ total }}$</p>
                    </p>
                    <h2
                        class="text-gray-900 text-lg mb-1 font-medium title-font"
                    >
                        Shipping Address
                    </h2>
                    <p class="leading-relaxed mb-5 text-gray-600">
                        1234 st example, usa, ny, 111 1111
                    </p>
                    <p class="leading-relaxed mb-5 text-gray-600">
                        or you can add new below
                    </p>
                    <div class="relative mb-4">
                        <label
                            for="name"
                            class="leading-7 text-sm text-gray-600"
                            >Name</label
                        >
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="w-full bg-white rounded border border-gray-300 focus:border-blue-700 focus:ring-1 focus:ring-blue-700 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                        />
                    </div>
                    <div class="relative mb-4">
                        <label
                            for="email"
                            class="leading-7 text-sm text-gray-600"
                            >Email</label
                        >
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="w-full bg-white rounded border border-gray-300 focus:border-blue-700 focus:ring-1 focus:ring-blue-700 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                        />
                    </div>
                    <div class="relative mb-4">
                        <label
                            for="message"
                            class="leading-7 text-sm text-gray-600"
                            >Message</label
                        >
                        <textarea
                            id="message"
                            name="message"
                            class="w-full bg-white rounded border border-gray-300 focus:border-blue-700 focus:ring-1 focus:ring-blue-700 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"
                        ></textarea>
                    </div>
                    <button
                        class="text-white bg-blue-500 border-0 py-2 px-6 focus:outline-none hover:bg-blue-600 rounded text-lg"
                    >
                        Checkout
                    </button>
                    <p class="text-xs text-gray-500 mt-3">
                        Continue Shopping !
                    </p>
                </div>
            </div>
        </section>
    </UserLayout>
</template>

<style></style>
