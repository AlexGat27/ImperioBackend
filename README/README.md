
---

### API для пользователей (Users)

#### Регистрация пользователя

**Метод:** POST  
**Endpoint:** `/api/v1/users/create`  
**Описание:** Регистрирует нового пользователя в системе.

**Параметры запроса:**
- `login` (обязательный): Логин пользователя.
- Другие параметры, необходимые для создания пользователя.

**Пример запроса:**
```json
{
  "login": "username",
  "password": "password",
  "email": "user@example.com",
  "role_name": "user"
}
```

**Пример успешного ответа:**
```json
{
  "message": "Registration successful",
  "user": {
    "id": 1,
    "login": "username",
    "email": "user@example.com"
    // Другие поля пользователя
  }
}
```

**Пример ответа в случае ошибки:**
```json
{
  "message": "Registration failed",
  "errors": {
    "field_name": [
      "Error message"
    ]
  }
}
```

#### Авторизация пользователя (логин)

**Метод:** POST  
**Endpoint:** `/api/v1/login`  
**Описание:** Авторизует пользователя и выдает токен доступа.

**Параметры запроса:**
- `login` (обязательный): Логин пользователя.
- `password` (обязательный): Пароль пользователя.

**Пример запроса:**
```json
{
  "login": "username",
  "password": "password"
}
```

**Пример успешного ответа:**
```json
{
  "access_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c"
}
```

**Пример ответа в случае ошибки:**
```json
{
  "message": "Invalid username or password"
}
```

#### Обновление данных пользователя

**Метод:** POST  
**Endpoint:** `/api/v1/users/update/<id>`  
**Описание:** Обновляет данные пользователя по идентификатору.

**Параметры запроса:**
- `id` (обязательный): Идентификатор пользователя.
- Другие параметры, которые можно обновить, например, `login`, `email`, `role_name`.

**Пример запроса:**
```json
{
  "login": "new_username",
  "email": "new_email@example.com",
  "role_name": "admin"
}
```

**Пример успешного ответа:**
```json
{
  "message": "User updated successfully",
  "user": {
    "id": 1,
    "login": "new_username",
    "email": "new_email@example.com",
    "role": "admin"
    // Другие обновленные поля пользователя
  }
}
```

**Пример ответа в случае ошибки:**
```json
{
  "message": "Failed to update user",
  "errors": {
    "field_name": [
      "Error message"
    ]
  }
}
```

#### Удаление пользователя

**Метод:** POST  
**Endpoint:** `/api/v1/users/delete/<id>`  
**Описание:** Удаляет пользователя по идентификатору.

**Параметры запроса:**
- `id` (обязательный): Идентификатор пользователя, которого нужно удалить.

**Пример успешного ответа:**
```json
{
  "message": "User deleted successfully"
}
```

**Пример ответа в случае ошибки:**
```json
{
  "message": "Failed to delete user"
}
```

#### Получение ролей пользователя

**Метод:** GET  
**Endpoint:** `/api/v1/users/<id>/get-role`  
**Описание:** Возвращает список ролей пользователя по его идентификатору.

**Параметры запроса:**
- `id` (обязательный): Идентификатор пользователя, чьи роли необходимо получить.

**Пример успешного ответа:**
```json
[
  {
    "name": "user",
    "description": "Basic user role"
  },
  {
    "name": "admin",
    "description": "Administrator role"
  }
]
```

**Пример ответа в случае ошибки:**
```json
{
  "message": "No roles found for this user"
}
```

---


### API для производителей (Manufactures)

#### Получение списка производителей

**Метод:** GET  
**Конечная точка:** `/api/v1/manufactures`  
**Описание:** Получение списка всех производителей с их электронными адресами и контактными данными.

**Успешный ответ:**
```json
[
  {
    "model": {
      "id": 1,
      "name": "Производитель A",
      "description": "Описание Производителя A",
      // Другие поля производителя
    },
    "emails": [
      {
        "id": 1,
        "email": "contact1@example.com",
        // Другие поля email
      },
      {
        "id": 2,
        "email": "contact2@example.com",
        // Другие поля email
      }
    ],
    "contacts": [
      {
        "id": 1,
        "name": "Контактное лицо 1",
        "phone": "123456789",
        // Другие поля контакта
      },
      {
        "id": 2,
        "name": "Контактное лицо 2",
        "phone": "987654321",
        // Другие поля контакта
      }
    ]
  },
  {
    // Данные следующего производителя
  }
]
```

