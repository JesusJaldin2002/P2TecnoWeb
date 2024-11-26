<script setup>
import { ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    employee: Object,
    errors: Object,
});

// Preevaluar los datos en el formulario
const form = ref({
    new_name: props.employee.user.name || "",
    email: props.employee.user.email || "",
    ci: props.employee.user.ci || "",
    phone_number: props.employee.user.phone_number || "",
    address: props.employee.user.address || "",
    occupation: props.employee.occupation || "",
    password: "", // Contraseña vacía
});

const submit = () => {
    router.put(
        route("employees.update", { employee: props.employee.id }),
        form.value
    );
};

const goBack = () => {
    router.visit(route("employees.index"));
};
</script>

<template>
    <AppLayout title="Editar Empleado">
        <template #header>
            <h2 class="custom-page-title">Editar Empleado</h2>
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
                <form @submit.prevent="submit" autocomplete="off">
                    <!-- Campo Nombre -->
                    <div class="custom-form-group">
                        <label for="new_name" class="custom-label">Nombre</label>
                        <input
                            type="text"
                            name="new_name"
                            id="new_name"
                            v-model="form.new_name"
                            class="custom-input"
                            autocomplete="off"
                        />
                        <div v-if="errors?.new_name" class="custom-error-message">
                            {{ errors.new_name }}
                        </div>
                    </div>

                    <!-- Campo Email -->
                    <div class="custom-form-group">
                        <label for="email" class="custom-label">Email</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            v-model="form.email"
                            class="custom-input"
                            autocomplete="off"
                        />
                        <div v-if="errors?.email" class="custom-error-message">
                            {{ errors.email }}
                        </div>
                    </div>

                    <!-- Campo Contraseña -->
                    <div class="custom-form-group">
                        <label for="password" class="custom-label">Contraseña (Opcional)</label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            v-model="form.password"
                            class="custom-input"
                            autocomplete="new-password"
                        />
                        <div v-if="errors?.password" class="custom-error-message">
                            {{ errors.password }}
                        </div>
                    </div>

                    <!-- Filas combinadas -->
                    <div class="custom-form-row">
                        <!-- Campo CI -->
                        <div class="custom-form-group custom-form-column">
                            <label for="ci" class="custom-label">CI</label>
                            <input
                                type="text"
                                name="ci"
                                id="ci"
                                v-model="form.ci"
                                class="custom-input"
                                autocomplete="off"
                            />
                            <div v-if="errors?.ci" class="custom-error-message">
                                {{ errors.ci }}
                            </div>
                        </div>

                        <!-- Campo Teléfono -->
                        <div class="custom-form-group custom-form-column">
                            <label for="phone_number" class="custom-label">Teléfono</label>
                            <input
                                type="text"
                                name="phone_number"
                                id="phone_number"
                                v-model="form.phone_number"
                                class="custom-input"
                                autocomplete="off"
                            />
                            <div v-if="errors?.phone_number" class="custom-error-message">
                                {{ errors.phone_number }}
                            </div>
                        </div>
                    </div>

                    <!-- Campo Dirección -->
                    <div class="custom-form-row">
                        <div class="custom-form-group custom-form-column">
                            <label for="address" class="custom-label">Dirección</label>
                            <input
                                type="text"
                                name="address"
                                id="address"
                                v-model="form.address"
                                class="custom-input"
                                autocomplete="off"
                            />
                            <div v-if="errors?.address" class="custom-error-message">
                                {{ errors.address }}
                            </div>
                        </div>

                        <!-- Campo Ocupación -->
                        <div class="custom-form-group custom-form-column">
                            <label for="occupation" class="custom-label">Ocupación</label>
                            <input
                                type="text"
                                name="occupation"
                                id="occupation"
                                v-model="form.occupation"
                                class="custom-input"
                                autocomplete="off"
                            />
                            <div v-if="errors?.occupation" class="custom-error-message">
                                {{ errors.occupation }}
                            </div>
                        </div>
                    </div>

                    <!-- Botón Enviar -->
                    <button type="submit" class="custom-btn custom-btn-primary custom-mt-4">
                        Guardar Cambios
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
