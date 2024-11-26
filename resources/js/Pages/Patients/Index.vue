<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { ref, watch, onMounted } from "vue";
import Paginator from "@/Components/Paginator.vue";

// Props
const props = defineProps({
    patients: [Object, Array],
    search: String,
    success: String,
});

// Referencia para búsqueda
const search = ref(props.search || "");

// Mostrar mensaje de éxito
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

// Función para búsqueda
watch(search, (newSearch) => {
    router.visit(route("patients.index"), {
        method: "get",
        data: { search: newSearch },
        preserveState: true,
        preserveScroll: true,
    });
});

// Confirmación de eliminación
const confirmDelete = (patient) => {
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
            router.delete(route("patients.destroy", { patient: patient.id }), {
                onSuccess: () => {
                    Swal.fire(
                        "Eliminado",
                        "El paciente ha sido eliminado correctamente.",
                        "success"
                    );
                },
            });
        }
    });
};
</script>

<template>
    <AppLayout title="Pacientes">
        <template #header>
            <h2 class="custom-page-title">Pacientes</h2>
        </template>

        <div class="custom-container">
            <div class="custom-flex">
                <Link
                    :href="route('patients.create')"
                    class="custom-btn custom-btn-primary"
                >
                    Crear Paciente
                </Link>
                <input
                    v-model="search"
                    type="text"
                    placeholder="Busca un paciente"
                    class="custom-input-search"
                />
            </div>

            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>CI</th>
                        <th>Dirección</th>
                        <th>Género</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Tipo de Sangre</th>
                        <th>Apoderado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="patient in patients.data" :key="patient.id">
                        <td>{{ patient.id }}</td>
                        <td>{{ patient.person.name }}</td>
                        <td>{{ patient.person.ci }}</td>
                        <td>{{ patient.person.address }}</td>
                        <td>
                            {{
                                patient.person.gender === "M"
                                    ? "Masculino"
                                    : "Femenino"
                            }}
                        </td>
                        <td>{{ patient.person.birth_date }}</td>
                        <td>{{ patient.blood_type }}{{ patient.rh_factor }}</td>
                        <td>
                            <!-- Mostramos el nombre del apoderado -->
                            {{ patient.proxy?.person.name || "Sin Apoderado" }}
                        </td>
                        <td class="custom-actions-cell">
                            <Link
                                :href="
                                    route('patients.edit', {
                                        patient: patient.id,
                                    })
                                "
                                class="custom-btn custom-btn-edit"
                            >
                                Editar
                            </Link>
                            <button
                                @click="confirmDelete(patient)"
                                class="custom-btn custom-btn-delete"
                            >
                                Eliminar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <Paginator :paginator="patients" />
        </div>
    </AppLayout>
</template>
