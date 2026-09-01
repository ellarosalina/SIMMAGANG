<script setup>
import { router } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    sekolah: {
        type: Object,
        required: true,
    },
});

const kembali = () => {
    router.visit('/admin/sekolah');
};

const editSekolah = () => {
    router.visit(`/admin/sekolah/${props.sekolah.id}/edit`);
};
</script>

<template>
    <div>
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">
                    Detail Sekolah
                </h2>
                <p class="text-sm text-gray-500">
                    Informasi lengkap sekolah mitra magang
                </p>
            </div>

            <div class="flex items-center gap-2">
                <button
                    type="button"
                    @click="kembali"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg text-sm hover:bg-gray-50"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>

                    <span>Kembali</span>
                </button>

                <button
                    type="button"
                    @click="editSekolah"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg text-sm hover:bg-gray-50"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.5-8.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 8.5-8.5z"
                        />
                    </svg>

                    <span>Edit</span>
                </button>
            </div>
        </div>

        <!-- Detail -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="border-b bg-gray-50 px-5 py-4">
                <h3 class="text-base font-semibold text-gray-800">
                    Informasi Sekolah
                </h3>
            </div>

            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                    <!-- NPSN -->
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">
                            NPSN
                        </p>
                        <p class="text-sm text-gray-800">
                            {{ sekolah.npsn || '-' }}
                        </p>
                    </div>

                    <!-- Nama Sekolah -->
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">
                            Nama Sekolah
                        </p>
                        <p class="text-sm text-gray-800">
                            {{ sekolah.nama_sekolah || '-' }}
                        </p>
                    </div>

                    <!-- Kepala Sekolah -->
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">
                            Kepala Sekolah
                        </p>
                        <p class="text-sm text-gray-800">
                            {{ sekolah.kepala_sekolah || '-' }}
                        </p>
                    </div>

                    <!-- Jenjang -->
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">
                            Jenjang
                        </p>
                        <p class="text-sm text-gray-800">
                            {{ sekolah.jenjang || '-' }}
                        </p>
                    </div>

                    <!-- Kecamatan -->
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">
                            Kecamatan
                        </p>
                        <p class="text-sm text-gray-800">
                            {{ sekolah.kecamatan || '-' }}
                        </p>
                    </div>

                    <!-- Kabupaten -->
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">
                            Kabupaten
                        </p>
                        <p class="text-sm text-gray-800">
                            {{ sekolah.kabupaten || '-' }}
                        </p>
                    </div>

                    <!-- Status -->
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">
                            Status
                        </p>

                        <span
                            class="inline-flex px-2 py-1 text-xs rounded"
                            :class="
                                sekolah.status === 'negeri'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-gray-100 text-gray-700'
                            "
                        >
                            {{
                                sekolah.status
                                    ? sekolah.status.charAt(0).toUpperCase() +
                                      sekolah.status.slice(1)
                                    : '-'
                            }}
                        </span>
                    </div>

                    <!-- Kuota -->
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">
                            Kuota Magang
                        </p>
                        <p class="text-sm text-gray-800">
                            {{ sekolah.kuota_magang ?? 0 }} mahasiswa
                        </p>
                    </div>

                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <p class="text-xs font-medium text-gray-500 mb-1">
                            Alamat
                        </p>
                        <p class="text-sm text-gray-800 leading-6">
                            {{ sekolah.alamat || '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>