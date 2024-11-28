<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

// Props
defineProps({
    doctor: Object,
});

const goBack = () => {
    router.visit(route('doctors.index'));
};
</script>

<template>
    <AppLayout title="Detalle del Doctor">
        <template #header>
            <h2 class="custom-page-title">Detalle del Doctor</h2>
        </template>

        <div class="custom-container">
            <!-- Tarjeta con información del doctor -->
            <div class="custom-card">
                <div class="custom-card-header">
                    <h3 class="custom-card-title">Información Personal</h3>
                </div>
                <div>
                    <!-- Información del usuario -->
                    <div class="custom-form-group">
                        <label class="custom-label">Nombre:</label>
                        <p class="custom-text">{{ doctor.user.name }}</p>
                    </div>

                    <div class="custom-form-group">
                        <label class="custom-label">Email:</label>
                        <p class="custom-text">{{ doctor.user.email }}</p>
                    </div>

                    <div class="custom-form-group">
                        <label class="custom-label">CI:</label>
                        <p class="custom-text">{{ doctor.user.ci }}</p>
                    </div>

                    <div class="custom-form-group">
                        <label class="custom-label">Teléfono:</label>
                        <p class="custom-text">{{ doctor.user.phone_number }}</p>
                    </div>

                    <div class="custom-form-group">
                        <label class="custom-label">Dirección:</label>
                        <p class="custom-text">{{ doctor.user.address }}</p>
                    </div>
                </div>

                <div class="custom-card-header">
                    <h3 class="custom-card-title">Información Profesional</h3>
                </div>
                <div>
                    <!-- Información específica del doctor -->
                    <div class="custom-form-group">
                        <label class="custom-label">Número de Seguro Social:</label>
                        <p class="custom-text">{{ doctor.number_ss }}</p>
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
