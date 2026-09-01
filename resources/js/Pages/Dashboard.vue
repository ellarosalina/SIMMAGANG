<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AdminLayout from '../Layouts/AdminLayout.vue';

defineOptions({
    layout: AdminLayout,
});

const page = usePage();

const totalMahasiswa = computed(() => {
    return page.props.totalMahasiswa ?? 0;
});

const totalSekolah = computed(() => {
    return page.props.totalSekolah ?? 0;
});

const totalGuruPamong = computed(() => {
    return page.props.totalGuruPamong ?? 0;
});

const totalPenempatan = computed(() => {
    return page.props.totalPenempatan ?? 0;
});
</script>

<template>
    <div>
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">
                Dashboard Monitoring Magang
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Sistem Informasi Monitoring Magang Mahasiswa.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">
                            Mahasiswa
                        </p>

                        <p class="mt-2 text-2xl font-bold text-gray-900">
                            {{ totalMahasiswa }}
                        </p>
                    </div>

                    <div class="w-11 h-11 rounded-lg bg-blue-50 flex items-center justify-center">
                        <svg
                            class="w-6 h-6 text-blue-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">
                            Sekolah
                        </p>

                        <p class="mt-2 text-2xl font-bold text-gray-900">
                            {{ totalSekolah }}
                        </p>
                    </div>

                    <div class="w-11 h-11 rounded-lg bg-green-50 flex items-center justify-center">
                        <svg
                            class="w-6 h-6 text-green-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">
                            Guru Pamong
                        </p>

                        <p class="mt-2 text-2xl font-bold text-gray-900">
                            {{ totalGuruPamong }}
                        </p>
                    </div>

                    <div class="w-11 h-11 rounded-lg bg-purple-50 flex items-center justify-center">
                        <svg
                            class="w-6 h-6 text-purple-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">
                            Penempatan
                        </p>

                        <p class="mt-2 text-2xl font-bold text-gray-900">
                            {{ totalPenempatan }}
                        </p>
                    </div>

                    <div class="w-11 h-11 rounded-lg bg-orange-50 flex items-center justify-center">
                        <svg
                            class="w-6 h-6 text-orange-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                            />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900">
                Monitoring Magang
            </h3>

            <p class="mt-2 text-sm text-gray-500">
                Data dashboard diambil langsung dari database melalui Laravel.
            </p>

            <div class="mt-5 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm font-medium text-green-700">
                    ✓ Vue 3 + Inertia.js + Laravel berhasil terhubung dengan database.
                </p>
            </div>
        </div>
    </div>
</template>