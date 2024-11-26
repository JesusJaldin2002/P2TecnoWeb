<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

// Props
defineProps({
    employee: Object, // Objeto del empleado (incluye la relación con user)
});

const goBack = () => {
    router.visit(route("employees.index"));
};
</script>

<template>
    <AppLayout title="Detalle del Empleado">
        <template #header>
            <h2 class="custom-page-title">Detalle del Empleado</h2>
        </template>

        <div class="custom-container">
            <!-- Tarjeta con información del empleado -->
            <div class="custom-card">
                <div class="custom-card-header">
                    <h3 class="custom-card-title">Información Personal</h3>
                </div>
                <div>
                    <!-- Información del usuario -->
                    <div class="custom-form-group">
                        <label class="custom-label">Nombre:</label>
                        <p class="custom-text">{{ employee.user.name }}</p>
                    </div>

                    <div class="custom-form-group">
                        <label class="custom-label">Email:</label>
                        <p class="custom-text">{{ employee.user.email }}</p>
                    </div>

                    <div class="custom-form-group">
                        <label class="custom-label">CI:</label>
                        <p class="custom-text">{{ employee.user.ci }}</p>
                    </div>

                    <div class="custom-form-group">
                        <label class="custom-label">Teléfono:</label>
                        <p class="custom-text">{{ employee.user.phone_number }}</p>
                    </div>

                    <div class="custom-form-group">
                        <label class="custom-label">Dirección:</label>
                        <p class="custom-text">{{ employee.user.address }}</p>
                    </div>
                </div>

                <div class="custom-card-header">
                    <h3 class="custom-card-title">Información Profesional</h3>
                </div>
                <div>
                    <!-- Información específica del empleado -->
                    <div class="custom-form-group">
                        <label class="custom-label">Ocupación:</label>
                        <p class="custom-text">{{ employee.occupation }}</p>
                    </div>
                </div>

                <!-- Botón para regresar -->
                <div class="custom-actions">
                    <button
                        @click="goBack"
                        class="custom-btn custom-btn-primary"
                    >
                        Volver Atrás
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
