<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { ref, watch, onMounted } from "vue";
import Paginator from "@/Components/Paginator.vue";

// Props
const props = defineProps({
    proxies: [Object, Array],
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
    router.visit(route("proxies.index"), {
        method: "get",
        data: { search: newSearch },
        preserveState: true,
        preserveScroll: true,
    });
});

// Confirmación de eliminación
const confirmDelete = (proxy) => {
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
            router.delete(route("proxies.destroy", { proxy: proxy.id }), {
                onSuccess: () => {
                    Swal.fire(
                        "Eliminado",
                        "El apoderado ha sido eliminado correctamente.",
                        "success"
                    );
                },
            });
        }
    });
};
</script>

<template>
    <AppLayout title="Apoderados">
        <template #header>
            <h2 class="custom-page-title">Apoderados</h2>
        </template>

        <div class="custom-container">
            <div class="custom-flex">
                <Link
                    :href="route('proxies.create')"
                    class="custom-btn custom-btn-primary"
                >
                    Crear Apoderado
                </Link>
                <input
                    v-model="search"
                    type="text"
                    placeholder="Busca un apoderado"
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
                        <th>Teléfono</th>
                        <th>Ocupación</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="proxy in proxies.data" :key="proxy.id">
                        <td>{{ proxy.id }}</td>
                        <td>{{ proxy.person.name }}</td>
                        <td>{{ proxy.person.ci }}</td>
                        <td>{{ proxy.person.address }}</td>
                        <td>
                            {{
                                proxy.person.gender === "M"
                                    ? "Masculino"
                                    : "Femenino"
                            }}
                        </td>
                        <td>{{ proxy.person.birth_date }}</td>
                        <td>{{ proxy.phone_number }}</td>
                        <td>{{ proxy.occupation }}</td>
                        <td class="custom-actions-cell">
                            <Link
                                :href="route('proxies.edit', { proxy: proxy.id })"
                                class="custom-btn custom-btn-edit"
                            >
                                Editar
                            </Link>
                            <button
                                @click="confirmDelete(proxy)"
                                class="custom-btn custom-btn-delete"
                            >
                                Eliminar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <Paginator :paginator="proxies" />
        </div>
    </AppLayout>
</template>
