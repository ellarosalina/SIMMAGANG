<script setup>
import { computed, ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import MahasiswaLayout from '../../../Layouts/MahasiswaLayout.vue';

defineOptions({
    layout: MahasiswaLayout,
});

const props = defineProps({
    logbook: {
        type: Object,
        required: true,
    },
});

const page = usePage();

const tanggal = ref(
    String(props.logbook.tanggal).split('T')[0]
);

const kegiatan = ref(
    props.logbook.kegiatan || ''
);

const detailKegiatan = ref(
    props.logbook.detail_kegiatan || ''
);

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

const dokumentasiUrl = computed(() => {
    if (!props.logbook.dokumentasi) {
        return null;
    }

    return `/storage/${props.logbook.dokumentasi}`;
});

const pilihDokumentasi = (event) => {
    dokumentasi.value = event.target.files[0] || null;
};

const simpanPerubahan = () => {
    router.post(
        `/mahasiswa/logbook/${props.logbook.id}`,
        {
            _method: 'PUT',
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

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">

                    <h1 class="text-lg font-semibold text-gray-800">
                        Edit Logbook
                    </h1>

                    <Link
                        href="/mahasiswa/logbook"
                        class="text-gray-400 hover:text-gray-600 transition"
                        title="Kembali"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </Link>

                </div>

                <div
                    v-if="daftarError.length"
                    class="mx-6 mt-5 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg"
                >

                    <ul class="list-disc list-inside text-sm space-y-1">

                        <li
                            v-for="(error, index) in daftarError"
                            :key="index"
                        >
                            {{ error }}
                        </li>

                    </ul>

                </div>

                <form @submit.prevent="simpanPerubahan">

                    <div class="px-6 py-5 space-y-5">

                        <div>

                            <label class="block text-xs font-medium text-gray-400 uppercase mb-2">
                                Tanggal
                            </label>

                            <input
                                v-model="tanggal"
                                type="date"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required
                            >

                        </div>

                        <div>

                            <label class="block text-xs font-medium text-gray-400 uppercase mb-2">
                                Kegiatan
                            </label>

                            <input
                                v-model="kegiatan"
                                type="text"
                                placeholder="Contoh: Sosialisasi Kebijakan Baru"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required
                            >

                        </div>

                        <div>

                            <label class="block text-xs font-medium text-gray-400 uppercase mb-2">
                                Detail Kegiatan
                            </label>

                            <textarea
                                v-model="detailKegiatan"
                                rows="7"
                                placeholder="Jelaskan secara lengkap kegiatan yang dilakukan hari ini..."
                                class="w-full px-3 py-3 border border-gray-200 rounded-lg text-sm text-gray-800 bg-white resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required
                            ></textarea>

                            <p class="text-xs text-gray-400 mt-2">
                                Jelaskan aktivitas, tugas yang dikerjakan, dan hasil kegiatan.
                            </p>

                        </div>

                        <div>

                            <label class="block text-xs font-medium text-gray-400 uppercase mb-2">
                                Dokumentasi
                            </label>

                            <div
                                v-if="dokumentasiUrl"
                                class="mb-3"
                            >

                                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">

                                    <img
                                        :src="dokumentasiUrl"
                                        alt="Dokumentasi"
                                        class="w-full max-h-64 object-contain rounded-lg"
                                    >

                                </div>

                                <p class="text-xs text-gray-400 mt-2">
                                    Foto saat ini. Upload foto baru jika ingin menggantinya.
                                </p>

                            </div>

                            <input
                                type="file"
                                accept="image/*"
                                @change="pilihDokumentasi"
                                class="w-full border border-gray-200 rounded-lg p-2.5 text-sm text-gray-600 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >

                            <p class="text-xs text-gray-400 mt-2">
                                Format JPG/PNG, maksimal 2MB.
                            </p>

                        </div>

                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">

                            <div class="flex gap-3">

                                <svg
                                    class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"
                                    />
                                </svg>

                                <p class="text-sm text-gray-700">

                                    <strong>Catatan:</strong>

                                    Jika logbook diedit, status verifikasi akan kembali menjadi
                                    <strong>Menunggu</strong> dan harus diverifikasi ulang oleh Guru Pamong.

                                </p>

                            </div>

                        </div>

                    </div>

                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-end gap-2">

                        <Link
                            href="/mahasiswa/logbook"
                            class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition"
                        >
                            Batal
                        </Link>

                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition"
                        >
                            Simpan Perubahan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
</template>