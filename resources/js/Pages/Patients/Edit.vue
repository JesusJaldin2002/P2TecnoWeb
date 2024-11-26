<script setup>
import { ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    patient: Object,
    proxies: Array,
    errors: Object,
});

const form = ref({
    new_name: props.patient.person.name || "",
    ci: props.patient.person.ci || "",
    address: props.patient.person.address || "",
    gender: props.patient.person.gender || "M",
    birth_date: props.patient.person.birth_date || "",
    blood_type: props.patient.blood_type || "A",
    rh_factor: props.patient.rh_factor || "+",
    proxy_id: props.patient.proxy_id || "",
});

const submit = () => {
    router.put(route("patients.update", { patient: props.patient.id }), form.value);
};

const goBack = () => {
    router.visit(route("patients.index"));
};
</script>

<template>
    <AppLayout title="Editar Paciente">
        <template #header>
            <h2 class="custom-page-title">Editar Paciente</h2>
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
                    <!-- Seleccionar Apoderado -->
                    <div class="custom-form-group">
                        <label for="proxy_id" class="custom-label">Apoderado</label>
                        <select
                            name="proxy_id"
                            id="proxy_id"
                            v-model="form.proxy_id"
                            class="custom-input"
                        >
                            <option value="" disabled>Seleccione un apoderado</option>
                            <option v-for="proxy in proxies" :key="proxy.id" :value="proxy.id">
                                {{ proxy.person.name }}
                            </option>
                        </select>
                        <div v-if="errors.proxy_id" class="custom-error-message">
                            {{ errors.proxy_id }}
                        </div>
                    </div>

                    <!-- CI y Nombre en una fila -->
                    <div class="custom-form-row">
                        <div class="custom-form-group custom-form-column">
                            <label for="ci" class="custom-label">CI</label>
                            <input
                                type="text"
                                name="ci"
                                id="ci"
                                v-model="form.ci"
                                class="custom-input"
                            />
                            <div v-if="errors.ci" class="custom-error-message">
                                {{ errors.ci }}
                            </div>
                        </div>

                        <div class="custom-form-group custom-form-column">
                            <label for="new_name" class="custom-label">Nombre</label>
                            <input
                                type="text"
                                name="new_name"
                                id="new_name"
                                v-model="form.new_name"
                                class="custom-input"
                            />
                            <div v-if="errors.new_name" class="custom-error-message">
                                {{ errors.new_name }}
                            </div>
                        </div>
                    </div>

                    <!-- Campo Dirección -->
                    <div class="custom-form-group">
                        <label for="address" class="custom-label">Dirección</label>
                        <input
                            type="text"
                            name="address"
                            id="address"
                            v-model="form.address"
                            class="custom-input"
                        />
                        <div v-if="errors.address" class="custom-error-message">
                            {{ errors.address }}
                        </div>
                    </div>

                    <!-- Fecha de Nacimiento y Género en una fila -->
                    <div class="custom-form-row">
                        <div class="custom-form-group custom-form-column">
                            <label for="birth_date" class="custom-label">Fecha de Nacimiento</label>
                            <input
                                type="date"
                                name="birth_date"
                                id="birth_date"
                                v-model="form.birth_date"
                                class="custom-input"
                            />
                            <div v-if="errors.birth_date" class="custom-error-message">
                                {{ errors.birth_date }}
                            </div>
                        </div>

                        <div class="custom-form-group custom-form-column">
                            <label for="gender" class="custom-label">Género</label>
                            <select
                                name="gender"
                                id="gender"
                                v-model="form.gender"
                                class="custom-input"
                            >
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                            <div v-if="errors.gender" class="custom-error-message">
                                {{ errors.gender }}
                            </div>
                        </div>
                    </div>

                    <!-- Tipo de Sangre y Factor RH en una fila -->
                    <div class="custom-form-row">
                        <div class="custom-form-group custom-form-column">
                            <label for="blood_type" class="custom-label">Tipo de Sangre</label>
                            <select
                                name="blood_type"
                                id="blood_type"
                                v-model="form.blood_type"
                                class="custom-input"
                            >
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>
                            </select>
                            <div v-if="errors.blood_type" class="custom-error-message">
                                {{ errors.blood_type }}
                            </div>
                        </div>

                        <div class="custom-form-group custom-form-column">
                            <label for="rh_factor" class="custom-label">Factor RH</label>
                            <select
                                name="rh_factor"
                                id="rh_factor"
                                v-model="form.rh_factor"
                                class="custom-input"
                            >
                                <option value="+">Positivo (+)</option>
                                <option value="-">Negativo (-)</option>
                            </select>
                            <div v-if="errors.rh_factor" class="custom-error-message">
                                {{ errors.rh_factor }}
                            </div>
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
