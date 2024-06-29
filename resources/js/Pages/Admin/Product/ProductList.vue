<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { ElNotification } from "element-plus";
import { computed, onBeforeUnmount, onMounted, reactive, ref } from "vue";
import { Plus } from "@element-plus/icons-vue";
import { ElMessage, ElMessageBox } from "element-plus";

const page = usePage();
// const products = page.props.products;
defineProps({ products: Array });
const brands = page.props.brands;
const categories = page.props.categories;

const dialogVisibleAdd = ref(false);
const dialogVisibleEdit = ref(false);
const temporaryImages = ref([]);

//product form data
const form = {
    id: ref(null),
    title: ref(null),
    price: ref(null),
    quantity: ref(null),
    description: ref(null),
    product_images: ref([]),
    published: ref(false),
    category_id: ref(""),
    brand_id: ref(""),
    inStock: ref(false),
};

////////////////////////////ELEMENT PLUS START////////////////////////////
//upload multiple images
const dialogImageUrl = ref("");
const dialogVisible2 = ref(false);

const handleFileChange = (file) => {
    temporaryImages.value.push(file);
    form.product_images.push(file);
};

const handleRemove = async (file) => {
    console.log(file.id);
    await router.delete(`products/images/destroy/${file.id}`, {
        onSuccess: () => {
            ElNotification({
                title: page.props.flash.success,
                type: "success",
            });
        },
        onError: () => {
            ElNotification({
                title: page.props.flash.error,
                type: "error",
            });
        },
    });
};

const handlePictureCardPreview = (file) => {
    dialogImageUrl.value = file.url;
    dialogVisible2.value = true;
};

////////////////////////////ELEMENT PLUS END////////////////////////////

////////////////////////////CRUD METHODS START////////////////////////////
//reset form
const resetForm = () => {
    form.id.value = null;
    form.title.value = null;
    form.price.value = null;
    form.quantity.value = null;
    form.description.value = null;
    form.product_images.value = [];
    form.published.value = false;
    form.category_id.value = "";
    form.brand_id.value = "";
};

//open add modal
const openAddModal = () => {
    resetForm();
    dialogVisibleAdd.value = true;
    dialogVisibleEdit.value = false;
};

//add product method
const addProduct = async () => {
    const formData = new FormData();
    formData.append("title", form.title.value);
    formData.append("price", form.price.value);
    formData.append("quantity", form.quantity.value);
    formData.append("description", form.description.value);
    formData.append("category_id", form.category_id.value);
    formData.append("brand_id", form.brand_id.value);

    //append images
    for (const image of form.product_images.value) {
        formData.append("product_images[]", image.raw);
    }

    try {
        await router.post("products/store", formData, {
            onSuccess: () => {
                ElNotification({
                    title: page.props.flash.success,
                    type: "success",
                });
                router.get("products");
                dialogVisibleAdd.value = false;
            },
        });
    } catch (error) {
        console.log(error);
    }
};

//open edit modal
const openEditModal = (product) => {
    resetForm();
    dialogVisibleEdit.value = true;
    dialogVisibleAdd.value = false;

    form.id.value = product.id;
    form.title.value = product.title;
    form.price.value = product.price;
    form.quantity.value = product.quantity;
    form.description.value = product.description;
    form.category_id.value = product.category_id;
    form.brand_id.value = product.brand_id;
    form.product_images.value = product.product_images.map((image) => {
        return {
            id: image.id,
            name: image.image.split("/").pop(),
            url: "http://127.0.0.1:8000/" + image.image,
        };
    });
};

//update product
const updateProduct = async () => {
    console.log("clicked");
    const formDataEdit = new FormData();
    // formDataEdit.append("id", form.id.value);
    formDataEdit.append("title", form.title.value);
    formDataEdit.append("price", form.price.value);
    formDataEdit.append("quantity", form.quantity.value);
    formDataEdit.append("description", form.description.value);
    formDataEdit.append("category_id", form.category_id.value);
    formDataEdit.append("brand_id", form.brand_id.value);
    formDataEdit.append("_method", "PUT");

    //append images
    for (const image of temporaryImages.value) {
        formDataEdit.append("product_images[]", image.raw);
    }

    try {
        await router.post("products/update/" + form.id.value, formDataEdit, {
            onSuccess: () => {
                ElNotification({
                    title: page.props.flash.success,
                    type: "success",
                });

                dialogVisibleEdit.value = false;
            },
        });
    } catch (error) {
        console.log(error);
    }
};

