<script setup>
import { ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    proxy: Object,
    errors: Object,
});

const form = ref({
    new_name: props.proxy.person.name || "",
    ci: props.proxy.person.ci || "",
    address: props.proxy.person.address || "",
    gender: props.proxy.person.gender || "M",
    birth_date: props.proxy.person.birth_date || "",
    phone_number: props.proxy.phone_number || "",
    occupation: props.proxy.occupation || "",
});

const submit = () => {
    router.put(route("proxies.update", { proxy: props.proxy.id }), form.value);
};

const goBack = () => {
    router.visit(route("proxies.index"));
};
</script>

<template>
    <AppLayout title="Editar Apoderado">
        <template #header>
            <h2 class="custom-page-title">Editar Apoderado</h2>
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
                        Guardar Cambios
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
