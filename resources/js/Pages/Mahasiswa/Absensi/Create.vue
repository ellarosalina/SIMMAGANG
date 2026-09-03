<script setup>
import { computed, ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import MahasiswaLayout from '../../../Layouts/MahasiswaLayout.vue';

defineOptions({
    layout: MahasiswaLayout,
});

const page = usePage();

const props = defineProps({
    penempatan: {
        type: Object,
        default: null,
    },
    tanggalHariIni: {
        type: [String, Object],
        default: null,
    },
    hariHariIni: {
        type: String,
        default: '',
    },
    sudahAbsen: {
        type: Boolean,
        default: false,
    },
    reopening: {
        type: Object,
        default: null,
    },
    bolehIsiTanggalLama: {
        type: Boolean,
        default: false,
    },
});

const status = ref('hadir');
const jamMasuk = ref('');
const jamPulang = ref('');

const errors = computed(() => {
    return page.props.errors || {};
});

const hasErrors = computed(() => {
    return Object.keys(errors.value).length > 0;
});

const formatTanggal = (tanggal) => {
    if (!tanggal) {
        return '';
    }

    let date;

    if (typeof tanggal === 'string') {
        date = new Date(tanggal);
    } else if (tanggal.date) {
        date = new Date(tanggal.date);
    } else {
        date = new Date(tanggal);
    }

    if (isNaN(date.getTime())) {
        return '';
    }

    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    });
};

const formatTanggalInput = (tanggal) => {
    if (!tanggal) {
        return '';
    }

    if (typeof tanggal === 'string') {
        return tanggal.substring(0, 10);
    }

    if (tanggal.date) {
        return tanggal.date.substring(0, 10);
    }

    return '';
};

const tanggalTampilan = computed(() => {
    return formatTanggal(props.tanggalHariIni);
});

const tanggalValue = computed(() => {
    return formatTanggalInput(props.tanggalHariIni);
});

const bagianJamVisible = computed(() => {
    return status.value === 'hadir';
});

const aturFormAbsensi = () => {
    if (status.value !== 'hadir') {
        jamMasuk.value = '';
        jamPulang.value = '';
    }
};

const simpan = () => {
    router.post('/mahasiswa/absensi', {
        tanggal: tanggalValue.value,
        status: status.value,
        jam_masuk: status.value === 'hadir' ? jamMasuk.value : null,
        jam_pulang: status.value === 'hadir' ? jamPulang.value : null,
    });
};
</script>

<template>
    <div class="p-6">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">
                Isi Absensi
            </h1>

            <div
                v-if="hasErrors"
                class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg"
            >
                <ul class="list-disc list-inside">
                    <li
                        v-for="(error, key) in errors"
                        :key="key"
                    >
                        {{ Array.isArray(error) ? error[0] : error }}
                    </li>
                </ul>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">
                <form @submit.prevent="simpan">
                    <!-- Hari -->
                    <div class="mb-4">
                        <label class="block font-medium mb-1">
                            Hari
                        </label>

                        <input
                            type="text"
                            :value="hariHariIni"
                            readonly
                            class="w-full border border-gray-300 rounded-lg p-2 bg-gray-100 text-gray-700"
                        >
                    </div>

                    <!-- Tanggal -->
                    <div class="mb-4">
                        <label class="block font-medium mb-1">
                            Tanggal
                        </label>

                        <input
                            type="text"
                            :value="tanggalTampilan"
                            readonly
                            class="w-full border border-gray-300 rounded-lg p-2 bg-gray-100 text-gray-700"
                        >

                        <input
                            type="hidden"
                            name="tanggal"
                            :value="tanggalValue"
                        >
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="block font-medium mb-1">
                            Status
                        </label>

                        <select
                            v-model="status"
                            name="status"
                            id="statusAbsensi"
                            class="w-full border border-gray-300 rounded-lg p-2"
                            required
                            @change="aturFormAbsensi"
                        >
                            <option value="hadir">
                                Hadir
                            </option>

                            <option value="izin">
                                Izin
                            </option>

                            <option value="sakit">
                                Sakit
                            </option>

                            <option value="alpa">
                                Alpa
                            </option>
                        </select>
                    </div>

                    <!-- Jam Masuk dan Jam Pulang -->
                    <div
                        id="bagianJam"
                        :class="{ hidden: !bagianJamVisible }"
                    >
                        <!-- Jam Masuk -->
                        <div class="mb-4">
                            <label class="block font-medium mb-1">
                                Jam Masuk <span class="text-red-500">*</span>
                            </label>

                            <input
                                v-model="jamMasuk"
                                type="time"
                                name="jam_masuk"
                                id="jamMasuk"
                                :required="status === 'hadir'"
                                class="w-full border border-gray-300 rounded-lg p-2"
                            >
                        </div>

                        <!-- Jam Pulang -->
                        <div class="mb-4">
                            <label class="block font-medium mb-1">
                                Jam Pulang <span class="text-red-500">*</span>
                            </label>

                            <input
                                v-model="jamPulang"
                                type="time"
                                name="jam_pulang"
                                id="jamPulang"
                                :required="status === 'hadir'"
                                class="w-full border border-gray-300 rounded-lg p-2"
                            >
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="flex gap-2 mt-6">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                        >
                            Simpan
                        </button>

                        <Link
                            href="/mahasiswa/absensi"
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