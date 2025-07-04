
# 3SC Blog API

A Laravel-based RESTful API for managing blog posts, featuring authentication, post scheduling, and Swagger documentation.

---

## Setup & Installation

### 1. Clone the repository

```bash
git clone https://github.com/chidimez/3sc-blog-api.git
cd 3sc-blog-api
```

### 2. Install dependencies

```bash
composer install
```

### 3. Setup environment variables

Copy the example environment file:

```bash
cp .env.example .env
```

Edit `.env` to configure your database connection.

Recommendation: Use SQLite for simplicity in testing:

```
DB_CONNECTION=sqlite
```

Create the SQLite database file:

```bash
touch database/database.sqlite
```

### 4. Generate application key

```bash
php artisan key:generate
```

### 5. Setup database

You have two options:

- Empty database (fresh migrations only):

```bash
php artisan migrate
```

- With seed data (creates test users and sample posts):

```bash
php artisan migrate --seed
```

### 6. Serve the application

```bash
php artisan serve
```

By default, the app runs at [http://localhost:8000](http://localhost:8000).

---

## Running Tests

Run all tests with:

```bash
php artisan test
```

To run only the PostApiTest:

```bash
php artisan test --filter=PostApiTest
```

---

## Scheduled Posts

To manually publish scheduled posts:

```bash
php artisan posts:publish-scheduled-posts
```

To run the scheduler worker:

```bash
php artisan schedule:work
```

---

## Notes & Assumptions

- **Authentication:** Uses Laravel Sanctum for token-based authentication. Protected routes require `Authorization: Bearer {token}` header.
- **Scheduling:** Posts with `scheduled_at` set in the future are unpublished until the scheduler runs and sets `published_at`.
- **Pagination:** Posts listing supports `page` and `per_page` query parameters.
- **Swagger docs:** Available at `/api/documentation` when app is running.
- **Database:** SQLite used for quick setup and testing.
- **Testing:** Feature tests cover CRUD operations.
- **Environment:** Requires PHP 8+, Composer, and Laravel 12.

---
