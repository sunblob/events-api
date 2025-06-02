# Laravel Project

## Installation and Project Setup

1. Clone the repository:
   ```bash
   git clone <repository-url>
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Copy .env.example to .env and configure database connection:
   ```bash
   cp .env.example .env
   ```

4. Generate application key:
   ```bash
   php artisan key:generate
   ```

5. Generate JWT key:
   ```bash
   php artisan jwt:secret
   ```

6. Run migrations with seeders:
   ```bash
   php artisan migrate:fresh --seed
   ```

7. Start the server:
   ```bash
   php artisan serve
   ```

## Main Laravel Commands

### Migrations
[Migrations Documentation](https://laravel.com/docs/migrations)

**Workflow:**
1. Create a new migration:
   ```bash
   php artisan make:migration create_table_name_table
   ```
2. Edit the migration file in the database/migrations folder.
3. Apply migrations:
   ```bash
   php artisan migrate
   ```
4. To rollback the last migration:
   ```bash
   php artisan migrate:rollback
   ```

### Seeders
[Seeding Documentation](https://laravel.com/docs/seeding)

**Workflow:**
1. Create a seeder:
   ```bash
   php artisan make:seeder UsersTableSeeder
   ```
2. Fill in the run() method in the created seeder file (database/seeders).
3. Run migrations with seeders:
   ```bash
   php artisan migrate:fresh --seed
   ```

### JWT Authentication
[JWT Documentation (jwt-auth)](https://jwt-auth.readthedocs.io/en/develop/)

**Workflow:**
1. After installing the package, generate a secret key:
   ```bash
   php artisan jwt:secret
   ```
2. Use standard authentication methods for token handling.

### Generating Models, Controllers, Resources
[Models Documentation](https://laravel.com/docs/eloquent), [Controllers](https://laravel.com/docs/controllers), [Resources](https://laravel.com/docs/eloquent-resources)

**Workflow:**
1. Create a model:
   ```bash
   php artisan make:model ModelName
   ```
2. Create a controller:
   ```bash
   php artisan make:controller ControllerName
   ```
3. Create a resource:
   ```bash
   php artisan make:resource ResourceName
   ```
4. Use generated classes to build business logic and API.

### Cache Clearing
[Cache Documentation](https://laravel.com/docs/cache)

**Workflow:**
- After changing configuration, routes, or other parameters, it's recommended to clear the corresponding cache:
  - Clear application cache:
    ```bash
    php artisan cache:clear
    ```
  - Clear route cache:
    ```bash
    php artisan route:clear
    ```
  - Clear config cache:
    ```bash
    php artisan config:clear
    ```

---

## API

### Authentication and Users

#### POST /api/register
Register a new user.

**Request Parameters:**
- `name` (string, required)
- `email` (string, required)
- `password` (string, required)
- `password_confirmation` (string, required)

**Example Response:**
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
Authenticate user with email and password. Returns JWT token.

**Request Parameters:**
- `email` (string, required)
- `password` (string, required)

**Example Request:**
```json
{
  "email": "user@example.com",
  "password": "password"
}
```

**Example Response:**
```json
{
  "access_token": "<jwt_token>",
  "token_type": "bearer",
  "expires_in": 3600
}
```

---

#### POST /api/logout
Logout user. Requires authentication (JWT token in Authorization header).

**Headers:**
- `Authorization: Bearer <jwt_token>`

**Example Response:**
```json
{
  "message": "Successfully logged out"
}
```

---

#### GET /api/me
Get current authenticated user data.

**Headers:**
- `Authorization: Bearer <jwt_token>`

**Example Response:**
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
Refresh JWT token. Requires authentication (JWT token in Authorization header).

**Headers:**
- `Authorization: Bearer <jwt_token>`

**Example Response:**
```json
{
  "access_token": "<new_jwt_token>",
  "token_type": "bearer",
  "expires_in": 3600
}
```

#### GET /api/users
Get list of users (requires admin authentication).

**Headers:**
- `Authorization: Bearer <jwt_token>`

**Request Parameters:**
- `page` (integer, optional)
- `per_page` (integer, optional)

**Example Response:**
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

### Event Years

#### GET /api/event-years
Get list of years with events.

**Headers:**
- `Authorization: Bearer <jwt_token>`

**Example Response:**
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

### Pages

#### GET /api/pages
Get list of pages.

**Headers:**
- `Authorization: Bearer <jwt_token>`

**Example Response:**
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
Get specific page.

**Headers:**
- `Authorization: Bearer <jwt_token>`

**Example Response:**
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

### Files

#### POST /api/files/upload
Upload a file.

**Headers:**
- `Authorization: Bearer <jwt_token>`
- `Content-Type: multipart/form-data`

**Request Parameters:**
- `file` (file, required)
- `type` (string, required) - file type (event, page)
- `entity_id` (integer, required) - related entity ID

**Example Response:**
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
Get file information.

**Headers:**
- `Authorization: Bearer <jwt_token>`

**Example Response:**
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
Delete file.

**Headers:**
- `Authorization: Bearer <jwt_token>`

**Example Response:**
```json
{
  "message": "File successfully deleted"
}
```

### Events

#### GET /api/events
Get list of all events.

**Headers:**
- `Authorization: Bearer <jwt_token>`

**Request Parameters:**
- `page` (integer, optional) - page number
- `per_page` (integer, optional) - items per page

**Example Response:**
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
Create new event.

**Headers:**
- `Authorization: Bearer <jwt_token>`

**Request Parameters:**
- `title` (string, required) - event title
- `description` (string, required) - event description
- `start_date` (datetime, required) - start date and time
- `end_date` (datetime, required) - end date and time

**Example Request:**
```json
{
  "title": "New Event",
  "description": "Event Description",
  "start_date": "2024-03-20 10:00:00",
  "end_date": "2024-03-20 12:00:00"
}
```

#### GET /api/events/{id}
Get specific event information.

**Headers:**
- `Authorization: Bearer <jwt_token>`

**Example Response:**
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
Update event.

**Headers:**
- `Authorization: Bearer <jwt_token>`

**Request Parameters:**
- `title` (string, optional)
- `description` (string, optional)
- `start_date` (datetime, optional)
- `end_date` (datetime, optional)

#### DELETE /api/events/{id}
Delete event.

**Headers:**
- `Authorization: Bearer <jwt_token>`

**Example Response:**
```json
{
  "message": "Event successfully deleted"
}
```
