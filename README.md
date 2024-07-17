# API Endpoints Documentation

# UserController API

## Authentication

### POST `/api/v1/login`

Authenticates a user based on username and password.

**Request:**
```json
{
  "login": "string",
  "password": "string"
}
```

**Response (success):**
```json
{
  "access_token": "string"
}
```

**Response (failure):**
```json
{
  "message": "string"
}
```

### GET `/api/v1/logout`

Logs out the current user (invalidates the token).

## User Management

### POST `/api/v1/users/create`

Registers a new user.

**Request:**
```json
{
  "username": "string",
  "password": "string",
  "role_name": "string" // optional
}
```

**Response (success):**
```json
{
  "message": "Registration successful",
  "user": {
    "id": "integer",
    "username": "string",
    "created_at": "string"
  }
}
```

**Response (failure):**
```json
{
  "message": "Registration failed",
  "errors": {
    "field_name": ["error message"]
  }
}
```

### POST `/api/v1/users/update/<id>`

Updates an existing user's information.

**Request:**
```json
{
  "username": "string",
  "role_name": "string" // optional
}
```

**Response (success):**
```json
{
  "message": "User updated successfully",
  "user": {
    "id": "integer",
    "username": "string",
    "created_at": "string"
  }
}
```

**Response (failure):**
```json
{
  "message": "Failed to update user",
  "errors": {
    "field_name": ["error message"]
  }
}
```

### POST `/api/v1/users/delete/<id>`

Deletes a user by ID.

**Response (success):**
```json
{
  "message": "User deleted successfully"
}
```

**Response (failure):**
```json
{
  "message": "Failed to delete user"
}
```


## Manufacture Endpoints

### GET `api/v1/manufactures`

- **Описание**: Получить список всех мануфактур.
- **Возвращает**: Список всех мануфактур, включая их контакты и email.

### GET `api/v1/manufactures/<id:\d+>`

- **Описание**: Получить информацию о конкретной мануфактуре по её ID.
- **Параметры**:
   - `id` (integer, path): ID мануфактуры.
- **Возвращает**: Информацию о мануфактуре, включая её контакты и email.

### POST `api/v1/manufactures`

- **Описание**: Создать новую мануфактуру.
- **Тело запроса**:
   - `name` (string, required): Название мануфактуры.
   - `website` (string, optional): Вебсайт мануфактуры.
   - `id_region` (integer, optional): ID региона.
   - `id_city` (integer, optional): ID города.
   - `address_loading` (string, optional): Адрес загрузки.
   - `note` (string, optional): Заметка.
   - `create_your_project` (boolean, optional): Флаг создания проекта.
   - `is_work` (boolean, optional): Флаг активности.
   - `emails` (array of strings, optional): Список email.
- **Возвращает**: Созданную мануфактуру.

### PUT `api/v1/manufactures/<id:\d+>`

- **Описание**: Обновить информацию о существующей мануфактуре.
- **Параметры**:
   - `id` (integer, path): ID мануфактуры.
- **Тело запроса**:
   - Поля, которые можно обновить: `name`, `website`, `id_region`, `id_city`, `address_loading`, `note`, `create_your_project`, `is_work`, `emails`.
- **Возвращает**: Обновленную мануфактуру.

### DELETE `api/v1/manufactures/<id:\d+>`

- **Описание**: Деактивировать мануфактуру.
- **Параметры**:
   - `id` (integer, path): ID мануфактуры.
- **Возвращает**: Статус операции (успешно или нет).

## Manufacture Contacts Endpoints

### POST `api/v1/manufacture-contacts`

- **Описание**: Создать новый контакт для мануфактуры.
- **Тело запроса**:
   - `id_manufacture` (integer, required): ID мануфактуры.
   - `telephone` (string, required): Телефон контакта.
- **Возвращает**: Созданный контакт.

### GET `api/v1/manufacture-contacts`

- **Описание**: Получить список всех контактов для мануфактур.
- **Возвращает**: Список всех контактов.

### PUT `api/v1/manufacture-contacts/<id:\d+>`

- **Описание**: Обновить информацию о существующем контакте мануфактуры.
- **Параметры**:
   - `id` (integer, path): ID контакта.
- **Тело запроса**:
   - Поля, которые можно обновить: `telephone`.
- **Возвращает**: Обновленный контакт.

### DELETE `api/v1/manufacture-contacts/<id:\d+>`

- **Описание**: Удалить контакт мануфактуры.
- **Параметры**:
   - `id` (integer, path): ID контакта.
- **Возвращает**: Статус операции (успешно или нет).