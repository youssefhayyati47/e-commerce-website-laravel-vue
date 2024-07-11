<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    terms: false,
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div
            class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0"
        >
            <a
                href="#"
                class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white"
            >
                <img class="w-12 h-12 mr-2" src="logo.png" alt="logo" />
                YMarket
            </a>
            <div
                class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700"
            >
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1
                        class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white"
                    >
                        Create an account
                    </h1>
                    <form
                        class="space-y-4 md:space-y-6"
                        @submit.prevent="submit"
                    >
                        <div>
                            <label
                                for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Your name</label
                            >
                            <input
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                id="name"
                                type="text"
                                v-model="form.name"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="Your name"
                            />
                        </div>
                        <div>
                            <label
                                for="email"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Your email</label
                            >
                            <input
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
                                autocomplete="username"
                                placeholder="example@example.com"
                            />
                        </div>
                        <div>
                            <label
                                for="password"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Password</label
                            >
                            <input
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                id="password"
                                type="password"
                                v-model="form.password"
                                required
                                autocomplete="new-password"
                                placeholder="****************"
                            />
                        </div>
                        <div>
                            <label
                                for="password_confirmation"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Confirm password</label
                            >
                            <input
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                id="password_confirmation"
                                type="password"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="****************"
                            />
                        </div>
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input
                                    id="terms"
                                    aria-describedby="terms"
                                    v-model="form.terms"
                                    type="checkbox"
                                    class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800"
                                    required=""
                                />
                            </div>
                            <div class="ml-3 text-sm">
                                <label
                                    for="terms"
                                    class="font-light text-gray-500 dark:text-gray-300"
                                    >I accept the
                                    <a
                                        class="font-medium text-blue-600 hover:underline dark:text-blue-500"
                                        href="#"
                                        >Terms and Conditions</a
                                    ></label
                                >
                            </div>
                        </div>
                        <button
                            type="submit"
                            class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                            Create an account
                        </button>
                        <p
                            class="text-sm font-light text-gray-500 dark:text-gray-400"
                        >
                            Already have an account?
                            <Link
                                :href="route('login')"
                                class="font-medium text-blue-600 hover:underline dark:text-blue-500"
                                >Login here
                            </Link>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>

<!-- <GuestLayout>
    <Head title="Register" />

    <form @submit.prevent="submit">
        <div>
            <InputLabel for="name" value="Name" />

            <TextInput
            class="mt-1 block w-full"
                id="name"
                type="text"
                v-model="form.name"
                required
                autofocus
                autocomplete="name"
            />

            <InputError class="mt-2" :message="form.errors.name" />
        </div>

        <div class="mt-4">
            <InputLabel for="email" value="Email" />

            <TextInput
            class="mt-1 block w-full"
                id="email"
                type="email"
                v-model="form.email"
                required
                autocomplete="username"
            />

            <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <div class="mt-4">
            <InputLabel for="password" value="Password" />

            <TextInput
            class="mt-1 block w-full"
                id="password"
                type="password"
                v-model="form.password"
                required
                autocomplete="new-password"
            />

            <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="mt-4">
            <InputLabel for="password_confirmation" value="Confirm Password" />

            <TextInput
            class="mt-1 block w-full"
                id="password_confirmation"
                type="password"
                v-model="form.password_confirmation"
                required
                autocomplete="new-password"
            />

            <InputError class="mt-2" :message="form.errors.password_confirmation" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <Link
                :href="route('login')"
                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                Already registered?
            </Link>

            <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Register
            </PrimaryButton>
        </div>
    </form>
</GuestLayout> -->
