<div align="center">

<h1>⚙️ ECAVO — Backend API</h1>
<p><strong>RESTful API for the ECAVO E-Commerce Platform</strong></p>

[![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white)](https://php.net)
[![Sanctum](https://img.shields.io/badge/Auth-Sanctum-orange)](https://laravel.com/docs/sanctum)
[![MySQL](https://img.shields.io/badge/Database-MySQL_8-4479A1?logo=mysql&logoColor=white)](https://mysql.com)
[![License](https://img.shields.io/badge/license-MIT-blue)](LICENSE)

</div>

---

## 📖 Overview

**ECAVO API** is a stateless REST API built with **Laravel 13** and **PHP 8.3** that powers the ECAVO e-commerce platform. It handles authentication, product management, cart, orders, wishlist, coupons, and a full admin panel — all secured with **Laravel Sanctum Bearer token** authentication.

> 100% stateless — no sessions, no cookies, no CSRF. Pure Bearer token auth.

---

## ✨ Features

- 🔐 **Auth** — Register, Login, Logout via Sanctum Bearer tokens
- 🛍️ **Products** — Full CRUD with bilingual names (AR/EN), images (JSON array), variants, specs, deals
- 📂 **Categories** — Hierarchical parent/child structure, bilingual
- 🛒 **Cart** — Session + auth cart, coupon support, delivery fee calculation
- 📦 **Orders** — Checkout, 11-status lifecycle, order tracking timeline
- ❤️ **Wishlist** — Toggle wishlist per user
- 🖼️ **Image Upload** — Multipart file upload to local storage, UUID filenames
- 🧑‍💼 **Admin Panel** — Dashboard stats, full CRUD on orders/products/categories, status management
- 🎟️ **Coupons** — Percent & fixed discount, usage limits, expiry, minimum order amount
- 💾 **MySQL 8** — Production-ready relational database with utf8mb4

---

## 🗂️ Project Structure

```
ecavo-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       ├── AuthController.php
│   │   │       ├── CartController.php
│   │   │       ├── CategoryController.php
│   │   │       ├── OrderController.php
│   │   │       ├── ProductController.php
│   │   │       ├── WishlistController.php
│   │   │       └── Admin/
│   │   │           ├── AdminCategoryController.php
│   │   │           ├── AdminOrderController.php
│   │   │           ├── AdminProductController.php
│   │   │           ├── DashboardController.php
│   │   │           └── ImageUploadController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Category.php
│       ├── Product.php
│       ├── ProductVariant.php
│       ├── Order.php
│       ├── OrderItem.php
│       ├── Cart.php
│       ├── CartItem.php
│       ├── Coupon.php
│       └── Wishlist.php
├── bootstrap/
│   └── app.php                 # Middleware registration (no statefulApi)
├── config/
│   ├── cors.php                # CORS (supports_credentials: false)
│   └── sanctum.php
├── database/
│   ├── migrations/             # 9 migration files
│   └── seeders/
│       └── DatabaseSeeder.php  # 1 admin + 10 categories + 20 products + 1 coupon
├── routes/
│   └── api.php                 # All API routes
└── storage/
    └── app/public/products/    # Uploaded product images
```

---

## 🚀 Getting Started

### Prerequisites

| Tool | Version |
|---|---|
| PHP | ≥ 8.3 |
| Composer | ≥ 2.x |
| MySQL | 8.0 |
| Laravel CLI | optional |

### Installation

```bash
# Clone the repository
git clone https://github.com/your-org/ecavo-api.git
cd ecavo-api

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Environment Configuration

Edit `.env` with your MySQL credentials:

```env
APP_NAME=ECAVO
APP_ENV=local
APP_URL=http://localhost:8001

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecavo_db
DB_USERNAME=ecavo
DB_PASSWORD=Ecavo@2024
```

### Database Setup

```bash
# Run migrations and seed
php artisan migrate:fresh --seed

# Create storage symlink (for image serving)
php artisan storage:link
```

### Start the Server

```bash
php artisan serve --port=8001
```

API is available at: **http://localhost:8001/api**

---

## 🌐 API Reference

### Authentication

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `POST` | `/api/auth/register` | — | Register new user |
| `POST` | `/api/auth/login` | — | Login → returns Bearer token |
| `POST` | `/api/auth/logout` | ✅ | Invalidate token |
| `GET` | `/api/auth/me` | ✅ | Get authenticated user |

### Products

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/products` | — | List products (paginated, filterable) |
| `GET` | `/api/products/{slug}` | — | Product detail with variants & reviews |

**Query params for listing:** `page`, `per_page`, `sort` (`latest`, `price_asc`, `price_desc`, `popular`, `discount`), `category`, `search`, `min_price`, `max_price`, `is_featured`

### Categories

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/categories` | — | All categories (with children) |
| `GET` | `/api/categories/{slug}` | — | Single category detail |
| `GET` | `/api/categories/{slug}/products` | — | Products by category |

### Cart

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/cart` | — | Get cart |
| `POST` | `/api/cart/add` | — | Add item `{product_id, quantity, variant_id?}` |
| `PATCH` | `/api/cart/update/{id}` | — | Update item quantity |
| `DELETE` | `/api/cart/remove/{id}` | — | Remove item |
| `POST` | `/api/cart/apply-coupon` | — | Apply coupon `{code}` |

### Orders

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `POST` | `/api/orders/checkout` | ✅ | Place order |
| `GET` | `/api/orders` | ✅ | My orders list |
| `GET` | `/api/orders/{id}` | ✅ | Order detail |
| `GET` | `/api/orders/{id}/track` | ✅ | Order tracking timeline |

### Wishlist

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/wishlist` | ✅ | Get wishlist items |
| `POST` | `/api/wishlist/toggle/{product_id}` | ✅ | Add / remove product |

---

### Admin Endpoints (Auth + Admin role required)

#### Dashboard
| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/admin/dashboard/stats` | KPI cards + recent orders |

#### Orders Management
| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/admin/orders` | List all orders (search, filter, paginate) |
| `GET` | `/api/admin/orders/{id}` | Order detail |
| `PATCH` | `/api/admin/orders/{id}/status` | Update order status |
| `PUT` | `/api/admin/orders/{id}` | Update order fields |

#### Products Management
| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/admin/products` | List products (search, paginate) |
| `POST` | `/api/admin/products` | Create product |
| `PUT` | `/api/admin/products/{id}` | Update product |
| `DELETE` | `/api/admin/products/{id}` | Soft delete product |

#### Categories Management
| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/admin/categories` | All categories |
| `POST` | `/api/admin/categories` | Create category |
| `PUT` | `/api/admin/categories/{id}` | Update category |
| `DELETE` | `/api/admin/categories/{id}` | Delete category |

#### Image Upload
| Method | Endpoint | Description |
|---|---|---|
| `POST` | `/api/admin/upload/image` | Upload image (multipart/form-data, field: `image`) |
| `DELETE` | `/api/admin/upload/image` | Delete image `{path: "products/uuid.jpg"}` |

---

## 🔐 Authentication Flow

All protected routes require a `Bearer` token in the `Authorization` header:

```http
Authorization: Bearer 2|5sQhTlnCbxaThn7atThIbBhpkevgonrr...
Accept: application/json
```

1. `POST /api/auth/login` → receive `token`
2. Store token (frontend uses Zustand + localStorage)
3. Attach to every subsequent request
4. `POST /api/auth/logout` → token invalidated server-side

**Admin routes** additionally require the user's `role === 'admin'` (checked by `AdminMiddleware`).

---

## 🗃️ Database Schema

| Table | Description |
|---|---|
| `users` | Customers and admins (role field) |
| `personal_access_tokens` | Sanctum Bearer tokens |
| `categories` | Bilingual categories (parent/child) |
| `products` | Bilingual with images JSON, specs JSON, soft deletes |
| `product_variants` | Size, color, extra price per product |
| `carts` / `cart_items` | Session-based cart |
| `orders` / `order_items` | Order lifecycle (11 statuses) |
| `wishlists` | User ↔ Product pivot |
| `coupons` | Percent/fixed discounts with limits |

---

## 📦 Order Statuses

| Status | Description |
|---|---|
| `pending` | Order placed, awaiting confirmation |
| `confirmed` | Confirmed by admin |
| `processing` | Being prepared |
| `packed` | Packaged and ready |
| `shipped` | Picked up by carrier |
| `out_for_delivery` | On its way to customer |
| `delivered` | Successfully delivered |
| `cancelled` | Cancelled |
| `refund_requested` | Customer requested refund |
| `refunded` | Refund processed |
| `failed` | Payment/delivery failed |

---

## 🌱 Default Seed Data

After running `php artisan migrate:fresh --seed`:

| Data | Count |
|---|---|
| Admin user | 1 (`admin@ecavo.com` / `Admin@1234`) |
| Categories | 10 (Appliances, Mobiles, TVs, Clothes, Shoes, Furniture, Beauty, Accessories, Houseware, Toys) |
| Products | 20 (with Unsplash CDN images, real descriptions, discounts) |
| Coupons | 1 (`ECAVO10` — 10% off orders over $50) |

---

## 🧰 Tech Stack

| Package | Version | Purpose |
|---|---|---|
| Laravel | 13 | PHP framework |
| PHP | 8.3 | Runtime |
| Laravel Sanctum | * | Stateless Bearer token auth |
| MySQL | 8.0 | Database |
| Laravel Storage | built-in | Local file storage for images |
| Laravel Tinker | 3 | REPL for debugging |

---

## 🖼️ Image Storage

Uploaded product images are stored at:
```
storage/app/public/products/{uuid}.{ext}
```

Served publicly via symlink as:
```
http://localhost:8001/storage/products/{uuid}.{ext}
```

Run `php artisan storage:link` once after cloning to create the symlink.

**Constraints:** JPEG, PNG, WebP, GIF — Max **4 MB** per file.

---

## 🛠️ Useful Commands

```bash
# Start server
php artisan serve --port=8001

# Fresh database with seed
php artisan migrate:fresh --seed

# Clear all caches
php artisan config:clear && php artisan cache:clear

# Create storage symlink
php artisan storage:link

# List all API routes
php artisan route:list --path=api

# Open Tinker REPL
php artisan tinker
```

---

## 📄 License

MIT © 2024 ECAVO. Built with ❤️ for the MENA e-commerce market.
