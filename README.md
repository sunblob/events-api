# Laravel Проект

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
3. Запустите сидеры:
   ```bash
   php artisan db:seed
   ```
4. Для запуска миграций и сидеров вместе:
   ```bash
   php artisan migrate --seed
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

### Аутентификация

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
