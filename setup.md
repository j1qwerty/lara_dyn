# Laravel Project Setup

This guide will walk you through the steps to set up and run this Laravel project.

## Quick Start

Here are all the commands you need to get the project running. Run them in order from the root of the project.

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
# Open .env and configure your database credentials
php artisan migrate
php artisan serve
npm run dev
```

---

## 1. Install Dependencies

This project uses both PHP and Node.js dependencies.

### PHP Dependencies (using Composer)

First, you need to install the PHP dependencies defined in `composer.json`.

```bash
composer install
```

**Explanation:** This command reads the `composer.json` file and installs all the required PHP libraries and packages into the `vendor` directory.

### JavaScript Dependencies (using npm)

Next, install the JavaScript dependencies defined in `package.json`.

```bash
npm install
```

**Explanation:** This command reads the `package.json` file and installs all the required JavaScript libraries and packages into the `node_modules` directory.

## 2. Configure Environment

The project needs an environment file to store configuration variables.

### Create .env file

Copy the example environment file to create your own configuration.

```bash
cp .env.example .env
```

**Explanation:** This command creates a new `.env` file, which Laravel uses to configure the application. This file is ignored by Git, so it's a safe place to store sensitive information like database credentials.

### Generate Application Key

Every Laravel project needs a unique application key.

```bash
php artisan key:generate
```

**Explanation:** This command generates a random, 32-character string and sets it as the `APP_KEY` in your `.env` file. This key is used for encryption and hashing.

## 3. Set Up the Database

The project needs a database to store data.

### Configure Database Credentials

Open the `.env` file and update the `DB_*` variables with your database credentials.

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

**Explanation:** These variables tell Laravel how to connect to your database. Make sure you have a database created with the name you specify in `DB_DATABASE`.

### Run Database Migrations

Once the database is configured, you can create the necessary tables.

```bash
php artisan migrate
```

**Explanation:** This command runs all the migration files in the `database/migrations` directory. Migrations are like version control for your database, allowing you to define and share the application's database schema.

## 4. Run the Application

Now you're ready to run the application.

### Start the Development Server

You can use the built-in Artisan command to start a development server.

```bash
php artisan serve
```

**Explanation:** This command will start a development server at `http://127.0.0.1:8000`.

### Start the Vite Development Server

For front-end development, you'll also need to run the Vite development server.

```bash
npm run dev
```

**Explanation:** This command compiles the front-end assets (CSS and JavaScript) and starts a development server that watches for changes and automatically updates the browser.

You should now be able to access the application in your browser at the address provided by `php artisan serve`.
