<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { ref, watch, onMounted } from "vue";
import Paginator from "@/Components/Paginator.vue";

// Props
const props = defineProps({
    rooms: [Object, Array],
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
    router.visit(route("rooms.index"), {
        method: "get",
        data: { search: newSearch },
        preserveState: true,
        preserveScroll: true,
    });
});

// Confirmación de eliminación
const confirmDelete = (room) => {
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
            router.delete(route("rooms.destroy", { room: room.id }), {
                onSuccess: () => {
                    Swal.fire(
                        "Eliminado",
                        "La habitación ha sido eliminada correctamente.",
                        "success"
                    );
                },
            });
        }
    });
};
</script>

<template>
    <AppLayout title="Habitaciones">
        <template #header>
            <h2 class="custom-page-title">Habitaciones</h2>
        </template>

        <div class="custom-container">
            <div class="custom-flex">
                <Link
                    :href="route('rooms.create')"
                    class="custom-btn custom-btn-primary"
                >
                    Crear Habitación
                </Link>
                <input
                    v-model="search"
                    type="text"
                    placeholder="Busca una habitación"
                    class="custom-input-search"
                />
            </div>

            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Capacidad</th>
                        <th>Disponibles</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="room in rooms.data" :key="room.id">
                        <td>{{ room.id }}</td>
                        <td>{{ room.name }}</td>
                        <td>{{ room.capacity }}</td>
                        <td>{{ room.available_rooms }}</td>
                        <td class="custom-actions-cell">
                            <Link
                                :href="route('rooms.edit', { room: room.id })"
                                class="custom-btn custom-btn-edit"
                            >
                                Editar
                            </Link>
                            <button
                                @click="confirmDelete(room)"
                                class="custom-btn custom-btn-delete"
                            >
                                Eliminar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <Paginator :paginator="rooms" />
        </div>
    </AppLayout>
</template>
