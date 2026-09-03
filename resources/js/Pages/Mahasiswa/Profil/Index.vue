<script setup>
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import MahasiswaLayout from '../../../Layouts/MahasiswaLayout.vue';

defineOptions({
    layout: MahasiswaLayout,
});

const page = usePage();

const props = defineProps({
    mahasiswa: {
        type: Object,
        required: true,
    },
});

const user = computed(() => {
    return props.mahasiswa?.user ?? page.props.auth?.user ?? {};
});

const fotoInput = ref(null);
const fotoError = ref(false);
const sedangMenyimpan = ref(false);
const namaFile = ref('');

const previewFoto = ref(
    props.mahasiswa?.foto
        ? `/storage/${props.mahasiswa.foto}?t=${Date.now()}`
        : user.value?.foto
            ? `/storage/${user.value.foto}?t=${Date.now()}`
            : null
);

const form = ref({
    no_hp: props.mahasiswa?.no_hp ?? '',
    alamat: props.mahasiswa?.alamat ?? '',
    dosen_pembimbing: props.mahasiswa?.dosen_pembimbing ?? '',
    foto: null,
    password: '',
    password_confirmation: '',
});

const bukaPilihFoto = () => {
    if (fotoInput.value) {
        fotoInput.value.click();
    }
};

const pilihFoto = (event) => {
    const file = event.target.files?.[0];

    if (!file) {
        return;
    }

    if (!['image/jpeg', 'image/png'].includes(file.type)) {
        alert('Foto harus berformat JPG, JPEG, atau PNG.');

        form.value.foto = null;
        namaFile.value = '';
        event.target.value = '';

        return;
    }

    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran foto maksimal 2MB.');

        form.value.foto = null;
        namaFile.value = '';
        event.target.value = '';

        return;
    }

    form.value.foto = file;
    namaFile.value = file.name;
    fotoError.value = false;

    const reader = new FileReader();

    reader.onload = (e) => {
        previewFoto.value = e.target.result;
    };

    reader.readAsDataURL(file);
};

const fotoGagalDimuat = () => {
    fotoError.value = true;
};

const submit = () => {
    sedangMenyimpan.value = true;

    router.post(
        '/mahasiswa/profil',
        {
            _method: 'PUT',
            no_hp: form.value.no_hp,
            alamat: form.value.alamat,
            dosen_pembimbing: form.value.dosen_pembimbing,
            foto: form.value.foto,
            password: form.value.password,
            password_confirmation: form.value.password_confirmation,
        },
        {
            forceFormData: true,
            preserveScroll: true,

            onSuccess: () => {
                form.value.foto = null;
                form.value.password = '';
                form.value.password_confirmation = '';
                namaFile.value = '';
                fotoError.value = false;

                if (fotoInput.value) {
                    fotoInput.value.value = '';
                }

                window.location.reload();
            },

            onFinish: () => {
                sedangMenyimpan.value = false;
            },
        }
    );
};
</script>

