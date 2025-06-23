A continuación, te presento el README modificado con el nuevo nombre **Graus**:

---

# Proyecto Graus

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  <br>
  <strong>Plataforma Educativa Adaptativa con Inteligencia Artificial</strong>
</p>

## Información del Proyecto

- **Gestor de Proyectos:** [@Santiago Gómez](#)
- **Miembros del Equipo:** [@Jeimy Velandia](#), [@Juan Esteban Suarez Martínez](#), [@Milthon Moreno](#), [@Andrés Felipe Castañeda Carrillo](#)
- **Fechas:**  
  - **Inicio:** 03/02/2025  
  - **Fin:** 31/06/2025

---

## Mensaje y Visión General

### Misión
> “Transformar la enseñanza de las matemáticas con tecnología adaptativa, gamificación y apoyo integral, para que cada estudiante descubra su potencial sin barreras.”

En **Graus** creemos que la educación es la herramienta más poderosa para transformar vidas y sociedades. Nuestra misión es hacer que el aprendizaje de las matemáticas sea accesible, atractivo y significativo para todos los estudiantes, a través de tecnología de vanguardia, inteligencia artificial y metodologías pedagógicas innovadoras.

### Visión
> “Ser la plataforma que abre oportunidades a través de las matemáticas, reduciendo brechas educativas y creando una comunidad de aprendizaje inclusiva e inspiradora.”

Aspiramos a ser el referente educativo en Latinoamérica que transforme la enseñanza de las matemáticas, reduciendo la deserción escolar y empoderando a estudiantes y docentes mediante un aprendizaje personalizado, colaborativo y psicoeducativo.

---

## Descripción del Proyecto

**Graus** es una plataforma educativa innovadora que transforma el aprendizaje de las matemáticas en un proceso personalizado, accesible y motivador. Utilizando inteligencia artificial, gamificación y aprendizaje adaptativo, la plataforma ajusta la dificultad y el contenido según el nivel, progreso y estilo de aprendizaje de cada estudiante, garantizando un desarrollo continuo y efectivo de sus habilidades.

El proyecto busca enfrentar uno de los desafíos educativos más importantes en Latinoamérica: la alta tasa de deserción en matemáticas y la falta de metodologías de enseñanza dinámicas y efectivas. Con una interfaz intuitiva, rutas de aprendizaje gamificadas y herramientas interactivas, **Graus** no solo mejora el rendimiento académico, sino que también fortalece la confianza y el pensamiento crítico de los estudiantes.

### Modelo de Impacto y Escalabilidad

- **Reducción de deserción escolar:** Implementación de un sistema motivacional basado en IA que retenga el interés y compromiso de los estudiantes.
- **Acceso Equitativo:** Alianzas estratégicas con instituciones y gobiernos para ofrecer acceso gratuito a comunidades vulnerables.
- **Comunidad de Aprendizaje:** Fomento de la interacción entre estudiantes, docentes y familias para compartir conocimientos.
- **Soporte Psicoeducativo:** Estrategias para reducir la ansiedad matemática y acompañamiento emocional continuo.
- **Habilidades del Siglo XXI:** Desarrollo del pensamiento crítico, resolución de problemas y autonomía en el aprendizaje.

---

## Propuesta de Valor

Sabemos que las matemáticas han sido tradicionalmente fuente de frustración y deserción escolar. **Graus** transforma este paradigma al ofrecer una experiencia de aprendizaje hiperpersonalizada y motivadora, inspirada en modelos exitosos como Duolingo y Brilliant, pero con un enfoque académico profundo. Nuestra tecnología ajusta dinámicamente la dificultad, analiza patrones de aprendizaje y proporciona estrategias adaptadas a cada usuario, mientras que un chatbot inteligente y elementos de gamificación fomentan la constancia y el compromiso.

---

## Objetivos

### Meta Principal
Reducir la deserción escolar en matemáticas en un **30%** para 2030 mediante un sistema motivacional basado en inteligencia artificial.

### Objetivos Específicos
1. **Personalización Educativa:**  
   - Desarrollar modelos de IA (RNN/Transformers) que adapten el contenido y la dificultad en función de métricas como tiempo de respuesta, errores y estilo de aprendizaje.

2. **Gamificación Efectiva:**  
   - Implementar un sistema de recompensas, insignias, "rachas de aprendizaje" y desafíos grupales para incentivar la constancia.

3. **Acceso Equitativo:**  
   - Establecer alianzas con gobiernos y colegios para ofrecer acceso gratuito a estudiantes en comunidades vulnerables.

4. **Soporte Integral:**  
   - Integrar un chatbot tutor basado en IA que oriente y refuerce el aprendizaje sin dar respuestas directas, junto con estrategias psicoeducativas para reducir la ansiedad matemática.

5. **Análisis y Optimización:**  
   - Desarrollar herramientas de análisis de datos para medir el impacto del aprendizaje, identificar patrones de comportamiento y optimizar la experiencia de usuario.

---

## Características Destacadas

- **Aprendizaje Adaptativo:**  
  Ajuste inteligente de contenido y dificultad mediante algoritmos de IA.

- **Gamificación y Motivación:**  
  Recompensas, insignias, desafíos y tablas de clasificación inspiradas en tendencias actuales (ej. “rachas de TikTok”).

- **Chatbot Inteligente:**  
  Tutor virtual que guía al estudiante y refuerza conceptos en tiempo real.

- **Evaluación Inteligente:**  
  Monitoreo continuo de métricas (tiempo de respuesta, errores, progreso) para ajustar el plan de aprendizaje.

- **Interacción Social:**  
  Espacios colaborativos como chats, foros y retos entre estudiantes.

- **Soporte Psicoeducativo:**  
  Estrategias para disminuir la ansiedad y fortalecer la confianza en el aprendizaje.

---

## Stack Tecnológico

- **Frontend:** Laravel Blade, TailwindCSS, Livewire
- **Backend:** Laravel 11, PHP 8.3
- **Base de Datos:** MySQL/MariaDB
- **APIs:** Google Meet, WhatsApp Business, OpenAI
- **IA/ML:** TensorFlow, PyTorch, HuggingFace Transformers
- **Infraestructura:** Docker, con proyecciones de escalabilidad usando Kubernetes y AWS EC2

---

## Diagrama de Arquitectura

```plaintext
                                  +-------------------+
                                  |    Frontend       |
                                  | (Laravel Blade)   |
                                  +-------------------+
                                           ↓
                                  +-------------------+
                                  |    API REST       |
                                  | (Laravel + JWT)   |
                                  +-------------------+
                                           ↓
                                  +-------------------+
                                  |  Base de Datos    |
                                  | (MySQL/MariaDB)   |
                                  +-------------------+
                                           ↓
                                  +-------------------+
                                  |  Servicios IA     |
                                  | (Python + Docker) |
                                  +-------------------+
```

---

## Roadmap y Métricas de Éxito

### Roadmap 2025-2026

| Trimestre | Objetivo                                  | Tecnología Involucrada            |
|-----------|-------------------------------------------|-----------------------------------|
| Q1 2025   | MVP con IA básica y gamificación          | Laravel, TensorFlow Lite          |
| Q2 2025   | Integración del chatbot tutor             | OpenAI GPT-4, Websockets          |
| Q3 2025   | Soporte multilingüe                       | HuggingFace Transformers          |
| Q4 2025   | Alianzas con gobiernos locales            | API de pagos Stripe               |
| 2026      | Expansión a 5 países latinos              | Kubernetes, AWS EC2               |

### Métricas de Éxito

- **Retención:** 75% de usuarios activos después de 3 meses.
- **Mejora Académica:** Incremento del 20% en calificaciones promedio en matemáticas.
- **Interacción:** Alcanzar 100,000 interacciones mensuales en la comunidad.

---

## Entorno de Desarrollo

### Instalación y Configuración

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/castarrillo/Graus.git
   cd Graus
   ```

2. **Instalar dependencias básicas:**
   ```bash
   composer install --optimize-autoloader
   npm install && npm run build
   ```

3. **Instalar Socialite y Twilio:**
   - **Socialite:**  
     Ejecuta el siguiente comando para instalar Laravel Socialite:
     ```bash
     composer require laravel/socialite
     ```
   - **Twilio:**  
     Ejecuta el siguiente comando para instalar el SDK de Twilio:
     ```bash
     composer require twilio/sdk
     ```

4. **Configurar el entorno:**
   - Copia el archivo de ejemplo del entorno y genera la clave de la aplicación:
     ```bash
     cp .env.example .env
     php artisan key:generate
     php artisan jwt:secret
     ```
   - **Variables de Socialite:**  
     Agrega las credenciales de los proveedores de autenticación que utilizarás (por ejemplo, Google, Facebook, etc.). Ejemplo:
     ```dotenv
     GOOGLE_CLIENT_ID=tu_google_client_id
     GOOGLE_CLIENT_SECRET=tu_google_client_secret
     GOOGLE_REDIRECT_URI=https://tu-dominio.com/auth/google/callback
     ```
   - **Variables de Twilio:**  
     Agrega tus credenciales de Twilio para la integración de notificaciones SMS o llamadas:
     ```dotenv
     TWILIO_SID=tu_twilio_account_sid
     TWILIO_AUTH_TOKEN=tu_twilio_auth_token
     TWILIO_FROM=tu_twilio_phone_number
     ```

5. **Ejecutar migraciones y seeders (incluye datos demo para IA):**
   ```bash
   php artisan migrate --seed
   ```

6. **Iniciar servicios:**
   ```bash
   php artisan serve
   php artisan queue:work
   ```

7. **Iniciar con Docker (opcional):**
   Si prefieres ejecutar el proyecto mediante contenedores, asegúrate de tener
   [Docker](https://www.docker.com/) y [Docker Compose](https://docs.docker.com/compose/)
   instalados. Luego ejecuta:
   ```bash
   docker-compose up --build
   ```
   El servidor quedará disponible en `http://localhost:8000` y se iniciará
   una instancia de MySQL en el puerto `3306`.

---

## Implementaciones Destacadas

- **Módulo de Aprendizaje Adaptativo:**  
  Registro detallado de métricas por sesión (tiempo de respuesta, nivel de frustración, progreso).

- **Sistema de Recompensas:**  
  Integración con webhooks para sincronizar logros y recompensas con Discord y WhatsApp, utilizando algoritmos de gamificación.

---

## Licencia

Este proyecto está bajo la [Licencia MIT](https://opensource.org/licenses/MIT).

---

**Equipo Graus**  
Correo: contacto@graus.edu.co  
Sitio Web: [www.graus.edu.co](http://www.graus.edu.co)

*"Transformando vidas a través de las matemáticas"*

---