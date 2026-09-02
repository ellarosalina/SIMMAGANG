<script setup>
import { ref, computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    mahasiswas: {
        type: Array,
        default: () => [],
    },
    sekolahs: {
        type: Array,
        default: () => [],
    },
    guruPamongs: {
        type: Array,
        default: () => [],
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

const openDropdown = ref(null);

const searchMahasiswa = ref('');
const searchSekolah = ref('');
const searchGuruPamong = ref('');

const selectedMahasiswaName = ref('');
const selectedSekolahName = ref('');
const selectedGuruPamongName = ref('');

const toggleDropdown = (dropdown) => {
    if (openDropdown.value === dropdown) {
        openDropdown.value = null;
        return;
    }

    openDropdown.value = dropdown;
};

const closeDropdown = () => {
    openDropdown.value = null;
};

const filteredMahasiswas = computed(() => {
    const keyword = searchMahasiswa.value.toLowerCase().trim();

    if (!keyword) {
        return props.mahasiswas;
    }

    return props.mahasiswas.filter((mahasiswa) => {
        const nama = mahasiswa.user?.name
            ? mahasiswa.user.name.toLowerCase()
            : '';

        const nim = mahasiswa.nim
            ? String(mahasiswa.nim).toLowerCase()
            : '';

        return nama.includes(keyword) || nim.includes(keyword);
    });
});

const filteredSekolahs = computed(() => {
    const keyword = searchSekolah.value.toLowerCase().trim();

    if (!keyword) {
        return props.sekolahs;
    }

    return props.sekolahs.filter((sekolah) => {
        const nama = sekolah.nama_sekolah
            ? sekolah.nama_sekolah.toLowerCase()
            : '';

        const npsn = sekolah.npsn
            ? String(sekolah.npsn).toLowerCase()
            : '';

        return nama.includes(keyword) || npsn.includes(keyword);
    });
});

const filteredGuruPamongs = computed(() => {
    if (!form.sekolah_id) {
        return [];
    }

    const keyword = searchGuruPamong.value.toLowerCase().trim();

    return props.guruPamongs.filter((guruPamong) => {
        if (String(guruPamong.sekolah_id) !== String(form.sekolah_id)) {
            return false;
        }

        if (!keyword) {
            return true;
        }

        const nama = guruPamong.user?.name
            ? guruPamong.user.name.toLowerCase()
            : '';

        const nip = guruPamong.nip
            ? String(guruPamong.nip).toLowerCase()
            : '';

        return nama.includes(keyword) || nip.includes(keyword);
    });
});

const selectMahasiswa = (mahasiswa) => {
    form.mahasiswa_id = mahasiswa.id;

    selectedMahasiswaName.value = mahasiswa.user?.name
        ? `${mahasiswa.user.name} (${mahasiswa.nim})`
        : mahasiswa.nim;

    searchMahasiswa.value = '';
    openDropdown.value = null;
};

const clearMahasiswa = () => {
    form.mahasiswa_id = '';
    selectedMahasiswaName.value = '';
    searchMahasiswa.value = '';
    openDropdown.value = null;
};

const selectSekolah = (sekolah) => {
    form.sekolah_id = sekolah.id;

    selectedSekolahName.value = sekolah.nama_sekolah;

    form.guru_pamong_id = '';
    selectedGuruPamongName.value = '';

    searchSekolah.value = '';
    searchGuruPamong.value = '';

    openDropdown.value = null;
};

const clearSekolah = () => {
    form.sekolah_id = '';
    selectedSekolahName.value = '';

    form.guru_pamong_id = '';
    selectedGuruPamongName.value = '';

    searchSekolah.value = '';
    searchGuruPamong.value = '';

    openDropdown.value = null;
};

const selectGuruPamong = (guruPamong) => {
    form.guru_pamong_id = guruPamong.id;

    selectedGuruPamongName.value = guruPamong.user?.name || '';

    searchGuruPamong.value = '';
    openDropdown.value = null;
};

const clearGuruPamong = () => {
    form.guru_pamong_id = '';
    selectedGuruPamongName.value = '';
    searchGuruPamong.value = '';
    openDropdown.value = null;
};

const submit = () => {
    closeDropdown();

    form.post('/admin/penempatan', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            selectedMahasiswaName.value = '';
            selectedSekolahName.value = '';
            selectedGuruPamongName.value = '';
            searchMahasiswa.value = '';
            searchSekolah.value = '';
            searchGuruPamong.value = '';
        },
    });
};

const kembali = () => {
    router.visit('/admin/penempatan');
};

const hasMahasiswaSearchResult = computed(() => {
    return filteredMahasiswas.value.length > 0;
});

const hasSekolahSearchResult = computed(() => {
    return filteredSekolahs.value.length > 0;
});

const hasGuruPamongSearchResult = computed(() => {
    return filteredGuruPamongs.value.length > 0;
});
</script>

