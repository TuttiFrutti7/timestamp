# Timestamp – Laravel Web App

**Timestamp** is a social media-style web application built using [**Laravel**](https://laravel.com/). What sets Timestamp apart is its **daily time limit feature** (15–60 minutes) to encourage mindful usage and reduce app addiction.

## Features

### User Roles

- **Regular users** – Can post, comment, and join communities.
- **Community Admins** – Manage their own communities.
- **Global Admins** – Platform-wide management capabilities.

### Functionality

- Time-limited daily usage
- Media-rich posts
- Comments & threaded discussions *(upcoming)*
- Community creation and management
- Profile pages & user customization

---

## Project Setup

### Prerequisites

Ensure you have the following installed:

- [**PHP (>=8.1)**](https://www.php.net/)
- [**Composer**](https://getcomposer.org/)
- [**MySQL**](https://www.mysql.com/) or compatible database
- [**Node.js**](https://nodejs.org/) and [**npm**](https://www.npmjs.com/)

### Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/yourusername/timestamp.git
   cd timestam

2. Install backend dependencies:

   ```bash
   composer install

3. Install frontend dependencies:

   ```bash
   npm install

4. Copy the **.env** file and set up environment variables:

   ```bash
   cp .env.example .env

   Update the .env file with your database, app URL, and other credentials.

5. Generate application key:

   ```bash
   php artisan key:generate

6. Run database migrations:

   ```bash
   php artisan migrate

7. (Optional) Seed database with demo data:

   ```bash
   php artisan db:seed

8. Build frontend assets:

   ```bash
   npm run build

9. Create storage link for media uploads:

   ```bash
   php artisan storage:link

## Running the app

Local Development

   ```bash
   php artisan serve
   ```

Visit http://localhost:8000 in your browser.

## Troubleshooting & Debugging

 - Ensure storage/ and bootstrap/cache/ directories are writable by the web server:

   ```bash
   sudo chown -R www-data:www-data storage bootstrap/cache
   sudo chmod -R 775 storage bootstrap/cache
   ```

 - Log files: storage/logs/laravel.log

