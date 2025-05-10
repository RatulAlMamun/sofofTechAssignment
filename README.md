# 📝 Task Management API – Sofof Tech Assignment

A RESTful API built with Laravel for managing tasks, user authentication, task assignment, filtering, and testing.

---

## 🚀 Features

-   Authentication via Sanctum
-   Task CRUD with proper validation
-   Assign tasks to users
-   Filter and sort tasks by status, priority, and due date
-   Feature and Unit tests included
-   Built with Laravel 12

---

## 🛠 Setup Instructions

### Prerequisites

-   PHP 8.3
-   Composer
-   MySQL
-   SQLite (test)

### Clone & Install

```bash
git clone https://github.com/RatulAlMamun/sofofTechAssignment.git
cd sofofTechAssignment
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```
