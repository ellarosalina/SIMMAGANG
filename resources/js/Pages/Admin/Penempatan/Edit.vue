<script setup>
import { computed, ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    mahasiswas: {
        type: Array,
        required: true,
    },
    sekolahs: {
        type: Array,
        required: true,
    },
    guruPamongs: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    mahasiswa_id: '',
    sekolah_id: '',
    guru_pamong_id: '',
    periode: '',
    tanggal_mulai: '',
    tanggal_selesai: '',
    status: 'menunggu',
});

const mahasiswaOpen = ref(false);
const mahasiswaSearch = ref('');

const sekolahOpen = ref(false);
const sekolahSearch = ref('');

const guruPamongOpen = ref(false);
const guruPamongSearch = ref('');

const selectedMahasiswa = computed(() => {
    return props.mahasiswas.find(
        mahasiswa => String(mahasiswa.id) === String(form.mahasiswa_id)
    );
});

const selectedSekolah = computed(() => {
    return props.sekolahs.find(
        sekolah => String(sekolah.id) === String(form.sekolah_id)
    );
});

const selectedGuruPamong = computed(() => {
    return props.guruPamongs.find(
        guruPamong => String(guruPamong.id) === String(form.guru_pamong_id)
    );
});

const filteredMahasiswas = computed(() => {
    const keyword = mahasiswaSearch.value.toLowerCase();

    if (!keyword) {
        return props.mahasiswas;
    }

    return props.mahasiswas.filter(mahasiswa => {
        const name = mahasiswa.user?.name?.toLowerCase() ?? '';
        const nim = mahasiswa.nim?.toLowerCase() ?? '';

        return (
            name.includes(keyword) ||
            nim.includes(keyword)
        );
    });
});

const filteredSekolahs = computed(() => {
    const keyword = sekolahSearch.value.toLowerCase();

    if (!keyword) {
        return props.sekolahs;
    }

    return props.sekolahs.filter(sekolah => {
        const name = sekolah.nama_sekolah?.toLowerCase() ?? '';

        return name.includes(keyword);
    });
});

const filteredGuruPamongs = computed(() => {
    const keyword = guruPamongSearch.value.toLowerCase();

    return props.guruPamongs.filter(guruPamong => {
        const sesuaiSekolah =
            String(guruPamong.sekolah_id) ===
            String(form.sekolah_id);

        const name =
            guruPamong.user?.name?.toLowerCase() ?? '';

        const sesuaiPencarian =
            !keyword ||
            name.includes(keyword);

        return sesuaiSekolah && sesuaiPencarian;
    });
});

const pilihMahasiswa = (mahasiswa) => {
    form.mahasiswa_id = mahasiswa.id;
    mahasiswaOpen.value = false;
    mahasiswaSearch.value = '';
};

const pilihSekolah = (sekolah) => {
    form.sekolah_id = sekolah.id;
    form.guru_pamong_id = '';

    sekolahOpen.value = false;
    sekolahSearch.value = '';
    guruPamongSearch.value = '';
};

const pilihGuruPamong = (guruPamong) => {
    form.guru_pamong_id = guruPamong.id;
    guruPamongOpen.value = false;
    guruPamongSearch.value = '';
};

const clearMahasiswa = () => {
    form.mahasiswa_id = '';
    mahasiswaSearch.value = '';
    mahasiswaOpen.value = false;
};

const clearSekolah = () => {
    form.sekolah_id = '';
    form.guru_pamong_id = '';

    sekolahSearch.value = '';
    guruPamongSearch.value = '';

    sekolahOpen.value = false;
};

const clearGuruPamong = () => {
    form.guru_pamong_id = '';
    guruPamongSearch.value = '';
    guruPamongOpen.value = false;
};

const submit = () => {
    form.post('/admin/penempatan', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            form.status = 'menunggu';
        },
    });
};

const batal = () => {
    router.visit('/admin/penempatan');
};
</script>

