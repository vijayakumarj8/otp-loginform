# 🔐 OTP Login System (Laravel API Project)

This is a Laravel-based OTP Authentication system where users can register/login using OTP verification. It also includes password reset and secure authentication features using Laravel's built-in security tools.

---

## 🚀 Features

- User Registration with OTP  
- OTP-based Login System  
- Password Reset functionality  
- Secure password hashing  
- API-based authentication (Postman ready)  
- Laravel Sanctum support (if enabled)  
- Clean MVC architecture  

---

## 🛠️ Tech Stack

- Laravel (PHP Framework)  
- MySQL Database  
- PHP  
- Laravel Sanctum (for API authentication)  
- Postman (for API testing)  
- HTML, Blade Templates  

---

## 📁 Project Structure

app/
├── Http/Controllers
├── Models
database/
├── migrations
resources/
├── views
routes/
├── api.php
├── web.php

---

## ⚙️ Installation Guide

### 1. Clone the repository
git clone https://github.com/vijayakumarj8/otp-loginform.git

### 2. Move into project folder
cd otp-loginform

### 3. Install dependencies
composer install

### 4. Copy `.env` file
cp .env.example .env

### 5. Set up database in `.env`
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=

### 6. Run migrations
php artisan migrate

### 7. Generate application key
php artisan key:generate

### 8. Start server
php artisan serve

---

## 📬 API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /api/send-otp | Send OTP to email |
| POST | /api/login-otp | Login using OTP |
| POST | /api/reset-password | Reset password |
| POST | /api/logout | Logout user |

---

## 🧪 Testing (Postman)

Base URL:
http://127.0.0.1:8000

Headers:
Accept: application/json

---

## 🔐 Security Features

- Passwords are hashed using Hash::make()  
- OTP-based authentication  
- API token protection (if Sanctum used)  

---

## 📸 Project Purpose

This project is built for learning and demonstrating:
- Laravel API development  
- Authentication systems  
- OTP verification flow  
- Secure backend development  

---

## 👨‍💻 Author

Vijayakumar J  

---

## ⭐ Future Improvements

- Add React frontend  
- Add JWT authentication  
- Email/SMS OTP integration  
- Role-based access (Admin/User)
