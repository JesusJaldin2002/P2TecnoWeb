<script setup>
import { ref, onMounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

const form = ref({
    service_id: "",
    date: new Date().toISOString().split("T")[0], // Fecha actual
    payment_type: "Efectivo", // Tipo de pago prellenado
    total: 0.0,
    tigo_transaction_id: null, // Vacío inicialmente
});

const props = defineProps({
    errors: Object, // Errores del backend
    services: Array, // Servicios disponibles
});

// Inicializar Select2
onMounted(() => {
    $(".js-service-select")
        .select2({
            placeholder: "Seleccione un servicio",
            language: {
                noResults: () => "No hay servicios disponibles",
                searching: () => "Buscando...",
            },
        })
        .on("change", function () {
            form.value.service_id = $(this).val();
            const selectedService = props.services.find(
                (service) => service.id == form.value.service_id
            );
            form.value.total = selectedService ? selectedService.price : 0;
        });
});

const submit = () => {
    router.post(route("payments.store"), form.value);
};

const goBack = () => {
    router.visit(route("payments.index"));
};
</script>

<template>
    <AppLayout title="Registrar Pago">
        <template #header>
            <h2 class="custom-page-title">Registrar Pago</h2>
        </template>

        <div class="custom-container">
            <div class="custom-card">
                <button
                    @click="goBack"
                    type="button"
                    class="custom-btn custom-btn-back custom-mb-4"
                >
                    Volver atrás
                </button>
                <form @submit.prevent="submit">
                    <!-- Seleccionar Servicio -->
                    <div class="custom-form-group">
                        <label for="service_id" class="custom-label">Servicio</label>
                        <select
                            id="service_id"
                            class="js-service-select custom-input"
                            name="service_id"
                        >
                            <option value="" disabled selected>
                                Seleccione un servicio
                            </option>
                            <option
                                v-for="service in services"
                                :key="service.id"
                                :value="service.id"
                            >
                                ID Servicio: {{ service.id }} - {{ service.patient.person.name }} -
                                Fecha: {{ new Date(service.created_at).toLocaleDateString() }} -
                                Precio: Bs {{ service.price }} -
                                Tipo:
                                {{
                                    service.service_type === 'C'
                                        ? 'Consulta'
                                        : service.service_type === 'T'
                                        ? 'Tratamiento'
                                        : service.service_type === 'V'
                                        ? 'Vacuna'
                                        : 'Desconocido'
                                }}
                            </option>
                        </select>
                        <div v-if="errors.service_id" class="custom-error-message">
                            {{ errors.service_id }}
                        </div>
                    </div>

                    <!-- Fecha -->
                    <div class="custom-form-group">
                        <label for="date" class="custom-label">Fecha</label>
                        <input
                            type="date"
                            name="date"
                            id="date"
                            v-model="form.date"
                            class="custom-input"
                            readonly
                        />
                    </div>

                    <!-- Tipo de Pago -->
                    <div class="custom-form-group">
                        <label for="payment_type" class="custom-label">Tipo de Pago</label>
                        <input
                            type="text"
                            name="payment_type"
                            id="payment_type"
                            v-model="form.payment_type"
                            class="custom-input"
                            readonly
                        />
                    </div>

                    <!-- Total -->
                    <div class="custom-form-group">
                        <label for="total" class="custom-label">Total</label>
                        <input
                            type="text"
                            name="total"
                            id="total"
                            v-model="form.total"
                            class="custom-input"
                            readonly
                        />
                    </div>

                    <!-- Botón Enviar -->
                    <button
                        type="submit"
                        class="custom-btn custom-btn-primary custom-mt-4"
                    >
                        Registrar Pago
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
