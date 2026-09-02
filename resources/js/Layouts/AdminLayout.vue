<script setup>
import { computed, onMounted, onBeforeUnmount, nextTick } from 'vue';
import { Link, router, usePage, Head } from '@inertiajs/vue3';
import Choices from 'choices.js';
import 'choices.js/public/assets/styles/choices.min.css';

const page = usePage();

const user = computed(() => {
    return page.props.auth?.user || {};
});

const currentUrl = computed(() => {
    return page.url || window.location.pathname;
});

const pageTitle = computed(() => {
    const url = currentUrl.value;

    if (url.startsWith('/admin/sekolah')) return 'Sekolah';
    if (url.startsWith('/admin/guru-pamong')) return 'Guru Pamong';
    if (url.startsWith('/admin/mahasiswa')) return 'Mahasiswa';
    if (url.startsWith('/admin/penempatan')) return 'Penempatan';
    if (url.startsWith('/admin/monitoring')) return 'Monitoring';
    if (url.startsWith('/admin/users')) return 'Manajemen User';
    if (url.startsWith('/admin/profil')) return 'Profil';

    return 'Dashboard';
});

const pageSubtitle = computed(() => {
    const url = currentUrl.value;

    if (url === '/admin/dashboard') {
        return 'Sistem Informasi Monitoring Magang Mahasiswa.';
    }

    if (url === '/admin/sekolah') {
        return 'Kelola data sekolah.';
    }

    if (url.includes('/admin/sekolah/create')) {
        return 'Tambah data sekolah baru.';
    }

    if (url.includes('/admin/sekolah/') && url.includes('/edit')) {
        return 'Ubah data sekolah.';
    }

    if (url.startsWith('/admin/guru-pamong')) {
        if (url.includes('/create')) {
            return 'Buat akun dan data guru pamong baru.';
        }

        if (url.includes('/edit')) {
            return 'Ubah data guru pamong.';
        }

        return 'Kelola data guru pamong.';
    }

    if (url.startsWith('/admin/mahasiswa')) {
        if (url.includes('/create')) {
            return 'Buat akun dan data mahasiswa baru.';
        }

        if (url.includes('/edit')) {
            return 'Ubah data mahasiswa.';
        }

        return 'Kelola data mahasiswa.';
    }

    if (url.startsWith('/admin/penempatan')) {
        if (url.includes('/create')) {
            return 'Assign mahasiswa ke sekolah, guru pamong, dan dosen pembimbing.';
        }

        if (url.includes('/edit')) {
            return 'Ubah data penempatan magang.';
        }

        return 'Kelola data penempatan magang.';
    }

    if (url.startsWith('/admin/monitoring')) {
        return 'Monitoring pelaksanaan magang mahasiswa.';
    }

    if (url.startsWith('/admin/users')) {
        return 'Kelola akun pengguna sistem.';
    }

    if (url.startsWith('/admin/profil')) {
        return 'Kelola profil administrator.';
    }

    return '';
});

const isActive = (path) => {
    const url = currentUrl.value;

    if (path === '/admin/dashboard') {
        return url === '/admin/dashboard';
    }

    return url.startsWith(path);
};

const logout = () => {
    router.post('/logout', {}, {
        preserveState: false,
        preserveScroll: false,
        onSuccess: () => {
            window.location.href = '/login';
        },
    });
};

let choicesInstances = {};

const initChoices = async () => {
    await nextTick();

    Object.values(choicesInstances).forEach((instance) => {
        instance.destroy();
    });

    choicesInstances = {};

    document.querySelectorAll('.searchable-select').forEach((el) => {
        choicesInstances[el.name] = new Choices(el, {
            searchEnabled: true,
            itemSelectText: '',
            shouldSort: false,
        });
    });
};

let removeNavigateListener;

onMounted(() => {
    initChoices();

    removeNavigateListener = router.on('navigate', () => {
        initChoices();
    });
});

