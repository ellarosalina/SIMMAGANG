<script setup>
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import MahasiswaLayout from '../../../Layouts/MahasiswaLayout.vue';

defineOptions({
    layout: MahasiswaLayout,
});

const props = defineProps({
    penempatan: {
        type: Object,
        default: null,
    },
    rekapAbsensi: {
        type: Array,
        default: () => [],
    },
});

const halamanSekarang = ref(1);
const dataPerHalaman = 10;
const modalTerbuka = ref(false);
const detailAbsensi = ref({
    tanggal: '-',
    status: '-',
    masuk: '-',
    pulang: '-',
});

const tanggalHariIni = new Date();

const tanggalHariIniValue = computed(() => {
    const tahun = tanggalHariIni.getFullYear();
    const bulan = String(tanggalHariIni.getMonth() + 1).padStart(2, '0');
    const tanggal = String(tanggalHariIni.getDate()).padStart(2, '0');

    return `${tahun}-${bulan}-${tanggal}`;
});

const formatTanggal = (tanggal, opsi = {}) => {
    if (!tanggal) {
        return '-';
    }

    const bagianTanggal = String(tanggal).split('T')[0].split('-');

    if (bagianTanggal.length !== 3) {
        return '-';
    }

    const date = new Date(
        Number(bagianTanggal[0]),
        Number(bagianTanggal[1]) - 1,
        Number(bagianTanggal[2])
    );

    return new Intl.DateTimeFormat('id-ID', opsi).format(date);
};

const formatHariTanggal = (tanggal) => {
    return formatTanggal(tanggal, {
        weekday: 'long',
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    });
};

const formatHari = (tanggal) => {
    return formatTanggal(tanggal, {
        weekday: 'long',
    });
};

