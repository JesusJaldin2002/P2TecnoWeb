<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { ref, watch, onMounted } from "vue";
import Paginator from "@/Components/Paginator.vue";

// Props
const props = defineProps({
    employees: [Object, Array],
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
    router.visit(route("employees.index"), {
        method: "get",
        data: { search: newSearch },
        preserveState: true,
        preserveScroll: true,
    });
});

// Confirmación de eliminación
const confirmDelete = (employee) => {
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
            router.delete(route("employees.destroy", { employee: employee.id }), {
                onSuccess: () => {
                    Swal.fire(
                        "Eliminado",
                        "El empleado ha sido eliminado correctamente.",
                        "success"
                    );
                },
            });
        }
    });
};
</script>

<template>
    <AppLayout title="Empleados">
        <template #header>
            <h2 class="custom-page-title">Empleados</h2>
        </template>

        <div class="custom-container">
            <div class="custom-flex">
                <Link
                    :href="route('employees.create')"
                    class="custom-btn custom-btn-primary"
                >
                    Crear Empleado
                </Link>
                <input
                    v-model="search"
                    type="text"
                    placeholder="Busca un empleado"
                    class="custom-input-search"
                />
            </div>

            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Ocupación</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="employee in employees.data" :key="employee.id">
                        <td>{{ employee.id }}</td>
                        <td>{{ employee.user.name }}</td>
                        <td>{{ employee.user.email }}</td>
                        <td>{{ employee.occupation }}</td>
                        <td class="custom-actions-cell">
                            <Link
                                :href="route('employees.edit', { employee: employee.id })"
                                class="custom-btn custom-btn-edit"
                            >
                                Editar
                            </Link>
                            <button
                                @click="confirmDelete(employee)"
                                class="custom-btn custom-btn-delete"
                            >
                                Eliminar
                            </button>
                            <Link
                                :href="route('employees.show', { employee: employee.id })"
                                class="custom-btn custom-btn-view"
                            >
                                Ver
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>

            <Paginator :paginator="employees" />
        </div>
    </AppLayout>
</template>
