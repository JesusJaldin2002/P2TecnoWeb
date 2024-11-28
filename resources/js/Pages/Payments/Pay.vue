<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref } from "vue";
import Swal from "sweetalert2";
import { Link, router } from "@inertiajs/vue3";

const props = defineProps({
    service_id: [Number, String],
    amount: [Number, String],
    client: String, // Nombre del cliente
    service_type: String, // Tipo de servicio ('C', 'T', 'V')
    service_price: Number, // Precio del servicio
});

// Variables reactivas para los datos enviados desde el controlador
const serviceId = ref(Number(props.service_id));
const newAmount = ref(parseFloat(props.amount));
const clientName = ref(props.client);
const serviceType = ref(props.service_type);
const servicePrice = ref(props.service_price);

const nombreUsuario = ref("");
const ciNit = ref("");
const telefono = ref("");
const correo = ref("");
const qrImage = ref(null);
const numeroTransaccion = ref(null);
const loadingQR = ref(false);
const loadingVerify = ref(false);
const errorMessage = ref("");

const generateQR = async () => {
    loadingQR.value = true;
    errorMessage.value = "";

    try {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");
        if (!csrfToken) {
            throw new Error(
                "CSRF token no encontrado. Verifica que esté presente en el HTML."
            );
        }

        const response = await fetch(route("payments.generateQR"), {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({
                service_id: serviceId.value,
                tnMontoClienteEmpresa: newAmount.value,
                tcNombreUsuario: nombreUsuario.value,
                tnCiNit: ciNit.value,
                tnTelefono: telefono.value,
                tcCorreo: correo.value,
            }),
        });

        if (!response.ok) {
            throw new Error("Error en la solicitud: " + response.statusText);
        }

        const data = await response.json();
        qrImage.value = data.qrImage;
        numeroTransaccion.value = data.numeroTransaccion;
    } catch (error) {
        errorMessage.value =
            "Error al generar el QR. Por favor, inténtelo nuevamente.";
        console.error("Error en generateQR:", error);
    } finally {
        loadingQR.value = false;
    }
};

const verifyPayment = async () => {
    loadingVerify.value = true;
    errorMessage.value = "";

    try {
        const response = await fetch(route("payments.verify"), {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute("content"),
            },
            body: JSON.stringify({
                numeroTransaccion: numeroTransaccion.value,
                service_id: serviceId.value,
            }),
        });

        const data = await response.json();

        if (data.success) {
            Swal.fire({
                title: "¡Éxito!",
                text: data.message,
                icon: "success",
                confirmButtonText: "Ir a Pagos",
            }).then((result) => {
                if (result.isConfirmed) {
                    router.visit(route("payments.index"));
                }
            });
        } else {
            Swal.fire({
                title: "Error",
                text: data.message,
                icon: "error",
                confirmButtonText: "OK",
            });
        }
    } catch (error) {
        Swal.fire({
            title: "Error Interno",
            text: "Hubo un error al verificar el pago. Inténtelo de nuevo más tarde.",
            icon: "error",
            confirmButtonText: "OK",
        });
        console.error("Error en verifyPayment:", error);
    } finally {
        loadingVerify.value = false;
    }
};
</script>

<template>
    <AppLayout title="Realizar Pago">
        <div class="custom-container custom-card">
            <div class="custom-card-header">
                <h3 class="custom-card-title">Detalles del Pago</h3>
            </div>

            <!-- Información adicional del servicio -->
            <div class="custom-mb-4">
                <p><strong>Cliente:</strong> {{ clientName }}</p>
                <p>
                    <strong>Tipo de Servicio:</strong>
                    {{
                        serviceType === "T"
                            ? "Tratamiento"
                            : serviceType === "C"
                            ? "Consulta"
                            : "Vacuna"
                    }}
                </p>
                <p>
                    <strong>Precio del Servicio:</strong> {{ servicePrice }} Bs
                </p>
            </div>

            <div v-if="errorMessage" class="custom-error-message">
                {{ errorMessage }}
            </div>

            <div class="custom-form-group">
                <label for="nombreUsuario" class="custom-label"
                    >Nombre del usuario:</label
                >
                <input
                    id="nombreUsuario"
                    v-model="nombreUsuario"
                    class="custom-input"
                    type="text"
                />
            </div>

            <div class="custom-form-group">
                <label for="ciNit" class="custom-label">CI/NIT:</label>
                <input
                    id="ciNit"
                    v-model="ciNit"
                    class="custom-input"
                    type="text"
                />
            </div>

            <div class="custom-form-group">
                <label for="telefono" class="custom-label">Teléfono:</label>
                <input
                    id="telefono"
                    v-model="telefono"
                    class="custom-input"
                    type="text"
                />
            </div>

            <div class="custom-form-group">
                <label for="correo" class="custom-label">Correo:</label>
                <input
                    id="correo"
                    v-model="correo"
                    class="custom-input"
                    type="email"
                />
            </div>

            <div class="custom-form-group">
                <label for="new_amount" class="custom-label"
                    >Monto a pagar:</label
                >
                <input
                    id="new_amount"
                    name="new_amount"
                    v-model.number="newAmount"
                    class="custom-input"
                    type="number"
                />
            </div>

            <div class="custom-flex custom-mt-4">
                <button
                    @click="generateQR"
                    class="custom-btn custom-btn-primary"
                    :disabled="loadingQR"
                >
                    <span v-if="loadingQR">Generando QR...</span>
                    <span v-else>Generar QR</span>
                </button>
            </div>

            <div v-if="qrImage" class="custom-mt-4">
                <div v-if="qrImage" class="custom-qr-container">
                    <img
                        :src="qrImage"
                        alt="Código QR"
                        class="custom-qr-image"
                    />
                </div>
                <button
                    @click="verifyPayment"
                    class="custom-btn custom-btn-success custom-mt-4"
                    :disabled="loadingVerify"
                >
                    <span v-if="loadingVerify">Verificando...</span>
                    <span v-else>Verificar Pago</span>
                </button>
            </div>
        </div>
    </AppLayout>
</template>
