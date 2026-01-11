# Tanda Blog (Backend)

The project represents a standalone backend prototype for the **Blog/Articles** module of the Tanda startup project.

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
> git clone git@gitlab.com:vance_7187-group/laravel-tanda-blog.git
> cd laravel-tanda-blog
```
2. Open */docker/php/entrypoint.sh* and */docker/php/entrypoint-scheduler.sh* in Visual Studio and select **LF** instead of **CRLF** to avoid Windows-Linux line-endings problem.
3. Start the containers:
```code
> docker compose up -d --build
```
4. Open Admin panel on http://localhost:8080/admin after waiting containers setup up for ~1 minute (until "*Press Ctrl+C to stop the server*" message in Docker Desktop).
    -  Default admin credentials: admin@tandateam.kz, qwerty123456.

## 📊 Test API endpoints

- Test a list of articles with pagination, with filters: category, tag, type, search:
```code
GET http://localhost:8080/api/blog/articles?page=1&per_page=12
GET http://localhost:8080/api/blog/articles?category=schools
GET http://localhost:8080/api/blog/articles?tag=education
GET http://localhost:8080/api/blog/articles?type=school
GET http://localhost:8080/api/blog/articles?search=private
```

- Test an article by slug, gworing 'views_count':
```code
GET http://localhost:8080/api/blog/articles/how-to-choose-private-school
```

- Test categories, tags and related articles:
```code
GET http://localhost:8080/api/blog/categories
GET http://localhost:8080/api/blog/tags
GET http://localhost:8080/api/blog/articles/2/related
```

- Test errors:
```code
GET http://localhost:8080/api/blog/articles/no-such-slug
```

## 📩 Contacts

[![GitHub](https://img.shields.io/badge/GitHub-mirroxEkb14-181717?logo=github&logoColor=white)](https://github.com/mirroxEkb14)
[![GitLab](https://img.shields.io/badge/GitLab-vance__7187-FCA121?logo=gitlab&logoColor=white)](https://gitlab.com/vance_7187)

## TODO

сделать:
- загружать обложки cover_upload через S3 (не локальный public disk),
- реализовать WYSIWYG для вставки/загрузки изображений (сейчас только базовый, RichEditor),
- добавить preview статьи,
- добавить related_articles[] внутри ответа статьи,
- реализовать cover_image как полноценный URL (https://cdn/...),
- реализовать UI/UX требования для фронта,
- ограничить роли по admin/editor,
- добавить XSS защиту (sanitization) (WYSIWYG),
- реализовать блок Аналитики (фронт) (показатели views_count, related_articles, reading_time уже есть),
- реализовать Future features
- update seeders: more complicated

? api to use controller only once, articlebyslag – send the same query, reorder for atricles, command model -m,c
