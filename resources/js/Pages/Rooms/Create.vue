<script setup>
import { ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

const form = ref({
    name: "",
    capacity: "",
});

defineProps({ errors: Object });

const submit = () => {
    router.post(route("rooms.store"), form.value);
};

const goBack = () => {
    router.visit(route("rooms.index"));
};
</script>

<template>
    <AppLayout title="Crear Habitación">
        <template #header>
            <h2 class="custom-page-title">Crear Habitación</h2>
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
                    <!-- Campo Nombre -->
                    <div class="custom-form-group">
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

                    <!-- Campo Capacidad -->
                    <div class="custom-form-group">
                        <label for="capacity" class="custom-label">Capacidad</label>
                        <input
                            type="number"
                            name="capacity"
                            id="capacity"
                            v-model="form.capacity"
                            class="custom-input"
                        />
                        <div v-if="errors.capacity" class="custom-error-message">
                            {{ errors.capacity }}
                        </div>
                    </div>

                    <!-- Botón Enviar -->
                    <button
                        type="submit"
                        class="custom-btn custom-btn-primary custom-mt-4"
                    >
                        Crear Habitación
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
