# 🚦 TransiControl / RutaX – Colombia Movilidad OS

**Sistema de monitoreo de incidentes de tránsito en tiempo real** con mapa interactivo, heatmap de congestión, cálculo de rutas seguras, información climática, calidad del aire, PWA instalable y panel de control para gestión de vehículos y usuarios.

---

## 📌 Tabla de Contenidos

- [Características Principales](#-características-principales)
- [Tecnologías Utilizadas](#-tecnologías-utilizadas)
- [Arquitectura y Estructura de Directorios](#-arquitectura-y-estructura-de-directorios)
- [APIs Externas Integradas](#-apis-externas-integradas)
- [Instalación y Configuración](#-instalación-y-configuración)
- [Progressive Web App (PWA)](#-progressive-web-app-pwa)
- [Uso del Mapa Principal (RutaX)](#-uso-del-mapa-principal-rutax)
- [Dashboard y Autenticación](#-dashboard-y-autenticación)
- [Base de Datos](#-base-de-datos)
- [Créditos](#-créditos)
- [Licencia](#-licencia)

---

## 🚀 Características Principales

- **Mapa interactivo** con Leaflet, marcadores clusterizados y heatmap (rojo para congestión).
- **Incidentes en tiempo real** mediante Server‑Sent Events (SSE).
- **Ruta segura** que evita zonas de alta densidad de incidentes (ruta en verde).
- **Geocodificación inversa** (al hacer clic en el mapa muestra la dirección).
- **Clima actual** (temperatura, humedad, viento, presión, visibilidad, índice UV).
- **Pronóstico a 3 días** (temperaturas máximas/mínimas y probabilidad de lluvia).
- **Calidad del aire** (índice US‑AQI, PM2.5, PM10, monóxido de carbono).
- **Información de amanecer / atardecer**.
- **Panel lateral** con estadísticas dinámicas, filtros y gráficos de barras.
- **PWA completa** – instalable, funciona offline parcialmente.
- **Dashboard** con autenticación (sesiones PHP + Google OAuth2) y CRUD de vehículos, seguimientos y reportes.
- **Diseño responsivo** y modo oscuro.

---

## 🛠️ Tecnologías Utilizadas

| Área | Tecnologías |
|------|--------------|
| **Frontend** | HTML5, CSS3 (Tailwind + estilos personalizados), JavaScript (ES6+), Leaflet, Leaflet.markercluster, Leaflet.heat, Font Awesome |
| **Backend** | PHP 7.4+, PDO MySQL, Server‑Sent Events (SSE) |
| **APIs externas** | Open‑Meteo (clima, calidad del aire), Sunrise‑Sunset.org, OpenRouteService, Nominatim (OSM) |
| **PWA** | Manifest JSON, Service Worker (caché básico) |
| **Autenticación** | Sesiones PHP, Google OAuth2 (GSI) |
| **Base de datos** | MySQL (solo para gestión de usuarios, vehículos y seguimientos) |
| **Herramientas** | Composer (opcional), Git |

---

## 📁 Arquitectura y Estructura de Directorios
```
├── app/
│   ├── controllers/          # Controladores de la aplicación
│   │   ├── AuthController.php
│   │   ├── HomeController.php
│   │   ├── DashboardController.php
│   ├── models/               # Modelos de datos
│   │   ├── User.php
│   │   ├── IncidentModel.php
│   ├── views/                # Vistas PHP
│   │   ├── home/
│   │   │   ├── index.php
│   │   │   ├── Abogados.php
│   │   │   ├── Agentes.php
│   │   │   └── Infracciones.php
│   │   └── auth/
│   │       ├── login.php
│   │       └── google.php
├── api/                      
│   ├── transito.php
│   ├── seguimientos.php
│   └── reportes.php
├── config/
│   ├── config.php           # Configuración general
│   └── database.php         # Singleton PDO
├── public/
│   ├── assets/
│   │   ├── css/
│   │   │   ├── login.css
│   │   │   └── home.css
│   │   ├── js/
│   │   │   ├── login.js
│   │   │   └──  home.js
│   │   │  
│   │   └── bootstrap/       # Bootstrap 5 local
│   └── images/
├── .htaccess
├── index.php                 # Front controller
└── information_schema.sql          # Script de base de datos
```

> **Nota:** El mapa principal se encuentra en `app/views/home/index.php`. El dashboard y la autenticación se gestionan mediante el `index.php` raíz.

---

## 🌐 APIs Externas Integradas (todas en tiempo real)

| API | Propósito | Clave |
|-----|-----------|-------|
| **Open‑Meteo Current Weather** | Clima actual (temp., humedad, viento, presión, visibilidad, UV) | No requiere |
| **Open‑Meteo Daily Forecast** | Pronóstico 3 días (temp. máx/mín, lluvia) | No requiere |
| **Open‑Meteo Air Quality** | Calidad del aire (AQI, PM2.5, PM10, CO) | No requiere |
| **Sunrise‑Sunset.org** | Horas de salida y puesta del sol | No requiere |
| **OpenRouteService** | Cálculo de rutas seguras (driving‑car) | Demo key incluida |
| **Nominatim (OSM)** | Geocodificación inversa (dirección a partir de coordenadas) | No requiere |
| **Botpress Webchat** | Chatbot de asistencia (simulado) | Script externo |

---

## ⚙️ Instalación y Configuración

### Requisitos previos
- PHP 7.4 o superior (con extensiones `pdo_mysql`, `json`, `session`)
- MySQL 5.7 o superior
- Servidor web (Apache recomendado, con `.htaccess` habilitado)
- Composer (opcional, para dependencias)

### Pasos

1. **Clonar el repositorio** dentro del directorio `htdocs` (XAMPP) o la raíz de tu servidor.
   ```bash
   git clone https://github.com/tu-usuario/transicontrol.git