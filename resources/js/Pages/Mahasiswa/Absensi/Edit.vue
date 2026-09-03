<script setup>
import { computed, ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import MahasiswaLayout from '../../../Layouts/MahasiswaLayout.vue';

defineOptions({
    layout: MahasiswaLayout,
});

const page = usePage();

const props = defineProps({
    absensi: {
        type: Object,
        required: true,
    },
});

const status = ref(props.absensi.status || 'hadir');
const jamMasuk = ref(props.absensi.jam_masuk || '');
const jamPulang = ref(props.absensi.jam_pulang || '');

const errors = computed(() => {
    return page.props.errors || {};
});

const hasErrors = computed(() => {
    return Object.keys(errors.value).length > 0;
});

const tanggal = computed(() => {
    if (!props.absensi.tanggal) {
        return null;
    }

    const date = new Date(props.absensi.tanggal);

    if (isNaN(date.getTime())) {
        return null;
    }

    return date;
});

const hari = computed(() => {
    if (!tanggal.value) {
        return '-';
    }

    return tanggal.value.toLocaleDateString('id-ID', {
        weekday: 'long',
    });
});

const tanggalTampilan = computed(() => {
    if (!tanggal.value) {
        return '-';
    }

    return tanggal.value.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    });
});

const update = () => {
    router.put(`/mahasiswa/absensi/${props.absensi.id}`, {
        status: status.value,
        jam_masuk: jamMasuk.value || null,
        jam_pulang: jamPulang.value || null,
    });
};
</script>

<template>
    <div class="p-6">
        <div class="max-w-2xl mx-auto">

            <h1 class="text-2xl font-bold text-gray-800 mb-6">
                Edit Absensi
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

                <form @submit.prevent="update">

                    <!-- Hari -->
                    <div class="mb-4">
                        <label class="block font-medium mb-1">
                            Hari
                        </label>

                        <input
                            type="text"
                            :value="hari"
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
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="block font-medium mb-1">
                            Status
                        </label>

                        <select
                            v-model="status"
                            name="status"
                            class="w-full border border-gray-300 rounded-lg p-2"
                            required
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

                    <!-- Jam Masuk -->
                    <div class="mb-4">
                        <label class="block font-medium mb-1">
                            Jam Masuk
                        </label>

                        <input
                            v-model="jamMasuk"
                            type="time"
                            name="jam_masuk"
                            class="w-full border border-gray-300 rounded-lg p-2"
                        >
                    </div>

                    <!-- Jam Pulang -->
                    <div class="mb-4">
                        <label class="block font-medium mb-1">
                            Jam Pulang
                        </label>

                        <input
                            v-model="jamPulang"
                            type="time"
                            name="jam_pulang"
                            class="w-full border border-gray-300 rounded-lg p-2"
                        >
                    </div>

                    <div class="flex gap-2 mt-6">

                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                        >
                            Update
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