<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { ref, watch, onMounted } from "vue";
import Paginator from "@/Components/Paginator.vue";

const props = defineProps({
    payments: [Object, Array],
    search: String,
    success: String,
});

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
    if (props.error) {
        Swal.fire({
            title: "Error",
            text: props.error,
            icon: "error",
            confirmButtonText: "OK",
        });
    }
});

// Función para búsqueda
watch(search, (newSearch) => {
    router.visit(route("payments.index"), {
        method: "get",
        data: { search: newSearch },
        preserveState: true,
        preserveScroll: true,
    });
});
</script>

<template>
    <AppLayout title="Pagos">
        <template #header>
            <h2 class="custom-page-title">Listado de Pagos</h2>
        </template>

        <div class="custom-container">
            <div class="custom-flex">
                <Link
                    :href="route('payments.create')"
                    class="custom-btn custom-btn-primary"
                >
                    Registrar Pago
                </Link>
                <input
                    v-model="search"
                    type="text"
                    placeholder="Buscar un pago..."
                    class="custom-input-search"
                />
            </div>

            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paciente</th>
                        <th>Tipo de Servicio</th>
                        <th>Fecha y Hora</th>
                        <th>Total</th>
                        <th>Tipo de Pago</th>
                        <th>ID Transacción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="payment in payments.data" :key="payment.id">
                        <td>{{ payment.id }}</td>
                        <td>{{ payment.service.patient.person.name }}</td>
                        <td>
                            {{
                                payment.service.service_type === 'C'
                                    ? 'Consulta'
                                    : payment.service.service_type === 'T'
                                    ? 'Tratamiento'
                                    : payment.service.service_type === 'V'
                                    ? 'Vacuna'
                                    : 'Desconocido'
                            }}
                        </td>
                        <td>{{ payment.date }} - {{ payment.payment_time }}</td>
                        <td><strong>Bs </strong>{{ payment.total }}</td>
                        <td>{{ payment.payment_type || 'No especificado' }}</td>
                        <td>{{ payment.tigo_transaction_id || 'No especificado' }}</td>
                    </tr>
                </tbody>
            </table>

            <Paginator :paginator="payments" />
        </div>
    </AppLayout>
</template>
