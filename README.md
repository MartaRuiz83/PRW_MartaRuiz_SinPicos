# SinPicos

Un gestor de comidas para diabéticos que permite registrar niveles de glucosa, planificar comidas con control de macros, y recibir recomendaciones nutricionales personalizadas.

## Tabla de contenidos

- [Instalación](#instalación)  
- [Uso](#uso)  
- [Arquitectura](#arquitectura)  
- [Base de datos](#base-de-datos)   
- [Tecnologías](#tecnologías)  
- [Autor](#autor)  

---

## Instalación

1. Clona este repositorio  
  
    git clone https://github.com/tu-usuario/SinPicos.git
    cd SinPicos

2. Intala dependencias de PHP y Node

    composer install
    npm install

3. Configura el entorno

    cp .env.example .env
    php artisan key:generate

4. Prepara la base de datos

    - Crea la base de datos sinpicos en MySQL
    - Ejecuta migraciones: 
        php artisan migrate

5. Arranca el servidor de desarrollo

    php artisan serve

## Uso

    Accede en tu navegador a: http://127.0.0.1:8000

## Arquitectura

    SinPicos/
    ├─ app/                 # Lógica de negocio (Laravel)
    ├─ bootstrap/           # Arranque de la aplicación
    ├─ config/              # Configuraciones generales
    ├─ database/            # Migraciones y seeders
    ├─ public/              # Punto de entrada web
    ├─ resources/           # Vistas Blade, assets y componentes
    ├─ routes/              # Definición de rutas
    ├─ tests/               # PHPUnit y Cypress
    ├─ composer.json        # Dependencias PHP
    ├─ package.json         # Dependencias JS (Vite, Tailwind…)
    └─ README.md            # Documentación principal

## Base de datos

El modelo relacional principal incluye:

    Usuarios: id, nombre, email (UNIQUE), password, rol, config_nutricional, timestamps

    Glucosa: id, usuario_id (FK), fecha, hora, momento, nivel_glucosa, timestamps

    Registro_Comidas: id, usuario_id (FK), fecha, momento, notas, timestamps

    Registro_Comida_Alimento (pivote): registro_id (FK), alimento_id (FK), cantidad (g)

    Alimentos: id, nombre (UNIQUE), carbohidratos, proteínas, grasas, calorías, timestamps

    Recomendaciones: id, titulo, descripcion, timestamps

    Tips: id, usuario_id (FK), recomendacion_id (FK), leido, timestamps

    Estadísticas: id, usuario_id (FK), tipo, valor, periodo, created_at

## Tecnologías

    Frontend: HTML5, CSS3, Bootstrap 5.3.0, Chart.js

    Backend: PHP 8, Laravel 9, JetStream

    Base de datos: MySQL

## Autor

    Marta Ruiz Pérez

    Email: martarp83@gmail.com  

    GitHub: MartaRuiz83