#### Просмотр информации о производителе

**Метод:** GET  
**Конечная точка:** `/api/v1/manufactures/<id>`  
**Описание:** Просмотр информации о производителе по его ID.

**Успешный ответ:**
```json
{
  "model": {
    "id": 1,
    "name": "Производитель A",
    "description": "Описание Производителя A",
    // Другие поля производителя
  },
  "emails": [
    {
      "id": 1,
      "email": "contact1@example.com",
      // Другие поля email
    },
    {
      "id": 2,
      "email": "contact2@example.com",
      // Другие поля email
    }
  ],
  "contacts": [
    {
      "id": 1,
      "name": "Контактное лицо 1",
      "phone": "123456789",
      // Другие поля контакта
    },
    {
      "id": 2,
      "name": "Контактное лицо 2",
      "phone": "987654321",
      // Другие поля контакта
    }
  ]
}
```

#### Создание производителя

**Метод:** POST  
**Конечная точка:** `/api/v1/manufactures`  
**Описание:** Создание нового производителя.

**Тело Запроса:**
```json
{
  "name": "Новый производитель",
  "description": "Описание нового производителя",
  "emails": [
    {
      "email": "contact1@example.com"
    },
    {
      "email": "contact2@example.com"
    }
  ],
  "contacts": [
    {
      "name": "Контактное лицо 1",
      "phone": "123456789"
    },
    {
      "name": "Контактное лицо 2",
      "phone": "987654321"
    }
  ]
}
```

**Успешный ответ:**
```json
{
  "id": 1,
  "name": "Новый производитель",
  "description": "Описание нового производителя",
  // Другие поля нового производителя
}
```

**Ошибка:**
```json
{
  "field_name": [
    "Сообщение об ошибке"
  ]
}
```

#### Обновление информации о производителе

**Метод:** PUT  
**Конечная точка:** `/api/v1/manufactures/<id>`  
**Описание:** Обновление информации о производителе по его ID.

**Тело Запроса:**
```json
{
  "name": "Н

овое имя производителя",
  "description": "Новое описание производителя",
  "emails": [
    {
      "id": 1,
      "email": "new_contact1@example.com"
    },
    {
      "email": "new_contact2@example.com"
    }
  ],
  "contacts": [
    {
      "id": 1,
      "name": "Новое контактное лицо 1",
      "phone": "111111111"
    },
    {
      "name": "Новое контактное лицо 2",
      "phone": "222222222"
    }
  ]
}
```

**Успешный ответ:**
```json
{
  "message": "Информация о производителе успешно обновлена",
  "model": {
    "id": 1,
    "name": "Новое имя производителя",
    "description": "Новое описание производителя",
    // Другие обновленные поля производителя
  }
}
```

**Ошибка:**
```json
{
  "field_name": [
    "Сообщение об ошибке"
  ]
}
```

#### Удаление производителя

**Метод:** DELETE  
**Конечная точка:** `/api/v1/manufactures/<id>`  
**Описание:** Удаление производителя по его ID.

**Успешный ответ:**
```json
{
  "message": "Производитель успешно удален"
}
```

**Ошибка:**
```json
{
  "message": "Не удалось удалить производителя"
}
```

### Маршруты API

```php
'GET api/v1/manufactures' => 'manufacture/index',
'GET api/v1/manufactures/<id:\d+>' => 'manufacture/view',
'POST api/v1/manufactures' => 'manufacture/create',
'PUT api/v1/manufactures/<id:\d+>' => 'manufacture/update',
'DELETE api/v1/manufactures/<id:\d+>' => 'manufacture/delete',
```
---

### API для управления ролями

#### Получение списка всех ролей с их дочерними ролями

**Метод:** GET  
**Конечная точка:** `/api/v1/roles`  
**Описание:** Получение списка всех ролей в системе с их дочерними ролями.

**Успешный ответ:**
```json
[
  {
    "role": {
      "name": "admin",
      "description": "Администраторская роль"
    },
    "children": [
      {
        "name": "moderator",
        "description": "Модераторская роль"
      }
    ]
  },
  {
    "role": {
      "name": "user",
      "description": "Базовая роль пользователя"
    },
    "children": []
  }
]
```

#### Просмотр информации о конкретной роли

**Метод:** GET  
**Конечная точка:** `/api/v1/roles/<name>`  
**Описание:** Получение информации о конкретной роли по её имени.

