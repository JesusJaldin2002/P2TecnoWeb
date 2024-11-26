<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { ref, watch, onMounted } from "vue";
import Paginator from "@/Components/Paginator.vue";

// Props
const props = defineProps({
    meals: [Object, Array],
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
    router.visit(route("meals.index"), {
        method: "get",
        data: { search: newSearch },
        preserveState: true,
        preserveScroll: true,
    });
});

// Confirmación de eliminación
const confirmDelete = (meal) => {
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
            router.delete(route("meals.destroy", { meal: meal.id }), {
                onSuccess: () => {
                    Swal.fire(
                        "Eliminado",
                        "El producto ha sido eliminada correctamente.",
                        "success"
                    );
                },
            });
        }
    });
};
</script>

<template>
    <AppLayout title="Productos">
        <template #header>
            <h2 class="custom-page-title">Productos</h2>
        </template>

        <div class="custom-container">
            <div class="custom-flex">
                <Link
                    :href="route('meals.create')"
                    class="custom-btn custom-btn-primary"
                >
                    Crear Producto
                </Link>
                <input
                    v-model="search"
                    type="text"
                    placeholder="Busca un producto..."
                    class="custom-input-search"
                />
            </div>

            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Ingredientes</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="meal in meals.data" :key="meal.id">
                        <td>{{ meal.id }}</td>
                        <td>{{ meal.name }}</td>
                        <td><strong>Bs </strong>{{ meal.price }}</td>
                        <td>{{ meal.ingredients }}</td>
                        <td class="custom-actions-cell">
                            <Link
                                :href="route('meals.edit', { meal: meal.id })"
                                class="custom-btn custom-btn-edit"
                            >
                                Editar
                            </Link>
                            <button
                                @click="confirmDelete(meal)"
                                class="custom-btn custom-btn-delete"
                            >
                                Eliminar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <Paginator :paginator="meals" />
        </div>
    </AppLayout>
</template>
