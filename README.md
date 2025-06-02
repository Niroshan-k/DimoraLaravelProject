# Dimora

A modern Laravel-based real estate platform with remote SQL/NoSQL database support and cloud storage for images.

---

## Features

- User authentication and profile management
- Create, edit, and browse property advertisements
- Image uploads via Firebase Storage
- Remote SQL (MySQL/PostgreSQL via Clever Cloud) or NoSQL (MongoDB Atlas) database support
- Responsive UI with Tailwind CSS
- Secure, scalable, and cloud-ready

---

## Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/dimora.git
cd dimora
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Configuration

Copy the example environment file and edit it:

```bash
cp .env.example .env
```

**Edit your `.env` file** and set the following keys:

- **APP_KEY**: Generate with `php artisan key:generate`
- **DB_CONNECTION**: `mysql`, `pgsql`, or `mongodb`
- **DB_HOST**, **DB_PORT**, **DB_DATABASE**, **DB_USERNAME**, **DB_PASSWORD**:  
  Get these from your Clever Cloud SQL instance or MongoDB Atlas dashboard.
- **FIREBASE_*:**  
  Add your Firebase project keys for image uploads.

Example for MySQL (Clever Cloud):

```
DB_CONNECTION=mysql
DB_HOST=your-clevercloud-host
DB_PORT=3306
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password
```

Example for MongoDB Atlas:

```
DB_CONNECTION=mongodb
DB_HOST=your-mongodb-host
DB_PORT=27017
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password
```

---

### 4. Build Assets

```bash
npm run build
```

---

### 5. Run Migrations

```bash
php artisan migrate
```

---

### 6. Start the Application

```bash
php artisan serve
```

---

## Deployment

- Deploy your code to your preferred platform (e.g., Render, Docker, etc.).
- Make sure your `.env` file is set up with the correct remote database and Firebase credentials.

---

## Notes

- **No local database required!**  
  All data is stored in your remote SQL/NoSQL database (Clever Cloud or MongoDB Atlas).
- **Image uploads** are handled via Firebase Storage.  
  Users do not need to configure local storage.

---

## License

MIT
