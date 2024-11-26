<script setup>
import { ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    room: Object,
    errors: Object,
});

const form = ref({
    new_name: props.room.name || "",
    capacity: props.room.capacity || "",
    available_rooms: props.room.available_rooms || "",
});

const submit = () => {
    router.put(route("rooms.update", { room: props.room.id }), form.value);
};

const goBack = () => {
    router.visit(route("rooms.index"));
};
</script>

<template>
    <AppLayout title="Editar Habitación">
        <template #header>
            <h2 class="custom-page-title">Editar Habitación</h2>
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

                    <!-- Campo Espacios Disponibles -->
                    <div class="custom-form-group">
                        <label for="available_rooms" class="custom-label">Espacios Disponibles</label>
                        <input
                            type="number"
                            name="available_rooms"
                            id="available_rooms"
                            v-model="form.available_rooms"
                            class="custom-input"
                        />
                        <div v-if="errors.available_rooms" class="custom-error-message">
                            {{ errors.available_rooms }}
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
