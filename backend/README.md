# 🌍 Urban Legends Map

> **Discover and map mysterious tales from around the world.**

A web application to map and catalog urban legends from around the world.  
This project is built with **Laravel** for the backend API and **Vue.js** for the frontend.


### 🧰 Tech Stack

**Backend:** Laravel (PHP)  
**Frontend:** Vue.js  
**Database:** MySQL  
**Containerization:** Docker & Docker Compose  
**Admin Tool:** phpMyAdmin

## ⚙️ Backend Setup (API)

## Clone the Repository:

```bash
git clone git@github.com:kapcruz/urbanlegendmaps.git urbanlegend

cd urbanlegend/backend

cp .env.example .env
```

## Start Containers

```bash
docker-compose up -d
```

## Install the dependencies:

```bash
docker exec -it urbanlegend_app composer install

docker exec -it urbanlegend_app php artisan key:generate

docker exec -it urbanlegend_app php artisan migrate
```

## Accessing phpMyAdmin

Url: http://localhost:8080

server: urbanlegend_mysql \
user: root \
senha: root



## ⚙️ Environment Variables

Both the backend and frontend require environment variables to be configured in their respective `.env` files.

---

### 🧩 Backend (/backend/.env)

The backend `.env` file contains the main API configuration:

```env
APP_PORT=8000
API_SECRET_KEY=api_secret_key_here
```
APP_PORT → Defines the port the Laravel API will run on (default: 8000)

API_SECRET_KEY → A secret key used to authenticate requests from the frontend

### 💻 Frontend (/frontend/.env)

The frontend .env file must reference the backend’s URL and secret key:

```env
VITE_API_URL=http://localhost:8000
VITE_API_TOKEN=api_secret_key_here
```
VITE_API_URL → The URL of the Laravel API (it should match the backend APP_PORT)

VITE_API_TOKEN → Must be identical to the backend’s API_SECRET_KEY

### 🔄 Example Setup

If your backend .env contains:

```env
APP_PORT=8000
API_SECRET_KEY=secret-key
```

Then your frontend .env should look like this:
```env
VITE_API_URL=http://localhost:8000
VITE_API_TOKEN=secret-key
```
This ensures the frontend can properly communicate with the API.

## API Documentation

[Documentation](./docs/api.md)
