<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { ref, watch, onMounted } from "vue";
import Paginator from "@/Components/Paginator.vue";

const props = defineProps({
    treatments: [Object, Array],
    search: String,
    success: String,
});

const goToPayment = (serviceId, amount) => {
    router.get(route('payments.pay', { service_id: serviceId, amount: amount }));
};

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
    router.visit(route("treatments.index"), {
        method: "get",
        data: { search: newSearch },
        preserveState: true,
        preserveScroll: true,
    });
});

const confirmDelete = (treatment) => {
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
            router.delete(
                route("treatments.destroy", { treatment: treatment.id }),
                {
                    onSuccess: () => {
                        Swal.fire(
                            "Eliminado",
                            "El tratamiento ha sido eliminado correctamente.",
                            "success"
                        );
                    },
                }
            );
        }
    });
};
</script>

<template>
    <AppLayout title="Tratamientos">
        <template #header>
            <h2 class="custom-page-title">Servicios de Tratamiento</h2>
        </template>

        <div class="custom-container">
            <div class="custom-flex">
                <Link
                    :href="route('treatments.create')"
                    class="custom-btn custom-btn-primary"
                >
                    Registrar Tratamiento
                </Link>
                <input
                    v-model="search"
                    type="text"
                    placeholder="Busca un tratamiento"
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
                        <th>Precio</th>
                        <th>Detalles de Comidas</th>
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
                        <td>{{ treatment.service.price }}</td>
                        <td>
                            <ul>
                                <li
                                    v-for="meal in treatment.meals"
                                    :key="meal.id"
                                >
                                    {{ meal.name }} (x{{ meal.pivot.quantity }})
                                    -
                                    {{
                                        (
                                            meal.price * meal.pivot.quantity
                                        ).toFixed(2)
                                    }}
                                    Bs
                                </li>
                            </ul>
                        </td>
                        <td class="custom-actions-cell">
                            <Link
                                :href="
                                    route('treatments.edit', {
                                        treatment: treatment.id,
                                    })
                                "
                                class="custom-btn custom-btn-edit"
                            >
                                Editar
                            </Link>
                            <button
                                @click="confirmDelete(treatment)"
                                class="custom-btn custom-btn-delete"
                            >
                                Eliminar
                            </button>
                            <button
                                @click="
                                    goToPayment(
                                        treatment.service.id,
                                        treatment.service.price
                                    )
                                "
                                class="custom-btn custom-btn-view"
                            >
                                Pagar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <Paginator :paginator="treatments" />
        </div>
    </AppLayout>
</template>
