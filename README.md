# Laravel Проект

## Установка и запуск проекта

1. Клонируйте репозиторий:
   ```bash
   git clone <repository-url>
   ```

2. Установите зависимости:
   ```bash
   composer install
   ```

3. Скопируйте .env.example в .env и настройте подключение к БД:
   ```bash
   cp .env.example .env
   ```

4. Сгенерируйте ключ приложения:
   ```bash
   php artisan key:generate
   ```

5. Сгенерируйте JWT ключ:
   ```bash
   php artisan jwt:secret
   ```

6. Запустите миграции с сидерами:
   ```bash
   php artisan migrate:fresh --seed
   ```

7. Запустите сервер:
   ```bash
   php artisan serve
   ```

## Основные команды Laravel

### Миграции
[Документация по миграциям](https://laravel.com/docs/migrations)

**Процесс работы:**
1. Создайте новую миграцию:
   ```bash
   php artisan make:migration create_table_name_table
   ```
2. Отредактируйте файл миграции в папке database/migrations.
3. Примените миграции:
   ```bash
   php artisan migrate
   ```
4. Для отката последней миграции используйте:
   ```bash
   php artisan migrate:rollback
   ```

### Сидеры
[Документация по сидерам](https://laravel.com/docs/seeding)

**Процесс работы:**
1. Создайте сидер:
   ```bash
   php artisan make:seeder UsersTableSeeder
   ```
2. Заполните метод run() в созданном файле сидера (database/seeders).
3. Запустите миграции с сидерами:
   ```bash
   php artisan migrate:fresh --seed
   ```

### JWT Аутентификация
[Документация по JWT (jwt-auth)](https://jwt-auth.readthedocs.io/en/develop/)

**Процесс работы:**
1. После установки пакета сгенерируйте секретный ключ:
   ```bash
   php artisan jwt:secret
   ```
2. Используйте стандартные методы аутентификации для работы с токенами.

### Генерация моделей, контроллеров, ресурсов
[Документация по моделям](https://laravel.com/docs/eloquent), [Контроллерам](https://laravel.com/docs/controllers), [Ресурсам](https://laravel.com/docs/eloquent-resources)

**Процесс работы:**
1. Создайте модель:
   ```bash
   php artisan make:model ModelName
   ```
2. Создайте контроллер:
   ```bash
   php artisan make:controller ControllerName
   ```
3. Создайте ресурс:
   ```bash
   php artisan make:resource ResourceName
   ```
4. Используйте сгенерированные классы для построения бизнес-логики и API.

### Очистка кэша
[Документация по кэшу](https://laravel.com/docs/cache)

**Процесс работы:**
- После изменения конфигурации, маршрутов или других параметров рекомендуется очищать соответствующий кэш:
  - Очистить кэш приложения:
    ```bash
    php artisan cache:clear
    ```
  - Очистить кэш маршрутов:
    ```bash
    php artisan route:clear
    ```
  - Очистить кэш конфигурации:
    ```bash
    php artisan config:clear
    ```

---

## API

### Аутентификация и пользователи

#### POST /api/register
Регистрация нового пользователя.

**Параметры запроса:**
- `name` (string, required)
- `email` (string, required)
- `password` (string, required)
- `password_confirmation` (string, required)

**Пример ответа:**
```json
{
  "message": "User successfully registered",
  "user": {
    "id": 1,
    "name": "User Name",
    "email": "user@example.com"
  }
}
```

#### POST /api/login
Авторизация пользователя по email и паролю. Возвращает JWT токен.

**Параметры запроса:**
- `email` (string, required)
- `password` (string, required)

**Пример запроса:**
```json
{
  "email": "user@example.com",
  "password": "password"
}
```

**Пример ответа:**
```json
{
  "access_token": "<jwt_token>",
  "token_type": "bearer",
  "expires_in": 3600
}
```

---

#### POST /api/logout
Выход пользователя. Требуется авторизация (JWT токен в заголовке Authorization).

**Заголовки:**
- `Authorization: Bearer <jwt_token>`

**Пример ответа:**
```json
{
  "message": "Successfully logged out"
}
```

---

#### GET /api/me
Получить данные текущего аутентифицированного пользователя.

**Заголовки:**
- `Authorization: Bearer <jwt_token>`

**Пример ответа:**
```json
{
  "id": 1,
  "name": "User Name",
  "email": "user@example.com",
  ...
}
```

---

#### POST /api/refresh
Обновить JWT токен. Требуется авторизация (JWT токен в заголовке Authorization).

**Заголовки:**
- `Authorization: Bearer <jwt_token>`

**Пример ответа:**
```json
{
  "access_token": "<new_jwt_token>",
  "token_type": "bearer",
  "expires_in": 3600
}
```

#### GET /api/users
Получение списка пользователей (требуется авторизация админа).

**Заголовки:**
- `Authorization: Bearer <jwt_token>`

**Параметры запроса:**
- `page` (integer, optional)
- `per_page` (integer, optional)

**Пример ответа:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "User Name",
      "email": "user@example.com",
      "role": "user"
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 50
  }
}
```

### Годы событий

#### GET /api/event-years
Получение списка годов с событиями.

**Заголовки:**
- `Authorization: Bearer <jwt_token>`

**Пример ответа:**
```json
{
  "data": [
    {
      "year": 2024,
      "events_count": 10
    },
    {
      "year": 2023,
      "events_count": 15
    }
  ]
}
```

### Страницы

#### GET /api/pages
Получение списка страниц.

**Заголовки:**
- `Authorization: Bearer <jwt_token>`

**Пример ответа:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "About Us",
      "slug": "about-us",
      "content": "Page content here"
    }
  ]
}
```

#### GET /api/pages/{id}
Получение конкретной страницы.

**Заголовки:**
- `Authorization: Bearer <jwt_token>`

**Пример ответа:**
```json
{
  "data": {
    "id": 1,
    "title": "About Us",
    "slug": "about-us",
    "content": "Page content here",
    "created_at": "2024-03-19 15:00:00",
    "updated_at": "2024-03-19 15:00:00"
  }
}
```

### Файлы

#### POST /api/files/upload
Загрузка файла.

**Заголовки:**
- `Authorization: Bearer <jwt_token>`
- `Content-Type: multipart/form-data`

**Параметры запроса:**
- `file` (file, required)
- `type` (string, required) - тип файла (event, page)
- `entity_id` (integer, required) - ID связанной сущности

**Пример ответа:**
```json
{
  "data": {
    "id": 1,
    "original_name": "document.pdf",
    "path": "uploads/2024/03/document.pdf",
    "mime_type": "application/pdf",
    "size": 1024000
  }
}
```

#### GET /api/files/{id}
Получение информации о файле.

**Заголовки:**
- `Authorization: Bearer <jwt_token>`

**Пример ответа:**
```json
{
  "data": {
    "id": 1,
    "original_name": "document.pdf",
    "path": "uploads/2024/03/document.pdf",
    "mime_type": "application/pdf",
    "size": 1024000,
    "created_at": "2024-03-19 15:00:00"
  }
}
```

#### DELETE /api/files/{id}
Удаление файла.

**Заголовки:**
- `Authorization: Bearer <jwt_token>`

**Пример ответа:**
```json
{
  "message": "File successfully deleted"
}
```

### События

#### GET /api/events
Получение списка всех событий.

**Заголовки:**
- `Authorization: Bearer <jwt_token>`

**Параметры запроса:**
- `page` (integer, optional) - номер страницы
- `per_page` (integer, optional) - количество элементов на странице

**Пример ответа:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Event Title",
      "description": "Event Description",
      "start_date": "2024-03-20 10:00:00",
      "end_date": "2024-03-20 12:00:00",
      "created_at": "2024-03-19 15:00:00",
      "updated_at": "2024-03-19 15:00:00"
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 50
  }
}
```

#### POST /api/events
Создание нового события.

**Заголовки:**
- `Authorization: Bearer <jwt_token>`

**Параметры запроса:**
- `title` (string, required) - название события
- `description` (string, required) - описание события
- `start_date` (datetime, required) - дата и время начала
- `end_date` (datetime, required) - дата и время окончания

**Пример запроса:**
```json
{
  "title": "New Event",
  "description": "Event Description",
  "start_date": "2024-03-20 10:00:00",
  "end_date": "2024-03-20 12:00:00"
}
```

#### GET /api/events/{id}
Получение информации о конкретном событии.

**Заголовки:**
- `Authorization: Bearer <jwt_token>`

**Пример ответа:**
```json
{
  "data": {
    "id": 1,
    "title": "Event Title",
    "description": "Event Description",
    "start_date": "2024-03-20 10:00:00",
    "end_date": "2024-03-20 12:00:00",
    "created_at": "2024-03-19 15:00:00",
    "updated_at": "2024-03-19 15:00:00"
  }
}
```

#### PUT /api/events/{id}
Обновление события.

**Заголовки:**
- `Authorization: Bearer <jwt_token>`

**Параметры запроса:**
- `title` (string, optional)
- `description` (string, optional)
- `start_date` (datetime, optional)
- `end_date` (datetime, optional)

#### DELETE /api/events/{id}
Удаление события.

**Заголовки:**
- `Authorization: Bearer <jwt_token>`

**Пример ответа:**
```json
{
  "message": "Event successfully deleted"
}
```
