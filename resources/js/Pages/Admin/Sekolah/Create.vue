<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '../../../Layouts/AdminLayout.vue'

defineOptions({
    layout: AdminLayout,
})

const form = useForm({
    npsn: '',
    nama_sekolah: '',
    kepala_sekolah: '',
    jenjang: '',
    kecamatan: '',
    kabupaten: '',
    alamat: '',
    status: '',
    kuota_magang: 0,
})

const submit = () => {
    form.post('/admin/sekolah', {
        preserveScroll: true,
    })
}
</script>

<template>
    <div>
        <div
            v-if="Object.keys(form.errors).length"
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
                        NPSN
                    </label>

                    <input
                        type="text"
                        v-model="form.npsn"
                        class="w-full border rounded p-2"
                    >
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Nama Sekolah
                    </label>

                    <input
                        type="text"
                        v-model="form.nama_sekolah"
                        class="w-full border rounded p-2"
                    >
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Kepala Sekolah
                    </label>

                    <input
                        type="text"
                        v-model="form.kepala_sekolah"
                        class="w-full border rounded p-2"
                    >
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Jenjang
                    </label>

                    <select
                        v-model="form.jenjang"
                        class="w-full border rounded p-2"
                    >
                        <option value="">
                            Pilih Jenjang
                        </option>

                        <option value="SMA">
                            SMA
                        </option>

                        <option value="SMK">
                            SMK
                        </option>

                        <option value="SLB">
                            SLB
                        </option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Kecamatan
                    </label>

                    <input
                        type="text"
                        v-model="form.kecamatan"
                        class="w-full border rounded p-2"
                    >
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Kabupaten
                    </label>

                    <input
                        type="text"
                        v-model="form.kabupaten"
                        class="w-full border rounded p-2"
                    >
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Alamat
                    </label>

                    <textarea
                        v-model="form.alamat"
                        class="w-full border rounded p-2"
                    ></textarea>
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Status
                    </label>

                    <select
                        v-model="form.status"
                        class="w-full border rounded p-2"
                    >
                        <option value="">
                            Pilih Status
                        </option>

                        <option value="negeri">
                            Negeri
                        </option>

                        <option value="swasta">
                            Swasta
                        </option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Kuota Magang
                    </label>

                    <input
                        type="number"
                        v-model="form.kuota_magang"
                        class="w-full border rounded p-2"
                    >
                </div>

                <div class="flex gap-2 mt-6">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
                    >
                        Simpan
                    </button>

                    <button
                        type="button"
                        @click="$inertia.visit('/admin/sekolah')"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
                    >
                        Batal
                    </button>
                </div>

            </form>
        </div>
    </div>
</template>