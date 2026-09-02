<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    penempatan: {
        type: Object,
        required: true,
    },

    absensiPerStatus: {
        type: Object,
        required: true,
    },

    logbookPerStatus: {
        type: Object,
        required: true,
    },

    logbooks: {
        type: Array,
        default: () => [],
    },
});

const formatTanggal = (tanggal) => {
    if (!tanggal) {
        return '-';
    }

    const date = new Date(tanggal);

    if (Number.isNaN(date.getTime())) {
        return tanggal;
    }

    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

const formatStatus = (status) => {
    if (!status) {
        return '-';
    }

    return status.charAt(0).toUpperCase() + status.slice(1);
};

const statusClass = (status) => {
    if (status === 'disetujui') {
        return 'bg-green-100 text-green-700';
    }

    if (status === 'revisi') {
        return 'bg-red-100 text-red-700';
    }

    return 'bg-yellow-100 text-yellow-700';
};
</script>

<template>
    <div>

        <Link
            href="/admin/monitoring"
            class="text-sm text-blue-600 hover:underline mb-4 inline-block"
        >
            &larr; Kembali ke daftar
        </Link>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

            <div class="bg-white rounded-lg shadow-sm p-5 lg:col-span-1">

                <h3 class="font-semibold text-gray-700 mb-3">
                    Info Penempatan
                </h3>

                <dl class="space-y-2 text-sm">

                    <div>
                        <dt class="text-gray-500">
                            Mahasiswa
                        </dt>

                        <dd class="font-medium">
                            {{ penempatan.mahasiswa?.user?.name || '-' }}

                            <span v-if="penempatan.mahasiswa?.nim">
                                ({{ penempatan.mahasiswa.nim }})
                            </span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-gray-500">
                            Sekolah
                        </dt>

                        <dd class="font-medium">
                            {{ penempatan.sekolah?.nama_sekolah || '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-gray-500">
                            Guru Pamong
                        </dt>

                        <dd class="font-medium">
                            {{ penempatan.guru_pamong?.user?.name || penempatan.guruPamong?.user?.name || '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-gray-500">
                            Dosen Pembimbing
                        </dt>

                        <dd class="font-medium">
                            {{ penempatan.mahasiswa?.dosen_pembimbing || '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-gray-500">
                            Periode
                        </dt>

                        <dd class="font-medium">
                            {{ penempatan.periode || '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-gray-500">
                            Status
                        </dt>

                        <dd class="font-medium">
                            {{ formatStatus(penempatan.status) }}
                        </dd>
                    </div>

                </dl>

            </div>

            <div class="bg-white rounded-lg shadow-sm p-5 lg:col-span-2">

                <h3 class="font-semibold text-gray-700 mb-3">
                    Progress Magang
                </h3>

                <div class="flex items-center gap-3 mb-6">

                    <div class="flex-1 bg-gray-200 rounded-full h-4">

                        <div
                            class="bg-blue-600 h-4 rounded-full"
                            :style="{
                                width: `${penempatan.progress_percent || 0}%`
                            }"
                        ></div>

                    </div>

                    <span class="font-semibold">
                        {{ penempatan.progress_percent || 0 }}%
                    </span>

                </div>

                <div class="grid grid-cols-2 gap-4">

                    <div>

                        <h4 class="text-sm font-semibold text-gray-600 mb-2">
                            Kehadiran
                        </h4>

                        <ul class="text-sm space-y-1">

                            <li>
                                Hadir:
                                <strong>
                                    {{ absensiPerStatus.hadir || 0 }}
                                </strong>
                            </li>

                            <li>
                                Izin:
                                <strong>
                                    {{ absensiPerStatus.izin || 0 }}
                                </strong>
                            </li>

                            <li>
                                Sakit:
                                <strong>
                                    {{ absensiPerStatus.sakit || 0 }}
                                </strong>
                            </li>

                            <li>
                                Alpa:
                                <strong>
                                    {{ absensiPerStatus.alpa || 0 }}
                                </strong>
                            </li>

                        </ul>

                    </div>

                    <div>

                        <h4 class="text-sm font-semibold text-gray-600 mb-2">
                            Logbook
                        </h4>

                        <ul class="text-sm space-y-1">

                            <li>
                                Menunggu:
                                <strong>
                                    {{ logbookPerStatus.menunggu || 0 }}
                                </strong>
                            </li>

                            <li>
                                Disetujui:
                                <strong>
                                    {{ logbookPerStatus.disetujui || 0 }}
                                </strong>
                            </li>

                            <li>
                                Revisi:
                                <strong>
                                    {{ logbookPerStatus.revisi || 0 }}
                                </strong>
                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>

        <div class="bg-white rounded-lg shadow-sm p-5">

            <h3 class="font-semibold text-gray-700 mb-4">
                Daftar Logbook
            </h3>

            <div class="space-y-3">

                <div
                    v-for="logbook in logbooks"
                    :key="logbook.id"
                    class="border rounded p-3"
                >

                    <div class="flex justify-between items-start mb-1">

                        <span class="text-sm font-semibold">
                            {{ formatTanggal(logbook.tanggal) }}
                        </span>

                        <span
                            class="px-2 py-1 text-xs rounded"
                            :class="statusClass(logbook.status_verifikasi)"
                        >
                            {{ formatStatus(logbook.status_verifikasi) }}
                        </span>

                    </div>

                    <p class="text-sm text-gray-700">
                        {{ logbook.kegiatan }}
                    </p>

                    <p
                        v-if="logbook.catatan_guru_pamong"
                        class="text-xs text-gray-500 mt-1"
                    >
                        <strong>
                            Catatan:
                        </strong>

                        {{ logbook.catatan_guru_pamong }}
                    </p>

                </div>

                <p
                    v-if="!logbooks || logbooks.length === 0"
                    class="text-sm text-gray-500"
                >
                    Belum ada logbook.
                </p>

            </div>

        </div>

    </div>
</template>