//delete product
const deleteProduct = (id) => {
    console.log("clicked");
    ElMessageBox.confirm("Are you sure you want to delete this product?", {
        distinguishCancelAndClose: true,
        confirmButtonText: "Yes, Delete",
        confirmButtonClass: "el-button--danger",
        cancelButtonText: "Cancel",
        type: "warning",
        draggable: true,
    })
        .then(async () => {
            console.log("entered here");
            await router.delete("products/destroy/" + id, {
                onSuccess: () => {
                    ElNotification({
                        title: page.props.flash.success,
                        type: "success",
                    });
                },
                onError: () => {
                    ElNotification({
                        title: page.props.flash.error,
                        type: "error",
                    });
                },
            });
        })
        .catch(() => {
            ElMessage({
                type: "info",
                message: "Delete canceled",
            });
        });
};

//////////////////////////////CRUD METHODS END////////////////////////////

////////////////////////////SCREEN REACTIVITY START////////////////////////////

// Reactive screen size state
const smallScreen = reactive({
    value: false,
});

// Computed property for select width
const selectWidth = computed(() => {
    return smallScreen.value ? "90%" : "36%";
});

// Function to check screen width
const checkScreenWidth = () => {
    smallScreen.value = window.innerWidth <= 768; // Adjust breakpoint as needed
};

// Set initial screen size and add resize event listener
onMounted(() => {
    checkScreenWidth();
    window.addEventListener("resize", checkScreenWidth);
});

// Remove resize event listener on component unmount
onBeforeUnmount(() => {
    window.removeEventListener("resize", checkScreenWidth);
});

////////////////////////////SCREEN REACTIVITY END////////////////////////////
</script>

