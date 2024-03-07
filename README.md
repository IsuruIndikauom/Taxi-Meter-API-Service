Project Name: Taxi App API for Drivers

Description

Taxi App API is a comprehensive backend system designed to power modern taxi applications. Built with Laravel, it offers robust API endpoints for managing trips, user management with OTP flow, and more, providing a seamless experience for both drivers and passengers.

Features

User Authentication: Register, login, and manage user profiles.
Trips: Allow Drivers to start and end trips
Fare Calculation: Dynamic calculation of fares based on distance and time.
Tariff : Allow update tariff for fair calculation
History: Drivers can see their driving history.

Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

Prerequisites

PHP >= 8.0
Composer
MySQL or any Laravel-supported database system
Laravel >= 8.0

Installation
Clone the repository
```bash
git clone https://github.com/PixelMaverick/taxi-api-microservice.git
cd taxi-api-microservice
```

Install dependencies

```bash
composer install
```

Set up environment variables
Copy .env.example to .env and configure your database and other environment-specific settings.

```bash
cp .env.example .env
```

Generate application key

```bash
php artisan key:generate
```

Run migrations and seeders

```bash
php artisan migrate --seed
```

Start the development server

```bash
php artisan serve
```

Your API should now be running on http://localhost:8000.

API Documentation

Refer to API_DOCUMENTATION.md for detailed information about API endpoints, including request/response formats, authentication methods, and examples.

This project also can run with Laravel Sail