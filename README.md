# Inventory POS

## Descripción
Inventory POS es una aplicación web para gestionar inventario y ventas en un punto de venta. Permite controlar productos, stock, movimientos, ventas y flujo operativo de un negocio de forma centralizada y simple.

## Instalación rápida

Requisitos:
- PHP 8.3 o superior
- Composer
- Node.js y npm
- Base de datos compatible con Laravel (por defecto SQLite en desarrollo)

Pasos:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
php artisan serve
```

Opcional: para iniciar el entorno de desarrollo con Vite y servidor Laravel en paralelo:

```bash
npm run dev
```

La aplicación quedará disponible en http://localhost:8000 y puede configurarse adicionalmente en el archivo `.env` según el entorno deseado.

