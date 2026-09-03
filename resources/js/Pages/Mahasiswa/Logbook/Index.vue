<script setup>
import { computed, ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import MahasiswaLayout from '../../../Layouts/MahasiswaLayout.vue';

defineOptions({
    layout: MahasiswaLayout,
});

const props = defineProps({
    penempatan: {
        type: Object,
        default: null,
    },
    logbooks: {
        type: Object,
        default: () => ({
            data: [],
            total: 0,
            from: 0,
            to: 0,
            current_page: 1,
            last_page: 1,
            links: [],
        }),
    },
    status: {
        type: String,
        default: 'semua',
    },
});

const page = usePage();

const modalLogbook = ref(false);
const modalHapus = ref(false);

const logbookTerpilih = ref(null);
const logbookUntukDihapus = ref(null);

const formatTanggal = (tanggal) => {
    if (!tanggal) {
        return '-';
    }

    const bagianTanggal = String(tanggal)
        .split('T')[0]
        .split('-');

    if (bagianTanggal.length !== 3) {
        return tanggal;
    }

    const date = new Date(
        Number(bagianTanggal[0]),
        Number(bagianTanggal[1]) - 1,
        Number(bagianTanggal[2])
    );

    if (isNaN(date.getTime())) {
        return tanggal;
    }

    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

const statusLabel = (status) => {
    if (status === 'disetujui') {
        return 'Disetujui';
    }

    if (status === 'revisi') {
        return 'Direvisi';
    }

    return 'Menunggu';
};

const statusClass = (status) => {
    if (status === 'disetujui') {
        return 'inline-flex px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs';
    }

    if (status === 'revisi') {
        return 'inline-flex px-2.5 py-1 bg-red-100 text-red-700 rounded-full text-xs';
    }

    return 'inline-flex px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs';
};

const statusKosong = computed(() => {
    if (props.status === 'menunggu') {
        return 'Menunggu';
    }

    if (props.status === 'disetujui') {
        return 'Disetujui';
    }

    if (props.status === 'revisi') {
        return 'Direvisi';
    }

    return 'tersebut';
});

const pilihStatus = (event) => {
    const status = event.target.value;

    router.get(
        '/mahasiswa/logbook',
        {
            status: status,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

const lihatLogbook = (logbook) => {
    logbookTerpilih.value = logbook;
    modalLogbook.value = true;
    document.body.classList.add('overflow-hidden');
};

const tutupLogbook = () => {
    modalLogbook.value = false;
    logbookTerpilih.value = null;
    document.body.classList.remove('overflow-hidden');
};

const bukaModalHapus = (logbook) => {
    logbookUntukDihapus.value = logbook;
    modalHapus.value = true;
    document.body.classList.add('overflow-hidden');
};

const tutupModalHapus = () => {
    modalHapus.value = false;
    logbookUntukDihapus.value = null;
    document.body.classList.remove('overflow-hidden');
};

const hapusLogbook = () => {
    if (!logbookUntukDihapus.value) {
        return;
    }

    router.delete(
        `/mahasiswa/logbook/${logbookUntukDihapus.value.id}`,
        {
            preserveScroll: true,
            onSuccess: () => {
                tutupModalHapus();
            },
        }
    );
};

const dokumentasiUrl = (dokumentasi) => {
    if (!dokumentasi) {
        return null;
    }

    return `/storage/${dokumentasi}`;
};

const halaman = computed(() => {
    return props.logbooks?.current_page || 1;
});

const totalHalaman = computed(() => {
    return props.logbooks?.last_page || 1;
});

const linksPagination = computed(() => {
    return props.logbooks?.links || [];
});

const bukaHalaman = (url) => {
    if (!url) {
        return;
    }

    router.visit(url, {
        preserveState: true,
        preserveScroll: true,
    });
};

const tutupDenganEscape = (event) => {
    if (event.key === 'Escape') {
        tutupLogbook();
        tutupModalHapus();
    }
};

window.addEventListener('keydown', tutupDenganEscape);
</script>

<template>
    <div class="space-y-6">

        <div
            v-if="page.props.flash?.success"
            class="p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700"
        >
            {{ page.props.flash.success }}
        </div>

        <div
            v-if="page.props.flash?.error"
            class="p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700"
        >
            {{ page.props.flash.error }}
        </div>

        <div v-if="!penempatan">
            <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                <h2 class="text-lg font-semibold text-gray-800">
                    Belum Ada Penempatan Magang
                </h2>

                <p class="text-sm text-gray-500 mt-2">
                    Silakan hubungi Admin GTK untuk informasi lebih lanjut.
                </p>
            </div>
        </div>

        <div v-else>
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">

                <div class="px-6 py-5 border-b">
                    <div class="flex items-center justify-between gap-4">
                    </div>

                    <div class="flex items-center justify-between mt-4">

                        <select
                            :value="status"
                            @change="pilihStatus"
                            class="px-5 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="semua">
                                Semua
                            </option>

                            <option value="menunggu">
                                Menunggu
                            </option>

                            <option value="disetujui">
                                Disetujui
                            </option>

                            <option value="revisi">
                                Direvisi
                            </option>
                        </select>

                        <Link
                            href="/mahasiswa/logbook/create"
                            class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium transition"
                        >
                            + Tambah Logbook
                        </Link>

                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">

                        <thead class="bg-gray-50 border-b">
                            <tr>

                                <th class="px-5 py-4 text-left text-xs text-gray-500 uppercase">
                                    No
                                </th>

                                <th class="px-5 py-4 text-left text-xs text-gray-500 uppercase">
                                    Tanggal
                                </th>

                                <th class="px-5 py-4 text-left text-xs text-gray-500 uppercase">
                                    Kegiatan
                                </th>

                                <th class="px-5 py-4 text-center text-xs text-gray-500 uppercase">
                                    Dokumentasi
                                </th>

                                <th class="px-5 py-4 text-center text-xs text-gray-500 uppercase">
                                    Status
                                </th>

                                <th class="px-5 py-4 text-left text-xs text-gray-500 uppercase">
                                    Catatan
                                </th>

                                <th class="px-5 py-4 text-center text-xs text-gray-500 uppercase">
                                    Aksi
                                </th>

                            </tr>
                        </thead>

                        <tbody class="divide-y">

                            <tr
                                v-for="(logbook, index) in logbooks.data"
                                :key="logbook.id"
                                class="hover:bg-gray-50"
                            >

                                <td class="px-5 py-4 text-gray-500">
                                    {{ (logbooks.from || 1) + index }}
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <p class="font-medium text-gray-800">
                                        {{ formatTanggal(logbook.tanggal) }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-medium text-gray-800">
                                        {{ logbook.kegiatan }}
                                    </p>
                                </td>

                                <td class="px-5 py-4 text-center">

                                    <img
                                        v-if="logbook.dokumentasi"
                                        :src="dokumentasiUrl(logbook.dokumentasi)"
                                        alt="Dokumentasi"
                                        class="w-20 h-14 object-cover rounded-lg mx-auto"
                                    >

                                    <span
                                        v-else
                                        class="text-xs text-gray-400"
                                    >
                                        Tidak ada
                                    </span>

                                </td>

                                <td class="px-5 py-4 text-center">

                                    <span :class="statusClass(logbook.status_verifikasi)">
                                        {{ statusLabel(logbook.status_verifikasi) }}
                                    </span>

                                </td>

                                <td class="px-5 py-4 text-gray-600">
                                    {{ logbook.catatan_guru_pamong || '-' }}
                                </td>

                                <td class="px-5 py-4">

                                    <div class="flex justify-center items-center gap-2">

                                        <button
                                            type="button"
                                            @click="lihatLogbook(logbook)"
                                            title="Lihat"
                                            class="w-8 h-8 flex items-center justify-center bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition"
                                        >
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
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                />

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 15a3 3 0 100-6 3 3 0 000 6z"
                                                />
                                            </svg>
                                        </button>

                                        <Link
                                            :href="`/mahasiswa/logbook/${logbook.id}/edit`"
                                            title="Edit"
                                            class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition"
                                        >
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
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"
                                                />

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"
                                                />
                                            </svg>
                                        </Link>

                                        <button
                                            type="button"
                                            @click="bukaModalHapus(logbook)"
                                            title="Hapus"
                                            class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition"
                                        >
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
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6V7m-7 0h8"
                                                />

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"
                                                />
                                            </svg>
                                        </button>

                                    </div>

                                </td>

                            </tr>

                            <tr v-if="!logbooks.data || logbooks.data.length === 0">

                                <td
                                    colspan="7"
                                    class="px-6 py-12 text-center"
                                >
                                    <p class="text-sm text-gray-400">
                                        Tidak ada logbook dengan status
                                        <span class="font-semibold">
                                            {{ statusKosong }}
                                        </span>.
                                    </p>
                                </td>

                            </tr>

                        </tbody>

                    </table>
                </div>

                <div class="px-6 py-4 border-t bg-gray-50">
                    <p class="text-sm text-gray-500">
                        Total
                        <span class="font-semibold text-gray-700">
                            {{ logbooks.total || 0 }}
                        </span>
                        logbook
                    </p>
                </div>

                <div
                    v-if="totalHalaman > 1"
                    class="px-6 py-4 border-t"
                >
                    <div class="flex items-center justify-center gap-1">

                        <button
                            v-for="(link, index) in linksPagination"
                            :key="index"
                            type="button"
                            :disabled="!link.url"
                            @click="bukaHalaman(link.url)"
                            :class="[
                                'px-3 py-2 text-sm rounded-lg transition',
                                link.active
                                    ? 'bg-blue-600 text-white'
                                    : link.url
                                        ? 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'
                                        : 'bg-gray-100 text-gray-400 cursor-not-allowed'
                            ]"
                        >
                            <span v-if="link.label.includes('Previous') || link.label.includes('&laquo;')">
                                ‹
                            </span>

                            <span v-else-if="link.label.includes('Next') || link.label.includes('&raquo;')">
                                ›
                            </span>

                            <span v-else>
                                {{ link.label }}
                            </span>
                        </button>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <div
        v-if="modalLogbook"
        class="fixed inset-0 z-[9999] bg-black/50 p-4 flex items-center justify-center"
        @click.self="tutupLogbook"
    >
        <div
            class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden"
        >

            <div class="flex-shrink-0 flex items-center justify-between px-6 py-4 border-b">

                <h2 class="text-lg font-semibold text-gray-800">
                    Detail Logbook
                </h2>

                <button
                    type="button"
                    @click="tutupLogbook"
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100"
                >
                    ✕
                </button>

            </div>

            <div class="flex-1 min-h-0 overflow-y-auto px-6 py-5">

                <div class="mb-5">
                    <p class="text-xs uppercase text-gray-400 mb-1">
                        Tanggal
                    </p>

                    <p class="font-semibold text-gray-800">
                        {{ formatTanggal(logbookTerpilih?.tanggal) }}
                    </p>
                </div>

                <div class="mb-5">
                    <p class="text-xs uppercase text-gray-400 mb-1">
                        Kegiatan
                    </p>

                    <p class="font-semibold text-gray-800">
                        {{ logbookTerpilih?.kegiatan || '-' }}
                    </p>
                </div>

                <div class="mb-5">
                    <p class="text-xs uppercase text-gray-400 mb-2">
                        Detail Kegiatan
                    </p>

                    <div class="w-full p-4 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 whitespace-pre-line break-words leading-6">
                        {{ logbookTerpilih?.detail_kegiatan || '-' }}
                    </div>
                </div>

                <div class="mb-5">
                    <p class="text-xs uppercase text-gray-400 mb-2">
                        Status
                    </p>

                    <div>
                        <span :class="statusClass(logbookTerpilih?.status_verifikasi)">
                            {{ statusLabel(logbookTerpilih?.status_verifikasi) }}
                        </span>
                    </div>
                </div>

                <div class="mb-5">
                    <p class="text-xs uppercase text-gray-400 mb-2">
                        Catatan Guru Pamong
                    </p>

                    <div class="w-full p-4 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 whitespace-pre-line break-words">
                        {{ logbookTerpilih?.catatan_guru_pamong || '-' }}
                    </div>
                </div>

                <div>
                    <p class="text-xs uppercase text-gray-400 mb-2">
                        Dokumentasi
                    </p>

                    <div v-if="logbookTerpilih?.dokumentasi">
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                            <img
                                :src="dokumentasiUrl(logbookTerpilih.dokumentasi)"
                                alt="Dokumentasi Logbook"
                                class="w-full max-h-80 object-contain rounded-lg"
                            >
                        </div>
                    </div>

                    <div
                        v-else
                        class="p-4 bg-gray-50 border border-gray-200 rounded-lg"
                    >
                        <p class="text-sm text-gray-400">
                            Tidak ada dokumentasi.
                        </p>
                    </div>
                </div>

            </div>

            <div class="flex-shrink-0 px-6 py-4 border-t bg-gray-50 flex justify-end">

                <button
                    type="button"
                    @click="tutupLogbook"
                    class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300"
                >
                    Tutup
                </button>

            </div>

        </div>
    </div>

    <div
        v-if="modalHapus"
        class="fixed inset-0 z-[10000] bg-black/50 p-4 flex items-center justify-center"
        @click.self="tutupModalHapus"
    >
        <div
            class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden"
        >

            <div class="px-6 py-5 border-b">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 flex items-center justify-center bg-red-100 text-red-600 rounded-full">

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
                                d="M12 9v4m0 4h.01M10.29 3.86l-8.18 14A2 2 0 003.84 21h16.32a2 2 0 001.73-3.14l-8.18-14a2 2 0 00-3.42 0z"
                            />
                        </svg>

                    </div>

                    <div>

                        <h2 class="text-lg font-semibold text-gray-800">
                            Hapus Logbook?
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Konfirmasi penghapusan data
                        </p>

                    </div>

                </div>

            </div>

            <div class="px-6 py-5">

                <p class="text-sm text-gray-600 leading-6">
                    Apakah kamu yakin ingin menghapus logbook ini?
                    Data yang sudah dihapus tidak dapat dikembalikan.
                </p>

            </div>

            <div class="px-6 py-4 border-t bg-gray-50 flex justify-end gap-2">

                <button
                    type="button"
                    @click="tutupModalHapus"
                    class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium transition"
                >
                    Batal
                </button>

                <button
                    type="button"
                    @click="hapusLogbook"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-medium transition"
                >
                    Hapus
                </button>

            </div>

        </div>
    </div>
</template>