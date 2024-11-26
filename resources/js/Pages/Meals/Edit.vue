<script setup>
import { ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    meal: Object,
    errors: Object,
});

const form = ref({
    new_name: props.meal.name || "",
    price: props.meal.price || "",
    ingredients: props.meal.ingredients || "",
});

const submit = () => {
    router.put(route("meals.update", { meal: props.meal.id }), form.value);
};

const goBack = () => {
    router.visit(route("meals.index"));
};
</script>

<template>
    <AppLayout title="Editar Producto">
        <template #header>
            <h2 class="custom-page-title">Editar Producto</h2>
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

                    <!-- Campo Precio -->
                    <div class="custom-form-group">
                        <label for="price" class="custom-label">Precio (Bs)</label>
                        <input
                            type="number"
                            step="0.01"
                            name="price"
                            id="price"
                            v-model="form.price"
                            class="custom-input"
                        />
                        <div v-if="errors.price" class="custom-error-message">
                            {{ errors.price }}
                        </div>
                    </div>

                    <!-- Campo Ingredientes -->
                    <div class="custom-form-group">
                        <label for="ingredients" class="custom-label">Ingredientes</label>
                        <textarea
                            name="ingredients"
                            id="ingredients"
                            v-model="form.ingredients"
                            class="custom-input"
                            rows="3"
                        ></textarea>
                        <div v-if="errors.ingredients" class="custom-error-message">
                            {{ errors.ingredients }}
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
