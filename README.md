# Employee Scheduling and Reservation Management

This project is a Laravel 10-based web application designed to manage employee work schedules and reservations. The system allows users to define work schedules, handle employee availability, and generate reservations based on available slots within the first week of each month. Additionally, it includes features for generating reports and sending schedule emails. The project comes pre-loaded with information regarding availabilities and reservations through seeders.

This project is part of a technical assessment for the Backend Developer position.

## Table of Contents

- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Database Setup](#database-setup)
- [Seeding the Database](#seeding-the-database)
- [SMTP Configuration](#smtp-configuration)
- [Usage](#usage)
- [Testing](#testing)
- [Production](#production)
- [Contributing](#contributing)

## Prerequisites

Before you begin, ensure you have the following installed:

- **PHP 8.1 or higher**
- **Composer**
- **MySQL** or any other database supported by Laravel

## Installation

1. **Clone the repository:**

    ```bash
    git clone https://github.com/carlosfdz/reservation-management.git
    cd reservation-management
    ```

2. **Install dependencies:**

    ```bash
    composer install
    ```

3. **Copy the `.env.example` file to `.env` and configure your environment:**

    ```bash
    cp .env.example .env
    ```

4. **Generate an application key:**

    ```bash
    php artisan key:generate
    ```

## Database Setup

1. **Create a database:**

    Create a new database in MySQL or your preferred database system.

2. **Update `.env` with your database details:**

    ```plaintext
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=database_name
    DB_USERNAME=root
    DB_PASSWORD=your_password
    ```

3. **Run migrations:**

    ```bash
    php artisan migrate
    ```

## Seeding the Database

This project includes seeders to initialize the database with initial data:

1. **Seed the database:**

    ```bash
    php artisan db:seed
    ```

    The seeders will:
    - Insert 3 sample employees into the database.
    - Generate work schedules for each employee for the months of September and October 2024.
    - Create exactly 8 reservations per employee in the first week of each month, based on available work slots.

## SMTP Configuration

To enable email functionalities, configure the SMTP settings in your `.env` file:

1. **Update `.env` with your SMTP details:**

    ```plaintext
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.your-email-provider.com
    MAIL_PORT=587
    MAIL_USERNAME=your-email@example.com
    MAIL_PASSWORD=your-email-password
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS=your-email@example.com
    MAIL_FROM_NAME="${APP_NAME}"
    ```

    Replace `smtp.your-email-provider.com`, `your-email@example.com`, and `your-email-password` with your SMTP provider's details.

## Usage

1. **Start the application:**

    ```bash
    php artisan serve
    ```

2. **Access the application:**

    Open your web browser and go to the URL provided by Laravel after running the `php artisan serve` command (usually [http://localhost:8000](http://localhost:8000)).

## Testing

To verify the system functionalities, you can access the following routes:

- **Check Employee Availability in a Time Interval:**  
  [http://localhost:8000/reservations/employees/availability/interval](http://localhost:8000/reservations/employees/availability/interval)

- **Check Employee Availability on Specific Date and Time:**  
  [http://localhost:8000/reservations/employees/availability/check](http://localhost:8000/reservations/employees/availability/check)

- **Generate Downloadable Excel Report:**  
  [http://localhost:8000/reservations/employees/export-schedule](http://localhost:8000/reservations/employees/export-schedule)

- **Send Daily Schedule Email:**  
  [http://localhost:8000/reservations/employees/send-schedule](http://localhost:8000/reservations/employees/send-schedule)

These APIs are documented and can be accessed directly via the provided URLs.

Make sure to replace `localhost:8000` with the actual URL if your server is running on a different port or host.

## Production

The application is also deployed in a production environment on DigitalOcean. You can access it via the following IP address:

- **Production URL:** [http://143.198.185.132](http://143.198.185.132)

## Contributing

Contributions are welcome! If you'd like to contribute, please fork the repository, create a new branch for your feature or bugfix, and submit a pull request.

Contributions are welcome! If you'd like to contribute, please fork the repository, create a new branch for your feature or bugfix, and submit a pull request. Ensure that your code adheres to the project's coding standards.
