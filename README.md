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

## 📊 API endpoints

<b>Note</b>: The responses below were gotten considering the articles seeder used in the project.

- Articles:
```
GET /api/blog/articles
```
```
{
    "data": [
        {
            "id": 1,
            "title": "How to Choose the Right Private School",
            "slug": "how-to-choose-private-school",
            "excerpt": "A practical guide for parents choosing a private school.",
            "cover_image": null,
            "reading_time": 0,
            "publish_at": "2026-01-06"
        }
    ],
    "meta": {
        "page": 1,
        "per_page": 9,
        "total": 2,
        "last_page": 1
    }
}
```

- Featured Articles:
```
GET /api/blog/articles/featured
```
```
{
    "data": [
        {
            "id": 1,
            "title": "How to Choose the Right Private School",
            "slug": "how-to-choose-private-school",
            "excerpt": "A practical guide for parents choosing a private school.",
            "cover_image": null,
            "reading_time": 0,
            "publish_at": "2026-01-06"
        }
    ]
}
```

- Related Articles (ID: <i>1</i>):
```
GET /api/blog/articles/{id}/related
```
```
{
    "data": [
        {
            "id": 2,
            "title": "IELTS Preparation Tips for 2025",
            "slug": "ielts-preparation-tips-2025",
            "excerpt": "What to focus on when preparing for IELTS.",
            "cover_image": null,
            "reading_time": 1,
            "publish_at": "2026-01-14"
        },
        {
            "id": 3,
            "title": "Common Mistakes Parents Make When Choosing Schools",
            "slug": "common-parent-mistakes-schools",
            "excerpt": "Avoid these common pitfalls.",
            "cover_image": null,
            "reading_time": 1,
            "publish_at": null
        }
    ],
    "meta": {
        "page": 1,
        "per_page": 6,
        "total": 2,
        "last_page": 1
    }
}
```

<b>Note</b>: The related articles are fetched based on <i>(Category) OR (Tags)</i> matching. The response includes only published articles.

- Article by slug (Slug: <i>ielts-preparation-tips-2025</i>):
```
GET /api/blog/articles/{slug}
```
```
{
    "data": {
        "id": 2,
        "title": "IELTS Preparation Tips for 2025",
        "slug": "ielts-preparation-tips-2025",
        "excerpt": "What to focus on when preparing for IELTS.",
        "content": "<p>IELTS preparation requires a structured approach...</p>",
        "cover_image": null,
        "reading_time": 0,
        "publish_at": "2026-01-14 03:14:45",
        "views_count": 1,
        "category": {
            "id": 3,
            "name": "Exams & IELTS",
            "slug": "exams-ielts"
        },
        "tags": [
            {
                "id": 3,
                "name": "Admissions",
                "slug": "admissions",
                "pivot": {
                    "blog_article_id": 2,
                    "blog_tag_id": 3
                }
            },
            {
                "id": 4,
                "name": "Abroad",
                "slug": "abroad",
                "pivot": {
                    "blog_article_id": 2,
                    "blog_tag_id": 4
                }
            },
            {
                "id": 5,
                "name": "Preparation",
                "slug": "preparation",
                "pivot": {
                    "blog_article_id": 2,
                    "blog_tag_id": 5
                }
            }
        ],
        "author": {
            "id": 1,
            "name": "Admin"
        },
        "seo": {
            "title": null,
            "description": null,
            "keywords": null,
            "canonical_url": null
        }
    }
}
```

- Categories:
```
GET /api/blog/categories
```
```
{
    "data": [
        {
            "id": 3,
            "name": "Exams & IELTS",
            "slug": "exams-ielts"
        },
        {
            "id": 2,
            "name": "Kindergartens",
            "slug": "kindergartens"
        },
        {
            "id": 1,
            "name": "Schools",
            "slug": "schools"
        }
    ]
}
```

- Tags:
```
GET /api/blog/tags
```
```
{
    "data": [
        {
            "id": 4,
            "name": "Abroad",
            "slug": "abroad"
        },
        {
            "id": 3,
            "name": "Admissions",
            "slug": "admissions"
        },
        {
            "id": 1,
            "name": "Education",
            "slug": "education"
        },
        {
            "id": 2,
            "name": "Parents",
            "slug": "parents"
        },
        {
            "id": 5,
            "name": "Preparation",
            "slug": "preparation"
        },
        {
            "id": 6,
            "name": "Tips",
            "slug": "tips"
        }
    ]
}
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

Notes:
<br>
liked articles, api to use controller only once, articlebyslag – send the same query, reorder for atricles

'http://localhost:8080/api/blog/articles/1/related' not working, 'http://localhost:8080/api/blog/articles?category=schools' returns only with ID 1 (but ID 3 also Schools),
