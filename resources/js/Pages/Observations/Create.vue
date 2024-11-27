<script setup>
import { reactive } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    treatment: Object, // Información del tratamiento
    errors: Object, // Errores de validación
});

const form = reactive({
    treatment_id: props.treatment.id, // Campo oculto
    weight: null,
    height: null,
    age: null,
    description: "",
});

// Enviar formulario
const submit = () => {
    router.post(route("observations.store"), form);
};

// Ir atrás
const goBack = () => {
    router.visit(route("observations.index"));
};
</script>

<template>
    <AppLayout title="Registrar Seguimiento">
        <template #header>
            <h2 class="custom-page-title">Registrar Seguimiento</h2>
        </template>

        <div class="custom-container">
            <div class="custom-card">
                <!-- Botón de Volver -->
                <button
                    @click="goBack"
                    type="button"
                    class="custom-btn custom-btn-back custom-mb-4"
                >
                    Volver atrás
                </button>

                <!-- Información del Tratamiento -->
                <div class="custom-info">
                    <p>
                        <strong>Paciente:</strong>
                        {{ treatment.service.patient.person.name }}
                    </p>
                    <p><strong>Origen:</strong> {{ treatment.origin }}</p>
                    <p><strong>Estado:</strong> {{ treatment.status }}</p>
                </div>

                <!-- Formulario -->
                <form @submit.prevent="submit">
                    <!-- Campo Oculto -->
                    <input
                        type="hidden"
                        name="treatment_id"
                        :value="form.treatment_id"
                    />
                    <div class="custom-form-row">
                        
                    </div>
                    <!-- Peso y Altura en una fila -->
                    <div class="custom-form-row">
                        <div class="custom-form-column">
                            <label for="weight" class="custom-label"
                                >Peso (kg)</label
                            >
                            <input
                                type="number"
                                step="0.01"
                                name="weight"
                                id="weight"
                                v-model.number="form.weight"
                                class="custom-input"
                            />
                            <div
                                v-if="errors.weight"
                                class="custom-error-message"
                            >
                                {{ errors.weight }}
                            </div>
                        </div>

                        <div class="custom-form-column">
                            <label for="height" class="custom-label"
                                >Altura (m)</label
                            >
                            <input
                                type="number"
                                step="0.01"
                                name="height"
                                id="height"
                                v-model.number="form.height"
                                class="custom-input"
                            />
                            <div
                                v-if="errors.height"
                                class="custom-error-message"
                            >
                                {{ errors.height }}
                            </div>
                        </div>
                    </div>

                    <!-- Edad -->
                    <div class="custom-form-group">
                        <label for="age" class="custom-label">Edad</label>
                        <input
                            type="number"
                            name="age"
                            id="age"
                            v-model.number="form.age"
                            class="custom-input"
                        />
                        <div v-if="errors.age" class="custom-error-message">
                            {{ errors.age }}
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="custom-form-group">
                        <label for="description" class="custom-label"
                            >Descripción</label
                        >
                        <textarea
                            name="description"
                            id="description"
                            v-model="form.description"
                            class="custom-textarea"
                        ></textarea>
                        <div
                            v-if="errors.description"
                            class="custom-error-message"
                        >
                            {{ errors.description }}
                        </div>
                    </div>

                    <!-- Botón Enviar -->
                    <button
                        type="submit"
                        class="custom-btn custom-btn-primary custom-mt-4"
                    >
                        Registrar Seguimiento
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
