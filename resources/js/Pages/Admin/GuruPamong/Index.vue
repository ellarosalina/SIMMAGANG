<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    guruPamongs: {
        type: Object,
        required: true,
    },
    search: {
        type: String,
        default: '',
    },
});

const search = ref(props.search || '');

const cariGuruPamong = () => {
    router.get(
        '/admin/guru-pamong',
        {
            search: search.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};

const resetSearch = () => {
    search.value = '';

    router.get(
        '/admin/guru-pamong',
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};

const createGuruPamong = () => {
    router.visit('/admin/guru-pamong/create');
};

const editGuruPamong = (id) => {
    router.visit(`/admin/guru-pamong/${id}/edit`);
};

const showDeleteModal = ref(false);
const selectedGuruPamong = ref(null);

const deleteGuruPamong = (guruPamong) => {
    selectedGuruPamong.value = guruPamong;
    showDeleteModal.value = true;
};

const cancelDelete = () => {
    showDeleteModal.value = false;
    selectedGuruPamong.value = null;
};

const confirmDelete = () => {
    if (!selectedGuruPamong.value) {
        return;
    }

    router.delete(`/admin/guru-pamong/${selectedGuruPamong.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteModal.value = false;
            selectedGuruPamong.value = null;
        },
    });
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

const numberOf = (index) => {
    return (
        (props.guruPamongs.current_page - 1) *
            props.guruPamongs.per_page +
        index +
        1
    );
};
</script>

<template>
    <div>
        <div
            v-if="$page.props.flash?.success"
            class="mb-4 p-4 bg-green-100 text-green-700 rounded"
        >
            {{ $page.props.flash.success }}
        </div>

        <div class="flex items-center justify-between mb-4">
            <button
                type="button"
                @click="createGuruPamong"
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

                <span>Tambah Guru Pamong</span>
            </button>

            <div class="flex items-center gap-2">
                <form @submit.prevent="cariGuruPamong">
                    <div class="relative">
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Cari Nama Guru Pamong..."
                            class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-400"
                        >

                        <button
                            type="submit"
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
                </form>

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

        <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="p-3 text-sm font-semibold text-gray-600 w-12">
                            No
                        </th>

                        <th class="p-3 text-sm font-semibold text-gray-600">
                            Nama
                        </th>

                        <th class="p-3 text-sm font-semibold text-gray-600">
                            Email
                        </th>

                        <th class="p-3 text-sm font-semibold text-gray-600">
                            Sekolah
                        </th>

                        <th class="p-3 text-sm font-semibold text-gray-600">
                            NIP
                        </th>

                        <th class="p-3 text-sm font-semibold text-gray-600">
                            Mapel
                        </th>

                        <th class="p-3 text-sm font-semibold text-gray-600">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="(guruPamong, index) in guruPamongs.data"
                        :key="guruPamong.id"
                        class="border-b hover:bg-gray-50"
                    >
                        <td class="p-3 text-sm">
                            {{ numberOf(index) }}
                        </td>

                        <td class="p-3 text-sm">
                            {{ guruPamong.user?.name || '-' }}
                        </td>

                        <td class="p-3 text-sm">
                            {{ guruPamong.user?.email || '-' }}
                        </td>

                        <td class="p-3 text-sm">
                            {{ guruPamong.sekolah?.nama_sekolah || '-' }}
                        </td>

                        <td class="p-3 text-sm">
                            {{ guruPamong.nip || '-' }}
                        </td>

                        <td class="p-3 text-sm">
                            {{ guruPamong.mapel || '-' }}
                        </td>

                        <td class="p-3 text-sm">
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    @click="editGuruPamong(guruPamong.id)"
                                    title="Edit"
                                    class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100"
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
                                            d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.5-8.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 8.5-8.5z"
                                        />
                                    </svg>
                                </button>

                                <button
                                    type="button"
                                    @click="deleteGuruPamong(guruPamong)"
                                    title="Hapus"
                                    class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-50 border border-red-200 rounded-md hover:bg-red-100"
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
                                            d="M6 7h12M10 11v6M14 11v6M9 7V4h6v3m-8 0l1 13h6L19 7"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr
                        v-if="
                            !guruPamongs.data ||
                            guruPamongs.data.length === 0
                        "
                    >
                        <td
                            colspan="7"
                            class="p-4 text-center text-gray-500"
                        >
                            <span v-if="search">
                                Data guru pamong "{{ search }}" tidak ditemukan.
                            </span>

                            <span v-else>
                                Belum ada data guru pamong.
                            </span>
                        </td>
                    </tr>
                </tbody>

                <tfoot>
                    <tr class="bg-gray-50 border-t font-semibold">
                        <td
                            colspan="7"
                            class="p-3 text-sm"
                        >
                            Total: {{ guruPamongs.total }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div
            v-if="guruPamongs.last_page > 1"
            class="mt-4 flex justify-end"
        >
            <div class="flex items-center gap-1">
                <button
                    v-if="guruPamongs.prev_page_url"
                    type="button"
                    @click="goToPage(guruPamongs.prev_page_url)"
                    class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                >
                    ‹
                </button>

                <span
                    v-else
                    class="px-3 py-2 text-sm text-gray-400 bg-white border border-gray-300 rounded-md"
                >
                    ‹
                </span>

                <button
                    v-for="page in guruPamongs.last_page"
                    :key="page"
                    type="button"
                    @click="goToPage(getPageUrl(page))"
                    class="px-3 py-2 text-sm rounded-md"
                    :class="
                        page === guruPamongs.current_page
                            ? 'text-gray-900 bg-white border border-gray-400 font-bold'
                            : 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50'
                    "
                >
                    {{ page }}
                </button>

                <button
                    v-if="guruPamongs.next_page_url"
                    type="button"
                    @click="goToPage(guruPamongs.next_page_url)"
                    class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                >
                    ›
                </button>

                <span
                    v-else
                    class="px-3 py-2 text-sm text-gray-400 bg-white border border-gray-300 rounded-md"
                >
                    ›
                </span>
            </div>
        </div>

        <!-- MODAL KONFIRMASI HAPUS -->
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 z-[10000] bg-black/50 p-4 flex items-center justify-center"
            @click.self="cancelDelete"
        >
            <div
                class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden"
                @click.stop
            >
                <div class="px-6 py-5 border-b">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 flex items-center justify-center bg-red-100 text-red-600 rounded-full"
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
                                    d="M12 9v4m0 4h.01M10.29 3.86l-8.18 14A2 2 0 003.84 21h16.32a2 2 0 001.73-3.14l-8.18-14a2 2 0 00-3.42 0z"
                                />
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">
                                Hapus Guru Pamong?
                            </h2>

                            <p class="text-sm text-gray-500 mt-1">
                                Konfirmasi penghapusan data
                            </p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-5">
                    <p class="text-sm text-gray-600 leading-6">
                        Apakah kamu yakin ingin menghapus guru pamong ini?

                        <span
                            v-if="selectedGuruPamong"
                            class="font-semibold text-gray-800"
                        >
                            {{ selectedGuruPamong.user?.name || '' }}
                        </span>

                        Akun login guru pamong ini juga akan terhapus.
                        Data yang sudah dihapus tidak dapat dikembalikan.
                    </p>
                </div>

                <div
                    class="px-6 py-4 border-t bg-gray-50 flex justify-end gap-2"
                >
                    <button
                        type="button"
                        @click="cancelDelete"
                        class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium transition"
                    >
                        Batal
                    </button>

                    <button
                        type="button"
                        @click="confirmDelete"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-medium transition"
                    >
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>