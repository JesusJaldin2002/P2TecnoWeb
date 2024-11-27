<script setup>
import { reactive } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    observation: Object, // Observación a editar
    errors: Object, // Errores de validación
});

const form = reactive({
    weight: props.observation.weight || null,
    height: props.observation.height || null,
    age: props.observation.age || null,
    description: props.observation.description || "",
});

// Enviar formulario
const submit = () => {
    router.put(
        route("observations.update", { observation: props.observation.id }),
        form
    );
};

// Ir atrás
const goBack = () => {
    router.visit(
        route("observations.show", {
            treatment: props.observation.treatment_id,
        })
    );
};
</script>

<template>
    <AppLayout title="Editar Seguimiento">
        <template #header>
            <h2 class="custom-page-title">Editar Seguimiento</h2>
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

                <!-- Información del tratamiento -->
                <div class="custom-info">
                    <p>
                        <strong>Paciente:</strong>
                        {{ observation.treatment.service.patient.person.name }}
                    </p>
                    <p>
                        <strong>Fecha:</strong>
                        {{ new Date(observation.date).toLocaleDateString() }}
                    </p>
                </div>

                <!-- Formulario -->
                <form @submit.prevent="submit">
                    <div class="custom-form-row">
                        <!-- Peso -->
                        <div class="custom-form-group">
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
                        <!-- Altura -->
                        <div class="custom-form-group">
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
                        Guardar Cambios
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
