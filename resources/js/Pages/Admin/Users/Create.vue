<script setup>
import { reactive } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';

defineOptions({
    layout: AdminLayout,
});

const page = usePage();

const form = reactive({
    name: '',
    email: '',
    password: '',
});

const submit = () => {
    router.post('/admin/users', form);
};
</script>

<template>
    <div>
        <div
            v-if="Object.keys(page.props.errors || {}).length"
            class="mb-4 p-4 bg-red-100 text-red-700 rounded"
        >
            <ul class="list-disc list-inside">
                <li
                    v-for="(error, key) in page.props.errors"
                    :key="key"
                >
                    {{ error }}
                </li>
            </ul>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 max-w-md">
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Nama Lengkap
                    </label>

                    <input
                        v-model="form.name"
                        type="text"
                        name="name"
                        class="w-full border rounded p-2"
                    >
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Email
                    </label>

                    <input
                        v-model="form.email"
                        type="email"
                        name="email"
                        class="w-full border rounded p-2"
                    >
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Password
                    </label>

                    <input
                        v-model="form.password"
                        type="password"
                        name="password"
                        class="w-full border rounded p-2"
                    >

                    <p class="text-sm text-gray-500 mt-1">
                        Minimal 8 karakter.
                    </p>
                </div>

                <div class="flex gap-2 mt-6">
                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                    >
                        Simpan
                    </button>

                    <Link
                        href="/admin/users"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
                    >
                        Batal
                    </Link>
                </div>
            </form>
        </div>
    </div>
</template>