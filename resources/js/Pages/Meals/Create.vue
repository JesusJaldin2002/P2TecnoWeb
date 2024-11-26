<script setup>
import { ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

const form = ref({
    name: "",
    price: "",
    ingredients: "",
});

defineProps({ errors: Object });

const submit = () => {
    router.post(route("meals.store"), form.value);
};

const goBack = () => {
    router.visit(route("meals.index"));
};
</script>

<template>
    <AppLayout title="Crear Producto">
        <template #header>
            <h2 class="custom-page-title">Crear Producto</h2>
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
                        <label for="name" class="custom-label">Nombre</label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            v-model="form.name"
                            class="custom-input"
                        />
                        <div v-if="errors.name" class="custom-error-message">
                            {{ errors.name }}
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
                        Crear Producto
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
