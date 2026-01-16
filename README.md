# Tanda Blog (Backend)

The project represents a standalone backend prototype for the **Blog/Articles** module of the Tanda startup project, including <i>admin panel</i>, <i>user authentication</i>, <i>public API</i>, <i>Swagger docs</i>.

## 🎥 Demo

> A short demonstration of the app in action:

[![Demo video](https://img.youtube.com/vi/IxJ6bcleUGE/0.jpg)](https://youtu.be/IxJ6bcleUGE)

## 🛠️ Tech Stack

![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)
![Filament](https://img.shields.io/badge/Filament-v4-FFB000?logo=laravel&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-3-4E56A6?logo=livewire&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8-4479A1?logo=mysql&logoColor=white)
![Redis](https://img.shields.io/badge/Redis-7-DC382D?logo=redis&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?logo=docker&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-7-646CFF?logo=vite&logoColor=white)
![Auth](https://img.shields.io/badge/Auth-Laravel%20Auth-4CAF50)
![Swagger](https://img.shields.io/badge/OpenAPI-Swagger-85EA2D?logo=swagger&logoColor=black)

## 📁 Project Structure

```
laravel-tanda-blog/
├── backend/
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── public/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── tests/
│   ├── .env.example
│   ├── artisan
│   ├── composer.json
│   └── ...
├── docker/
│   └── php/
│       ├── Dockerfile
│       ├── entrypoint.sh
│       ├── entrypoint-scheduler.sh
│       └── opcache.ini
├── .gitignore
├── docker-compose.yaml
└── README.md
```

## 🚀 Getting Started

> Prerequisites:
> - 🐳Docker
> - 🐳Docker Compose

1. <b>Clone</b> the repository:
```code
> https://github.com/mirroxEkb14/laravel-tanda-blog.git
> cd laravel-tanda-blog/
```
2. <b>Open</b> */docker/php/entrypoint.sh* and */docker/php/entrypoint-scheduler.sh* in Visual Studio and select **LF** instead of **CRLF** to avoid Windows-Linux line-endings problem.
3. <b>Start</b> the containers:
   - ~3,2 mins in GitBash so start the containers,
   - ~60 secs in Docker Desktop to run the entrypoint scripts,
   - ~1,5 min to setup Vite dependencies.
```code
> docker compose up -d --build
```
4. <b>Navigate</b> to Admin panel on http://localhost:8080/admin after waiting containers setup (until "*Press Ctrl+C to stop the server*" message in Docker Desktop).
   - Default Super Admin credentials:  <i>superadmin@tandakids.kz</i>, <i>qwerty123456</i>.
   - Default Admin credentials: <i>admin@tandakids.kz</i>, <i>qwerty123456</i>.

## 👤 User Authentication

The project includes custom user registration and authentication with email verification, implemented using Laravel’s built-in auth mechanisms.

1. Registration:
   - <b>/register</b> endpoint,
   - email verification link after registration.
2. Email Verification:
   - <b>/verify-email</b> endpoint,
   - <b>note</b>: other routes are unavailable until email verification.
3. Login:
   - <b>/login</b> endpoint.
4. Dashboard:
   - <b>/dashboard</b> endpoint,
   - protected by auth + verified middleware.

<b>Note</b>: Roles and permissions for admin panel (<b>/admin</b>) are managed via <i>Spatie Permission</i> + <i>Filament Shield</i>.

### ✉️ Mail Configuration

It's needed to set up Gmail SMTP in .env, specifically:
- <i>MAIL_USERNAME</i> and <i>MAIL_FROM_ADDRESS</i> – one same email address,
- <i>MAIL_PASSWORD</i> – Gmail App Password (16 characters).

### 📚 User Dashboard

The project includes a User Dashboard that allows registered users to browse and read published blog content outside of the admin panel.
- Dashboard homepage (<b>/dashboard</b>):
  - displays a list of published articles.
- Article page (<b>/dashboard/articles/{slug}</b>):
  - displays the full article content.
- Author page (<b>/dashboard/authors/{user}</b>):
  - displays author details,
  - lists all published articles written by the selected author.

## 📊 API endpoints

The project uses Swagger available at <b>/docs/swagger</b> and <b>/docs/swagger.json</b>.

> To see the full API endpoints list with accurate response bodies, visit <a href="https://www.notion.so/Tanda-Blog-Backend-160350f4e44880dabf59e7782d031343?source=copy_link" target="_blank">Notion</a>.

```
GET /api/blog/articles
GET /api/blog/articles/featured
GET /api/blog/articles/{id}/related
GET /api/blog/articles/{slug}
GET /api/blog/categories
GET /api/blog/tags
```

## 📩 Contacts

[![GitHub](https://img.shields.io/badge/GitHub-mirroxEkb14-181717?logo=github&logoColor=white)](https://github.com/mirroxEkb14)
[![GitLab](https://img.shields.io/badge/GitLab-vance__7187-FCA121?logo=gitlab&logoColor=white)](https://gitlab.com/vance_7187)

## 🧭 TODO / Future Improvements

- Upload article cover images to S3 (instead of local `public` disk)
- Improve WYSIWYG editor:
   - Image upload
   - Image embedding
   - XSS sanitization
- Add article preview before publishing
- Include `related_articles[]` in article API response
- Return full CDN URLs for `cover_image`
- Improve frontend UI/UX requirements
- Restrict admin roles (`admin`, `editor`)
- Add analytics block (views_count, reading_time, related_articles already exist)
- Improve seeders (more realistic demo data)

<b>Notes</b>: liked articles, api to use controller only once, articlebyslag – send the same query, reorder for atricles
