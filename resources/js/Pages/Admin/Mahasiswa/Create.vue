<script setup>
import { router, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';

defineOptions({
    layout: AdminLayout,
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    nim: '',
    universitas: '',
    fakultas: '',
    prodi: '',
    no_hp: '',
    alamat: '',
});

const submit = () => {
    form.post('/admin/mahasiswa', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const batal = () => {
    router.visit('/admin/mahasiswa');
};
</script>

<template>
    <div>
        <div
            v-if="Object.keys(form.errors).length > 0"
            class="mb-4 p-4 bg-red-100 text-red-700 rounded"
        >
            <ul class="list-disc list-inside">
                <li
                    v-for="(error, key) in form.errors"
                    :key="key"
                >
                    {{ error }}
                </li>
            </ul>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 max-w-2xl">
            <form @submit.prevent="submit">
                <h3 class="font-semibold text-lg mb-2 mt-2">
                    Akun Login
                </h3>

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

                <h3 class="font-semibold text-lg mb-2 mt-6">
                    Data Mahasiswa
                </h3>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        NIM
                    </label>

                    <input
                        v-model="form.nim"
                        type="text"
                        name="nim"
                        class="w-full border rounded p-2"
                    >
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Universitas
                    </label>

                    <input
                        v-model="form.universitas"
                        type="text"
                        name="universitas"
                        class="w-full border rounded p-2"
                    >
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Fakultas
                    </label>

                    <input
                        v-model="form.fakultas"
                        type="text"
                        name="fakultas"
                        class="w-full border rounded p-2"
                    >
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Program Studi
                    </label>

                    <input
                        v-model="form.prodi"
                        type="text"
                        name="prodi"
                        class="w-full border rounded p-2"
                    >
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        No. HP
                    </label>

                    <input
                        v-model="form.no_hp"
                        type="text"
                        name="no_hp"
                        class="w-full border rounded p-2"
                    >
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Alamat
                    </label>

                    <textarea
                        v-model="form.alamat"
                        name="alamat"
                        class="w-full border rounded p-2"
                    ></textarea>
                </div>

                <div class="flex gap-2 mt-6">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                    </button>

                    <button
                        type="button"
                        @click="batal"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
                    >
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>