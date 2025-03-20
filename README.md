<p align="center">
    <a href="#" target="_blank">
        <img src="./.doc/header-image.jpg" width="300" alt="Logo">
    </a>
</p>

# Banking API

This repository consists of a REST API for bank account management.

**IMPORTANT: This is a project used for personal study purposes!**

### Documentation

For more details about requirements and business rules, see the project [documentation](.doc/).

### Technologies used

- PHP 8.2+
- Composer 2
- Laravel 12
- MySQL 8
- PHPUnit
- PHP CS Fixer(via Laravel Pint)
- Docker (via Laravel Sail)

## Environment Setup

Follow these instructions to set up the development environment:

### Prerequisites

Make sure you have installed:

- [Docker v28+](https://docs.docker.com/engine/install/)
- [Docker Compose](https://docs.docker.com/compose/)

### Installation Steps

1. Configure the `.env` file:

   ```bash
   cp .env.example .env
   ```

2. Start Docker containers:

   ```bash
   ./sail up -d
   ```

3. Install Composer dependencies:

   ```bash
   ./sail composer install
   ```

4. Generate Laravel application key:

   ```bash
   ./sail artisan key:generate
   ```

5. Run migrations:

   ```bash
   ./sail artisan migrate
   ```

6. Restart containers:

   ```bash
   ./sail restart
   ```
---

> The API will be available at http://localhost/api.

## Code Quality

### Tests

To run automated tests, execute:

```bash
./sail test --coverage
```

### Code Standards Fixer

To check and fix syntax patterns, execute:

```bash
./sail pint
```

---
