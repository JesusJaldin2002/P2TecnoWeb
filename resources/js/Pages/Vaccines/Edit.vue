<script setup>
import { ref, onMounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

// Formulario
const props = defineProps({
    vaccine: Object,
    patients: Array,
    errors: Object,
});

const form = ref({
    patient_id: props.vaccine.service.patient.id || "",
    vaccine_name: props.vaccine.name || "",
});

onMounted(() => {
    // Inicializar Select2
    $('.js-example-basic-single').select2({
        language: {
                noResults: function () {
                    return "No hay resultados";
                },
                searching: function () {
                    return "Buscando..";
                },
            },
    }).val(form.value.patient_id).trigger('change');

    // Actualizar el valor en el formulario cuando cambie en Select2
    $('.js-example-basic-single').on('change', function () {
        form.value.patient_id = $(this).val();
    });
});

const submit = () => {
    router.put(route("vaccines.update", { vaccine: props.vaccine.id }), form.value);
};

const goBack = () => {
    router.visit(route("vaccines.index"));
};
</script>

<template>
    <AppLayout title="Editar Vacuna">
        <template #header>
            <h2 class="custom-page-title">Editar Vacuna</h2>
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
                            class="js-example-basic-single custom-input"
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

                    <!-- Nombre de la Vacuna -->
                    <div class="custom-form-group">
                        <label for="vaccine_name" class="custom-label">Nombre de la Vacuna</label>
                        <input
                            type="text"
                            name="vaccine_name"
                            id="vaccine_name"
                            v-model="form.vaccine_name"
                            class="custom-input"
                        />
                        <div v-if="errors.vaccine_name" class="custom-error-message">
                            {{ errors.vaccine_name }}
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
