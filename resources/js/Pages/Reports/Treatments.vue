<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { reactive, onMounted } from "vue";
import { router } from "@inertiajs/vue3";

const form = reactive({
    patient_id: "",
    date_from: "",
    date_to: "",
});

const props = defineProps({
    errors: Object,
    patients: Array, // Lista de pacientes
    treatments: [Object, Array], // Datos del reporte
});

onMounted(() => {
    // Inicializar select2 para pacientes
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
    router.get(route("reports.treatment"), form);
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
                <title>Reporte de Tratamientos</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .card { border: 1px solid #ccc; padding: 15px; margin-bottom: 15px; }
                    .card-header { font-weight: bold; margin-bottom: 10px; }
                    h2 { text-align: center; }
                </style>
            </head>
            <body>
                <h2>Reporte de Tratamientos</h2>
                ${printableSection.innerHTML}
            </body>
        </html>
    `);

    newWindow.document.close();
    newWindow.print();
};
</script>

<template>
    <AppLayout title="Reporte de Tratamientos">
        <template #header>
            <h2 class="custom-page-title">Reporte de Tratamientos de un Paciente</h2>
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
                    v-if="treatments && treatments.data && treatments.data.length"
                    @click="printResults"
                    class="custom-btn custom-btn-success custom-mb-4"
                >
                    Imprimir Resultados
                </button>

                <div
                    v-if="treatments && treatments.data && treatments.data.length"
                    id="printable-section"
                >
                    <div
                        v-for="treatment in treatments.data"
                        :key="treatment.id"
                        class="custom-card"
                    >
                        <div class="card-header">
                            Tratamiento ID: {{ treatment.id }} - Paciente:
                            {{ treatment.service.patient.person.name }} (CI:
                            {{ treatment.service.patient.person.ci }})
                        </div>
                        <p><strong>Fecha:</strong> {{ treatment.service.created_at }}</p>
                        <p><strong>Origen:</strong> {{ treatment.origin }}</p>
                        <p><strong>Sala:</strong> {{ treatment.room.name || "Sin sala asignada" }}</p>
                        <p><strong>Estado:</strong> {{ treatment.status }}</p>
                        <p><strong>Observaciones:</strong></p>
                        <ul>
                            <li
                                v-for="observation in treatment.observations"
                                :key="observation.id"
                            >
                                Fecha: {{ observation.date }} - Peso:
                                {{ observation.weight }}kg - Altura:
                                {{ observation.height }}cm - Edad:
                                {{ observation.age }} - Doctor:
                                {{ observation.doctor.user.name }}
                            </li>
                        </ul>
                        <p><strong>Comidas:</strong></p>
                        <ul>
                            <li
                                v-for="meal in treatment.meals"
                                :key="meal.id"
                            >
                                {{ meal.name }} - Precio: {{ meal.price }} Bs
                            </li>
                        </ul>
                    </div>
                </div>
                <div v-else class="custom-text-center">
                    <p>No se encontraron resultados.</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
