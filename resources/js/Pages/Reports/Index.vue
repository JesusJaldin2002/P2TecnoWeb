<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

const goToReport = (routeName) => {
    router.visit(route(routeName));
};
</script>

<template>
    <AppLayout title="Reportes">
        <template #header>
            <h2 class="custom-page-title">Gestión de Reportes</h2>
        </template>

        <div class="custom-container">
            <div class="custom-grid">
                <!-- Reporte de Pagos -->
                <div class="custom-card" @click="goToReport('reports.payment')" style="cursor: pointer;">
                    <h3 class="custom-card-title">Reporte de Pagos de un Cliente</h3>
                    <p>
                        Genera un reporte detallado de todos los pagos realizados
                        por un cliente en un período de tiempo.
                    </p>
                </div>

                <!-- Reporte de Consultas -->
                <div class="custom-card" @click="goToReport('reports.consult')" style="cursor: pointer;">
                    <h3 class="custom-card-title">Reporte de Consultas de un Doctor</h3>
                    <p>
                        Consulta las visitas realizadas a un doctor específico
                        junto con sus detalles.
                    </p>
                </div>

                <!-- Reporte de Tratamientos -->
                <div class="custom-card" @click="goToReport('reports.treatment')" style="cursor: pointer;">
                    <h3 class="custom-card-title">
                        Reporte de Tratamientos y Seguimientos
                    </h3>
                    <p>
                        Genera un reporte completo de los tratamientos y
                        observaciones asociadas a un paciente.
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
