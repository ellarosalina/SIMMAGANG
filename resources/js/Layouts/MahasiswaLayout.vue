<script setup>
import { computed } from 'vue';
import { Link, router, usePage, Head } from '@inertiajs/vue3';

const page = usePage();

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

const user = computed(() => {
    return page.props.auth?.user || {};
});

const currentUrl = computed(() => {
    return page.url || window.location.pathname;
});

const pageTitle = computed(() => {
    const url = currentUrl.value;

    if (url.startsWith('/mahasiswa/absensi')) return 'Absensi';

    if (url.startsWith('/mahasiswa/logbook')) return 'Logbook';

    if (url.startsWith('/mahasiswa/profil')) return 'Profil';

    return 'Dashboard';
});

const pageSubtitle = computed(() => {
    const url = currentUrl.value;

    if (url === '/mahasiswa/dashboard') {
        return 'Sistem Informasi Monitoring Magang Mahasiswa.';
    }

    if (url.startsWith('/mahasiswa/absensi')) {
        return 'Kelola kehadiran selama pelaksanaan magang.';
    }

    if (url.startsWith('/mahasiswa/logbook')) {
        return 'Kelola catatan kegiatan selama pelaksanaan magang.';
    }

    if (url.startsWith('/mahasiswa/profil')) {
        return 'Kelola profil mahasiswa.';
    }

    return '';
});

const isActive = (path) => {
    const url = currentUrl.value;

    if (path === '/mahasiswa/dashboard') {
        return url === '/mahasiswa/dashboard';
    }

    return url.startsWith(path);
};

const logout = async () => {
    try {
        const response = await fetch('/logout', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({}),
        });

        if (response.ok || response.redirected) {
            window.location.replace('/login');
            return;
        }

        if (response.status === 419) {
            window.location.reload();
            return;
        }

        console.error('Logout gagal:', response.status);
    } catch (error) {
        console.error('Terjadi kesalahan saat logout:', error);
    }
};
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

            <!-- PROFILE MAHASISWA -->
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
                                : 'M'
                        }}
                    </span>
                </div>

                <div class="min-w-0">
                    <p
                        class="text-sm font-semibold leading-tight truncate"
                    >
                        {{ user.name || 'Mahasiswa' }}
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
                    href="/mahasiswa/dashboard"
                    class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-slate-700"
                    :class="isActive('/mahasiswa/dashboard') ? 'bg-blue-600' : ''"
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

                <!-- KEGIATAN MAGANG -->
                <div
                    class="px-4 pt-3 pb-1 text-xs text-slate-400 uppercase tracking-wide"
                >
                    Kegiatan Magang
                </div>

                <Link
                    href="/mahasiswa/absensi"
                    class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-slate-700"
                    :class="isActive('/mahasiswa/absensi') ? 'bg-blue-600' : ''"
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
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 006 0M9 5a2 2 0 012-2h2a2 2 0 012 2m-5 5h.01M9 13h6m-6 4h6"
                        />
                    </svg>

                    <span>
                        Absensi
                    </span>
                </Link>

                <Link
                    href="/mahasiswa/logbook"
                    class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-slate-700"
                    :class="isActive('/mahasiswa/logbook') ? 'bg-blue-600' : ''"
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
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                        />
                    </svg>

                    <span>
                        Logbook
                    </span>
                </Link>

                <!-- PENGATURAN -->
                <div
                    class="px-4 pt-3 pb-1 text-xs text-slate-400 uppercase tracking-wide"
                >
                    Pengaturan
                </div>

                <Link
                    href="/mahasiswa/profil"
                    class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-slate-700"
                    :class="isActive('/mahasiswa/profil') ? 'bg-blue-600' : ''"
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
                <div
                    class="border-t border-slate-700 mt-2 pt-2"
                >
                    <button
                        type="button"
                        @click="logout"
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
                </div>
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
                    {{ user.name || 'Mahasiswa' }}

                    <span class="text-gray-400">
                        - Mahasiswa
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