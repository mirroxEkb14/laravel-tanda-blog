# Tanda Blog (Backend)

The project represents a standalone backend prototype for the **Blog/Articles** module of the Tanda startup project.

## 🛠️ Tech Stack

![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)
![Filament](https://img.shields.io/badge/Filament-v4-FFB000?logo=laravel&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-3-4E56A6?logo=livewire&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8-4479A1?logo=mysql&logoColor=white)
![Redis](https://img.shields.io/badge/Redis-7-DC382D?logo=redis&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?logo=docker&logoColor=white)


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
│   ├── artisan
│   ├── composer.json
│   ├── .env
│   └── ...
├── docker/
│   └── php/
│       ├── Dockerfile
│       └── entrypoint.sh
├── .gitignore
├── docker-compose.yaml
└── README.md
```

## 🚀 Getting Started

> Prerequisites:
> - 🐳Docker
> - 🐳Docker Compose

1. Clone the repository:
```code
git clone git@gitlab.com:vance_7187-group/laravel-tanda-blog.git
cd laravel-tanda-blog
```
2. Open */docker/php/entrypoint.sh* in Visual Studio and select **LF** instead of **CRLF** to avoid Windows-Linux line-endings problem.
3. Start the containers:
```code
docker compose up -d --build
```
4. Open Admin panel on http://localhost:8080/admin after waiting containers setup up for ~10 minutes (until "*Press Ctrl+C to stop the server*" message in Docker Desktop).
    -  Default admin credentials: admin@tandateam.kz, qwerty123456.
5. Test the API endpoints (returned as JSON).
    - Articles are only with 'published' status (not draft, scheduled, or published with a future 'publish_at').
```code
curl http://localhost:8080/api/blog/articles
curl http://localhost:8080/api/blog/categories
curl http://localhost:8080/api/blog/tags
```

## 📩 Contacts

[![GitHub](https://img.shields.io/badge/GitHub-mirroxEkb14-181717?logo=github&logoColor=white)](https://github.com/mirroxEkb14)
[![GitLab](https://img.shields.io/badge/GitLab-vance__7187-FCA121?logo=gitlab&logoColor=white)](https://gitlab.com/vance_7187)
