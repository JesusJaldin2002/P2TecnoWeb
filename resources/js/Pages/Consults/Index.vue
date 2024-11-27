<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { ref, watch, onMounted } from "vue";
import Paginator from "@/Components/Paginator.vue";

// Props
const props = defineProps({
    consults: [Object, Array],
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
    router.visit(route("consults.index"), {
        method: "get",
        data: { search: newSearch },
        preserveState: true,
        preserveScroll: true,
    });
});

// Confirmación de eliminación
const confirmDelete = (consult) => {
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
            router.delete(route("consults.destroy", { consult: consult.id }), {
                onSuccess: () => {
                    Swal.fire(
                        "Eliminado",
                        "La consulta ha sido eliminada correctamente.",
                        "success"
                    );
                },
            });
        }
    });
};
</script>

<template>
    <AppLayout title="Consultas">
        <template #header>
            <h2 class="custom-page-title">Servicios de Consulta</h2>
        </template>

        <div class="custom-container">
            <div class="custom-flex">
                <Link
                    :href="route('consults.create')"
                    class="custom-btn custom-btn-primary"
                >
                    Registrar Consulta
                </Link>
                <input
                    v-model="search"
                    type="text"
                    placeholder="Busca una consulta"
                    class="custom-input-search"
                />
            </div>

            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paciente</th>
                        <th>Empleado</th>
                        <th>Doctor</th>
                        <th>Fecha</th>
                        <th>Motivo</th>
                        <th>Precio</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="consult in consults.data" :key="consult.id">
                        <td>{{ consult.id }}</td>
                        <td>{{ consult.service.patient.person.name }}</td>
                        <td>{{ consult.service.employee.user.name }}</td>
                        <td>{{ consult.doctor.user.name }}</td>
                        <td>{{ consult.date }}</td>
                        <td>
                            {{ consult.reason || "Sin motivo especificado" }}
                        </td>
                        <td>{{ consult.service.price }}</td>
                        <td class="custom-actions-cell">
                            <Link
                                :href="
                                    route('consults.edit', {
                                        consult: consult.id,
                                    })
                                "
                                class="custom-btn custom-btn-edit"
                            >
                                Editar
                            </Link>
                            <button
                                @click="confirmDelete(consult)"
                                class="custom-btn custom-btn-delete"
                            >
                                Eliminar
                            </button>
                            <button
                                @click="
                                    goToPayment(
                                        consult.service.id,
                                        consult.service.price
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

            <Paginator :paginator="consults" />
        </div>
    </AppLayout>
</template>