const formatTanggalSingkat = (tanggal) => {
    return formatTanggal(tanggal, {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

const formatJam = (jam) => {
    if (!jam) {
        return '-';
    }

    const nilai = String(jam);

    if (nilai.includes(':')) {
        const bagian = nilai.split(':');

        if (bagian.length >= 2) {
            return `${bagian[0].padStart(2, '0')}:${bagian[1].padStart(2, '0')}`;
        }
    }

    return nilai;
};

const sudahAbsenHariIni = computed(() => {
    return props.rekapAbsensi.some((item) => {
        return item.tanggal === tanggalHariIniValue.value &&
            item.absensi !== null;
    });
});

const hariIniAdalahWeekend = computed(() => {
    const hari = tanggalHariIni.getDay();

    return hari === 0 || hari === 6;
});

const totalData = computed(() => props.rekapAbsensi.length);

const totalHalaman = computed(() => {
    return Math.ceil(totalData.value / dataPerHalaman);
});

const dataHalaman = computed(() => {
    const mulai = (halamanSekarang.value - 1) * dataPerHalaman;
    const selesai = mulai + dataPerHalaman;

    return props.rekapAbsensi.slice(mulai, selesai);
});

const nomorAwal = computed(() => {
    if (totalData.value === 0) {
        return 0;
    }

    return (halamanSekarang.value - 1) * dataPerHalaman + 1;
});

const nomorAkhir = computed(() => {
    return Math.min(
        halamanSekarang.value * dataPerHalaman,
        totalData.value
    );
});

const nomorHalaman = computed(() => {
    const hasil = [];

    for (let i = 1; i <= totalHalaman.value; i++) {
        hasil.push(i);
    }

    return hasil;
});

const ubahHalaman = (halaman) => {
    if (
        halaman < 1 ||
        halaman > totalHalaman.value
    ) {
        return;
    }

    halamanSekarang.value = halaman;
};

const statusLabel = (status) => {
    if (status === 'hadir') {
        return 'Hadir';
    }

    if (status === 'sakit') {
        return 'Sakit';
    }

    if (status === 'izin') {
        return 'Izin';
    }

    if (status === 'alpa') {
        return 'Alpa';
    }

    if (status === 'belum_absen') {
        return 'Belum Absen';
    }

    if (status === 'dibuka') {
        return 'Dibuka Kembali';
    }

    return status;
};

const statusClass = (status) => {
    if (status === 'hadir') {
        return 'inline-flex items-center px-2.5 py-1 rounded-full bg-green-100 text-green-700 font-medium text-xs';
    }

    if (status === 'sakit') {
        return 'inline-flex items-center px-2.5 py-1 rounded-full bg-orange-100 text-orange-700 font-medium text-xs';
    }

    if (status === 'izin') {
        return 'inline-flex items-center px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 font-medium text-xs';
    }

    if (status === 'alpa') {
        return 'inline-flex items-center px-2.5 py-1 rounded-full bg-red-100 text-red-700 font-medium text-xs';
    }

    if (status === 'belum_absen') {
        return 'inline-flex items-center px-2.5 py-1 rounded-full bg-yellow-100 text-yellow-700 font-medium text-xs';
    }

    if (status === 'dibuka') {
        return 'inline-flex items-center px-2.5 py-1 rounded-full bg-indigo-100 text-indigo-700 font-medium text-xs';
    }

    return 'inline-flex items-center px-2.5 py-1 rounded-full bg-gray-100 text-gray-700 font-medium text-xs';
};

const lihatAbsensi = (item) => {
    detailAbsensi.value = {
        tanggal: formatHariTanggal(item.tanggal),
        status: statusLabel(item.status),
        masuk: formatJam(item.jam_masuk),
        pulang: formatJam(item.jam_pulang),
    };

    modalTerbuka.value = true;
};

const tutupAbsensi = () => {
    modalTerbuka.value = false;
};
</script>

<template>
    <div class="space-y-1">
        <div v-if="penempatan">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm text-gray-600">
                    Hari ini:
                    <span class="font-semibold text-gray-900">
                        {{ formatHariTanggal(tanggalHariIniValue) }}
                    </span>
                </div>

                <span
                    v-if="hariIniAdalahWeekend"
                    class="text-sm text-gray-600"
                >
                    Hari Libur - Absensi Tidak Tersedia
                </span>

                <span
                    v-else-if="sudahAbsenHariIni"
                    class="inline-flex items-center px-4 py-2 bg-green-100 text-green-700 text-sm font-medium rounded-lg"
                >
                    ✓ Sudah Absen Hari Ini
                </span>

                <Link
                    v-else
                    href="/mahasiswa/absensi/create"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-blue-600 border border-blue-600 text-sm font-medium rounded-lg hover:bg-blue-50 transition"
                >
                    + Isi Absensi Hari Ini
                </Link>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="text-center px-4 py-4 font-semibold text-gray-900 w-16">
                                    No
                                </th>
                                <th class="text-left px-4 py-4 font-semibold text-gray-900">
                                    Hari
                                </th>
                                <th class="text-left px-4 py-4 font-semibold text-gray-900">
                                    Tanggal
                                </th>
                                <th class="text-left px-4 py-4 font-semibold text-gray-900">
                                    Jam Masuk
                                </th>
                                <th class="text-left px-4 py-4 font-semibold text-gray-900">
                                    Jam Pulang
                                </th>
                                <th class="text-left px-4 py-4 font-semibold text-gray-900">
                                    Status
                                </th>
                                <th class="text-center px-4 py-4 font-semibold text-gray-900">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="(item, index) in dataHalaman"
                                :key="item.tanggal"
                                class="baris-absensi border-b border-gray-100 hover:bg-gray-50 transition"
                            >
                                <td class="px-4 py-4 text-center text-gray-500 nomor-baris">
                                    {{ nomorAwal + index }}
                                </td>

                                <td class="px-4 py-4 text-gray-900">
                                    {{ formatHari(item.tanggal) }}
                                </td>

                                <td class="px-4 py-4 text-gray-900">
                                    {{ formatTanggalSingkat(item.tanggal) }}
                                </td>

                                <td class="px-4 py-4 text-gray-700">
                                    {{ formatJam(item.jam_masuk) }}
                                </td>

                                <td class="px-4 py-4 text-gray-700">
                                    {{ formatJam(item.jam_pulang) }}
                                </td>

                                <td class="px-4 py-4">
                                    <span :class="statusClass(item.status)">
                                        {{ statusLabel(item.status) }}
                                    </span>
                                </td>

                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <Link
                                            v-if="item.status === 'belum_absen'"
                                            href="/mahasiswa/absensi/create"
                                            title="Isi Absen"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition"
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
                                                    d="M12 4v16m8-8H4"
                                                />
                                            </svg>
                                        </Link>

                                        <Link
                                            v-else-if="item.status === 'dibuka'"
                                            :href="`/mahasiswa/absensi/create?tanggal=${item.tanggal}`"
                                            title="Isi Absen"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition"
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
                                                    d="M12 4v16m8-8H4"
                                                />
                                            </svg>
                                        </Link>

                                        <button
                                            v-else
                                            type="button"
                                            @click="lihatAbsensi(item)"
                                            title="Lihat Detail"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 transition"
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
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="dataHalaman.length === 0">
                                <td
                                    colspan="7"
                                    class="px-4 py-10 text-center text-gray-500"
                                >
                                    Belum ada hari kerja dalam periode magang.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="totalData > 0"
                    class="border-t border-gray-200"
                >
                    <div class="px-6 py-4 bg-gray-50">
                        <span class="text-sm text-gray-500">
                            Total {{ totalData }} absensi
                        </span>
                    </div>

                    <div class="px-6 py-4 flex items-center justify-between">
                        <p class="text-sm text-gray-500">
                            Showing {{ nomorAwal }} to {{ nomorAkhir }} of {{ totalData }} results
                        </p>

                        <div class="flex items-center">
                            <button
                                type="button"
                                @click="ubahHalaman(halamanSekarang - 1)"
                                :disabled="halamanSekarang === 1"
                                class="w-10 h-10 flex items-center justify-center bg-gray-700 text-white border-r border-gray-600 rounded-l-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-800 transition"
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
                                        d="M15 19l-7-7 7-7"
                                    />
                                </svg>
                            </button>

                            <div class="flex">
                                <button
                                    v-for="halaman in nomorHalaman"
                                    :key="halaman"
                                    type="button"
                                    @click="ubahHalaman(halaman)"
                                    :class="[
                                        'w-10 h-10 flex items-center justify-center text-sm border-r border-gray-600 transition',
                                        halaman === halamanSekarang
                                            ? 'bg-gray-700 text-white font-medium'
                                            : 'bg-gray-600 text-gray-200 hover:bg-gray-700'
                                    ]"
                                >
                                    {{ halaman }}
                                </button>
                            </div>

                            <button
                                type="button"
                                @click="ubahHalaman(halamanSekarang + 1)"
                                :disabled="halamanSekarang === totalHalaman"
                                class="w-10 h-10 flex items-center justify-center bg-gray-700 text-white rounded-r-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-800 transition"
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
                                        d="M9 5l7 7-7 7"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <p class="text-gray-500">
                    Anda belum memiliki penempatan magang.
                </p>
            </div>
        </div>

        <div
            v-if="modalTerbuka"
            class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 px-4"
            @click.self="tutupAbsensi"
        >
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Detail Absensi
                    </h2>

                    <button
                        type="button"
                        @click="tutupAbsensi"
                        class="text-gray-400 hover:text-gray-600 text-xl"
                    >
                        ×
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-xs text-gray-500">
                            Tanggal
                        </p>
                        <p class="font-semibold text-gray-800 mt-1">
                            {{ detailAbsensi.tanggal }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500">
                            Status
                        </p>
                        <p class="font-semibold text-gray-800 mt-1">
                            {{ detailAbsensi.status }}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500">
                                Jam Masuk
                            </p>
                            <p class="font-semibold text-gray-800 mt-1">
                                {{ detailAbsensi.masuk }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">
                                Jam Pulang
                            </p>
                            <p class="font-semibold text-gray-800 mt-1">
                                {{ detailAbsensi.pulang }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t flex justify-end">
                    <button
                        type="button"
                        @click="tutupAbsensi"
                        class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>