<template>
    <div>
        <div
            v-if="Object.keys(form.errors).length > 0"
            class="mb-4 p-4 bg-red-100 text-red-700 rounded"
        >
            <ul class="list-disc list-inside">
                <li
                    v-for="(error, key) in form.errors"
                    :key="key"
                >
                    {{ error }}
                </li>
            </ul>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 max-w-2xl">
            <form @submit.prevent="submit">

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Mahasiswa
                    </label>

                    <div class="relative">
                        <button
                            type="button"
                            @click="mahasiswaOpen = !mahasiswaOpen"
                            class="w-full border rounded p-2 text-left bg-white flex items-center justify-between"
                        >
                            <span
                                :class="
                                    selectedMahasiswa
                                        ? 'text-gray-800'
                                        : 'text-gray-400'
                                "
                            >
                                {{
                                    selectedMahasiswa
                                        ? `${selectedMahasiswa.user?.name ?? ''} (${selectedMahasiswa.nim ?? ''})`
                                        : '-- Pilih Mahasiswa --'
                                }}
                            </span>

                            <span class="text-gray-500">
                                ▼
                            </span>
                        </button>

                        <div
                            v-if="mahasiswaOpen"
                            class="absolute z-30 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg overflow-hidden"
                        >
                            <div class="p-2 border-b border-gray-200">
                                <input
                                    v-model="mahasiswaSearch"
                                    type="text"
                                    placeholder="Cari mahasiswa..."
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-200"
                                >
                            </div>

                            <div class="max-h-48 overflow-y-auto">
                                <button
                                    type="button"
                                    @click="clearMahasiswa"
                                    class="w-full px-3 py-2 text-left text-sm text-gray-500 hover:bg-gray-50"
                                >
                                    -- Pilih Mahasiswa --
                                </button>

                                <button
                                    v-for="mahasiswa in filteredMahasiswas"
                                    :key="mahasiswa.id"
                                    type="button"
                                    @click="pilihMahasiswa(mahasiswa)"
                                    class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50"
                                >
                                    {{ mahasiswa.user?.name }}

                                    <span class="text-gray-400">
                                        ({{ mahasiswa.nim }})
                                    </span>
                                </button>

                                <div
                                    v-if="
                                        mahasiswaSearch &&
                                        filteredMahasiswas.length === 0
                                    "
                                    class="px-3 py-3 text-sm text-gray-500 text-center"
                                >
                                    Mahasiswa tidak ditemukan.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Sekolah
                    </label>

                    <div class="relative">
                        <button
                            type="button"
                            @click="sekolahOpen = !sekolahOpen"
                            class="w-full border rounded p-2 text-left bg-white flex items-center justify-between"
                        >
                            <span
                                :class="
                                    selectedSekolah
                                        ? 'text-gray-800'
                                        : 'text-gray-400'
                                "
                            >
                                {{
                                    selectedSekolah
                                        ? selectedSekolah.nama_sekolah
                                        : '-- Pilih Sekolah --'
                                }}
                            </span>

                            <span class="text-gray-500">
                                ▼
                            </span>
                        </button>

                        <div
                            v-if="sekolahOpen"
                            class="absolute z-30 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg overflow-hidden"
                        >
                            <div class="p-2 border-b border-gray-200">
                                <input
                                    v-model="sekolahSearch"
                                    type="text"
                                    placeholder="Cari sekolah..."
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-200"
                                >
                            </div>

                            <div class="max-h-48 overflow-y-auto">
                                <button
                                    type="button"
                                    @click="clearSekolah"
                                    class="w-full px-3 py-2 text-left text-sm text-gray-500 hover:bg-gray-50"
                                >
                                    -- Pilih Sekolah --
                                </button>

                                <button
                                    v-for="sekolah in filteredSekolahs"
                                    :key="sekolah.id"
                                    type="button"
                                    @click="pilihSekolah(sekolah)"
                                    class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50"
                                >
                                    {{ sekolah.nama_sekolah }}
                                </button>

                                <div
                                    v-if="
                                        sekolahSearch &&
                                        filteredSekolahs.length === 0
                                    "
                                    class="px-3 py-3 text-sm text-gray-500 text-center"
                                >
                                    Sekolah tidak ditemukan.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Guru Pamong
                    </label>

                    <div class="relative">
                        <button
                            type="button"
                            @click="guruPamongOpen = !guruPamongOpen"
                            :disabled="!form.sekolah_id"
                            class="w-full border rounded p-2 text-left bg-white flex items-center justify-between disabled:bg-gray-50 disabled:text-gray-400"
                        >
                            <span
                                :class="
                                    selectedGuruPamong
                                        ? 'text-gray-800'
                                        : 'text-gray-400'
                                "
                            >
                                {{
                                    selectedGuruPamong
                                        ? selectedGuruPamong.user?.name
                                        : '-- Pilih Guru Pamong --'
                                }}
                            </span>

                            <span class="text-gray-500">
                                ▼
                            </span>
                        </button>

                        <div
                            v-if="guruPamongOpen"
                            class="absolute z-30 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg overflow-hidden"
                        >
                            <div class="p-2 border-b border-gray-200">
                                <input
                                    v-model="guruPamongSearch"
                                    type="text"
                                    placeholder="Cari guru pamong..."
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-200"
                                >
                            </div>

                            <div class="max-h-48 overflow-y-auto">
                                <button
                                    type="button"
                                    @click="clearGuruPamong"
                                    class="w-full px-3 py-2 text-left text-sm text-gray-500 hover:bg-gray-50"
                                >
                                    -- Pilih Guru Pamong --
                                </button>

                                <button
                                    v-for="guruPamong in filteredGuruPamongs"
                                    :key="guruPamong.id"
                                    type="button"
                                    @click="pilihGuruPamong(guruPamong)"
                                    class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50"
                                >
                                    {{ guruPamong.user?.name }}
                                </button>

                                <div
                                    v-if="
                                        filteredGuruPamongs.length === 0
                                    "
                                    class="px-3 py-3 text-sm text-gray-500 text-center"
                                >
                                    Tidak ada guru pamong di sekolah ini.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Periode
                    </label>

                    <input
                        v-model="form.periode"
                        type="text"
                        placeholder="Contoh: Ganjil 2026/2027"
                        class="w-full border rounded p-2"
                    >
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Tanggal Mulai
                    </label>

                    <input
                        v-model="form.tanggal_mulai"
                        type="date"
                        class="w-full border rounded p-2"
                    >
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Tanggal Selesai
                    </label>

                    <input
                        v-model="form.tanggal_selesai"
                        type="date"
                        class="w-full border rounded p-2"
                    >
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Status
                    </label>

                    <select
                        v-model="form.status"
                        class="w-full border rounded p-2"
                    >
                        <option value="menunggu">
                            Menunggu
                        </option>

                        <option value="berjalan">
                            Berjalan
                        </option>

                        <option value="selesai">
                            Selesai
                        </option>

                        <option value="dibatalkan">
                            Dibatalkan
                        </option>
                    </select>
                </div>

                <div class="flex gap-2 mt-6">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                    </button>

                    <button
                        type="button"
                        @click="batal"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
                    >
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>