<template>
    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <!-- Start coding here -->
            <div
                class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden"
            >
                <div
                    class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4"
                >
                    <div class="w-full md:w-1/2">
                        <form class="flex items-center">
                            <label for="simple-search" class="sr-only"
                                >Search</label
                            >
                            <div class="relative w-full">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
                                >
                                    <svg
                                        aria-hidden="true"
                                        class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                        fill="currentColor"
                                        viewbox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    id="simple-search"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Search"
                                    required=""
                                />
                            </div>
                        </form>
                    </div>
                    <div
                        class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0"
                    >
                        <button
                            @click="openAddModal"
                            type="button"
                            class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                stroke="currentColor"
                                class="size-6 pr-2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 4.5v15m7.5-7.5h-15"
                                />
                            </svg>

                            Add product
                        </button>
                        <div
                            class="flex items-center space-x-3 w-full md:w-auto"
                        >
                            <button
                                id="actionsDropdownButton"
                                data-dropdown-toggle="actionsDropdown"
                                class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                type="button"
                            >
                                <svg
                                    class="-ml-1 mr-1.5 w-5 h-5"
                                    fill="currentColor"
                                    viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                    aria-hidden="true"
                                >
                                    <path
                                        clip-rule="evenodd"
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    />
                                </svg>
                                Actions
                            </button>
                            <div
                                id="actionsDropdown"
                                class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600"
                            >
                                <ul
                                    class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="actionsDropdownButton"
                                >
                                    <li>
                                        <a
                                            href="#"
                                            class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                            >Mass Edit</a
                                        >
                                    </li>
                                </ul>
                                <div class="py-1">
                                    <a
                                        href="#"
                                        class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                                        >Delete all</a
                                    >
                                </div>
                            </div>
                            <button
                                id="filterDropdownButton"
                                data-dropdown-toggle="filterDropdown"
                                class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                type="button"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    aria-hidden="true"
                                    class="h-4 w-4 mr-2 text-gray-400"
                                    viewbox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                Filter
                                <svg
                                    class="-mr-1 ml-1.5 w-5 h-5"
                                    fill="currentColor"
                                    viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                    aria-hidden="true"
                                >
                                    <path
                                        clip-rule="evenodd"
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    />
                                </svg>
                            </button>
                            <div
                                id="filterDropdown"
                                class="z-10 hidden w-48 p-3 bg-white rounded-lg shadow dark:bg-gray-700"
                            >
                                <h6
                                    class="mb-3 text-sm font-medium text-gray-900 dark:text-white"
                                >
                                    Choose brand
                                </h6>
                                <ul
                                    class="space-y-2 text-sm"
                                    aria-labelledby="filterDropdownButton"
                                >
                                    <li class="flex items-center">
                                        <input
                                            id="apple"
                                            type="checkbox"
                                            value=""
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                                        />
                                        <label
                                            for="apple"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100"
                                            >Apple (56)</label
                                        >
                                    </li>
                                    <li class="flex items-center">
                                        <input
                                            id="fitbit"
                                            type="checkbox"
                                            value=""
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                                        />
                                        <label
                                            for="fitbit"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100"
                                            >Microsoft (16)</label
                                        >
                                    </li>
                                    <li class="flex items-center">
                                        <input
                                            id="razor"
                                            type="checkbox"
                                            value=""
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                                        />
                                        <label
                                            for="razor"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100"
                                            >Razor (49)</label
                                        >
                                    </li>
                                    <li class="flex items-center">
                                        <input
                                            id="nikon"
                                            type="checkbox"
                                            value=""
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                                        />
                                        <label
                                            for="nikon"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100"
                                            >Nikon (12)</label
                                        >
                                    </li>
                                    <li class="flex items-center">
                                        <input
                                            id="benq"
                                            type="checkbox"
                                            value=""
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                                        />
                                        <label
                                            for="benq"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100"
                                            >BenQ (74)</label
                                        >
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table
                        class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                    >
                        <thead
                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                        >
                            <tr>
                                <th scope="col" class="px-4 py-3">
                                    Product name
                                </th>
                                <th scope="col" class="px-4 py-3">Category</th>
                                <th scope="col" class="px-4 py-3">Brand</th>
                                <th scope="col" class="px-4 py-3">
                                    Description
                                </th>
                                <th scope="col" class="px-4 py-3">Price</th>
                                <th scope="col" class="px-4 py-3">Stock</th>
                                <th scope="col" class="px-4 py-3">Publish</th>
                                <th scope="col" class="px-4 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="product in products"
                                :key="product.id"
                                class="border-b dark:border-gray-700"
                            >
                                <th
                                    scope="row"
                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                >
                                    {{ product.title }}
                                </th>
                                <td class="px-4 py-3">
                                    {{ product.category.name }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ product.brand.name }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ product.quantity }}
                                </td>
                                <td class="px-4 py-3">{{ product.price }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        v-if="product.inStock"
                                        class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300"
                                        >in stock</span
                                    >
                                    <span
                                        v-else
                                        class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300"
                                        >out of stock</span
                                    >
                                </td>
                                <td class="px-4 py-3">
                                    <button
                                        v-if="product.published"
                                        type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-green-700 rounded-lg"
                                    >
                                        Published
                                    </button>
                                    <button
                                        v-else
                                        type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-red-700 rounded-lg"
                                    >
                                        UnPublished
                                    </button>
                                </td>
                                <td
                                    class="px-4 py-3 flex items-center justify-end"
                                >
                                    <button
                                        :id="`${product.id}-button`"
                                        :data-dropdown-toggle="`${product.id}`"
                                        class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                        type="button"
                                    >
                                        <svg
                                            class="w-5 h-5"
                                            aria-hidden="true"
                                            fill="currentColor"
                                            viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"
                                            />
                                        </svg>
                                    </button>
                                    <div
                                        :id="`${product.id}`"
                                        class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600"
                                    >
                                        <ul
                                            class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                            :aria-labelledby="`${product.id}-button`"
                                        >
                                            <li>
                                                <a
                                                    @click="
                                                        openEditModal(product)
                                                    "
                                                    class="py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white flex gap-2 items-center"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="2"
                                                        stroke="#5850EC"
                                                        class="w-[12px] h-[15px]"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"
                                                        />
                                                    </svg>

                                                    <p class="text-indigo-600">
                                                        Edit
                                                    </p>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="py-1">
                                            <a href="">
                                                <el-button
                                                    plain
                                                    @click.prevent="
                                                        deleteProduct(
                                                            product.id
                                                        )
                                                    "
                                                    href="#"
                                                    class="custom-button"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="1.5"
                                                        stroke="#F05252"
                                                        class="w-[12px] h-[15px]"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                                        />
                                                    </svg>
                                                    <p class="text-red-500">
                                                        Delete
                                                    </p></el-button
                                                >
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <nav
                    class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4"
                    aria-label="Table navigation"
                >
                    <span
                        class="text-sm font-normal text-gray-500 dark:text-gray-400"
                    >
                        Showing
                        <span
                            class="font-semibold text-gray-900 dark:text-white"
                            >1-10</span
                        >
                        of
                        <span
                            class="font-semibold text-gray-900 dark:text-white"
                            >1000</span
                        >
                    </span>
                    <ul class="inline-flex items-stretch -space-x-px">
                        <li>
                            <a
                                href="#"
                                class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                            >
                                <span class="sr-only">Previous</span>
                                <svg
                                    class="w-5 h-5"
                                    aria-hidden="true"
                                    fill="currentColor"
                                    viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a
                                href="#"
                                class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                                >1</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                                >2</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                aria-current="page"
                                class="flex items-center justify-center text-sm z-10 py-2 px-3 leading-tight text-primary-600 bg-primary-50 border border-primary-300 hover:bg-primary-100 hover:text-primary-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white"
                                >3</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                                >...</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                                >100</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                            >
                                <span class="sr-only">Next</span>
                                <svg
                                    class="w-5 h-5"
                                    aria-hidden="true"
                                    fill="currentColor"
                                    viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>

    <!-- dialogue box for adding a product -->
    <el-dialog v-model="dialogVisibleAdd" :width="selectWidth">
        <!-- form starts -->
        <section class="bg-white dark:bg-gray-900 mt-0">
            <div class="max-w-2xl px-4 mx-auto">
                <h2
                    class="mb-4 text-xl font-bold text-gray-900 dark:text-white"
                >
                    Add Product
                </h2>
                <form action="#">
                    <div
                        class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5"
                    >
                        <div class="sm:col-span-2">
                            <label
                                for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Product Name</label
                            >
                            <input
                                v-model="form.title.value"
                                type="text"
                                id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Type product name"
                            />
                        </div>
                        <div>
                            <label
                                for="brand"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Brand</label
                            >
                            <select
                                v-model="form.brand_id.value"
                                id="brand"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            >
                                <option value="" selected>
                                    Select a brand
                                </option>
                                <option
                                    v-for="brand in brands"
                                    :key="brand.id"
                                    :value="brand.id"
                                >
                                    {{ brand.name }}
                                </option>
                            </select>
                        </div>
                        <div class="w-full">
                            <label
                                for="price"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Price</label
                            >
                            <input
                                v-model="form.price.value"
                                type="number"
                                id="price"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="0.00"
                            />
                        </div>
                        <div>
                            <label
                                for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Category</label
                            >
                            <select
                                v-model="form.category_id.value"
                                id="category"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            >
                                <option value="" selected>
                                    Select a category
                                </option>
                                <option
                                    v-for="category in categories"
                                    :key="category.id"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label
                                for="item-weight"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Quantity</label
                            >
                            <input
                                v-model="form.quantity.value"
                                type="number"
                                id="item-weight"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="0"
                            />
                        </div>
                        <div class="sm:col-span-2">
                            <label
                                for="description"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Description</label
                            >
                            <textarea
                                v-model="form.description.value"
                                id="description"
                                rows="3"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Write a product description here..."
                            >
                            </textarea>
                        </div>
                        <!-- multiple images upload -->
                        <div class="sm:col-span-2">
                            <label
                                for="description"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Product Images</label
                            >
                            <el-upload
                                v-model:file-list="form.product_images.value"
                                list-type="picture-card"
                                :on-preview="handlePictureCardPreview"
                                :on-change="handleFileChange"
                                multiple
                            >
                                <el-icon>
                                    <Plus />
                                </el-icon>
                            </el-upload>
                            <el-dialog v-model="dialogVisible2">
                                <img
                                    w-full
                                    :src="dialogImageUrl"
                                    alt="Preview Image"
                                />
                            </el-dialog>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <!-- form ends -->
        <template #footer>
            <div class="dialog-footer">
                <el-button @click="dialogVisibleAdd = false">Cancel</el-button>
                <el-button
                    color="#1A56DB"
                    type="primary"
                    @click="addProduct"
                    :disabled="form.processing"
                >
                    Confirm
                </el-button>
            </div>
        </template>
    </el-dialog>

    <!-- dialogue box for adding a product -->
    <el-dialog v-model="dialogVisibleEdit" :width="selectWidth">
        <!-- form starts -->
        <section class="bg-white dark:bg-gray-900 mt-0">
            <div class="max-w-2xl px-4 mx-auto">
                <h2
                    class="mb-4 text-xl font-bold text-gray-900 dark:text-white"
                >
                    Edit Product
                </h2>
                <form action="#">
                    <div
                        class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5"
                    >
                        <div class="sm:col-span-2">
                            <label
                                for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Product Name</label
                            >
                            <input
                                v-model="form.title.value"
                                type="text"
                                id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Type product name"
                            />
                        </div>
                        <div>
                            <label
                                for="brand"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Brand</label
                            >
                            <select
                                v-model="form.brand_id.value"
                                id="brand"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            >
                                <option value="" selected>
                                    Select a brand
                                </option>
                                <option
                                    v-for="brand in brands"
                                    :key="brand.id"
                                    :value="brand.id"
                                >
                                    {{ brand.name }}
                                </option>
                            </select>
                        </div>
                        <div class="w-full">
                            <label
                                for="price"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Price</label
                            >
                            <input
                                v-model="form.price.value"
                                type="number"
                                id="price"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="0.00"
                            />
                        </div>
                        <div>
                            <label
                                for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Category</label
                            >
                            <select
                                v-model="form.category_id.value"
                                id="category"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            >
                                <option value="" selected>
                                    Select a category
                                </option>
                                <option
                                    v-for="category in categories"
                                    :key="category.id"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label
                                for="item-weight"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Quantity</label
                            >
                            <input
                                v-model="form.quantity.value"
                                type="number"
                                id="item-weight"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="0"
                            />
                        </div>
                        <div class="sm:col-span-2">
                            <label
                                for="description"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Description</label
                            >
                            <textarea
                                v-model="form.description.value"
                                id="description"
                                rows="3"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Write a product description here..."
                            >
                            </textarea>
                        </div>

                        <!-- multiple images upload -->
                        <div class="sm:col-span-2">
                            <label
                                for="description"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Product Images</label
                            >
                            <el-upload
                                v-model:file-list="form.product_images"
                                list-type="picture-card"
                                :on-preview="handlePictureCardPreview"
                                :on-remove="handleRemove"
                                :on-change="handleFileChange"
                                multiple
                            >
                                <el-icon>
                                    <Plus />
                                </el-icon>
                            </el-upload>
                            <el-dialog v-model="dialogVisible2">
                                <img
                                    w-full
                                    :src="dialogImageUrl"
                                    alt="Preview Image"
                                />
                            </el-dialog>
                        </div>
                        <!-- multiple images upload ends -->
                    </div>
                </form>
            </div>
        </section>

        <!-- form ends -->
        <template #footer>
            <div class="dialog-footer">
                <el-button @click="dialogVisibleEdit = false">Cancel</el-button>
                <el-button
                    color="#1A56DB"
                    type="primary"
                    @click="updateProduct"
                    :disabled="form.processing"
                >
                    Confirm
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<style>
.el-dialog {
    margin-top: 5vh !important;
}
</style>

<style scoped>
.custom-button {
    display: flex !important;
    gap: 10px !important;
    align-items: center !important;
    justify-content: start !important;
    padding: 8px 16px !important;
    font-size: 14px !important;
    color: #4a5568 !important; /* text-gray-700 */
    background-color: transparent !important;
    border: none !important;
    width: 100% !important;
}

.custom-button:hover {
    background-color: #f7fafc !important; /* hover:bg-gray-100 */
    color: #1a202c !important; /* dark:text-gray-200 */
}

.custom-button svg {
    width: 12px !important;
    height: 15px !important;
    margin-right: 8px !important;
}

.custom-button p {
    color: #f05252 !important; /* text-red-500 */
}
</style>
