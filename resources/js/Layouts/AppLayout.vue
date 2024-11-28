<script setup>
import { ref, onMounted, computed } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import Banner from "@/Components/Banner.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { usePage } from "@inertiajs/vue3";
import FooterComponent from '@/composables/FooterComponent.vue';

defineProps({
    title: String,
});

const page = usePage();
const userRole = computed(() => page.props.auth.user.role.name);
console.log(page.props.auth.user.id);
console.log("Usuario autenticado:", page.props.auth.user.name);

const isGerente = computed(() => userRole.value === "Gerente");
const isEmpleado = computed(() => userRole.value === "Empleado");
const isDoctor = computed(() => userRole.value === "Doctor");

const showingNavigationDropdown = ref(false);
const showingResponsiveDropdownUsuarios = ref(false);
const showingResponsiveDropdownRecursos = ref(false);
const showingResponsiveDropdownServicios = ref(false);
const showingResponsiveDropdownAtencion = ref(false);
const showingResponsiveDropdownFinanciera = ref(false);

const currentTheme = ref("default");

const logout = () => {
    router.post(route("logout"));
};

const isDarkMode = computed(() =>
    document.documentElement.classList.contains("theme-dark")
);
// Función para cambiar el tema (incluye la lógica manual/automática)
const changeTheme = (theme, manualDarkMode = null) => {
    const currentHour = new Date().getHours();
    const isNightTime =
        manualDarkMode === null
            ? currentHour >= 19 || currentHour < 6 // Automático: basado en la hora
            : manualDarkMode; // Manual: forzado por el usuario

    const htmlElement = document.documentElement;

    // Elimina las clases de tema actuales
    htmlElement.classList.remove(
        "theme-default",
        "theme-children",
        "theme-youth",
        "theme-adults",
        "theme-dark"
    );

    // Agregar la clase del nuevo tema
    if (theme !== "default") {
        htmlElement.classList.add(`theme-${theme}`);
    }

    // Agregar el modo oscuro si corresponde
    if (isNightTime) {
        htmlElement.classList.add("theme-dark");
    }

    // Guardar las preferencias en localStorage
    localStorage.setItem("theme", theme);
    if (manualDarkMode !== null) {
        localStorage.setItem("manualDarkMode", manualDarkMode);
    } else {
        localStorage.removeItem("manualDarkMode"); // Restaurar a automático si no es manual
    }

    console.log(`Tema: ${theme}`);
    console.log(`Modo oscuro: ${isNightTime ? "Activado" : "Desactivado"}`);
    console.log(`Modo manual: ${manualDarkMode !== null ? "Sí" : "No"}`);
};

// Función para alternar modo día/noche manualmente
const toggleManualDarkMode = () => {
    const currentDarkMode =
        document.documentElement.classList.contains("theme-dark");
    const newManualDarkMode = !currentDarkMode; // Alternamos
    const savedTheme = localStorage.getItem("theme") || "default";

    // Aplicamos el nuevo modo manual
    changeTheme(savedTheme, newManualDarkMode);
};

// Al montar el componente, cargar el tema y el estado manual
onMounted(() => {
    const savedTheme = localStorage.getItem("theme") || "default";
    const manualDarkMode = localStorage.getItem("manualDarkMode") === "true";
    changeTheme(savedTheme, manualDarkMode ? manualDarkMode : null);
});
</script>

