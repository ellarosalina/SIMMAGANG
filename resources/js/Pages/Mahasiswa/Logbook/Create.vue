<script setup>
import { computed, ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import MahasiswaLayout from '../../../Layouts/MahasiswaLayout.vue';

defineOptions({
    layout: MahasiswaLayout,
});

defineProps({
    penempatan: {
        type: Object,
        default: null,
    },
});

const page = usePage();

const tanggal = ref(
    new Date().toISOString().split('T')[0]
);

const kegiatan = ref('');
const detailKegiatan = ref('');
const dokumentasi = ref(null);

const errors = computed(() => {
    return page.props.errors || {};
});

const daftarError = computed(() => {
    const hasil = [];

    Object.values(errors.value).forEach((error) => {
        if (Array.isArray(error)) {
            error.forEach((item) => hasil.push(item));
        } else {
            hasil.push(error);
        }
    });

    return hasil;
});

const pilihDokumentasi = (event) => {
    dokumentasi.value = event.target.files[0] || null;
};

const simpan = () => {
    router.post(
        '/mahasiswa/logbook',
        {
            tanggal: tanggal.value,
            kegiatan: kegiatan.value,
            detail_kegiatan: detailKegiatan.value,
            dokumentasi: dokumentasi.value,
        },
        {
            forceFormData: true,
        }
    );
};
</script>

<template>
    <div class="p-6">

        <div class="max-w-2xl mx-auto">

            <h1 class="text-2xl font-bold text-gray-800 mb-6">
                Tambah Logbook
            </h1>

            <div
                v-if="daftarError.length"
                class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg"
            >
                <ul class="list-disc list-inside">
                    <li
                        v-for="(error, index) in daftarError"
                        :key="index"
                    >
                        {{ error }}
                    </li>
                </ul>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">

                <form @submit.prevent="simpan">

                    <div class="mb-4">

                        <label class="block font-medium mb-1">
                            Tanggal
                        </label>

                        <input
                            v-model="tanggal"
                            type="date"
                            class="w-full border border-gray-300 rounded-lg p-2"
                            required
                        >

                    </div>

                    <div>

                        <label class="block text-sm font-medium text-gray-800 mb-2">
                            Kegiatan
                        </label>

                        <input
                            v-model="kegiatan"
                            type="text"
                            placeholder="Contoh: Sosialisasi Kebijakan Baru"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 focus:border-blue-500 focus:ring-blue-500"
                            required
                        >

                    </div>

                    <div class="mt-5">

                        <label class="block text-sm font-medium text-gray-800 mb-2">
                            Detail Kegiatan
                        </label>

                        <textarea
                            v-model="detailKegiatan"
                            rows="6"
                            placeholder="Jelaskan secara lengkap kegiatan yang dilakukan hari ini..."
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 focus:border-blue-500 focus:ring-blue-500"
                            required
                        ></textarea>

                        <p class="text-xs text-gray-500 mt-2">
                            Jelaskan aktivitas, tugas yang dikerjakan, dan hasil kegiatan.
                        </p>

                    </div>

                    <div class="mb-4">

                        <label class="block font-medium mb-1">
                            Dokumentasi (opsional)
                        </label>

                        <input
                            type="file"
                            accept="image/*"
                            @change="pilihDokumentasi"
                            class="w-full border border-gray-300 rounded-lg p-2"
                        >

                        <p class="text-sm text-gray-500 mt-1">
                            Format gambar (JPG/PNG), maksimal 2MB.
                        </p>

                    </div>

                    <div class="flex gap-2 mt-6">

                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                        >
                            Simpan
                        </button>

                        <Link
                            href="/mahasiswa/logbook"
                            class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300"
                        >
                            Batal
                        </Link>

                    </div>

                </form>

            </div>

        </div>

    </div>
</template>