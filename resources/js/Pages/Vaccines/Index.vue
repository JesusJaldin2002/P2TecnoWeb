<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { ref, watch, onMounted } from "vue";
import Paginator from "@/Components/Paginator.vue";

// Props
const props = defineProps({
    vaccines: [Object, Array],
    search: String,
    success: String,
});

const goToPayment = (serviceId, amount) => {
    router.get(route('payments.pay', { service_id: serviceId, amount: amount }));
};

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
    router.visit(route("vaccines.index"), {
        method: "get",
        data: { search: newSearch },
        preserveState: true,
        preserveScroll: true,
    });
});

// Confirmación de eliminación
const confirmDelete = (vaccine) => {
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
            router.delete(route("vaccines.destroy", { vaccine: vaccine.id }), {
                onSuccess: () => {
                    Swal.fire(
                        "Eliminado",
                        "El Servicio de Vacuna ha sido eliminado correctamente.",
                        "success"
                    );
                },
            });
        }
    });
};
</script>

<template>
    <AppLayout title="Vacunas">
        <template #header>
            <h2 class="custom-page-title">Servicios de Vacunación</h2>
        </template>

        <div class="custom-container">
            <div class="custom-flex">
                <Link
                    :href="route('vaccines.create')"
                    class="custom-btn custom-btn-primary"
                >
                    Registrar Vacuna
                </Link>
                <input
                    v-model="search"
                    type="text"
                    placeholder="Busca un servicio de vacuna"
                    class="custom-input-search"
                />
            </div>

            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paciente</th>
                        <th>Empleado</th>
                        <th>Precio</th>
                        <th>Vacuna</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="vaccine in vaccines.data" :key="vaccine.id">
                        <td>{{ vaccine.id }}</td>
                        <td>{{ vaccine.service.patient.person.name }}</td>
                        <td>{{ vaccine.service.employee.user.name }}</td>
                        <td>{{ vaccine.service.price }}</td>
                        <td>{{ vaccine.name }}</td>
                        <td class="custom-actions-cell">
                            <Link
                                :href="route('vaccines.edit', { vaccine: vaccine.id })"
                                class="custom-btn custom-btn-edit"
                            >
                                Editar
                            </Link>
                            <button
                                @click="confirmDelete(vaccine)"
                                class="custom-btn custom-btn-delete"
                            >
                                Eliminar
                            </button>
                            <button
                                @click="
                                    goToPayment(
                                        vaccine.service.id,
                                        vaccine.service.price
                                    )
                                "
                                class="custom-btn custom-btn-view"
                            >
                                Pagar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <Paginator :paginator="vaccines" />
        </div>
    </AppLayout>
</template>
