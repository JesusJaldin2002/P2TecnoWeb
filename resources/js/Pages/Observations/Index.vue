<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { ref, watch, onMounted } from "vue";
import Paginator from "@/Components/Paginator.vue";

const props = defineProps({
    treatments: [Object, Array],
    search: String,
    success: String, // Mensaje de éxito
});

const search = ref(props.search || "");

onMounted(() => {
    if (props.success) {
        Swal.fire({
            title: "Éxito",
            text: props.success,
            icon: "success",
            confirmButtonText: "OK",
        });
    }
});

watch(search, (newSearch) => {
    router.visit(route("observations.index"), {
        method: "get",
        data: { search: newSearch },
        preserveState: true,
        preserveScroll: true,
    });
});

const goToTracking = (treatment) => {
    router.get(route('observations.show', { treatment: treatment }));
};

const goToRegisterTracking = (treatmentId) => {
    router.get(route('observations.create') + `?treatment_id=${treatmentId}`);
};
</script>

<template>
    <AppLayout title="Tratamientos Activos">
        <template #header>
            <h2 class="custom-page-title">Tratamientos Activos</h2>
        </template>

        <div class="custom-container">
            <div class="custom-flex">
                <input
                    v-model="search"
                    type="text"
                    placeholder="Buscar tratamiento"
                    class="custom-input-search"
                />
            </div>

            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paciente</th>
                        <th>Empleado</th>
                        <th>Habitación</th>
                        <th>Origen</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="treatment in treatments.data"
                        :key="treatment.id"
                    >
                        <td>{{ treatment.id }}</td>
                        <td>{{ treatment.service.patient.person.name }}</td>
                        <td>{{ treatment.service.employee.user.name }}</td>
                        <td>
                            {{
                                treatment.room?.name ||
                                "Sin habitación asignada"
                            }}
                        </td>
                        <td>{{ treatment.origin }}</td>
                        <td>{{ treatment.status }}</td>
                        <td class="custom-actions-cell">
                            <button
                                @click="goToTracking(treatment)"
                                class="custom-btn custom-btn-view"
                            >
                                Ver Seguimiento
                            </button>
                            <button
                                @click="goToRegisterTracking(treatment.id)"
                                class="custom-btn custom-btn-primary"
                            >
                                Registrar Seguimiento
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <Paginator :paginator="treatments" />
        </div>
    </AppLayout>
</template>
