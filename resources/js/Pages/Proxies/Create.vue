<script setup>
import { ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

const form = ref({
    ci: "",
    name: "",
    address: "",
    gender: "M",
    birth_date: "",
    phone_number: "",
    occupation: "",
});

defineProps({ errors: Object });

const submit = () => {
    router.post(route("proxies.store"), form.value);
};

const goBack = () => {
    router.visit(route("proxies.index"));
};
</script>

<template>
    <AppLayout title="Crear Apoderado">
        <template #header>
            <h2 class="custom-page-title">Crear Apoderado</h2>
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
                            <label for="name" class="custom-label">Nombre</label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                v-model="form.name"
                                class="custom-input"
                            />
                            <div v-if="errors.name" class="custom-error-message">
                                {{ errors.name }}
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

                    <!-- Teléfono y Ocupación en una fila -->
                    <div class="custom-form-row">
                        <div class="custom-form-group custom-form-column">
                            <label for="phone_number" class="custom-label">Teléfono</label>
                            <input
                                type="text"
                                name="phone_number"
                                id="phone_number"
                                v-model="form.phone_number"
                                class="custom-input"
                            />
                            <div v-if="errors.phone_number" class="custom-error-message">
                                {{ errors.phone_number }}
                            </div>
                        </div>

                        <div class="custom-form-group custom-form-column">
                            <label for="occupation" class="custom-label">Ocupación</label>
                            <input
                                type="text"
                                name="occupation"
                                id="occupation"
                                v-model="form.occupation"
                                class="custom-input"
                            />
                            <div v-if="errors.occupation" class="custom-error-message">
                                {{ errors.occupation }}
                            </div>
                        </div>
                    </div>

                    <!-- Botón Enviar -->
                    <button
                        type="submit"
                        class="custom-btn custom-btn-primary custom-mt-4"
                    >
                        Crear Apoderado
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