onBeforeUnmount(() => {
    Object.values(choicesInstances).forEach((instance) => {
        instance.destroy();
    });

    if (removeNavigateListener) {
        removeNavigateListener();
    }
});
</script>

<template>
    <Head>
        <title>{{ pageTitle }} - SIM Magang GTK</title>
        <meta charset="utf-8">
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
        >
    </Head>

    <div class="min-h-screen flex bg-gray-100 overflow-x-hidden">

        <!-- SIDEBAR -->
        <aside
                class="w-64 min-h-screen bg-slate-800 text-white flex-shrink-0 sticky top-0 self-stretch overflow-hidden flex flex-col"
            >

            <!-- LOGO -->
            <div
                class="p-4 text-lg font-bold border-b border-slate-700 flex items-center gap-2 flex-shrink-0"
            >
                <span class="text-blue-400">
                    SIM
                </span>

                MagangGTK
            </div>

            <!-- PROFILE ADMIN -->
            <div
                class="p-4 flex items-center gap-3 border-b border-slate-700 flex-shrink-0"
            >
                <div
                    class="w-10 h-10 rounded-full bg-slate-600 flex items-center justify-center text-lg font-bold overflow-hidden flex-shrink-0"
                >
                    <img
                        v-if="user.foto"
                        :src="`/storage/${user.foto}`"
                        alt="Foto Profil"
                        class="w-full h-full object-cover"
                    >

                    <span v-else>
                        {{
                            user.name
                                ? user.name.substring(0, 1).toUpperCase()
                                : 'A'
                        }}
                    </span>
                </div>

                <div class="min-w-0">
                    <p
                        class="text-sm font-semibold leading-tight truncate"
                    >
                        {{ user.name || 'Admin' }}
                    </p>

                    <p
                        class="text-xs text-green-400 flex items-center gap-1 mt-0.5"
                    >
                        <span
                            class="w-2 h-2 bg-green-400 rounded-full inline-block"
                        ></span>

                        Online
                    </p>
                </div>
            </div>

            <!-- MENU SIDEBAR -->
            <nav
                class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden py-2"
            >

                <!-- UTAMA -->
                <div
                    class="px-4 pt-2 pb-1 text-xs text-slate-400 uppercase tracking-wide"
                >
                    Utama
                </div>

                <Link
                    href="/admin/dashboard"
                    class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-slate-700"
                    :class="isActive('/admin/dashboard') ? 'bg-blue-600' : ''"
                >
                    <svg
                        class="w-5 h-5 flex-shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a2 2 0 001 2v-4a1 1 0 011-1h2a1 1 0 011 1v4a2 2 0 001 2m-6 0h6"
                        />
                    </svg>

                    <span>
                        Dashboard
                    </span>
                </Link>


                <!-- DATA MASTER -->
                <div
                    class="px-4 pt-3 pb-1 text-xs text-slate-400 uppercase tracking-wide"
                >
                    Data Master
                </div>

                <Link
                    href="/admin/sekolah"
                    class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-slate-700"
                    :class="isActive('/admin/sekolah') ? 'bg-blue-600' : ''"
                >
                    <svg
                        class="w-5 h-5 flex-shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5"
                        />
                    </svg>

                    <span>
                        Sekolah
                    </span>
                </Link>

                <Link
                    href="/admin/guru-pamong"
                    class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-slate-700"
                    :class="isActive('/admin/guru-pamong') ? 'bg-blue-600' : ''"
                >
                    <svg
                        class="w-5 h-5 flex-shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 12a5 5 0 100-10 5 5 0 000 10zm-7 9a7 7 0 0114 0M19 8l2 2-4 4-2-2"
                        />
                    </svg>

                    <span>
                        Guru Pamong
                    </span>
                </Link>

                <Link
                    href="/admin/mahasiswa"
                    class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-slate-700"
                    :class="isActive('/admin/mahasiswa') ? 'bg-blue-600' : ''"
                >
                    <svg
                        class="w-5 h-5 flex-shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19a4 4 0 00-8 0m4-8a3 3 0 100-6 3 3 0 000 6zm7-1a2 2 0 100-4 2 2 0 000 4zm-1 3a3 3 0 013 3"
                        />
                    </svg>

                    <span>
                        Mahasiswa
                    </span>
                </Link>


                <!-- MANAJEMEN -->
                <div
                    class="px-4 pt-3 pb-1 text-xs text-slate-400 uppercase tracking-wide"
                >
                    Manajemen
                </div>

                <Link
                    href="/admin/penempatan"
                    class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-slate-700"
                    :class="isActive('/admin/penempatan') ? 'bg-blue-600' : ''"
                >
                    <svg
                        class="w-5 h-5 flex-shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 0 0-2-2h-2M9 5a3 3 0 006 0M9 5a2 2 0 012-2h2a2 2 0 012 2m-5 5h.01M9 13h6m-6 4h6"
                        />
                    </svg>

                    <span>
                        Penempatan
                    </span>
                </Link>

                <Link
                    href="/admin/monitoring"
                    class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-slate-700"
                    :class="isActive('/admin/monitoring') ? 'bg-blue-600' : ''"
                >
                    <svg
                        class="w-5 h-5 flex-shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2"
                        />
                    </svg>

                    <span>
                        Monitoring
                    </span>
                </Link>


                <!-- PENGATURAN -->
                <div
                    class="px-4 pt-3 pb-1 text-xs text-slate-400 uppercase tracking-wide"
                >
                    Pengaturan
                </div>

                <Link
                    href="/admin/users"
                    class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-slate-700"
                    :class="isActive('/admin/users') ? 'bg-blue-600' : ''"
                >
                    <svg
                        class="w-5 h-5 flex-shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 100-8 4 4 0 000 8zm6 2a4 4 0 00-3-3.87m-9 3.87a4 4 0 013-3.87"
                        />
                    </svg>

                    <span>
                        Manajemen User
                    </span>
                </Link>

                <Link
                    href="/admin/profil"
                    class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-slate-700"
                    :class="isActive('/admin/profil') ? 'bg-blue-600' : ''"
                >
                    <svg
                        class="w-5 h-5 flex-shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 12a5 5 0 100-10 5 5 0 000 10zm-8 9a8 8 0 0116 0"
                        />
                    </svg>

                    <span>
                        Profil
                    </span>
                </Link>


                <!-- LOGOUT -->
                <form
                    @submit.prevent="logout"
                    class="border-t border-slate-700 mt-2 pt-2"
                >
                    <button
                        type="submit"
                        class="w-full text-left flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-slate-700"
                    >
                        <svg
                            class="w-5 h-5 flex-shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                            />
                        </svg>

                        <span>
                            Logout
                        </span>
                    </button>
                </form>

            </nav>

        </aside>


        <!-- KONTEN UTAMA -->
        <div class="flex-1 min-w-0 flex flex-col">

            <!-- HEADER -->
            <header
                class="bg-white shadow-sm px-6 py-4 flex justify-between items-center flex-shrink-0"
            >

                <div class="min-w-0">

                    <h1
                        class="text-lg font-semibold text-gray-800 truncate"
                    >
                        {{ pageTitle }}
                    </h1>

                    <p
                        class="text-xs text-gray-400 truncate"
                    >
                        {{ pageSubtitle }}
                    </p>

                </div>

                <div
                    class="text-sm text-gray-600 flex-shrink-0"
                >
                    {{ user.name || 'Admin' }}

                    <span class="text-gray-400">
                        - Admin GTK
                    </span>
                </div>

            </header>


            <!-- ISI HALAMAN -->
            <main
                class="flex-1 min-w-0 w-full max-w-full p-6 overflow-x-hidden overflow-y-auto"
            >
                <slot />
            </main>

        </div>

    </div>
</template>