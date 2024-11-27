<script setup>
import { ref, reactive, computed, onMounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

// Formulario Reactivo
const props = defineProps({
    treatment: Object,
    patients: Array,
    rooms: Array,
    meals: Array,
    errors: Object,
});

const form = reactive({
    patient_id: props.treatment.service.patient.id || null,
    room_id: props.treatment.room.id || null,
    origin: props.treatment.origin || "",
    status: props.treatment.status || "Pendiente",
    meals: props.treatment.meals.map((meal) => ({
        id: meal.id,
        name: meal.name,
        price: meal.price,
        quantity: meal.pivot.quantity,
    })),
});

// Calcular el precio total basado en las comidas seleccionadas
const totalPrice = computed(() => {
    return form.meals.reduce((sum, meal) => sum + meal.price * (meal.quantity || 0), 0);
});

// Inicializar Select2
onMounted(() => {
    $(".js-patient-select")
        .select2({
            placeholder: "Seleccione un paciente",
        })
        .val(form.patient_id)
        .trigger("change")
        .on("change", function () {
            form.patient_id = $(this).val();
        });

    $(".js-room-select")
        .select2({
            placeholder: "Seleccione una habitación",
        })
        .val(form.room_id)
        .trigger("change")
        .on("change", function () {
            form.room_id = $(this).val();
        });
});

// Enviar formulario
const submit = () => {
    router.put(route("treatments.update", { treatment: props.treatment.id }), form);
};

// Ir atrás
const goBack = () => {
    router.visit(route("treatments.index"));
};

// Manejar selección de comidas
const toggleMeal = (meal) => {
    const index = form.meals.findIndex((m) => m.id === meal.id);
    if (index === -1) {
        form.meals.push({ ...meal, quantity: 1 });
    } else {
        form.meals.splice(index, 1);
    }
};

// Actualizar cantidad
const updateQuantity = (meal, quantity) => {
    const index = form.meals.findIndex((m) => m.id === meal.id);
    if (index !== -1) {
        form.meals[index].quantity = quantity > 0 ? quantity : 1;
    }
};
</script>

<template>
    <AppLayout title="Editar Tratamiento">
        <template #header>
            <h2 class="custom-page-title">Editar Tratamiento</h2>
        </template>

        <div class="custom-container">
            <div class="custom-card">
                <!-- Botón de Volver -->
                <button @click="goBack" type="button" class="custom-btn custom-btn-back custom-mb-4">
                    Volver atrás
                </button>

                <!-- Formulario -->
                <form @submit.prevent="submit">
                    <!-- Selección de Paciente -->
                    <div class="custom-form-group">
                        <label for="patient_id" class="custom-label">Paciente</label>
                        <select id="patient_id" class="js-patient-select custom-input">
                            <option value="" disabled selected>Seleccione un paciente</option>
                            <option v-for="patient in patients" :key="patient.id" :value="patient.id">
                                {{ patient.person.name }} (CI: {{ patient.person.ci }})
                            </option>
                        </select>
                        <div v-if="errors.patient_id" class="custom-error-message">
                            {{ errors.patient_id }}
                        </div>
                    </div>

                    <!-- Selección de Habitación -->
                    <div class="custom-form-group">
                        <label for="room_id" class="custom-label">Habitación</label>
                        <select id="room_id" class="js-room-select custom-input">
                            <option value="" disabled selected>Seleccione una habitación</option>
                            <option v-for="room in rooms" :key="room.id" :value="room.id">
                                {{ room.name }} (Capacidad: {{ room.capacity }}, Disponibles: {{ room.available_rooms }})
                            </option>
                        </select>
                        <div v-if="errors.room_id" class="custom-error-message">
                            {{ errors.room_id }}
                        </div>
                    </div>

                    <!-- Origen -->
                    <div class="custom-form-group">
                        <label for="origin" class="custom-label">Origen</label>
                        <input
                            type="text"
                            name="origin"
                            id="origin"
                            v-model="form.origin"
                            class="custom-input"
                        />
                        <div v-if="errors.origin" class="custom-error-message">{{ errors.origin }}</div>
                    </div>

                    <!-- Selección de Productos -->
                    <div class="custom-form-group">
                        <label class="custom-label">Productos</label>
                        <div class="custom-grid-container">
                            <div v-for="meal in meals" :key="meal.id" class="custom-product-card">
                                <input
                                    type="checkbox"
                                    :id="'meal-' + meal.id"
                                    :checked="form.meals.some((m) => m.id === meal.id)"
                                    @change="toggleMeal(meal)"
                                    class="custom-checkbox"
                                />
                                <label :for="'meal-' + meal.id" class="custom-label">
                                    <strong>{{ meal.name }}</strong><br />
                                    Ingredientes: {{ meal.ingredients }}<br />
                                    Precio: {{ meal.price }} Bs
                                </label>
                                <div
                                    v-if="form.meals.some((m) => m.id === meal.id)"
                                    class="custom-quantity-input"
                                >
                                    <label :for="'quantity-' + meal.id">Cantidad:</label>
                                    <input
                                        type="number"
                                        :id="'quantity-' + meal.id"
                                        min="1"
                                        v-model.number="form.meals.find((m) => m.id === meal.id).quantity"
                                        class="custom-input"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Precio Total -->
                    <div class="custom-form-group">
                        <p><strong>Precio Total: {{ totalPrice }} Bs</strong></p>
                    </div>

                    <!-- Botón Enviar -->
                    <button type="submit" class="custom-btn custom-btn-primary custom-mt-4">
                        Guardar Cambios
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
