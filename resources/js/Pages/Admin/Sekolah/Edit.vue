<script setup>
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import { router, useForm } from '@inertiajs/vue3';

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    sekolah: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    npsn: props.sekolah.npsn ?? '',
    nama_sekolah: props.sekolah.nama_sekolah ?? '',
    kepala_sekolah: props.sekolah.kepala_sekolah ?? '',
    jenjang: props.sekolah.jenjang ?? '',
    kecamatan: props.sekolah.kecamatan ?? '',
    kabupaten: props.sekolah.kabupaten ?? '',
    alamat: props.sekolah.alamat ?? '',
    status: props.sekolah.status ?? '',
    kuota_magang: props.sekolah.kuota_magang ?? 0,
});

const submit = () => {
    form.put(`/admin/sekolah/${props.sekolah.id}`, {
        preserveScroll: true,
    });
};

const kembali = () => {
    router.visit('/admin/sekolah');
};
</script>

<template>
    <div class="w-full">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">
                Edit Sekolah
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Ubah data sekolah.
            </p>
        </div>

        <div
            v-if="Object.keys(form.errors).length > 0"
            class="mb-5 p-4 bg-red-50 border border-red-200 rounded-lg"
        >
            <div class="flex items-start gap-3">
                <svg
                    class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v4m0 4h.01M10.29 3.86l-8.1 14a2 2 0 001.73 3h16.16a2 2 0 001.73-3l-8.1-14a2 2 0 00-3.42 0z"
                    />
                </svg>

                <div>
                    <p class="text-sm font-semibold text-red-700">
                        Data belum dapat diperbarui.
                    </p>

                    <ul class="mt-2 list-disc list-inside text-sm text-red-600 space-y-1">
                        <li
                            v-for="(error, field) in form.errors"
                            :key="field"
                        >
                            {{ error }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-3xl">
            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            NPSN
                        </label>

                        <input
                            v-model="form.npsn"
                            type="text"
                            placeholder="Masukkan NPSN"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400"
                            :class="{ 'border-red-400': form.errors.npsn }"
                        >

                        <p
                            v-if="form.errors.npsn"
                            class="mt-1 text-xs text-red-500"
                        >
                            {{ form.errors.npsn }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nama Sekolah
                        </label>

                        <input
                            v-model="form.nama_sekolah"
                            type="text"
                            placeholder="Masukkan nama sekolah"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400"
                            :class="{ 'border-red-400': form.errors.nama_sekolah }"
                        >

                        <p
                            v-if="form.errors.nama_sekolah"
                            class="mt-1 text-xs text-red-500"
                        >
                            {{ form.errors.nama_sekolah }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Kepala Sekolah
                        </label>

                        <input
                            v-model="form.kepala_sekolah"
                            type="text"
                            placeholder="Masukkan nama kepala sekolah"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400"
                            :class="{ 'border-red-400': form.errors.kepala_sekolah }"
                        >

                        <p
                            v-if="form.errors.kepala_sekolah"
                            class="mt-1 text-xs text-red-500"
                        >
                            {{ form.errors.kepala_sekolah }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Jenjang
                        </label>

                        <select
                            v-model="form.jenjang"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400"
                            :class="{ 'border-red-400': form.errors.jenjang }"
                        >
                            <option value="">
                                Pilih Jenjang
                            </option>

                            <option value="SMA">
                                SMA
                            </option>

                            <option value="SMK">
                                SMK
                            </option>

                            <option value="SLB">
                                SLB
                            </option>
                        </select>

                        <p
                            v-if="form.errors.jenjang"
                            class="mt-1 text-xs text-red-500"
                        >
                            {{ form.errors.jenjang }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Kecamatan
                        </label>

                        <input
                            v-model="form.kecamatan"
                            type="text"
                            placeholder="Masukkan kecamatan"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400"
                            :class="{ 'border-red-400': form.errors.kecamatan }"
                        >

                        <p
                            v-if="form.errors.kecamatan"
                            class="mt-1 text-xs text-red-500"
                        >
                            {{ form.errors.kecamatan }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Kabupaten
                        </label>

                        <input
                            v-model="form.kabupaten"
                            type="text"
                            placeholder="Masukkan kabupaten"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400"
                            :class="{ 'border-red-400': form.errors.kabupaten }"
                        >

                        <p
                            v-if="form.errors.kabupaten"
                            class="mt-1 text-xs text-red-500"
                        >
                            {{ form.errors.kabupaten }}
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Alamat
                        </label>

                        <textarea
                            v-model="form.alamat"
                            rows="4"
                            placeholder="Masukkan alamat lengkap sekolah"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 resize-none focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400"
                            :class="{ 'border-red-400': form.errors.alamat }"
                        ></textarea>

                        <p
                            v-if="form.errors.alamat"
                            class="mt-1 text-xs text-red-500"
                        >
                            {{ form.errors.alamat }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Status
                        </label>

                        <select
                            v-model="form.status"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400"
                            :class="{ 'border-red-400': form.errors.status }"
                        >
                            <option value="">
                                Pilih Status
                            </option>

                            <option value="negeri">
                                Negeri
                            </option>

                            <option value="swasta">
                                Swasta
                            </option>
                        </select>

                        <p
                            v-if="form.errors.status"
                            class="mt-1 text-xs text-red-500"
                        >
                            {{ form.errors.status }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Kuota Magang
                        </label>

                        <input
                            v-model="form.kuota_magang"
                            type="number"
                            min="0"
                            placeholder="Masukkan kuota"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400"
                            :class="{ 'border-red-400': form.errors.kuota_magang }"
                        >

                        <p
                            v-if="form.errors.kuota_magang"
                            class="mt-1 text-xs text-red-500"
                        >
                            {{ form.errors.kuota_magang }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-7 pt-5 border-t border-gray-100">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 disabled:opacity-60 disabled:cursor-not-allowed transition-colors"
                    >
                        <svg
                            v-if="form.processing"
                            class="w-4 h-4 animate-spin"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            />

                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                            />
                        </svg>

                        <span>
                            {{ form.processing ? 'Memperbarui...' : 'Update' }}
                        </span>
                    </button>

                    <button
                        type="button"
                        @click="kembali"
                        :disabled="form.processing"
                        class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 disabled:opacity-60 transition-colors"
                    >
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>