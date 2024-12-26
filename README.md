# 🚀 Comic Portal Backend

<div align="center">

[![Laravel](https://img.shields.io/badge/Laravel-v10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-v8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-v8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](LICENSE)

<p>A robust RESTful API backend for the Comic Portal platform, built with Laravel and featuring comprehensive RBAC.</p>

[🎨 Frontend Repo](https://github.com/mehara-rothila/comic-mrr-front)

</div>

## ✨ Features

- 🔐 **Advanced Authentication**
  - Secure token-based auth with Laravel Sanctum
  - Role-based access control (RBAC)
  - Protected API routes
  - Comprehensive middleware system

- 📚 **Comic Management**
  - Full CRUD operations for comics
  - Category management
  - File upload handling
  - Data validation and sanitization

- 👑 **Administrative Features**
  - User management system
  - Analytics endpoints
  - Activity logging
  - Permission management
  - System monitoring

- 🛡️ **Security Features**
  - CORS configuration
  - XSS protection
  - CSRF protection
  - Rate limiting
  - Input validation

## 🚀 Quick Start

### Prerequisites

Before you begin, ensure you have:
- 📦 PHP 8.2 or higher
- 🔧 Composer
- 📊 MySQL 8.0
- 🖥️ Apache/Nginx server

### Installation

1️⃣ **Clone the repository**
```bash
git clone https://github.com/mehara-rothila/comic-mrr-back.git
cd comic-mrr-back
```

2️⃣ **Install dependencies**
```bash
composer install
```

3️⃣ **Set up environment**
```bash
cp .env.example .env
php artisan key:generate

# Configure your database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=comic_portal
DB_USERNAME=root
DB_PASSWORD=

# Configure CORS settings
SANCTUM_STATEFUL_DOMAINS=localhost:5173
SESSION_DOMAIN=localhost
CORS_ALLOWED_ORIGINS=http://localhost:5173
```

4️⃣ **Set up database**
```bash
php artisan migrate
php artisan db:seed --class=AdminUserSeeder
php artisan db:seed --class=ComicSeeder
```

5️⃣ **Start the server**
```bash
php artisan serve
```

## 🌐 API Endpoints

### Authentication
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/register` | User registration |
| POST | `/api/login` | User authentication |
| POST | `/api/logout` | Session termination |
| GET | `/api/user` | Get user info |

### Comics
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/comics` | List all comics |
| POST | `/api/comics` | Create new comic |
| GET | `/api/comics/{id}` | Get comic details |
| PUT | `/api/comics/{id}` | Update comic |
| DELETE | `/api/comics/{id}` | Delete comic |

### Admin Routes
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/admin/stats` | System statistics |
| GET | `/api/admin/users` | User management |
| GET | `/api/admin/logs` | Activity logs |

## 🛠️ Development Commands

```bash
# Clear application cache
php artisan cache:clear

# Clear route cache
php artisan route:clear

# Clear config cache
php artisan config:clear

# Run tests
php artisan test
```

## 📄 License

Distributed under the MIT License. See `LICENSE` for more information.

## 📞 Contact & Support

<div align="center">

[![GitHub](https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white)](https://github.com/mehara-rothila)

</div>

---

<div align="center">
  <p>Built with ❤️ by Mehara Rothila</p>
  
  [![Stars](https://img.shields.io/github/stars/mehara-rothila/comic-mrr-back?style=social)](https://github.com/mehara-rothila/comic-mrr-back/stargazers)
  [![Forks](https://img.shields.io/github/forks/mehara-rothila/comic-mrr-back?style=social)](https://github.com/mehara-rothila/comic-mrr-back/network/members)
</div>
