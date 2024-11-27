<script setup>
import { ref, onMounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

// Props para datos iniciales
const props = defineProps({
    consult_id: {
        type: Number,
        required: true, // El ID de la consulta es obligatorio
    },
    patient_name: {
        type: String,
        required: true, // El nombre del paciente es obligatorio
    },
    errors: {
        type: Object,
        default: () => ({}), // Si no hay errores, inicializa como un objeto vacío
    },
});

// Formulario reactivo
const form = ref({
    consult_id: props.consult_id, // Inicializa el ID de la consulta desde las props
    symptoms: "",
    diagnosis: "",
    notes: "",
});

// Métodos
const submit = () => {
    router.post(route("caresheets.store"), form.value);
};

const goBack = () => {
    router.visit(route("caresheets.index"));
};
</script>

<template>
    <AppLayout title="Crear Hoja de Atención">
        <template #header>
            <h2 class="custom-page-title">Crear Hoja de Atención</h2>
        </template>

        <div class="custom-container">
            <div class="custom-card">
                <!-- Mostrar información del paciente -->
                <p class="custom-mb-4">
                    <strong>Paciente:</strong> {{ props.patient_name }}
                </p>

                <!-- Botón para volver -->
                <button
                    @click="goBack"
                    type="button"
                    class="custom-btn custom-btn-back custom-mb-4"
                >
                    Volver atrás
                </button>

                <!-- Formulario -->
                <form @submit.prevent="submit">
                    <!-- Campo Consult ID (oculto) -->
                    <input type="hidden" v-model="form.consult_id" />

                    <!-- Campo Síntomas -->
                    <div class="custom-form-group">
                        <label for="symptoms" class="custom-label">Síntomas</label>
                        <input
                            type="text"
                            name="symptoms"
                            id="symptoms"
                            v-model="form.symptoms"
                            class="custom-input"
                        />
                        <div v-if="props.errors.symptoms" class="custom-error-message">
                            {{ props.errors.symptoms }}
                        </div>
                    </div>

                    <!-- Campo Diagnóstico -->
                    <div class="custom-form-group">
                        <label for="diagnosis" class="custom-label">Diagnóstico</label>
                        <input
                            type="text"
                            name="diagnosis"
                            id="diagnosis"
                            v-model="form.diagnosis"
                            class="custom-input"
                        />
                        <div v-if="props.errors.diagnosis" class="custom-error-message">
                            {{ props.errors.diagnosis }}
                        </div>
                    </div>

                    <!-- Campo Notas -->
                    <div class="custom-form-group">
                        <label for="notes" class="custom-label">Notas</label>
                        <textarea
                            name="notes"
                            id="notes"
                            v-model="form.notes"
                            class="custom-textarea"
                        ></textarea>
                        <div v-if="props.errors.notes" class="custom-error-message">
                            {{ props.errors.notes }}
                        </div>
                    </div>

                    <!-- Botón Enviar -->
                    <button
                        type="submit"
                        class="custom-btn custom-btn-primary custom-mt-4"
                    >
                        Crear Hoja de Atención
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
