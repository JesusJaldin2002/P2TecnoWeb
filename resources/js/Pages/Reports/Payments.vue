<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, reactive, onMounted } from "vue";
import { router } from "@inertiajs/vue3";

const form = reactive({
    patient_id: "",
    date_from: "",
    date_to: "",
});

const props = defineProps({
    errors: Object,
    patients: Array, // Lista de pacientes
    payments: [Object, Array], // Datos del reporte
});

onMounted(() => {
    // Inicializar select2
    $(".js-patient-select")
        .select2({
            placeholder: "Seleccione un paciente",
            language: {
                noResults: () => "No hay resultados",
                searching: () => "Buscando...",
            },
        })
        .on("change", function () {
            form.patient_id = $(this).val();
        });
});

const searchReport = () => {
    router.get(route("reports.payment"), form);
};

const printResults = () => {
    const printableSection = document.getElementById("printable-section");

    if (!printableSection) {
        alert("No hay resultados para imprimir.");
        return;
    }

    const newWindow = window.open("", "_blank");

    newWindow.document.write(`
        <html>
            <head>
                <title>Reporte de Pagos</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
                    th { background-color: #f4f4f4; }
                    h2 { text-align: center; }
                </style>
            </head>
            <body>
                <h2>Reporte de Pagos de un Cliente</h2>
                ${printableSection.innerHTML}
            </body>
        </html>
    `);

    newWindow.document.close();
    newWindow.print();
};
</script>

<template>
    <AppLayout title="Reporte de Pagos">
        <template #header>
            <h2 class="custom-page-title">Reporte de Pagos de un Cliente</h2>
        </template>

        <div class="custom-container">
            <!-- Filtros -->
            <div class="custom-card custom-mb-4">
                <div class="custom-form-group">
                    <label for="patient_id" class="custom-label">Paciente</label>
                    <select
                        id="patient_id"
                        class="js-patient-select custom-input"
                        name="patient_id"
                    >
                        <option value="" disabled selected>Seleccione un paciente</option>
                        <option
                            v-for="patient in patients"
                            :key="patient.id"
                            :value="patient.id"
                        >
                            {{ patient.person.name }} (CI: {{ patient.person.ci }})
                        </option>
                    </select>
                    <div v-if="errors?.patient_id" class="custom-error-message">
                        {{ errors.patient_id }}
                    </div>
                </div>

                <div class="custom-flex">
                    <div class="custom-form-group">
                        <label for="date_from" class="custom-label">Fecha Desde</label>
                        <input
                            type="date"
                            id="date_from"
                            v-model="form.date_from"
                            class="custom-input"
                        />
                        <div v-if="errors?.date_from" class="custom-error-message">
                            {{ errors.date_from }}
                        </div>
                    </div>
                    <div class="custom-form-group">
                        <label for="date_to" class="custom-label">Fecha Hasta</label>
                        <input
                            type="date"
                            id="date_to"
                            v-model="form.date_to"
                            class="custom-input"
                        />
                        <div v-if="errors?.date_to" class="custom-error-message">
                            {{ errors.date_to }}
                        </div>
                    </div>
                </div>

                <button
                    @click="searchReport"
                    class="custom-btn custom-btn-primary custom-mt-4"
                >
                    Buscar
                </button>
            </div>

            <!-- Resultados -->
            <div>
                <button
                    v-if="payments && payments.data && payments.data.length"
                    @click="printResults"
                    class="custom-btn custom-btn-success custom-mb-4"
                >
                    Imprimir Resultados
                </button>

                <div
                    v-if="payments && payments.data && payments.data.length"
                    id="printable-section"
                    class="custom-card"
                >
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Paciente</th>
                                <th>CI</th>
                                <th>Tipo de Servicio</th>
                                <th>Fecha del Pago</th>
                                <th>Total (Bs)</th>
                                <th>Tipo de Pago</th>
                                <th>ID Transacción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="payment in payments.data" :key="payment.id">
                                <td>{{ payment.id }}</td>
                                <td>{{ payment.service.patient.person.name }}</td>
                                <td>{{ payment.service.patient.person.ci }}</td>
                                <td>
                                    {{
                                        payment.service.service_type === "C"
                                            ? "Consulta"
                                            : payment.service.service_type === "T"
                                            ? "Tratamiento"
                                            : payment.service.service_type === "V"
                                            ? "Vacuna"
                                            : "Desconocido"
                                    }}
                                </td>
                                <td>{{ payment.date }}</td>
                                <td><strong></strong>{{ payment.total }}</td>
                                <td>{{ payment.payment_type || "No especificado" }}</td>
                                <td>{{ payment.tigo_transaction_id || "No especificado" }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="custom-text-center">
                    <p>No se encontraron resultados.</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

