<script setup>
import { ref, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Bar, Pie } from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    ArcElement,
} from "chart.js";

// Registrar los elementos de Chart.js
ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    ArcElement
);

// Obtener las páginas, pagos y consultas del controlador
const { pages, payments, consults } = usePage().props;

// Preparar los datos para el gráfico de visitas (barra)
const chartData = ref({
    labels: [],
    datasets: [
        {
            label: "Visitas",
            data: [],
            backgroundColor: [],
            borderColor: "rgba(75, 192, 192, 1)",
            borderWidth: 1,
        },
    ],
});

// Preparar los datos para el gráfico de pagos por paciente (pastel)
const paymentChartData = ref({
    labels: [],
    datasets: [
        {
            data: [],
            backgroundColor: [
                "rgba(75, 192, 192, 0.5)",
                "rgba(153, 102, 255, 0.5)",
                "rgba(255, 159, 64, 0.5)",
            ],
        },
    ],
});

// Preparar los datos para el gráfico de consultas por doctor (barras)
const consultChartData = ref({
    labels: [],
    datasets: [
        {
            label: "Consultas",
            data: [],
            backgroundColor: "rgba(54, 162, 235, 0.5)",
            borderColor: "rgba(54, 162, 235, 1)",
            borderWidth: 1,
        },
    ],
});

// Función para generar colores aleatorios para barras de visitas
const generateRandomColors = (count) => {
    const colors = [];
    for (let i = 0; i < count; i++) {
        const r = Math.floor(Math.random() * 256);
        const g = Math.floor(Math.random() * 256);
        const b = Math.floor(Math.random() * 256);
        colors.push(`rgba(${r}, ${g}, ${b}, 0.5)`);
    }
    return colors;
};

// Cargar los datos de las páginas, pagos y consultas
onMounted(() => {
    const pageLabels = [];
    const pageData = [];

    pages.forEach((page) => {
        if (page.visitas > 0) {
            pageLabels.push(page.nombre);
            pageData.push(page.visitas);
        }
    });

    chartData.value.labels = pageLabels;
    chartData.value.datasets[0].data = pageData;
    chartData.value.datasets[0].backgroundColor = generateRandomColors(
        pageData.length
    );

    // Gráfico de pagos por paciente
    const paymentLabels = payments.map(
        (payment) => payment.patient_name // Usamos `patient_name` que ya es el nombre correcto
    );
    const paymentData = payments.map((payment) => payment.total);

    paymentChartData.value.labels = paymentLabels;
    paymentChartData.value.datasets[0].data = paymentData;

    // Gráfico de consultas por doctor (Barras)
    const consultLabels = consults.map(
        (consult) => consult.doctor_name // Cambiado de doctor_id a doctor_name
    );
    const consultData = consults.map((consult) => consult.total);

    consultChartData.value.labels = consultLabels;
    consultChartData.value.datasets[0].data = consultData;
});
</script>

<template>
    <AppLayout title="Estadísticas de Visitas">
        <template #header>
            <h2 class="custom-page-title">Estadísticas</h2>
        </template>

        <div class="custom-container">
            <!-- Gráfico de Visitas -->
            <div class="custom-card">
                <h3 class="chart-title" style="color: black;">Gráfico de Visitas por Página</h3>
                <div v-if="chartData.labels.length > 0">
                    <div class="chart-container">
                        <Bar :data="chartData" :options="chartOptions" />
                    </div>
                </div>
                <div v-else>
                    <p>No hay páginas con visitas registradas.</p>
                </div>
            </div>

            <!-- Gráfico de pagos por paciente (Pastel) -->
            <div class="custom-card">
                <h3 class="chart-title" style="color: black;">Gráfico de Pagos por Paciente</h3>
                <div v-if="paymentChartData.labels.length > 0">
                    <div class="chart-container-small">
                        <Pie :data="paymentChartData" />
                    </div>
                </div>
                <div v-else>
                    <p style="color: black;">No hay pagos registrados.</p>
                </div>
            </div>

            <!-- Gráfico de consultas por doctor (Barras) -->
            <div class="custom-card">
                <h3 class="chart-title" style="color: black;">Gráfico de Consultas por Doctor</h3>
                <div v-if="consultChartData.labels.length > 0">
                    <div class="chart-container">
                        <Bar :data="consultChartData" :options="chartOptions" />
                    </div>
                </div>
                <div v-else>
                    <p style="color: black;">No hay consultas registradas.</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { Bar as BarChart, Pie as PieChart } from "vue-chartjs";
export { BarChart, PieChart };
</script>

<style scoped>
.custom-page-title {
    font-size: 1.5rem;
    font-weight: bold;
}

.custom-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
}

.custom-card {
    background-color: #ffffff;
    
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    width: 70%;
    margin-top: 20px;
}

.chart-container {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
}

.chart-title {
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 10px;
}

.chart-container-small {
    background-color: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
    width: 80%; /* Reducido para el tamaño más pequeño */
}
</style>
