<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";
import Swal from "sweetalert2";

const props = defineProps({
    treatment: Object, // Información del tratamiento
    observations: Array, // Observaciones ordenadas por fecha
    success: String, // Mensaje de éxito
});

const editObservation = (observationId) => {
    router.get(route("observations.edit", { observation: observationId }));
};

const deleteObservation = (observationId) => {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¡Esta acción no se puede deshacer!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("observations.destroy", { observation: observationId }), {
                onSuccess: () => {
                    Swal.fire("Eliminado", "El seguimiento ha sido eliminado correctamente.", "success");
                },
            });
        }
    });
};

const goBack = () => {
    router.visit(route("observations.index"));
};

// Mostrar mensaje de éxito al montar el componente, si existe
if (props.success) {
    Swal.fire({
        title: "Éxito",
        text: props.success,
        icon: "success",
        confirmButtonText: "OK",
    });
}
</script>

<template>
    <AppLayout title="Detalles del Seguimiento">
        <template #header>
            <h2 class="custom-page-title">
                Seguimientos de {{ treatment.service.patient.person.name }}
            </h2>
        </template>

        <div class="custom-container">
            <!-- Botón de Volver -->
            <button @click="goBack" type="button" class="custom-btn custom-btn-back custom-mb-4">
                Volver atrás
            </button>

            <!-- Información del tratamiento -->
            <div class="custom-card custom-mb-4">
                <p><strong>Origen:</strong> {{ treatment.origin }}</p>
                <p><strong>Estado:</strong> {{ treatment.status }}</p>
            </div>

            <!-- Observaciones -->
            <div class="custom-grid">
                <div
                    v-for="observation in observations"
                    :key="observation.id"
                    class="custom-card"
                >
                    <!-- Botones de Editar y Eliminar -->
                    <div class="custom-actions" style="justify-content: flex-end;">
                        <button
                            @click="editObservation(observation.id)"
                            class="custom-btn custom-btn-edit"
                        >
                            Editar
                        </button>
                        <button
                            @click="deleteObservation(observation.id)"
                            class="custom-btn custom-btn-delete"
                        >
                            Borrar
                        </button>
                    </div>

                    <!-- Detalles de la observación -->
                    <h3 class="custom-card-title">
                        Seguimiento del
                        {{ new Date(observation.date).toLocaleDateString() }}
                    </h3>
                    <p><strong>Peso:</strong> {{ observation.weight }} kg</p>
                    <p><strong>Altura:</strong> {{ observation.height }} cm</p>
                    <p><strong>Edad:</strong> {{ observation.age }} años</p>
                    <p>
                        <strong>Descripción:</strong>
                        {{ observation.description }}
                    </p>
                    <p>
                        <strong>Médico:</strong>
                        {{
                            observation.doctor && observation.doctor.user
                                ? observation.doctor.user.name
                                : "No especificado"
                        }}
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
