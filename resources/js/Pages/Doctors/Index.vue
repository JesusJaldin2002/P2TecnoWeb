<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { ref, watch, onMounted } from "vue";
import Paginator from "@/Components/Paginator.vue";

// Props
const props = defineProps({
    doctors: [Object, Array],
    search: String,
    success: String,
});

// Referencia para búsqueda
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

// Función para búsqueda
watch(search, (newSearch) => {
    router.visit(route("doctors.index"), {
        method: "get",
        data: { search: newSearch },
        preserveState: true,
        preserveScroll: true,
    });
});

// Confirmación de eliminación
const confirmDelete = (doctor) => {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¡Esta acción no se puede deshacer!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminarlo",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("doctors.destroy", { doctor: doctor.id }), {
                onSuccess: () => {
                    Swal.fire(
                        "Eliminado",
                        "El doctor ha sido eliminado correctamente.",
                        "success"
                    );
                },
            });
        }
    });
};
</script>

<template>
    <AppLayout title="Doctores">
        <template #header>
            <h2 class="custom-page-title">Doctores</h2>
        </template>

        <div class="custom-container">
            <div class="custom-flex">
                <Link
                    :href="route('doctors.create')"
                    class="custom-btn custom-btn-primary"
                >
                    Crear Doctor
                </Link>
                <input
                    v-model="search"
                    type="text"
                    placeholder="Busca un doctor"
                    class="custom-input-search"
                />
            </div>

            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Número SS</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="doctor in doctors.data" :key="doctor.id">
                        <td>{{ doctor.id }}</td>
                        <td>{{ doctor.user.name }}</td>
                        <td>{{ doctor.user.email }}</td>
                        <td>{{ doctor.number_ss }}</td>
                        <td class="custom-actions-cell">
                            <Link
                                :href="route('doctors.edit', { doctor: doctor.id })"
                                class="custom-btn custom-btn-edit"
                            >
                                Editar
                            </Link>
                            <button
                                @click="confirmDelete(doctor)"
                                class="custom-btn custom-btn-delete"
                            >
                                Eliminar
                            </button>
                            <Link
                                :href="route('doctors.show', { doctor: doctor.id })"
                                class="custom-btn custom-btn-view"
                            >
                                Ver
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>

            <Paginator :paginator="doctors" />
        </div>
    </AppLayout>
</template>
