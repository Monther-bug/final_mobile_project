# Coding Practice Platform API

A robust Laravel-based backend API for a coding practice platform (similar to LeetCode/HackerRank). This API handles user authentication, exercise management, problem solving, and automated code validation.

## Features

- **Authentication**: Secure user registration and login using Laravel Sanctum.
- **Exercise Management**: Browse exercises and problems categorized by difficulty and topic.
- **Code Validation Engine**: innovative service that validates user submissions against test cases.
- **Progress Tracking**: Tracks user progress, completed exercises, and submission history.
- **Advanced Features**: Leaderboards, hints system, and submission timers.
- **API Documentation**: Integrated Swagger/OpenAPI documentation.

## Prerequisites

- PHP >= 8.2
- Composer
- Database (MySQL, SQLite, etc.)

## Installation

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd final_mobile_project
   ```

2. **Install Dependencies:**
   ```bash
   composer install
   ```

3. **Environment Setup:**
   Copy the example environment file and configure your database settings:
   ```bash
   cp .env.example .env
   ```
   *Edit `.env` and set your `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`.*

4. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

5. **Database Migration & Seeding:**
   Run the content seeder to create the Admin user and sample exercises:
   ```bash
   php artisan migrate:fresh --seed
   ```

## Running the Application

Start the local development server:
```bash
php artisan serve
```

The API will be accessible at `http://localhost:8000/api`.

### Default Credentials
A default admin user is created by the seeder:
- **Email**: `admin@example.com`
- **Password**: `password`

## API Documentation

This project uses `l5-swagger` for API documentation.
To generate/update the docs:
```bash
php artisan l5-swagger:generate
```
Access the documentation at: `http://localhost:8000/api/documentation`

## Running Tests

The project includes a comprehensive Feature Test suite covering Auth, API endpoints, and Validation logic.

Run the tests using:
```bash
php artisan test
```

## Key Endpoints

- `POST /api/register` - Create new account
- `POST /api/login` - Authenticate
- `GET /api/exercises` - List all exercises
- `GET /api/problems/{id}` - View problem details
- `POST /api/solutions` - Submit code for validation
- `GET /api/leaderboard` - View top users

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
