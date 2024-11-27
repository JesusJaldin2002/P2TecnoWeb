<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";

// Props para los datos de la hoja de atención
const props = defineProps({
    caresheet: Object,
    patient_name: String,
});

// Método para confirmar la eliminación
const deleteCaresheet = () => {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¡Esta acción no se puede deshacer!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminarlo",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("caresheets.destroy", { id: props.caresheet.id }), {
                onSuccess: () => {
                    Swal.fire(
                        "Eliminado",
                        "La hoja de atención ha sido eliminada correctamente.",
                        "success"
                    );
                    router.visit(route("caresheets.index"));
                },
            });
        }
    });
};

const goBack = () => {
    router.visit(route("caresheets.index"));
};
</script>

<template>
    <AppLayout title="Detalles de la Hoja de Atención">
        <template #header>
            <h2 class="custom-page-title">Detalles de la Hoja de Atención</h2>
        </template>

        <div class="custom-container">
            <div class="custom-card">
                <!-- Información del Paciente -->
                <div class="custom-actions">
                    <p class="custom-page-title">
                        <strong>Paciente:</strong> {{ patient_name }}
                    </p>

                    <!-- Botones de Acción -->
                    <div class="custom-flex">
                        <button
                            @click="goBack"
                            type="button"
                            class="custom-btn custom-btn-back"
                        >
                            Volver atrás
                        </button>
                        <Link
                            :href="
                                route('caresheets.edit', {
                                    id: props.caresheet.id,
                                })
                            "
                            class="custom-btn custom-btn-edit"
                        >
                            Editar
                        </Link>
                        <button
                            @click="deleteCaresheet"
                            class="custom-btn custom-btn-delete"
                        >
                            Eliminar
                        </button>
                    </div>
                </div>

                <!-- Detalles de la Hoja de Atención -->
                <table class="custom-table">
                    <tbody>
                        <tr>
                            <th>Síntomas</th>
                            <td>{{ props.caresheet.symptoms }}</td>
                        </tr>
                        <tr>
                            <th>Diagnóstico</th>
                            <td>{{ props.caresheet.diagnosis }}</td>
                        </tr>
                        <tr>
                            <th>Notas</th>
                            <td>{{ props.caresheet.notes }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
