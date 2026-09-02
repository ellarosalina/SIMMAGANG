<script setup>
import { ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';

defineOptions({
    layout: AdminLayout,
});

const page = usePage();

const props = defineProps({
    users: {
        type: Object,
        required: true,
    },
    role: {
        type: String,
        default: '',
    },
    search: {
        type: String,
        default: '',
    },
});

const search = ref(props.search || '');
const openId = ref(null);
const showPassword = ref(false);

const editForm = ref({
    name: '',
    email: '',
    password: '',
});

const cariUser = () => {
    router.get(
        '/admin/users',
        {
            role: props.role || undefined,
            search: search.value || undefined,
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
        '/admin/users',
        {
            role: props.role || undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};

const changeRole = (event) => {
    const selectedRole = event.target.value;

    router.get(
        '/admin/users',
        {
            role: selectedRole || undefined,
            search: search.value || undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};

const openEdit = (user) => {
    openId.value = user.id;
    showPassword.value = false;

    editForm.value = {
        name: user.name || '',
        email: user.email || '',
        password: '',
    };
};

const closeEdit = () => {
    openId.value = null;
    showPassword.value = false;

    editForm.value = {
        name: '',
        email: '',
        password: '',
    };
};

const submitEdit = () => {
    router.put(
        `/admin/users/${openId.value}`,
        editForm.value,
        {
            preserveScroll: true,
            onSuccess: () => {
                closeEdit();
            },
        }
    );
};

const hapusUser = (user) => {
    if (user.id === page.props.auth?.user?.id) {
        return;
    }

    const yakin = confirm(
        `Yakin hapus akun ${user.name}? Semua data terkait akun ini juga akan terhapus.`
    );

    if (!yakin) {
        return;
    }

    router.delete(`/admin/users/${user.id}`, {
        preserveScroll: true,
    });
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

const getPageUrl = (pageNumber) => {
    const url = new URL(window.location.href);

    url.searchParams.set('page', pageNumber);

    if (props.role) {
        url.searchParams.set('role', props.role);
    } else {
        url.searchParams.delete('role');
    }

    if (search.value) {
        url.searchParams.set('search', search.value);
    } else {
        url.searchParams.delete('search');
    }

    return url.pathname + '?' + url.searchParams.toString();
};

const numberOf = (index) => {
    return (
        (props.users.current_page - 1) *
            props.users.per_page +
        index +
        1
    );
};

const formatRole = (role) => {
    if (!role) {
        return '-';
    }

    return role
        .replaceAll('_', ' ')
        .replace(/\b\w/g, (letter) => letter.toUpperCase());
};

const roleClass = (role) => {
    if (role === 'admin_gtk') {
        return 'bg-purple-100 text-purple-700';
    }

    if (role === 'guru_pamong') {
        return 'bg-blue-100 text-blue-700';
    }

    return 'bg-green-100 text-green-700';
};

const formatDate = (date) => {
    if (!date) {
        return '-';
    }

    const tanggal = new Date(date);

    return tanggal.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};
</script>

<template>
    <div>
        <div
            v-if="page.props.flash?.success"
            class="mb-4 p-4 bg-green-100 text-green-700 rounded"
        >
            {{ page.props.flash.success }}
        </div>

        <div
            v-if="page.props.flash?.error"
            class="mb-4 p-4 bg-red-100 text-red-700 rounded"
        >
            {{ page.props.flash.error }}
        </div>

        <div
            v-if="Object.keys(page.props.errors || {}).length"
            class="mb-4 p-4 bg-red-100 text-red-700 rounded"
        >
            <ul class="list-disc list-inside">
                <li
                    v-for="(error, key) in page.props.errors"
                    :key="key"
                >
                    {{ error }}
                </li>
            </ul>
        </div>

        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="relative w-48">
                    <select
                        :value="role"
                        @change="changeRole"
                        class="appearance-none w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg text-sm bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-400"
                    >
                        <option value="">
                            Semua
                        </option>

                        <option value="admin_gtk">
                            Admin
                        </option>

                        <option value="guru_pamong">
                            Guru Pamong
                        </option>

                        <option value="mahasiswa">
                            Mahasiswa
                        </option>
                    </select>

                    <div
                        class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>
                    </div>
                </div>

                <form @submit.prevent="cariUser">
                    <div class="relative">
                        <input
                            v-model="search"
                            type="text"
                            name="search"
                            placeholder="Cari Nama User..."
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

            <Link
                v-if="role === 'admin_gtk'"
                href="/admin/users/create"
                title="Tambah Admin"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg text-sm hover:bg-gray-50"
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
                        d="M12 4v16m8-8H4"
                    />
                </svg>

                <span>Tambah Admin</span>
            </Link>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="p-3 text-sm font-semibold text-gray-600 w-12">
                            No
                        </th>

                        <th class="p-3 text-sm font-semibold text-gray-600">
                            Nama
                        </th>

                        <th class="p-3 text-sm font-semibold text-gray-600">
                            Email
                        </th>

                        <th class="p-3 text-sm font-semibold text-gray-600">
                            Role
                        </th>

                        <th class="p-3 text-sm font-semibold text-gray-600">
                            Terdaftar
                        </th>

                        <th class="p-3 text-sm font-semibold text-gray-600">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <template
                        v-for="(user, index) in users.data"
                        :key="user.id"
                    >
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 text-sm">
                                {{ numberOf(index) }}
                            </td>

                            <td class="p-3 text-sm">
                                {{ user.name }}

                                <span
                                    v-if="user.id === page.props.auth?.user?.id"
                                    class="text-xs text-gray-400"
                                >
                                    (Anda)
                                </span>
                            </td>

                            <td class="p-3 text-sm">
                                {{ user.email }}
                            </td>

                            <td class="p-3 text-sm">
                                <span
                                    v-for="r in user.roles"
                                    :key="r.id"
                                    class="inline-flex items-center px-2 py-1 text-xs rounded mr-1"
                                    :class="roleClass(r.name)"
                                >
                                    {{ formatRole(r.name) }}
                                </span>
                            </td>

                            <td class="p-3 text-sm">
                                {{ formatDate(user.created_at) }}
                            </td>

                            <td class="p-3 text-sm">
                                <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        title="Edit"
                                        :aria-label="`Edit ${user.name}`"
                                        @click="openEdit(user)"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-md text-blue-600 bg-blue-50 border border-blue-200 hover:bg-blue-100 transition"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.5-8.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 8.5-8.5z"
                                            />
                                        </svg>
                                    </button>

                                    <button
                                        v-if="user.id !== page.props.auth?.user?.id"
                                        type="button"
                                        title="Hapus"
                                        :aria-label="`Hapus ${user.name}`"
                                        @click="hapusUser(user)"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-md text-red-600 bg-red-50 border border-red-200 hover:bg-red-100 transition"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M6 7h12M10 11v6M14 11v6M9 7V4h6v3m-8 0l1 13h6L19 7"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <tr v-if="!users.data || users.data.length === 0">
                        <td
                            colspan="6"
                            class="p-4 text-center text-gray-500"
                        >
                            Belum ada data user.
                        </td>
                    </tr>
                </tbody>

                <tfoot>
                    <tr class="bg-gray-50 border-t font-semibold">
                        <td
                            colspan="6"
                            class="p-3 text-sm"
                        >
                            Total: {{ users.total }} akun
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div
            v-if="users.total > users.per_page"
            class="mt-4"
        >
            <div class="flex items-center justify-end gap-1">
                <button
                    v-if="users.prev_page_url"
                    type="button"
                    @click="goToPage(users.prev_page_url)"
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
                    v-for="pageNumber in users.last_page"
                    :key="pageNumber"
                    type="button"
                    @click="goToPage(getPageUrl(pageNumber))"
                    class="inline-flex items-center justify-center min-w-[36px] h-9 px-3 rounded-lg text-sm font-medium transition"
                    :class="
                        pageNumber === users.current_page
                            ? 'bg-gray-900 text-white border border-gray-900 font-bold'
                            : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-100'
                    "
                >
                    {{ pageNumber }}
                </button>

                <button
                    v-if="users.next_page_url"
                    type="button"
                    @click="goToPage(users.next_page_url)"
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

        <div
            v-if="openId !== null"
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
            @click.self="closeEdit"
        >
            <div
                class="bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[85vh] overflow-y-auto p-6"
            >
                <h3 class="font-semibold text-lg mb-4">
                    Edit {{ editForm.name }}
                </h3>

                <form @submit.prevent="submitEdit">
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">
                            Nama
                        </label>

                        <input
                            v-model="editForm.name"
                            type="text"
                            name="name"
                            class="w-full border rounded p-2"
                        >
                    </div>
                    
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">
                            Email
                        </label>

                        <input
                            v-model="editForm.email"
                            type="email"
                            name="email"
                            class="w-full border rounded p-2"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">
                            Password Baru
                        </label>

                        <div class="relative">
                            <input
                                v-model="editForm.password"
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
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
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                    />
                                </svg>

                                <svg
                                    v-else
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
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

                        <p class="text-xs text-gray-500 mt-1">
                            Kosongkan jika tidak ingin mengubah password.
                        </p>
                    </div>

                    <div class="flex gap-2 mt-4">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                        >
                            Simpan
                        </button>

                        <button
                            type="button"
                            @click="closeEdit"
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
                        >
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>