**Успешный ответ:**
```json
{
  "role": {
    "name": "admin",
    "description": "Администраторская роль"
  },
  "children": [
    {
      "name": "moderator",
      "description": "Модераторская роль"
    }
  ]
}
```

#### Создание новой роли

**Метод:** POST  
**Конечная точка:** `/api/v1/roles`  
**Описание:** Создание новой роли.

**Тело Запроса:**
```json
{
  "role_name": "new_role",
  "description": "Описание новой роли",
  "children": ["child_role1", "child_role2"]
}
```

**Успешный ответ:**
```json
{
  "role": {
    "name": "new_role",
    "description": "Описание новой роли"
  },
  "children": ["child_role1", "child_role2"]
}
```

#### Обновление информации о роли

**Метод:** PUT  
**Конечная точка:** `/api/v1/roles/<name>`  
**Описание:** Обновление информации о существующей роли.

**Тело Запроса:**
```json
{
  "description": "Новое описание роли",
  "children": ["new_child_role"]
}
```

**Успешный ответ:**
```json
{
  "name": "role_name",
  "description": "Новое описание роли",
  "children": ["new_child_role"]
}
```

#### Удаление роли

**Метод:** DELETE  
**Конечная точка:** `/api/v1/roles/<name>`  
**Описание:** Удаление роли по её имени.

**Успешный ответ:**
```json
{
  "message": "Роль успешно удалена"
}
```

### Маршруты API

```php
'GET api/v1/roles' => 'role/index',
'GET api/v1/roles/<name>' => 'role/view',
'POST api/v1/roles' => 'role/create',
'PUT api/v1/roles/<name>' => 'role/update',
'DELETE api/v1/roles/<name>' => 'role/delete',
```

---

### API для управления контактами производителей

#### Получение списка всех контактов

**Метод:** GET  
**Конечная точка:** `/api/v1/manufacture-contacts`  
**Описание:** Получение списка всех контактов производителей.

**Успешный ответ:**
```json
[
  {
    "id": 1,
    "name": "John Doe",
    "email": "john.doe@example.com",
    "phone": "+1234567890",
    "id_manufacture": 1
  },
  {
    "id": 2,
    "name": "Jane Smith",
    "email": "jane.smith@example.com",
    "phone": "+9876543210",
    "id_manufacture": 2
  }
]
```

#### Просмотр информации о конкретном контакте

**Метод:** GET  
**Конечная точка:** `/api/v1/manufacture-contacts/<id>`  
**Описание:** Получение информации о конкретном контакте по его ID.

**Успешный ответ:**
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "john.doe@example.com",
  "phone": "+1234567890",
  "id_manufacture": 1
}
```

#### Создание нового контакта

**Метод:** POST  
**Конечная точка:** `/api/v1/manufacture-contacts`  
**Описание:** Создание нового контакта для производителя.

**Тело Запроса:**
```json
{
  "manufacture_name": "Manufacturer Name",
  "name": "New Contact Name",
  "email": "new.contact@example.com",
  "phone": "+9876543210"
}
```

**Успешный ответ:**
```json
{
  "id": 3,
  "name": "New Contact Name",
  "email": "new.contact@example.com",
  "phone": "+9876543210",
  "id_manufacture": 3
}
```

#### Обновление информации о контакте

**Метод:** PUT  
**Конечная точка:** `/api/v1/manufacture-contacts/<id>`  
**Описание:** Обновление информации о существующем контакте производителя.

**Тело Запроса:**
```json
{
  "name": "Updated Contact Name",
  "email": "updated.contact@example.com",
  "phone": "+1234567890"
}
```

**Успешный ответ:**
```json
{
  "id": 1,
  "name": "Updated Contact Name",
  "email": "updated.contact@example.com",
  "phone": "+1234567890",
  "id_manufacture": 1
}
```

#### Удаление контакта

**Метод:** DELETE  
**Конечная точка:** `/api/v1/manufacture-contacts/<id>`  
**Описание:** Удаление контакта производителя по его ID.

**Успешный ответ:**
```json
{
  "message": "Контакт успешно удален"
}
```

### Маршруты API

```php
'GET api/v1/manufacture-contacts' => 'manufacture-contact/index',
'GET api/v1/manufacture-contacts/<id:\d+>' => 'manufacture-contact/view',
'POST api/v1/manufacture-contacts' => 'manufacture-contact/create',
'PUT api/v1/manufacture-contacts/<id:\d+>' => 'manufacture-contact/update',
'DELETE api/v1/manufacture-contacts/<id:\d+>' => 'manufacture-contact/delete',
```