<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    sekolahs: {
        type: Object,
        required: true,
    },
    search: {
        type: String,
        default: '',
    },
});

const search = ref(props.search || '');

let searchTimeout = null;

watch(search, (value) => {
    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(() => {
        router.get(
            '/admin/sekolah',
            {
                search: value,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            }
        );
    }, 400);
});

const createSekolah = () => {
    router.visit('/admin/sekolah/create');
};

const showSekolah = (id) => {
    router.visit(`/admin/sekolah/${id}`);
};

const editSekolah = (id) => {
    router.visit(`/admin/sekolah/${id}/edit`);
};

/*
|--------------------------------------------------------------------------
| Modal Hapus
|--------------------------------------------------------------------------
*/

const showDeleteModal = ref(false);
const selectedSekolah = ref(null);

const deleteSekolah = (sekolah) => {
    selectedSekolah.value = sekolah;
    showDeleteModal.value = true;
};

const cancelDelete = () => {
    showDeleteModal.value = false;
    selectedSekolah.value = null;
};

const confirmDelete = () => {
    if (!selectedSekolah.value) {
        return;
    }

    router.delete(`/admin/sekolah/${selectedSekolah.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteModal.value = false;
            selectedSekolah.value = null;
        },
    });
};

const resetSearch = () => {
    clearTimeout(searchTimeout);

    search.value = '';

    router.get(
        '/admin/sekolah',
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};

/*
|--------------------------------------------------------------------------
| Pagination
|--------------------------------------------------------------------------
*/

const getPageUrl = (page) => {
    const url = new URL(window.location.href);

    url.searchParams.set('page', page);

    if (search.value) {
        url.searchParams.set('search', search.value);
    } else {
        url.searchParams.delete('search');
    }

    return url.pathname + '?' + url.searchParams.toString();
};

const goToPage = (url) => {
    if (!url) {
        return;
    }

    router.visit(url, {
        preserveState: true,
        preserveScroll: true,
    });
};

const startNumber = () => {
    if (!props.sekolahs.current_page || !props.sekolahs.per_page) {
        return 1;
    }

    return (props.sekolahs.current_page - 1) * props.sekolahs.per_page + 1;
};

const numberOf = (index) => {
    return startNumber() + index;
};
</script>

<template>
    <div>
        <!-- Pesan sukses -->
        <div
            v-if="$page.props.flash?.success"
            class="mb-4 p-4 bg-green-100 text-green-700 rounded"
        >
            {{ $page.props.flash.success }}
        </div>

        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <!-- Tombol Tambah Sekolah -->
            <button
                type="button"
                @click="createSekolah"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg text-sm hover:bg-gray-50"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 4v16m8-8H4"
                    />
                </svg>

                <span>Tambah Sekolah</span>
            </button>

            <!-- Search + Reset -->
            <div class="flex items-center gap-2">
                <div class="relative">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari Sekolah, NPSN..."
                        class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-400"
                    >

                    <button
                        type="button"
                        class="absolute left-0 top-0 h-full px-3 text-gray-400 hover:text-gray-600"
                        title="Cari"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0z"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Reset aktif -->
                <button
                    v-if="search"
                    type="button"
                    @click="resetSearch"
                    class="inline-flex items-center justify-center w-10 h-10 border border-gray-300 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-50"
                    title="Reset pencarian"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4 4v5h5"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M20 20v-5h-5"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5.5 9A7.5 7.5 0 0 1 18 6.5L20 9M18.5 15A7.5 7.5 0 0 1 6 17.5L4 15"
                        />
                    </svg>
                </button>

                <!-- Reset tidak aktif -->
                <button
                    v-else
                    type="button"
                    disabled
                    class="inline-flex items-center justify-center w-10 h-10 border border-gray-300 rounded-lg text-gray-400 bg-gray-50 cursor-default"
                    title="Reset pencarian"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4 4v5h5"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M20 20v-5h-5"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5.5 9A7.5 7.5 0 0 1 18 6.5L20 9M18.5 15A7.5 7.5 0 0 1 6 17.5L4 15"
                        />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Tabel -->
        <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
            <table class="min-w-[1400px] w-full text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th
                            class="p-3 text-sm font-semibold text-gray-600 w-12 whitespace-nowrap"
                        >
                            No
                        </th>

                        <th
                            class="p-3 text-sm font-semibold text-gray-600 min-w-[120px] whitespace-nowrap"
                        >
                            NPSN
                        </th>

                        <th
                            class="p-3 text-sm font-semibold text-gray-600 min-w-[220px] whitespace-nowrap"
                        >
                            Nama Sekolah
                        </th>

                        <th
                            class="p-3 text-sm font-semibold text-gray-600 min-w-[200px] whitespace-nowrap"
                        >
                            Kepala Sekolah
                        </th>

                        <th
                            class="p-3 text-sm font-semibold text-gray-600 min-w-[100px] whitespace-nowrap"
                        >
                            Jenjang
                        </th>

                        <th
                            class="p-3 text-sm font-semibold text-gray-600 min-w-[160px] whitespace-nowrap"
                        >
                            Kecamatan
                        </th>

                        <th
                            class="p-3 text-sm font-semibold text-gray-600 min-w-[160px] whitespace-nowrap"
                        >
                            Kabupaten
                        </th>

                        <th
                            class="p-3 text-sm font-semibold text-gray-600 min-w-[300px] whitespace-nowrap"
                        >
                            Alamat
                        </th>

                        <th
                            class="p-3 text-sm font-semibold text-gray-600 min-w-[100px] whitespace-nowrap"
                        >
                            Status
                        </th>

                        <th
                            class="p-3 text-sm font-semibold text-gray-600 min-w-[140px] whitespace-nowrap"
                        >
                            Kuota
                        </th>

                        <th
                            class="p-3 text-sm font-semibold text-gray-600 min-w-[100px] whitespace-nowrap"
                        >
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <!-- Data sekolah -->
                    <tr
                        v-for="(sekolah, index) in sekolahs.data"
                        :key="sekolah.id"
                        class="border-b hover:bg-gray-50"
                    >
                        <!-- No -->
                        <td class="p-3 text-sm whitespace-nowrap">
                            {{ numberOf(index) }}
                        </td>

                        <!-- NPSN -->
                        <td class="p-3 text-sm whitespace-nowrap">
                            {{ sekolah.npsn || '-' }}
                        </td>

                        <!-- Nama Sekolah -->
                        <td
                            class="p-3 text-sm min-w-[220px] whitespace-nowrap"
                        >
                            {{ sekolah.nama_sekolah || '-' }}
                        </td>

                        <!-- Kepala Sekolah -->
                        <td
                            class="p-3 text-sm min-w-[200px] whitespace-nowrap"
                        >
                            {{ sekolah.kepala_sekolah || '-' }}
                        </td>

                        <!-- Jenjang -->
                        <td
                            class="p-3 text-sm min-w-[100px] whitespace-nowrap"
                        >
                            {{ sekolah.jenjang || '-' }}
                        </td>

                        <!-- Kecamatan -->
                        <td
                            class="p-3 text-sm min-w-[160px] whitespace-nowrap"
                        >
                            {{ sekolah.kecamatan || '-' }}
                        </td>

                        <!-- Kabupaten -->
                        <td
                            class="p-3 text-sm min-w-[160px] whitespace-nowrap"
                        >
                            {{ sekolah.kabupaten || '-' }}
                        </td>

                        <!-- Alamat -->
                        <td
                            class="p-3 text-sm min-w-[300px] whitespace-nowrap"
                        >
                            {{ sekolah.alamat || '-' }}
                        </td>

                        <!-- Status -->
                        <td
                            class="p-3 text-sm min-w-[100px] whitespace-nowrap"
                        >
                            <span
                                class="px-2 py-1 text-xs rounded"
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
                        </td>

                        <!-- Kuota -->
                        <td
                            class="p-3 text-sm min-w-[140px] whitespace-nowrap"
                        >
                            {{ sekolah.kuota_magang ?? 0 }}
                        </td>

                        <!-- Aksi -->
                        <td class="p-3 text-sm whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <!-- Lihat -->
                                <button
                                    type="button"
                                    @click="showSekolah(sekolah.id)"
                                    title="Lihat"
                                    class="inline-flex items-center justify-center w-8 h-8 text-gray-600 bg-gray-50 border border-gray-200 rounded-md hover:bg-gray-100"
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
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"
                                        />
                                    </svg>
                                </button>

                                <!-- Edit -->
                                <button
                                    type="button"
                                    @click="editSekolah(sekolah.id)"
                                    title="Edit"
                                    class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100"
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
                                </button>

                                <!-- Hapus -->
                                <button
                                    type="button"
                                    @click="deleteSekolah(sekolah)"
                                    title="Hapus"
                                    class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-50 border border-red-200 rounded-md hover:bg-red-100"
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
                                            d="M6 7h12M10 11v6M14 11v6M9 7V4h6v3m-8 0l1 13h6L19 7"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Data kosong -->
                    <tr
                        v-if="
                            !sekolahs.data ||
                            sekolahs.data.length === 0
                        "
                    >
                        <td
                            colspan="11"
                            class="p-4 text-center text-gray-500"
                        >
                            <span v-if="search">
                                Data sekolah tidak ditemukan.
                            </span>

                            <span v-else>
                                Belum ada data sekolah.
                            </span>
                        </td>
                    </tr>
                </tbody>

                <!-- Total -->
                <tfoot>
                    <tr class="bg-gray-50 border-t font-semibold">
                        <td
                            colspan="11"
                            class="p-3 text-sm text-gray-900 whitespace-nowrap"
                        >
                            Total: {{ sekolahs.total }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex items-center justify-end">
            <div class="flex items-center gap-1">
                <!-- Previous -->
                <button
                    type="button"
                    v-if="sekolahs.prev_page_url"
                    @click="goToPage(sekolahs.prev_page_url)"
                    class="inline-flex items-center justify-center min-w-[36px] h-9 px-3 border border-gray-300 rounded-lg text-sm text-gray-600 bg-white hover:bg-gray-50"
                    title="Sebelumnya"
                >
                    &laquo;
                </button>

                <button
                    type="button"
                    v-else
                    disabled
                    class="inline-flex items-center justify-center min-w-[36px] h-9 px-3 border border-gray-200 rounded-lg text-sm text-gray-300 bg-gray-50 cursor-default"
                    title="Sebelumnya"
                >
                    &laquo;
                </button>

                <!-- Nomor halaman -->
                <button
                    v-for="page in sekolahs.last_page"
                    :key="page"
                    type="button"
                    @click="goToPage(getPageUrl(page))"
                    class="inline-flex items-center justify-center min-w-[36px] h-9 px-3 border rounded-lg text-sm"
                    :class="
                        page === sekolahs.current_page
                            ? 'bg-gray-900 text-white border-gray-900'
                            : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50'
                    "
                >
                    {{ page }}
                </button>

                <!-- Next -->
                <button
                    type="button"
                    v-if="sekolahs.next_page_url"
                    @click="goToPage(sekolahs.next_page_url)"
                    class="inline-flex items-center justify-center min-w-[36px] h-9 px-3 border border-gray-300 rounded-lg text-sm text-gray-600 bg-white hover:bg-gray-50"
                    title="Berikutnya"
                >
                    &raquo;
                </button>

                <button
                    type="button"
                    v-else
                    disabled
                    class="inline-flex items-center justify-center min-w-[36px] h-9 px-3 border border-gray-200 rounded-lg text-sm text-gray-300 bg-gray-50 cursor-default"
                    title="Berikutnya"
                >
                    &raquo;
                </button>
            </div>
        </div>

        <!-- Modal Konfirmasi Hapus -->
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4"
        >
            <!-- Overlay -->
            <div
                class="absolute inset-0"
                @click="cancelDelete"
            ></div>

            <!-- Kotak Modal -->
            <div
                class="relative w-full max-w-md bg-white rounded-xl shadow-xl overflow-hidden"
            >
                <!-- Header Modal -->
                <div class="px-6 py-5 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-red-50"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5 text-red-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 7h12M10 11v6M14 11v6M9 7V4h6v3m-8 0l1 13h6L19 7"
                                />
                            </svg>
                        </div>

                        <div>
                            <h3
                                class="text-base font-semibold text-gray-800"
                            >
                                Hapus Data Sekolah
                            </h3>

                            <p class="text-xs text-gray-500 mt-1">
                                Konfirmasi penghapusan data
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Isi Modal -->
                <div class="px-6 py-5">
                    <p class="text-sm text-gray-600 leading-6">
                        Apakah Anda yakin ingin menghapus data sekolah
                        <span
                            v-if="selectedSekolah"
                            class="font-semibold text-gray-800"
                        >
                            {{ selectedSekolah.nama_sekolah }}
                        </span>
                        ?
                    </p>

                    <p class="mt-2 text-xs text-gray-500">
                        Data yang sudah dihapus tidak dapat dikembalikan.
                    </p>
                </div>

                <!-- Tombol Modal -->
                <div
                    class="flex items-center justify-end gap-2 px-6 py-4 bg-gray-50 border-t border-gray-200"
                >
                    <!-- Batal -->
                    <button
                        type="button"
                        @click="cancelDelete"
                        class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg text-sm hover:bg-gray-50"
                    >
                        Batal
                    </button>

                    <!-- Hapus -->
                    <button
                        type="button"
                        @click="confirmDelete"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700"
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
                                d="M6 7h12M10 11v6M14 11v6M9 7V4h6v3m-8 0l1 13h6L19 7"
                            />
                        </svg>

                        <span>Hapus</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>