<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    user: Object
})

const showPassword = ref(false)

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: ''
})

const submit = () => {
    form.put(route('admin.users.update', props.user.id))
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

        <div class="bg-white rounded-lg shadow-sm p-6 max-w-md">
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Nama Lengkap
                    </label>

                    <input
                        v-model="form.name"
                        type="text"
                        class="w-full border rounded p-2"
                    >
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Email
                    </label>

                    <input
                        v-model="form.email"
                        type="email"
                        class="w-full border rounded p-2"
                    >
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">
                        Password Baru
                    </label>

                    <div class="relative">
                        <input
                            v-model="form.password"
                            :type="showPassword ? 'text' : 'password'"
                            class="w-full border rounded p-2 pr-10"
                        >

                        <button
                            type="button"
                            title="Tampilkan password"
                            @click="showPassword = !showPassword"
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500"
                        >
                            <svg
                                v-if="!showPassword"
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
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                />
                            </svg>

                            <svg
                                v-else
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
                                    stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0 1 12 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 0 1 2.132-3.411m3.712-2.687A9.98 9.98 0 0 1 12 5c4.478 0 8.268 2.943 9.542 7a9.957 9.957 0 0 1-4.132 5.411M3 3l18 18"
                                />
                            </svg>
                        </button>
                    </div>

                    <p class="text-sm text-gray-500 mt-1">
                        Kosongkan kalau tidak ingin mengubah password.
                    </p>
                </div>

                <div class="flex gap-2 mt-6">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
                    >
                        Update
                    </button>

                    <Link
                        :href="route('admin.users.index')"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
                    >
                        Batal
                    </Link>
                </div>
            </form>
        </div>
    </div>
</template>