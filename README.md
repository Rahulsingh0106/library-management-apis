# 📚 Library Management API

This is a simple Library Management System built using **Laravel 11**.  
The project supports **user registration/login**, **role-based access (admin/user)**, and **book CRUD for admins**.

---

## 🧰 Tech Stack

- Laravel 11  
- Sanctum (API Authentication)  
- Spatie Laravel Permission (Role & Permission Management)  
- MySQL (Database)  

---

## ⚙️ Setup Instructions

1. **Clone the project**

```bash
git clone https://github.com/Rahulsingh0106/library-management-apis.git
cd library-management-apis
```

2. **Install dependencies**

```bash
composer install
```

3. **Create `.env` file**

```bash
cp .env.example .env
```

4. **Generate app key**

```bash
php artisan key:generate
```

5. **Set your database credentials in `.env`**

```
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. **Run migrations and seeders**

```bash
php artisan migrate --seed
```

7. **Serve the application**

```bash
php artisan serve
```

---

## 🔐 Authentication

Using **Laravel Sanctum** for authentication.

### 🔸 Register (POST `/api/register`)

**Payload:**

```json
{
  "name": "Rahul",
  "email": "rahul@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```

### 🔸 Login (POST `/api/login`)

**Payload:**

```json
{
  "email": "rahul@example.com",
  "password": "password"
}
```

On successful login, you'll receive an auth token. Pass this token in the header as:

```
Authorization: Bearer <token>
```

---

## 👮 Role Management

- **Admin**: Can create, update, delete books.
- **User**: Can only view book list.

To assign an admin role:

```php
php artisan tinker

$user = App\Models\User::find(1);
$user->assignRole('admin');
```

---

## 📚 Book APIs (Admin Only)

| Method | Endpoint       | Description         |
|--------|----------------|---------------------|
| POST   | /api/books     | Create a book       |
| PUT    | /api/books/{id}| Update a book       |
| DELETE | /api/books/{id}| Delete a book       |

**Payload Example (Create/Update):**

```json
{
  "title": "The Alchemist",
  "author": "Paulo Coelho"
}
```

---

## 👀 View All Books (All Users)

```bash
GET /api/books
```

In the root folder you will get two files Library Management.postman_environment.json and Library-Management-APIS.postman_collection.json 

Import this two files in your postman to test APIS