<template>
    <div class="max-w-6xl mx-auto">

        <!-- PESAN BERHASIL -->
        <div
            v-if="page.props.flash?.success"
            class="mb-5 px-4 py-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700 flex items-center gap-2"
        >
            <svg
                class="w-4 h-4 flex-shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                />
            </svg>

            <span>
                {{ page.props.flash.success }}
            </span>
        </div>


        <!-- PESAN ERROR -->
        <div
            v-if="page.props.errors && Object.keys(page.props.errors).length"
            class="mb-5 px-4 py-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700"
        >
            <div
                v-for="(error, key) in page.props.errors"
                :key="key"
                class="flex items-start gap-2"
            >
                <span>•</span>
                <span>{{ error }}</span>
            </div>
        </div>


        <form
            @submit.prevent="submit"
            enctype="multipart/form-data"
        >

            <div class="grid grid-cols-2 gap-5 items-start">

                <!-- INFORMASI AKUN -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

                    <!-- HEADER -->
                    <div class="px-6 py-5 border-b border-gray-100">

                        <h2 class="text-base font-semibold text-gray-800">
                            Informasi Akun
                        </h2>

                        <p class="text-xs text-gray-400 mt-1">
                            Informasi mahasiswa yang sedang digunakan.
                        </p>

                    </div>


                    <!-- ISI -->
                    <div class="px-6 py-6">

                        <!-- FOTO DAN IDENTITAS -->
                        <div class="flex items-center gap-5 pb-6 border-b border-gray-100">

                            <!-- AREA FOTO -->
                            <div
                                class="relative flex-shrink-0"
                                style="width: 96px; height: 96px; min-width: 96px; min-height: 96px;"
                            >

                                <!-- FOTO -->
                                <button
                                    type="button"
                                    @click="bukaPilihFoto"
                                    class="relative overflow-hidden rounded-full bg-slate-600 border border-gray-200 shadow-sm group focus:outline-none focus:ring-2 focus:ring-gray-300"
                                    style="width: 96px; height: 96px; min-width: 96px; max-width: 96px; min-height: 96px; max-height: 96px; padding: 0;"
                                >

                                    <!-- FOTO TERSIMPAN / PREVIEW -->
                                    <img
                                        v-if="previewFoto && !fotoError"
                                        :src="previewFoto"
                                        alt="Foto profil"
                                        class="absolute block object-cover"
                                        style="width: 96px; height: 96px; min-width: 96px; max-width: 96px; min-height: 96px; max-height: 96px; left: 0; top: 0;"
                                        @error="fotoGagalDimuat"
                                    >

                                    <!-- INISIAL -->
                                    <span
                                        v-else
                                        class="absolute flex items-center justify-center text-white text-3xl font-semibold"
                                        style="width: 96px; height: 96px; left: 0; top: 0;"
                                    >
                                        {{
                                            user.name
                                                ? user.name.substring(0, 1).toUpperCase()
                                                : 'M'
                                        }}
                                    </span>

                                    <!-- OVERLAY -->
                                    <span
                                        class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition duration-200"
                                    >
                                        <svg
                                            class="w-6 h-6 text-white"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4 7h3l2-2h6l2 2h3v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"
                                            />

                                            <circle
                                                cx="12"
                                                cy="12"
                                                r="3"
                                                stroke-width="2"
                                            />
                                        </svg>
                                    </span>

                                </button>


                                <!-- IKON KAMERA -->
                                <button
                                    type="button"
                                    @click="bukaPilihFoto"
                                    title="Ubah foto"
                                    class="absolute flex items-center justify-center rounded-full bg-gray-800 hover:bg-gray-900 border-2 border-white shadow-md text-white transition duration-200"
                                    style="width: 32px; height: 32px; right: -2px; bottom: -2px;"
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
                                            d="M4 7h3l2-2h6l2 2h3v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"
                                        />

                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="3"
                                            stroke-width="2"
                                        />
                                    </svg>

                                </button>


                                <!-- INPUT FOTO -->
                                <input
                                    ref="fotoInput"
                                    type="file"
                                    name="foto"
                                    accept="image/jpeg,image/png"
                                    class="hidden"
                                    @change="pilihFoto"
                                >

                            </div>


                            <!-- IDENTITAS MAHASISWA -->
                            <div class="min-w-0 flex-1">

                                <h3 class="text-lg font-semibold text-gray-800">
                                    {{ user.name }}
                                </h3>

                                <p class="text-sm text-gray-500 mt-1 break-all">
                                    {{ user.email }}
                                </p>


                                <div class="flex items-center gap-2 mt-3 flex-wrap">

                                    <span class="inline-flex items-center px-2.5 py-1 bg-gray-100 border border-gray-200 rounded-full text-xs font-medium text-gray-600">
                                        Mahasiswa
                                    </span>

                                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-500">

                                        <span class="w-2 h-2 bg-green-400 rounded-full"></span>

                                        Aktif

                                    </span>

                                </div>


                                <!-- NAMA FILE FOTO BARU -->
                                <p
                                    v-if="namaFile"
                                    class="text-xs text-gray-400 mt-2 truncate max-w-xs"
                                >
                                    Foto baru: {{ namaFile }}
                                </p>

                            </div>

                        </div>


                        <!-- DETAIL AKUN -->
                        <div class="mt-6 grid grid-cols-2 gap-x-6 gap-y-5">

                            <!-- NAMA -->
                            <div>

                                <p class="text-xs text-gray-400 mb-1">
                                    Nama Lengkap
                                </p>

                                <p class="text-sm font-medium text-gray-700">
                                    {{ user.name }}
                                </p>

                            </div>


                            <!-- ROLE -->
                            <div>

                                <p class="text-xs text-gray-400 mb-1">
                                    Role
                                </p>

                                <p class="text-sm font-medium text-gray-700">
                                    Mahasiswa
                                </p>

                            </div>


                            <!-- EMAIL -->
                            <div>

                                <p class="text-xs text-gray-400 mb-1">
                                    Email
                                </p>

                                <p class="text-sm font-medium text-gray-700 break-all">
                                    {{ user.email }}
                                </p>

                            </div>


                            <!-- NIM -->
                            <div>

                                <p class="text-xs text-gray-400 mb-1">
                                    NIM
                                </p>

                                <p class="text-sm font-medium text-gray-700">
                                    {{ mahasiswa.nim ?? '-' }}
                                </p>

                            </div>


                            <!-- UNIVERSITAS -->
                            <div>

                                <p class="text-xs text-gray-400 mb-1">
                                    Universitas
                                </p>

                                <p class="text-sm font-medium text-gray-700">
                                    {{ mahasiswa.universitas ?? '-' }}
                                </p>

                            </div>


                            <!-- FAKULTAS -->
                            <div>

                                <p class="text-xs text-gray-400 mb-1">
                                    Fakultas
                                </p>

                                <p class="text-sm font-medium text-gray-700">
                                    {{ mahasiswa.fakultas ?? '-' }}
                                </p>

                            </div>


                            <!-- PROGRAM STUDI -->
                            <div class="col-span-2">

                                <p class="text-xs text-gray-400 mb-1">
                                    Program Studi
                                </p>

                                <p class="text-sm font-medium text-gray-700">
                                    {{ mahasiswa.prodi ?? '-' }}
                                </p>

                            </div>

                        </div>


                        <!-- PETUNJUK FOTO -->
                        <div class="mt-6 pt-4 border-t border-gray-100">

                            <p class="text-xs text-gray-400">
                                Klik foto atau ikon kamera untuk mengganti foto profil.
                            </p>

                            <p class="text-xs text-gray-400 mt-1">
                                JPG, JPEG, PNG · Maksimal 2 MB
                            </p>

                        </div>

                    </div>

                </div>


                <!-- KEAMANAN AKUN -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

                    <!-- HEADER -->
                    <div class="px-6 py-5 border-b border-gray-100">

                        <h2 class="text-base font-semibold text-gray-800">
                            Informasi & Keamanan Akun
                        </h2>

                        <p class="text-xs text-gray-400 mt-1">
                            Perbarui data profil dan password akun.
                        </p>

                    </div>


                    <!-- ISI -->
                    <div class="px-6 py-6">

                        <div class="space-y-5">

                            <!-- DOSEN PEMBIMBING -->
                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Dosen Pembimbing
                                </label>

                                <input
                                    v-model="form.dosen_pembimbing"
                                    type="text"
                                    name="dosen_pembimbing"
                                    class="w-full h-10 px-3 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-400 transition"
                                    placeholder="Nama dosen pembimbing dari kampus"
                                >

                            </div>


                            <!-- NO HP -->
                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    No. HP
                                </label>

                                <input
                                    v-model="form.no_hp"
                                    type="text"
                                    name="no_hp"
                                    class="w-full h-10 px-3 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-400 transition"
                                    placeholder="Masukkan nomor HP"
                                >

                            </div>


                            <!-- ALAMAT -->
                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Alamat
                                </label>

                                <textarea
                                    v-model="form.alamat"
                                    name="alamat"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-400 transition"
                                    placeholder="Masukkan alamat"
                                ></textarea>

                            </div>


                            <!-- PEMBATAS PASSWORD -->
                            <div class="pt-1 border-t border-gray-100"></div>


                            <!-- PASSWORD BARU -->
                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Password Baru
                                </label>

                                <input
                                    v-model="form.password"
                                    type="password"
                                    name="password"
                                    autocomplete="new-password"
                                    class="w-full h-10 px-3 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-400 transition"
                                    placeholder="Masukkan password baru"
                                >

                                <p class="text-xs text-gray-400 mt-1.5">
                                    Minimal 8 karakter.
                                </p>

                            </div>


                            <!-- KONFIRMASI PASSWORD -->
                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Konfirmasi Password
                                </label>

                                <input
                                    v-model="form.password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    autocomplete="new-password"
                                    class="w-full h-10 px-3 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-400 transition"
                                    placeholder="Ulangi password baru"
                                >

                            </div>


                            <!-- INFORMASI KEAMANAN -->
                            <div class="pt-4 border-t border-gray-100">

                                <div class="flex items-start gap-3">

                                    <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center flex-shrink-0">

                                        <svg
                                            class="w-4 h-4 text-gray-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v2h8z"
                                            />
                                        </svg>

                                    </div>

                                    <div>

                                        <p class="text-xs font-medium text-gray-600">
                                            Keamanan password
                                        </p>

                                        <p class="text-xs text-gray-400 mt-0.5">
                                            Gunakan password minimal 8 karakter
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- TOMBOL SIMPAN -->
                    <div class="px-6 py-4 border-t border-gray-100 flex justify-end">

                        <button
                            type="submit"
                            :disabled="sedangMenyimpan"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 active:bg-gray-300 border border-gray-300 hover:border-gray-400 text-gray-700 hover:text-gray-800 disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed rounded-lg text-sm font-medium shadow-sm transition duration-200"
                        >

                            <!-- ICON SIMPAN -->
                            <svg
                                v-if="!sedangMenyimpan"
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>


                            <!-- LOADING -->
                            <svg
                                v-else
                                class="w-4 h-4 animate-spin"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                />

                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                                />
                            </svg>


                            {{ sedangMenyimpan ? 'Menyimpan...' : 'Simpan Perubahan' }}

                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>
</template>