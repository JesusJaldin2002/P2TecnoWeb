<script setup>
import { ref, onMounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

// Props
const props = defineProps({
    caresheet: Object,
    patient_name: String,
    errors: Object,
});

// Formulario reactivo
const form = ref({
    symptoms: props.caresheet.symptoms || "",
    diagnosis: props.caresheet.diagnosis || "",
    notes: props.caresheet.notes || "",
});

// Métodos
const submit = () => {
    router.put(route("caresheets.update", { id: props.caresheet.id }), form.value);
};

const goBack = () => {
    router.visit(route("caresheets.index"));
};
</script>

<template>
    <AppLayout title="Editar Hoja de Atención">
        <template #header>
            <h2 class="custom-page-title">Editar Hoja de Atención</h2>
        </template>

        <div class="custom-container">
            <div class="custom-card">
                <!-- Información del paciente -->
                <p class="custom-mb-4">
                    <strong>Paciente:</strong> {{ patient_name }}
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
                        <div v-if="errors.symptoms" class="custom-error-message">
                            {{ errors.symptoms }}
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
                        <div v-if="errors.diagnosis" class="custom-error-message">
                            {{ errors.diagnosis }}
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
                        <div v-if="errors.notes" class="custom-error-message">
                            {{ errors.notes }}
                        </div>
                    </div>

                    <!-- Botón Guardar Cambios -->
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
