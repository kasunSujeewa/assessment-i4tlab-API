# Assessment-i4t-labs Local Setup Steps

This project is a Laravel 11 application

## Requirements

-   PHP 8.1 or higher
-   Composer
-   MySQL or any supported database

## Installation

1. **Clone the repository:**

    ```bash
    git clone https://github.com/kasunSujeewa/assessment-i4tlab-API.git
    cd assessment-i4tlab-API
    ```

2. **Install dependencies:**

    ```bash
    composer install
    ```

3. **Copy the `.env` file:**

**Linux Server:**
    ```bash
    cp .env.example .env
    ```
**Windows CMD:**
    ```bash
    copy .env.example .env
    ```

4. **Generate the application key:**

    ```bash
    php artisan key:generate
    ```

5. **Configure the `.env` file:**

    Update your `.env` file with the correct database and email service settings.

    **My SQL Database:**

    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

    **Postgres SQL Database:**

    ```dotenv
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=your_database_name
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

6. **Run database migrations:**

    ```bash
    php artisan migrate
    ```

    This will run the database migrations and make the tables
7. **Run Test Cases**

    ```bash
    php artisan test
    ```

    This will run the test cases

## Usage

1. **Start the development server:**

    ```bash
    php artisan serve
    ```

2. **Access the application:**

    Use main URL as this `http://localhost:8000` in your postman application.
