<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, reactive, onMounted } from "vue";
import { router } from "@inertiajs/vue3";

const form = reactive({
    doctor_id: "",
    date_from: "",
    date_to: "",
});

const props = defineProps({
    errors: Object,
    doctors: Array, // Lista de doctores
    consults: [Object, Array], // Datos del reporte
});

onMounted(() => {
    // Inicializar select2 para doctores
    $(".js-doctor-select")
        .select2({
            placeholder: "Seleccione un doctor",
            language: {
                noResults: () => "No hay resultados",
                searching: () => "Buscando...",
            },
        })
        .on("change", function () {
            form.doctor_id = $(this).val();
        });
});

const searchReport = () => {
    router.get(route("reports.consult"), form);
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
                <title>Reporte de Consultas</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
                    th { background-color: #f4f4f4; }
                    h2 { text-align: center; }
                </style>
            </head>
            <body>
                <h2>Reporte de Consultas de un Doctor</h2>
                ${printableSection.innerHTML}
            </body>
        </html>
    `);

    newWindow.document.close();
    newWindow.print();
};
</script>

<template>
    <AppLayout title="Reporte de Consultas">
        <template #header>
            <h2 class="custom-page-title">Reporte de Consultas de un Doctor</h2>
        </template>

        <div class="custom-container">
            <!-- Filtros -->
            <div class="custom-card custom-mb-4">
                <div class="custom-form-group">
                    <label for="doctor_id" class="custom-label">Doctor</label>
                    <select
                        id="doctor_id"
                        class="js-doctor-select custom-input"
                        name="doctor_id"
                    >
                        <option value="" disabled selected>Seleccione un doctor</option>
                        <option
                            v-for="doctor in doctors"
                            :key="doctor.id"
                            :value="doctor.id"
                        >
                            {{ doctor.user.name }} (CI: {{ doctor.user.ci }})
                        </option>
                    </select>
                    <div v-if="errors?.doctor_id" class="custom-error-message">
                        {{ errors.doctor_id }}
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
                    v-if="consults && consults.data && consults.data.length"
                    @click="printResults"
                    class="custom-btn custom-btn-success custom-mb-4"
                >
                    Imprimir Resultados
                </button>

                <div
                    v-if="consults && consults.data && consults.data.length"
                    id="printable-section"
                    class="custom-card"
                >
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Doctor</th>
                                <th>Paciente</th>
                                <th>CI Paciente</th>
                                <th>Fecha</th>
                                <th>Razón</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="consult in consults.data" :key="consult.id">
                                <td>{{ consult.id }}</td>
                                <td>{{ consult.doctor.user.name }}</td>
                                <td>{{ consult.service.patient.person.name }}</td>
                                <td>{{ consult.service.patient.person.ci }}</td>
                                <td>{{ consult.date }}</td>
                                <td>{{ consult.reason || "No especificada" }}</td>
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
