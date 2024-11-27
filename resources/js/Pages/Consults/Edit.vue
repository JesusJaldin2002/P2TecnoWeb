<script setup>
import { ref, onMounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

// Formulario
const props = defineProps({
    consult: Object,
    patients: Array,
    doctors: Array,
    errors: Object,
});

const form = ref({
    patient_id: props.consult.service.patient.id || "",
    doctor_id: props.consult.doctor.id || "",
    date: props.consult.date || "",
    reason: props.consult.reason || "",
});

onMounted(() => {
    // Inicializar Select2 para pacientes
    $('.js-patient-select')
        .select2({
            language: {
                noResults: function () {
                    return "No hay resultados";
                },
                searching: function () {
                    return "Buscando..";
                },
            },
        })
        .val(form.value.patient_id)
        .trigger('change')
        .on('change', function () {
            form.value.patient_id = $(this).val();
        });

    // Inicializar Select2 para doctores
    $('.js-doctor-select')
        .select2({
            language: {
                noResults: function () {
                    return "No hay resultados";
                },
                searching: function () {
                    return "Buscando..";
                },
            },
        })
        .val(form.value.doctor_id)
        .trigger('change')
        .on('change', function () {
            form.value.doctor_id = $(this).val();
        });
});

const submit = () => {
    router.put(route("consults.update", { consult: props.consult.id }), form.value);
};

const goBack = () => {
    router.visit(route("consults.index"));
};
</script>

<template>
    <AppLayout title="Editar Consulta">
        <template #header>
            <h2 class="custom-page-title">Editar Consulta</h2>
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
                    <!-- Seleccionar Paciente -->
                    <div class="custom-form-group">
                        <label for="patient_id" class="custom-label">Paciente</label>
                        <select
                            id="patient_id"
                            class="js-patient-select custom-input"
                            name="patient_id"
                        >
                            <option value="" disabled>Seleccione un paciente</option>
                            <option
                                v-for="patient in patients"
                                :key="patient.id"
                                :value="patient.id"
                            >
                                {{ patient.person.name }} (CI: {{ patient.person.ci }})
                            </option>
                        </select>
                        <div v-if="errors.patient_id" class="custom-error-message">
                            {{ errors.patient_id }}
                        </div>
                    </div>

                    <!-- Seleccionar Doctor -->
                    <div class="custom-form-group">
                        <label for="doctor_id" class="custom-label">Doctor</label>
                        <select
                            id="doctor_id"
                            class="js-doctor-select custom-input"
                            name="doctor_id"
                        >
                            <option value="" disabled>Seleccione un doctor</option>
                            <option
                                v-for="doctor in doctors"
                                :key="doctor.id"
                                :value="doctor.id"
                            >
                                {{ doctor.user.name }} (CI: {{ doctor.user.ci }})
                            </option>
                        </select>
                        <div v-if="errors.doctor_id" class="custom-error-message">
                            {{ errors.doctor_id }}
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
                        />
                        <div v-if="errors.date" class="custom-error-message">
                            {{ errors.date }}
                        </div>
                    </div>

                    <!-- Motivo -->
                    <div class="custom-form-group">
                        <label for="reason" class="custom-label">Motivo</label>
                        <textarea
                            name="reason"
                            id="reason"
                            v-model="form.reason"
                            class="custom-input"
                            rows="3"
                        ></textarea>
                        <div v-if="errors.reason" class="custom-error-message">
                            {{ errors.reason }}
                        </div>
                    </div>

                    <!-- Botón Enviar -->
                    <button
                        type="submit"
                        class="custom-btn custom-btn-primary custom-mt-4"
                    >
                        Guardar Cambios
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
