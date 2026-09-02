<script setup>
import { ref, computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    guruPamong: {
        type: Object,
        required: true,
    },
    sekolahs: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    name: props.guruPamong.user?.name || '',
    email: props.guruPamong.user?.email || '',
    password: '',
    sekolah_id: props.guruPamong.sekolah_id || '',
    mapel: props.guruPamong.mapel || '',
    nip: props.guruPamong.nip || '',
    no_hp: props.guruPamong.no_hp || '',
});

const sekolahOpen = ref(false);
const sekolahSearch = ref('');

const selectedSekolahName = computed(() => {
    const sekolah = props.sekolahs.find(
        (sekolah) => String(sekolah.id) === String(form.sekolah_id)
    );

    return sekolah ? sekolah.nama_sekolah : '';
});

const toggleSekolah = () => {
    sekolahOpen.value = !sekolahOpen.value;
};

const selectSekolah = (sekolah) => {
    form.sekolah_id = sekolah.id;
    sekolahOpen.value = false;
    sekolahSearch.value = '';
};

const clearSekolah = () => {
    form.sekolah_id = '';
    sekolahOpen.value = false;
    sekolahSearch.value = '';
};

const filteredSekolahs = computed(() => {
    if (!sekolahSearch.value) {
        return props.sekolahs;
    }

    return props.sekolahs.filter((sekolah) =>
        sekolah.nama_sekolah
            .toLowerCase()
            .includes(sekolahSearch.value.toLowerCase())
    );
});

const submit = () => {
    form.put(`/admin/guru-pamong/${props.guruPamong.id}`, {
        preserveScroll: true,
    });
};

const batal = () => {
    router.visit('/admin/guru-pamong');
};
</script>

<template>
    <div>
        <div
            v-if="Object.keys(form.errors).length > 0"
            class="mb-3 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm"
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

        <div class="max-w-5xl mx-auto">
            <form @submit.prevent="submit">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- AKUN LOGIN -->
                        <div>
                            <div class="flex items-center gap-2 mb-5">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500">
                                    <svg
                                        class="w-4 h-4"
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

                                <h3 class="text-sm font-semibold text-gray-800">
                                    Akun Login
                                </h3>
                            </div>

                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Nama Lengkap
                                    </label>

                                    <input
                                        v-model="form.name"
                                        type="text"
                                        name="name"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-200"
                                    >
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Email
                                    </label>

                                    <input
                                        v-model="form.email"
                                        type="email"
                                        name="email"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-200"
                                    >
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Password Baru
                                    </label>

                                    <input
                                        v-model="form.password"
                                        type="password"
                                        name="password"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-200"
                                    >

                                    <p class="text-xs text-gray-500 mt-2">
                                        Kosongkan jika tidak ingin mengubah password.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- DATA GURU PAMONG -->
                        <div>
                            <div class="flex items-center gap-2 mb-5">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500">
                                    <svg
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-4h6v4"
                                        />
                                    </svg>
                                </div>

                                <h3 class="text-sm font-semibold text-gray-800">
                                    Data Guru Pamong
                                </h3>
                            </div>

                            <div class="space-y-4">
                                <div class="relative">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Sekolah
                                    </label>

                                    <input
                                        type="hidden"
                                        name="sekolah_id"
                                        :value="form.sekolah_id"
                                    >

                                    <button
                                        type="button"
                                        @click="toggleSekolah"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white text-left flex items-center justify-between focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-200"
                                    >
                                        <span
                                            :class="selectedSekolahName ? 'text-gray-800' : 'text-gray-400'"
                                        >
                                            {{ selectedSekolahName || '-- Pilih Sekolah --' }}
                                        </span>

                                        <svg
                                            class="w-4 h-4 text-gray-500"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 9l-7 7-7-7"
                                            />
                                        </svg>
                                    </button>

                                    <div
                                        v-if="sekolahOpen"
                                        class="absolute z-30 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg overflow-hidden"
                                    >
                                        <div class="p-2 border-b border-gray-200">
                                            <input
                                                v-model="sekolahSearch"
                                                type="text"
                                                placeholder="Cari sekolah..."
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-gray-400"
                                            >
                                        </div>

                                        <div class="max-h-48 overflow-y-auto">
                                            <button
                                                type="button"
                                                @click="clearSekolah"
                                                class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50"
                                            >
                                                -- Pilih Sekolah --
                                            </button>

                                            <button
                                                v-for="sekolah in filteredSekolahs"
                                                :key="sekolah.id"
                                                type="button"
                                                @click="selectSekolah(sekolah)"
                                                class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50"
                                            >
                                                {{ sekolah.nama_sekolah }}
                                            </button>

                                            <div
                                                v-if="sekolahSearch && filteredSekolahs.length === 0"
                                                class="px-3 py-3 text-sm text-gray-500 text-center"
                                            >
                                                Sekolah tidak ditemukan.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Mata Pelajaran
                                    </label>

                                    <input
                                        v-model="form.mapel"
                                        type="text"
                                        name="mapel"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-200"
                                    >
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        NIP
                                    </label>

                                    <input
                                        v-model="form.nip"
                                        type="text"
                                        name="nip"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-200"
                                    >
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        No. HP
                                    </label>

                                    <input
                                        v-model="form.no_hp"
                                        type="text"
                                        name="no_hp"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-200"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TOMBOL -->
                    <div class="flex justify-end gap-2 mt-6 pt-4 border-t border-gray-100">
                        <button
                            type="button"
                            @click="batal"
                            class="px-4 py-2 bg-white border border-gray-300 text-gray-600 text-sm rounded-lg hover:bg-gray-50"
                        >
                            Batal
                        </button>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>