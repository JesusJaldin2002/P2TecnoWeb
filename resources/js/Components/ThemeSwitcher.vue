<script setup>
import { ref, onMounted } from "vue";

// Crear una referencia para el tema actual
const currentTheme = ref("default");

// Función para cambiar el tema
const changeTheme = (theme) => {
    console.log(`Cambiando tema a: ${theme}`); // Verificamos qué tema se está cambiando
    currentTheme.value = theme;

    // Guardar el tema en localStorage
    localStorage.setItem("theme", theme);
    console.log(`Tema guardado en localStorage: ${theme}`); // Verificamos que se guarde correctamente

    // Eliminar las clases de tema anteriores
    const htmlElement = document.documentElement;
    htmlElement.classList.remove("theme-children", "theme-youth", "theme-adults");
    console.log('Clases de tema eliminadas.');

    // Agregar la clase del nuevo tema
    if (theme !== "default") {
        htmlElement.classList.add(`theme-${theme}`);
        console.log(`Clase theme-${theme} agregada.`); // Verificamos que se agregue la clase correctamente
    } else {
        console.log('Clase "default" no tiene clase adicional.');
    }
};

// Cargar el tema guardado desde localStorage al montar el componente
onMounted(() => {
    const savedTheme = localStorage.getItem("theme");
    console.log(`Tema guardado en localStorage al montar: ${savedTheme}`); // Verificamos qué tema se obtiene de localStorage

    if (savedTheme) {
        currentTheme.value = savedTheme;
        console.log(`Tema recuperado: ${savedTheme}`); // Verificamos que el tema se recupera correctamente
        changeTheme(savedTheme);
    } else {
        console.log('No se encontró tema guardado, usando "default"'); // Verificamos si no hay tema guardado
        changeTheme("default"); // Si no hay tema guardado, usar el predeterminado
    }
});
</script>

<template>
    <div>
        <!-- Botones para cambiar el tema -->
        <div class="theme-switcher">
            <button @click="changeTheme('default')" class="custom-btn custom-btn-back">Default</button>
            <button @click="changeTheme('children')" class="custom-btn custom-btn-primary">Niños</button>
            <button @click="changeTheme('youth')" class="custom-btn custom-btn-success">Jóvenes</button>
            <button @click="changeTheme('adults')" class="custom-btn custom-btn-secondary">Adultos</button>
        </div>
    </div>
</template>

<style>
/* Estilos para el selector de temas */
.theme-switcher {
    display: flex;
    gap: 1rem;
    margin: 1rem 0;
}
</style>