<template>
    <div>
        <Head class="title" :title="title" />

        <Banner />

        <div class="">
            <nav class="border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <img
                                        src="https://www.tecnoweb.org.bo/inf513/grupo04sa/proyecto3/P2TecnoWeb/public/images/medical.png"
                                        alt="Logo"
                                        class="block h-12 w-auto"
                                    />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                            >
                                <NavLink
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                >
                                    Dashboard
                                </NavLink>
                            </div>

                            <!-- Dropdown: Registro de Usuarios -->
                            <div
                                v-if="isGerente || isEmpleado"
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                            >
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none"
                                        >
                                            Registro
                                            <svg
                                                class="ms-2 -me-0.5 h-5 w-5"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
                                                />
                                            </svg>
                                        </button>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            v-if="isGerente"
                                            :href="route('doctors.index')"
                                            :active="
                                                route().current('doctors.index')
                                            "
                                        >
                                            Doctores
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="isGerente"
                                            :href="route('employees.index')"
                                            :active="
                                                route().current(
                                                    'employees.index'
                                                )
                                            "
                                        >
                                            Empleados
                                        </DropdownLink>
                                        <div
                                            v-if="isGerente"
                                            class="border-t border-gray-200"
                                        ></div>
                                        <DropdownLink
                                            v-if="isGerente || isEmpleado"
                                            :href="route('patients.index')"
                                            :active="
                                                route().current(
                                                    'patients.index'
                                                )
                                            "
                                        >
                                            Pacientes
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="isGerente || isEmpleado"
                                            :href="route('proxies.index')"
                                            :active="
                                                route().current('proxies.index')
                                            "
                                        >
                                            Apoderados
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>

                            <!-- Dropdown: Recursos -->
                            <div
                                v-if="isGerente || isEmpleado"
                                class="hidden space-x-8 sm:-my-px sm:ms-4 sm:flex"
                            >
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none"
                                        >
                                            Recursos
                                            <svg
                                                class="ms-2 -me-0.5 h-5 w-5"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
                                                />
                                            </svg>
                                        </button>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            v-if="isGerente"
                                            :href="route('rooms.index')"
                                            :active="
                                                route().current('rooms.index')
                                            "
                                        >
                                            Salas
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="isGerente || isEmpleado"
                                            :href="route('meals.index')"
                                            :active="
                                                route().current('meals.index')
                                            "
                                        >
                                            Productos
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                            <!-- Dropdown: Servicios -->
                            <div
                                v-if="isEmpleado"
                                class="hidden space-x-8 sm:-my-px sm:ms-4 sm:flex"
                            >
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none"
                                        >
                                            Servicios
                                            <svg
                                                class="ms-2 -me-0.5 h-5 w-5"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
                                                />
                                            </svg>
                                        </button>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            v-if="isEmpleado"
                                            :href="route('treatments.index')"
                                            :active="
                                                route().current(
                                                    'treatments.index'
                                                )
                                            "
                                        >
                                            Tratamientos
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="isEmpleado"
                                            :href="route('consults.index')"
                                            :active="
                                                route().current(
                                                    'consults.index'
                                                )
                                            "
                                        >
                                            Consultas
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="isEmpleado"
                                            :href="route('vaccines.index')"
                                            :active="
                                                route().current(
                                                    'vaccines.index'
                                                )
                                            "
                                        >
                                            Vacunas
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                            <!-- Dropdown: Atencion -->
                            <div
                                v-if="isDoctor"
                                class="hidden space-x-8 sm:-my-px sm:ms-4 sm:flex"
                            >
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none"
                                        >
                                            Atención
                                            <svg
                                                class="ms-2 -me-0.5 h-5 w-5"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
                                                />
                                            </svg>
                                        </button>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            v-if="isDoctor"
                                            :href="route('caresheets.index')"
                                            :active="
                                                route().current(
                                                    'caresheets.index'
                                                )
                                            "
                                        >
                                            Hojas de Atención
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="isDoctor"
                                            :href="route('observations.index')"
                                            :active="
                                                route().current(
                                                    'observations.index'
                                                )
                                            "
                                        >
                                            Seguimientos
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                            <!-- Dropdown: Financiera -->
                            <div
                                v-if="isGerente || isEmpleado"
                                class="hidden space-x-8 sm:-my-px sm:ms-4 sm:flex"
                            >
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none"
                                        >
                                            Adm. Financiera
                                            <svg
                                                class="ms-2 -me-0.5 h-5 w-5"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
                                                />
                                            </svg>
                                        </button>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            v-if="isGerente || isEmpleado"
                                            :href="route('payments.index')"
                                            :active="
                                                route().current(
                                                    'payments.index'
                                                )
                                            "
                                        >
                                            Pagos
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="isGerente || isEmpleado"
                                            :href="route('reports.index')"
                                            :active="
                                                route().current('reports.index')
                                            "
                                        >
                                            Reportes
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="isGerente || isEmpleado"
                                            :href="route('stadistics.index')"
                                            :active="
                                                route().current(
                                                    'stadistics.index'
                                                )
                                            "
                                        >
                                            Estadísticas
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>
                        <!-- Theme Switcher in Dropdown -->
                        <div
                            class="hidden sm:flex sm:items-center sm:ms-6 gap-4"
                        >
                            <!-- Dropdown para cambiar tema -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-500 bg-white border-2 border-transparent rounded-md hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150"
                                        >
                                            Cambiar Tema
                                            <svg
                                                class="ms-2 -me-0.5 size-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                                                />
                                            </svg>
                                        </button>
                                    </template>

                                    <template #content>
                                        <!-- Botones para seleccionar temas -->
                                        <div>
                                            <DropdownLink
                                                href="#"
                                                @click="changeTheme('default')"
                                            >
                                                Default
                                            </DropdownLink>
                                            <DropdownLink
                                                href="#"
                                                @click="changeTheme('children')"
                                            >
                                                Niños
                                            </DropdownLink>
                                            <DropdownLink
                                                href="#"
                                                @click="changeTheme('youth')"
                                            >
                                                Jóvenes
                                            </DropdownLink>
                                            <DropdownLink
                                                href="#"
                                                @click="changeTheme('adults')"
                                            >
                                                Adultos
                                            </DropdownLink>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>

                            <!-- Botón para alternar modo día/noche -->
                            <button
                                @click="toggleManualDarkMode"
                                class="px-4 py-2 text-sm font-medium text-white bg-gray-800 rounded-md hover:bg-gray-700 focus:outline-none focus:bg-gray-700 transition ease-in-out duration-150"
                            >
                                Probar
                            </button>
                        </div>
                        <!-- Settings Dropdown -->
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            v-if="
                                                $page.props.jetstream
                                                    .managesProfilePhotos
                                            "
                                            class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition"
                                        >
                                            <img
                                                class="size-8 rounded-full object-cover"
                                                :src="
                                                    $page.props.auth.user
                                                        .profile_photo_url
                                                "
                                                :alt="
                                                    $page.props.auth.user.name
                                                "
                                            />
                                        </button>

                                        <span
                                            v-else
                                            class="inline-flex rounded-md"
                                        >
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}
                                                <svg
                                                    class="ms-2 -me-0.5 size-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div
                                            class="block px-4 py-2 text-xs text-gray-400"
                                        >
                                            Manage Account
                                        </div>
                                        <DropdownLink
                                            :href="route('profile.show')"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="
                                                $page.props.jetstream
                                                    .hasApiFeatures
                                            "
                                            :href="route('api-tokens.index')"
                                        >
                                            API Tokens
                                        </DropdownLink>
                                        <div
                                            class="border-t border-gray-200"
                                        ></div>
                                        <form @submit.prevent="logout">
                                            <DropdownLink as="button"
                                                >Log Out</DropdownLink
                                            >
                                        </form>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                            >
                                <svg
                                    class="size-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Dashboard
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Dropdown: Registro de Usuarios -->
                    <div
                        v-if="isGerente || isEmpleado"
                        class="pt-2 pb-3 space-y-1"
                    >
                        <button
                            class="w-full flex items-center justify-between px-4 py-2 text-left text-gray-500 bg-white hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-600 transition duration-150 ease-in-out"
                            @click="
                                showingResponsiveDropdownUsuarios =
                                    !showingResponsiveDropdownUsuarios
                            "
                        >
                            Registro
                            <svg
                                class="h-5 w-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
                                />
                            </svg>
                        </button>
                        <div
                            v-if="showingResponsiveDropdownUsuarios"
                            class="mt-2 bg-gray-50 border border-gray-200 rounded-md shadow-md"
                        >
                            <div class="py-1">
                                <ResponsiveNavLink
                                    v-if="isGerente"
                                    :href="route('doctors.index')"
                                    :active="route().current('doctors.index')"
                                >
                                    Doctores
                                </ResponsiveNavLink>
                                <ResponsiveNavLink
                                    v-if="isGerente"
                                    :href="route('employees.index')"
                                    :active="route().current('employees.index')"
                                >
                                    Empleados
                                </ResponsiveNavLink>
                                <div class="border-t border-gray-200"></div>
                                <ResponsiveNavLink
                                    v-if="isGerente || isEmpleado"
                                    :href="route('patients.index')"
                                    :active="route().current('patients.index')"
                                >
                                    Pacientes
                                </ResponsiveNavLink>
                                <ResponsiveNavLink
                                    v-if="isGerente || isEmpleado"
                                    :href="route('proxies.index')"
                                    :active="route().current('proxies.index')"
                                >
                                    Apoderados
                                </ResponsiveNavLink>
                            </div>
                        </div>
                    </div>

                    <!-- Responsive Dropdown: Recursos -->
                    <div
                        v-if="isGerente || isEmpleado"
                        class="pt-2 pb-3 space-y-1"
                    >
                        <button
                            class="w-full flex items-center justify-between px-4 py-2 text-left text-gray-500 bg-white hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-600 transition duration-150 ease-in-out"
                            @click="
                                showingResponsiveDropdownRecursos =
                                    !showingResponsiveDropdownRecursos
                            "
                        >
                            Recursos
                            <svg
                                class="h-5 w-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
                                />
                            </svg>
                        </button>
                        <div
                            v-if="showingResponsiveDropdownRecursos"
                            class="mt-2 bg-gray-50 border border-gray-200 rounded-md shadow-md"
                        >
                            <div class="py-1">
                                <ResponsiveNavLink
                                    v-if="isGerente"
                                    :href="route('rooms.index')"
                                    :active="route().current('rooms.index')"
                                >
                                    Salas
                                </ResponsiveNavLink>
                                <ResponsiveNavLink
                                    v-if="isGerente || isEmpleado"
                                    :href="route('meals.index')"
                                    :active="route().current('meals.index')"
                                >
                                    Productos
                                </ResponsiveNavLink>
                            </div>
                        </div>
                    </div>
                    <!-- Responsive Dropdown: Servicios -->
                    <div v-if="isEmpleado" class="pt-2 pb-3 space-y-1">
                        <button
                            class="w-full flex items-center justify-between px-4 py-2 text-left text-gray-500 bg-white hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-600 transition duration-150 ease-in-out"
                            @click="
                                showingResponsiveDropdownServicios =
                                    !showingResponsiveDropdownServicios
                            "
                        >
                            Servicios
                            <svg
                                class="h-5 w-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
                                />
                            </svg>
                        </button>
                        <div
                            v-if="showingResponsiveDropdownServicios"
                            class="mt-2 bg-gray-50 border border-gray-200 rounded-md shadow-md"
                        >
                            <div class="py-1">
                                <ResponsiveNavLink
                                    v-if="isEmpleado"
                                    :href="route('treatments.index')"
                                    :active="
                                        route().current('treatments.index')
                                    "
                                >
                                    Tratamientos
                                </ResponsiveNavLink>
                                <ResponsiveNavLink
                                    v-if="isEmpleado"
                                    :href="route('consults.index')"
                                    :active="route().current('consults.index')"
                                >
                                    Consultas
                                </ResponsiveNavLink>
                                <ResponsiveNavLink
                                    v-if="isEmpleado"
                                    :href="route('vaccines.index')"
                                    :active="route().current('vaccines.index')"
                                >
                                    Vacunas
                                </ResponsiveNavLink>
                            </div>
                        </div>
                    </div>
                    <!-- Responsive Dropdown: Atencion -->
                    <div v-if="isDoctor" class="pt-2 pb-3 space-y-1">
                        <button
                            class="w-full flex items-center justify-between px-4 py-2 text-left text-gray-500 bg-white hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-600 transition duration-150 ease-in-out"
                            @click="
                                showingResponsiveDropdownAtencion =
                                    !showingResponsiveDropdownAtencion
                            "
                        >
                            Atención
                            <svg
                                class="h-5 w-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
                                />
                            </svg>
                        </button>
                        <div
                            v-if="showingResponsiveDropdownAtencion"
                            class="mt-2 bg-gray-50 border border-gray-200 rounded-md shadow-md"
                        >
                            <div class="py-1">
                                <ResponsiveNavLink
                                    v-if="isDoctor"
                                    :href="route('caresheets.index')"
                                    :active="
                                        route().current('caresheets.index')
                                    "
                                >
                                    Hojas de Atención
                                </ResponsiveNavLink>
                                <ResponsiveNavLink
                                    v-if="isDoctor"
                                    :href="route('observations.index')"
                                    :active="
                                        route().current('observations.index')
                                    "
                                >
                                    Seguimientos
                                </ResponsiveNavLink>
                            </div>
                        </div>
                    </div>
                    <!-- Responsive Dropdown: Financiera -->
                    <div
                        v-if="isGerente || isEmpleado"
                        class="pt-2 pb-3 space-y-1"
                    >
                        <button
                            class="w-full flex items-center justify-between px-4 py-2 text-left text-gray-500 bg-white hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-600 transition duration-150 ease-in-out"
                            @click="
                                showingResponsiveDropdownFinanciera =
                                    !showingResponsiveDropdownFinanciera
                            "
                        >
                            Adm. Financiera
                            <svg
                                class="h-5 w-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
                                />
                            </svg>
                        </button>
                        <div
                            v-if="showingResponsiveDropdownFinanciera"
                            class="mt-2 bg-gray-50 border border-gray-200 rounded-md shadow-md"
                        >
                            <div class="py-1">
                                <ResponsiveNavLink
                                    v-if="isGerente || isEmpleado"
                                    :href="route('payments.index')"
                                    :active="route().current('payments.index')"
                                >
                                    Pagos
                                </ResponsiveNavLink>
                                <ResponsiveNavLink
                                    v-if="isGerente || isEmpleado"
                                    :href="route('reports.index')"
                                    :active="route().current('reports.index')"
                                >
                                    Reportes
                                </ResponsiveNavLink>
                                <ResponsiveNavLink
                                    :href="route('stadistics.index')"
                                    :active="
                                        route().current('stadistics.index')
                                    "
                                >
                                    Estadísticas
                                </ResponsiveNavLink>
                            </div>
                        </div>
                    </div>
                    <!-- Responsive Dropdown: Cambiar Tema -->
                    <div class="pt-2 pb-3 space-y-1">
                        <button
                            class="w-full flex items-center justify-between px-4 py-2 text-left text-gray-500 bg-white hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-600 transition duration-150 ease-in-out"
                            @click="
                                showingResponsiveDropdownFinanciera =
                                    !showingResponsiveDropdownFinanciera
                            "
                        >
                            Cambiar Tema
                            <svg
                                class="h-5 w-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
                                />
                            </svg>
                        </button>
                        <div
                            v-if="showingResponsiveDropdownFinanciera"
                            class="mt-2 bg-gray-50 border border-gray-200 rounded-md shadow-md"
                        >
                            <div class="py-1">
                                <ResponsiveNavLink
                                    href="#"
                                    @click.prevent="changeTheme('default')"
                                >
                                    Default
                                </ResponsiveNavLink>
                                <ResponsiveNavLink
                                    href="#"
                                    @click.prevent="changeTheme('children')"
                                >
                                    Niños
                                </ResponsiveNavLink>
                                <ResponsiveNavLink
                                    href="#"
                                    @click.prevent="changeTheme('youth')"
                                >
                                    Jóvenes
                                </ResponsiveNavLink>
                                <ResponsiveNavLink
                                    href="#"
                                    @click.prevent="changeTheme('adults')"
                                >
                                    Adultos
                                </ResponsiveNavLink>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>

            <FooterComponent />
        </div>
    </div>
</template>
