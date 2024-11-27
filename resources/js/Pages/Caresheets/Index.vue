<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { ref, watch, onMounted } from "vue";
import Paginator from "@/Components/Paginator.vue";

// Props
const props = defineProps({
    consults: [Object, Array], // Consultas asociadas al doctor
    search: String, // Búsqueda actual
    success: String, // Mensaje de éxito
});

// Estado para búsqueda
const search = ref(props.search || "");

// Mostrar mensaje de éxito
onMounted(() => {
    if (props.success) {
        Swal.fire({
            title: "Éxito",
            text: props.success,
            icon: "success",
            confirmButtonText: "OK",
        });
    }
});

// Función para búsqueda en tiempo real
watch(search, (newSearch) => {
    router.visit(route("caresheets.index"), {
        method: "get",
        data: { search: newSearch },
        preserveState: true,
        preserveScroll: true,
    });
});
</script>

<template>
    <AppLayout title="Hojas de Atención">
        <template #header>
            <h2 class="custom-page-title">Hojas de Atención</h2>
        </template>

        <div class="custom-container">
            <!-- Búsqueda -->
            <div class="custom-flex custom-mb-4">
                <input
                    v-model="search"
                    type="text"
                    placeholder="Buscar por paciente, fecha o motivo"
                    class="custom-input-search"
                />
            </div>

            <!-- Tabla de Consultas -->
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paciente</th>
                        <th>Fecha</th>
                        <th>Motivo</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="consult in consults.data" :key="consult.id">
                        <td>{{ consult.id }}</td>
                        <td>{{ consult.service.patient.person.name }}</td>
                        <td>{{ consult.date }}</td>
                        <td>{{ consult.reason }}</td>
                        <td class="custom-actions-cell">
                            <!-- Botón dinámico para crear o ver hoja de atención -->
                            <Link
                                :href="consult.caresheet
                                    ? route('caresheets.show', { id: consult.caresheet.id })
                                    : route('caresheets.create', { consult_id: consult.id })"
                                class="custom-btn"
                                :class="consult.caresheet
                                    ? 'custom-btn-view'
                                    : 'custom-btn-primary'"
                            >
                                {{
                                    consult.caresheet
                                        ? 'Ver Hoja de Atención'
                                        : 'Crear Hoja de Atención'
                                }}
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Paginador -->
            <Paginator :paginator="consults" />
        </div>
    </AppLayout>
</template>
