<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    penempatans: {
        type: Object,
        required: true,
    },

    search: {
        type: String,
        default: '',
    },
});

const search = ref(props.search || '');

const cariMonitoring = () => {
    router.get(
        '/admin/monitoring',
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
        '/admin/monitoring',
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
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
        (props.penempatans.current_page - 1) *
            props.penempatans.per_page +
        index +
        1
    );
};

const formatStatus = (status) => {
    if (!status) {
        return '-';
    }

    return status.charAt(0).toUpperCase() + status.slice(1);
};
</script>

<template>
    <div>

        <div class="flex items-center justify-between mb-4">

            <a
                href="/admin/monitoring/export"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg text-sm hover:bg-gray-50"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5 text-green-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 17v-6m0 0 3 3m-3-3-3 3m8-7h3m-3 4h3m-3 4h3M5 21h14a2 2 0 0 0 2-2V7.5L16.5 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2z"
                    />
                </svg>

                <span>
                    Export Excel
                </span>
            </a>

            <div class="flex items-center gap-2">

                <form @submit.prevent="cariMonitoring">

                    <div class="relative">

                        <input
                            v-model="search"
                            type="text"
                            name="search"
                            placeholder="Cari Monitoring..."
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
                            Mahasiswa
                        </th>

                        <th class="p-3 text-sm font-semibold text-gray-600">
                            Sekolah
                        </th>

                        <th class="p-3 text-sm font-semibold text-gray-600">
                            Periode
                        </th>

                        <th class="p-3 text-sm font-semibold text-gray-600">
                            Progress
                        </th>

                        <th class="p-3 text-sm font-semibold text-gray-600">
                            Kehadiran
                        </th>

                        <th class="p-3 text-sm font-semibold text-gray-600">
                            Logbook
                        </th>

                        <th class="p-3 text-sm font-semibold text-gray-600">
                            Status
                        </th>

                        <th class="p-3 text-sm font-semibold text-gray-600">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <tr
                        v-for="(penempatan, index) in penempatans.data"
                        :key="penempatan.id"
                        class="border-b hover:bg-gray-50"
                    >

                        <td class="p-3 text-sm">
                            {{ numberOf(index) }}
                        </td>

                        <td class="p-3 text-sm">
                            {{ penempatan.mahasiswa?.user?.name || '-' }}
                        </td>

                        <td class="p-3 text-sm">
                            {{ penempatan.sekolah?.nama_sekolah || '-' }}
                        </td>

                        <td class="p-3 text-sm">
                            {{ penempatan.periode || '-' }}
                        </td>

                        <td class="p-3 text-sm w-40">

                            <div class="flex items-center gap-2">

                                <div
                                    class="flex-1 bg-gray-200 rounded-full h-2"
                                >
                                    <div
                                        class="bg-blue-600 h-2 rounded-full"
                                        :style="{
                                            width: `${penempatan.progress_percent || 0}%`
                                        }"
                                    ></div>
                                </div>

                                <span class="text-xs text-gray-500">
                                    {{ penempatan.progress_percent || 0 }}%
                                </span>

                            </div>

                        </td>

                        <td class="p-3 text-sm">
                            {{ penempatan.hadir_count || 0 }}/{{ penempatan.absensis_count || 0 }}
                        </td>

                        <td class="p-3 text-sm">

                            {{ penempatan.logbook_disetujui_count || 0 }}/{{ penempatan.logbooks_count || 0 }}

                            <span
                                v-if="penempatan.logbook_menunggu_count > 0"
                                class="text-xs text-yellow-600"
                            >
                                ({{ penempatan.logbook_menunggu_count }} menunggu)
                            </span>

                        </td>

                        <td class="p-3 text-sm">

                            <span
                                class="px-2 py-1 text-xs rounded"
                                :class="{
                                    'bg-blue-100 text-blue-700':
                                        penempatan.status === 'berjalan',

                                    'bg-green-100 text-green-700':
                                        penempatan.status === 'selesai',

                                    'bg-red-100 text-red-700':
                                        penempatan.status === 'dibatalkan',

                                    'bg-yellow-100 text-yellow-700':
                                        penempatan.status === 'menunggu'
                                }"
                            >
                                {{ formatStatus(penempatan.status) }}
                            </span>

                        </td>

                        <td class="p-3 text-sm">

                            <div class="flex items-center gap-2">

                                <Link
                                    :href="`/admin/monitoring/${penempatan.id}`"
                                    title="Lihat Detail"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-gray-100 hover:text-gray-600 transition"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-4 h-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.458 12C3.732 8.943 7.523 6.5 12 6.5s8.268 2.443 9.542 5.5C20.268 15.057 16.477 17.5 12 17.5S3.732 15.057 2.458 12z"
                                        />

                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="2.5"
                                        />
                                    </svg>
                                </Link>

                                <a
                                    :href="`/admin/monitoring/${penempatan.id}/export`"
                                    title="Unduh Monitoring"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-gray-100 hover:text-gray-600 transition"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-4 h-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 4v11"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="m8 11 4 4 4-4"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M5 20h14"
                                        />
                                    </svg>
                                </a>

                            </div>

                        </td>

                    </tr>

                    <tr
                        v-if="!penempatans.data || penempatans.data.length === 0"
                    >
                        <td
                            colspan="9"
                            class="p-4 text-center text-gray-500"
                        >
                            <span v-if="search">
                                Data monitoring tidak ditemukan.
                            </span>

                            <span v-else>
                                Belum ada data penempatan.
                            </span>
                        </td>
                    </tr>

                </tbody>

                <tfoot>

                    <tr class="bg-gray-50 border-t font-semibold">

                        <td
                            colspan="9"
                            class="p-3 text-sm"
                        >
                            Total: {{ penempatans.total }}
                        </td>

                    </tr>

                </tfoot>

            </table>

        </div>

        <div
    v-if="penempatans.total > penempatans.per_page"
    class="mt-4"
>
    <div class="flex items-center justify-end gap-1">
        <button
            v-if="penempatans.prev_page_url"
            type="button"
            @click="goToPage(penempatans.prev_page_url)"
            class="inline-flex items-center justify-center min-w-[36px] h-9 px-3 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white hover:bg-gray-100 transition"
            title="Sebelumnya"
        >
            ‹
        </button>

        <button
            v-else
            type="button"
            disabled
            class="inline-flex items-center justify-center min-w-[36px] h-9 px-3 border border-gray-300 rounded-lg text-sm text-gray-400 bg-gray-100 cursor-default"
            title="Sebelumnya"
        >
            ‹
        </button>

        <button
            v-for="page in penempatans.last_page"
            :key="page"
            type="button"
            @click="goToPage(getPageUrl(page))"
            class="inline-flex items-center justify-center min-w-[36px] h-9 px-3 rounded-lg text-sm font-medium transition"
            :class="
                page === penempatans.current_page
                    ? 'bg-gray-900 text-white border border-gray-900 font-bold'
                    : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-100'
            "
        >
            {{ page }}
        </button>

        <button
            v-if="penempatans.next_page_url"
            type="button"
            @click="goToPage(penempatans.next_page_url)"
            class="inline-flex items-center justify-center min-w-[36px] h-9 px-3 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white hover:bg-gray-100 transition"
            title="Berikutnya"
        >
            ›
        </button>

        <button
            v-else
            type="button"
            disabled
            class="inline-flex items-center justify-center min-w-[36px] h-9 px-3 border border-gray-300 rounded-lg text-sm text-gray-400 bg-gray-100 cursor-default"
            title="Berikutnya"
        >
            ›
        </button>
    </div>
</div>

    </div>
</template>