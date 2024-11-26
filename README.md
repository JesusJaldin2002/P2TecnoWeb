
# 🏥 Sistema de Gestión para Centro Nutricional

Bienvenido al repositorio del **Sistema de Gestión para Centro Nutricional**. Este proyecto tiene como objetivo optimizar las operaciones de un centro nutricional, gestionando datos de pacientes, servicios, tratamientos y pagos. El sistema está siendo desarrollado con las siguientes tecnologías:

- 🌐 **Laravel** para la gestión del backend.
- 📊 **Inertia** para aplicaciones de una sola página con renderizado desde el servidor.
- 🎨 **Vue 3** como framework moderno y reactivo para el frontend.

---

## 🛠️ Funcionalidades

- **Gestión de Usuarios**: Roles para doctores, empleados y pacientes con autenticación segura.
- **Seguimiento de Servicios**: Manejo de consultas, tratamientos y vacunas.
- **Asignación de Habitaciones y Dietas**: Gestión de habitaciones y planes alimenticios para pacientes internados.
- **Integración de Pagos**: Uso de **PagoFacil** para soluciones de pago con código QR.
- **Observaciones Dinámicas**: Registro y seguimiento de métricas del paciente como peso, altura y edad.
- **Base de Datos Relacional**: Esquema SQL estructurado para una gestión eficiente de todas las entidades.

---

## 🚀 Tecnologías Utilizadas

| Stack         | Descripción                                         |
|---------------|-----------------------------------------------------|
| Laravel       | Framework backend para API y lógica.                |
| Inertia       | Middleware para aplicaciones SPA.                   |
| Vue 3         | Framework reactivo para interacción con el usuario. |
| PostgreSQL    | Base de datos relacional para almacenamiento.       |
| PagoFacil     | Integración de pagos con QR.                        |

---

## 🗂️ Esquema de Base de Datos

Se diseñó un diagrama ERD (Entity-Relationship Diagram) detallado que abarca:
- **Personas**: Pacientes, Apoderados y Empleados.
- **Servicios**: Tratamientos, Vacunas y Consultas.
- **Habitaciones y Dietas**: Asignación de recursos para pacientes internados.
- **Pagos**: Manejo de procesos de pago seguros.

---

## 📋 Guía de Instalación

### Prerrequisitos
- PHP >= 8.1
- Node.js >= 16
- Composer
- Base de datos PostgreSQL

### Pasos

1. Clona el repositorio:
   ```bash
   git clone https://github.com/JesusJaldin2002/P2TecnoWeb.git
   cd sistema-centro-nutricional
   ```

2. Instala las dependencias:
   ```bash
   composer install
   npm install
   ```

3. Configura el archivo `.env`:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Actualiza las credenciales de la base de datos y las claves de la API de PagoFacil en el archivo `.env`.

4. Ejecuta las migraciones y carga los datos iniciales:
   ```bash
   php artisan migrate:fresh --seed
   ```

5. Inicia el servidor de desarrollo:
   ```bash
   php artisan serve
   npm run dev
   ```
