<script setup>
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const page = usePage();

const dataMasterOpen = ref(false);

const currentUrl = computed(() => page.url);

const userName = computed(() => {
    return page.props.auth?.user?.name ?? 'Admin GTK';
});

const pageTitle = computed(() => {
    return page.props.title ?? 'Dashboard';
});

const pageSubtitle = computed(() => {
    return page.props.subtitle ?? null;
});

const isActive = (path) => {
    if (path === '/admin/dashboard') {
        return currentUrl.value === path;
    }

    return currentUrl.value.startsWith(path);
};

const isDataMasterActive = computed(() => {
    return (
        currentUrl.value.startsWith('/admin/sekolah') ||
        currentUrl.value.startsWith('/admin/guru-pamong') ||
        currentUrl.value.startsWith('/admin/mahasiswa')
    );
});

const toggleDataMaster = () => {
    dataMasterOpen.value = !dataMasterOpen.value;
};

const logout = () => {
    router.post('/logout');
};
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <div class="flex min-h-screen">
            <aside class="w-64 bg-slate-800 text-white flex-shrink-0 flex flex-col">
                <div class="p-4 text-lg font-bold border-b border-slate-700 flex items-center gap-2">
                    <span class="text-blue-400">
                        SIM
                    </span>
                    <span>
                        MagangGTK
                    </span>
                </div>

                <div class="p-4 flex items-center gap-3 border-b border-slate-700">
                    <div class="w-10 h-10 rounded-full bg-slate-600 flex items-center justify-center text-lg font-bold">
                        {{ userName.charAt(0).toUpperCase() }}
                    </div>

                    <div class="min-w-0">
                        <p class="text-sm font-semibold leading-tight truncate">
                            {{ userName }}
                        </p>

                        <p class="text-xs text-green-400 flex items-center gap-1 mt-1">
                            <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                            Online
                        </p>
                    </div>
                </div>

                <p class="px-4 pt-4 pb-2 text-xs text-slate-400 uppercase tracking-wide">
                    Main Navigation
                </p>

                <nav class="flex-1 overflow-y-auto">
                    <button
                        type="button"
                        @click="router.visit('/admin/dashboard')"
                        :class="[
                            'w-full flex items-center gap-3 px-4 py-3 text-sm transition-colors duration-150 text-left',
                            isActive('/admin/dashboard')
                                ? 'bg-blue-600'
                                : 'hover:bg-slate-700'
                        ]"
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
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a2 2 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                            />
                        </svg>

                        Dashboard
                    </button>

                    <button
                        type="button"
                        @click="toggleDataMaster"
                        :class="[
                            'w-full flex items-center justify-between px-4 py-3 text-sm transition-colors duration-150',
                            isDataMasterActive
                                ? 'bg-slate-700'
                                : 'hover:bg-slate-700'
                        ]"
                    >
                        <span class="flex items-center gap-3">
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

                            Data Master
                        </span>

                        <svg
                            class="w-4 h-4 flex-shrink-0 transition-transform duration-200"
                            :class="dataMasterOpen ? 'rotate-90' : ''"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </button>

                    <div
                        v-show="dataMasterOpen"
                        class="bg-slate-900"
                    >
                        <button
                            type="button"
                            @click="router.visit('/admin/sekolah')"
                            :class="[
                                'w-full flex items-center gap-2 pl-12 pr-4 py-2.5 text-sm text-left transition-colors duration-150 hover:bg-slate-700',
                                isActive('/admin/sekolah')
                                    ? 'bg-slate-700 text-blue-400'
                                    : 'text-slate-300'
                            ]"
                        >
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                            Sekolah
                        </button>

                        <button
                            type="button"
                            @click="router.visit('/admin/guru-pamong')"
                            :class="[
                                'w-full flex items-center gap-2 pl-12 pr-4 py-2.5 text-sm text-left transition-colors duration-150 hover:bg-slate-700',
                                isActive('/admin/guru-pamong')
                                    ? 'bg-slate-700 text-blue-400'
                                    : 'text-slate-300'
                            ]"
                        >
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                            Guru Pamong
                        </button>

                        <button
                            type="button"
                            @click="router.visit('/admin/mahasiswa')"
                            :class="[
                                'w-full flex items-center gap-2 pl-12 pr-4 py-2.5 text-sm text-left transition-colors duration-150 hover:bg-slate-700',
                                isActive('/admin/mahasiswa')
                                    ? 'bg-slate-700 text-blue-400'
                                    : 'text-slate-300'
                            ]"
                        >
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                            Mahasiswa
                        </button>
                    </div>

                    <button
                        type="button"
                        @click="router.visit('/admin/penempatan')"
                        :class="[
                            'w-full flex items-center gap-3 px-4 py-3 text-sm text-left transition-colors duration-150 hover:bg-slate-700 transition-colors duration-150',
                            isActive('/admin/penempatan')
                                ? 'bg-blue-600'
                                : ''
                        ]"
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
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                            />
                        </svg>

                        Penempatan
                    </button>

                    <button
                        type="button"
                        @click="router.visit('/profile')"
                        class="w-full text-left flex items-center gap-3 px-4 py-3 text-sm transition-colors duration-150 hover:bg-slate-700"
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
                                d="M12 12a5 5 0 100-10 5 5 0 000 10zm-7 9a7 7 0 0114 0"
                            />
                        </svg>

                        Profil
                    </button>

                    <button
                        type="button"
                        @click="logout"
                        class="w-full text-left flex items-center gap-3 px-4 py-3 text-sm transition-colors duration-150 hover:bg-slate-700"
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

                        Logout
                    </button>
                </nav>
            </aside>

            <div class="flex-1 flex flex-col min-w-0">
                <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center">
                    <div>
                        <h1 class="text-lg font-semibold text-gray-800">
                            {{ pageTitle }}
                        </h1>

                        <p
                            v-if="pageSubtitle"
                            class="text-xs text-gray-400"
                        >
                            {{ pageSubtitle }}
                        </p>
                    </div>

                    <div class="text-sm text-gray-600">
                        {{ userName }}

                        <span class="text-gray-400">
                            - Admin GTK
                        </span>
                    </div>
                </header>

                <main class="p-6 flex-1">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>