<template>
    <div @click="closeDropdown">
        <div
            v-if="$page.props.errors && Object.keys($page.props.errors).length > 0"
            class="mb-4 p-4 bg-red-100 text-red-700 rounded"
            @click.stop
        >
            <ul class="list-disc list-inside">
                <li
                    v-for="(error, field) in $page.props.errors"
                    :key="field"
                >
                    {{ error }}
                </li>
            </ul>
        </div>

        <div
            class="bg-white rounded-lg shadow-sm p-6 max-w-2xl"
            @click.stop
        >
            <form @submit.prevent="submit">

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Mahasiswa
                    </label>

                    <div class="relative">
                        <button
                            type="button"
                            @click.stop="toggleDropdown('mahasiswa')"
                            class="w-full border rounded p-2 text-left bg-white flex items-center justify-between"
                        >
                            <span
                                :class="
                                    selectedMahasiswaName
                                        ? 'text-gray-800'
                                        : 'text-gray-400'
                                "
                            >
                                {{
                                    selectedMahasiswaName ||
                                    '-- Pilih Mahasiswa --'
                                }}
                            </span>

                            <svg
                                class="w-4 h-4 text-gray-500 transition-transform"
                                :class="
                                    openDropdown === 'mahasiswa'
                                        ? 'rotate-180'
                                        : ''
                                "
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m6 9 6 6 6-6"
                                />
                            </svg>
                        </button>

                        <div
                            v-if="openDropdown === 'mahasiswa'"
                            class="absolute z-[60] w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg overflow-hidden"
                            @click.stop
                        >
                            <div class="p-2 border-b border-gray-200">
                                <input
                                    v-model="searchMahasiswa"
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
                                    @click="selectMahasiswa(mahasiswa)"
                                    class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50"
                                >
                                    {{ mahasiswa.user?.name }}
                                    <span class="text-gray-400">
                                        ({{ mahasiswa.nim }})
                                    </span>
                                </button>

                                <div
                                    v-if="!hasMahasiswaSearchResult"
                                    class="px-3 py-4 text-sm text-gray-500 text-center"
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
                            @click.stop="toggleDropdown('sekolah')"
                            class="w-full border rounded p-2 text-left bg-white flex items-center justify-between"
                        >
                            <span
                                :class="
                                    selectedSekolahName
                                        ? 'text-gray-800'
                                        : 'text-gray-400'
                                "
                            >
                                {{
                                    selectedSekolahName ||
                                    '-- Pilih Sekolah --'
                                }}
                            </span>

                            <svg
                                class="w-4 h-4 text-gray-500 transition-transform"
                                :class="
                                    openDropdown === 'sekolah'
                                        ? 'rotate-180'
                                        : ''
                                "
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m6 9 6 6 6-6"
                                />
                            </svg>
                        </button>

                        <div
                            v-if="openDropdown === 'sekolah'"
                            class="absolute z-[60] w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg overflow-hidden"
                            @click.stop
                        >
                            <div class="p-2 border-b border-gray-200">
                                <input
                                    v-model="searchSekolah"
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
                                    @click="selectSekolah(sekolah)"
                                    class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50"
                                >
                                    {{ sekolah.nama_sekolah }}
                                </button>

                                <div
                                    v-if="!hasSekolahSearchResult"
                                    class="px-3 py-4 text-sm text-gray-500 text-center"
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
                            @click.stop="
                                form.sekolah_id
                                    ? toggleDropdown('guruPamong')
                                    : null
                            "
                            :disabled="!form.sekolah_id"
                            class="w-full border rounded p-2 text-left bg-white flex items-center justify-between disabled:bg-gray-50 disabled:cursor-not-allowed"
                        >
                            <span
                                :class="
                                    selectedGuruPamongName
                                        ? 'text-gray-800'
                                        : 'text-gray-400'
                                "
                            >
                                {{
                                    selectedGuruPamongName ||
                                    '-- Pilih Guru Pamong --'
                                }}
                            </span>

                            <svg
                                class="w-4 h-4 text-gray-500 transition-transform"
                                :class="
                                    openDropdown === 'guruPamong'
                                        ? 'rotate-180'
                                        : ''
                                "
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m6 9 6 6 6-6"
                                />
                            </svg>
                        </button>

                        <div
                            v-if="openDropdown === 'guruPamong'"
                            class="absolute z-[60] w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg overflow-hidden"
                            @click.stop
                        >
                            <div class="p-2 border-b border-gray-200">
                                <input
                                    v-model="searchGuruPamong"
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
                                    @click="selectGuruPamong(guruPamong)"
                                    class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-50"
                                >
                                    {{ guruPamong.user?.name }}
                                </button>

                                <div
                                    v-if="!hasGuruPamongSearchResult"
                                    class="px-3 py-4 text-sm text-gray-500 text-center"
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
                        required
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
                        required
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
                        required
                    >
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Status
                    </label>

                    <select
                        v-model="form.status"
                        class="w-full border rounded p-2"
                        required
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
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                    </button>

                    <button
                        type="button"
                        @click="kembali"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
                    >
                        Batal
                    </button>
                </div>

            </form>
        </div>
    </div>
</template>