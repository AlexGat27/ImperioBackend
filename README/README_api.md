# API Documentation

## Endpoint: `manufactures/search`

Этот эндпоинт позволяет искать производителей по различным критериям, таким как категория, район, регион и город. Также возвращаются адреса электронной почты и контакты для найденных производителей.

### URL

GET /api/v1/manufactures/search

markdown
Копировать код

### Method

`GET`

### Roles

- admin
- snab
- user

### Query Parameters

- `category` (string, optional): Название категории для поиска в продукции.
- `district` (string, optional): Название района для поиска в продукции.
- `region` (string, optional): Название региона для поиска в продукции.
- `city` (string, optional): Название города для поиска в продукции.

### Request Example

GET /api/manufactures/search?category=Electronics&district=North&region=California&city=San Francisco

### Response

#### Success Response

- **Code**: 200 OK
- **Content**:

```json
[
    {
        "manufacture_name": "Manufacturer 1",
        "website": "http://manufacturer1.com",
        "category": "Electronics",
        "region": "California",
        "city": "San Francisco",
        "district": "North",
        "emails": ["info@manufacturer1.com", "support@manufacturer1.com"],
        "contacts": [
            {
                "telephone": "+1-800-123-4567",
                "name_personal": "John Doe",
                "note": "Primary contact"
            },
            {
                "telephone": "+1-800-765-4321",
                "name_personal": "Jane Smith",
                "note": "Secondary contact"
            }
        ]
    },
    {
        "manufacture_name": "Manufacturer 2",
        "website": "http://manufacturer2.com",
        "category": "Electronics",
        "region": "California",
        "city": "San Francisco",
        "district": "North",
        "emails": ["contact@manufacturer2.com"],
        "contacts": []
    }
]
```
Error Response
Code: 400 Bad Request
Content:
```json
{
    "error": "Invalid parameters provided."
}
```
### Description
Этот эндпоинт выполняет поиск производителей в базе данных с учетом различных фильтров:

- category: Фильтрует производителей по названию категории.
- district: Фильтрует производителей по названию района.
- region: Фильтрует производителей по названию региона.
- city: Фильтрует производителей по названию города.

Для каждого найденного производителя возвращаются:

- Название производителя (manufacture_name)
- Вебсайт (website)
- Название категории (category)
- Название региона (region)
- Название города (city)
- Название района (district)
- Список адресов электронной почты (emails)
- Список контактов (contacts), включающий телефон, имя и заметку

Errors
Invalid parameters: Если параметры запроса некорректны или отсутствуют, возвращается ошибка с кодом 400.

## Endpoint: `products/search`

Этот эндпоинт позволяет выполнять поиск продукции и категорий на основе переданных параметров и чекбоксов. Поиск может быть выполнен по имени продукции, категории или избранному.

### URL

GET /api/v1/products/search

markdown
Копировать код

### Method

`GET`

### Roles

- admin
- manager
- snab
- user

### Query Parameters

- `product_name` (string, optional): Название продукции для поиска.
- `category_name` (string, optional): Название категории для поиска.
- `checkbox_product` (integer, optional): Флаг (0 или 1) для указания, следует ли искать по названию продукции.
- `checkbox_category` (integer, optional): Флаг (0 или 1) для указания, следует ли искать по названию категории.

### Request Example

GET /api/products/search?product_name=Smartphone&category_name=Electronics&checkbox_product=1&checkbox_category=0

### Response

#### Success Response

- **Code**: 200 OK
- **Content**:

```json
[
    {
        "id": 1,
        "name": "Smartphone X",
        "length": 150,
        "width": 75,
        "height": 8,
        "weight": 180,
        "category_name": "Electronics"
    },
    {
        "id": 2,
        "name": "Smartphone Y",
        "length": 155,
        "width": 77,
        "height": 8.5,
        "weight": 185,
        "category_name": "Electronics"
    }
]
```
Error Response
Code: 400 Bad Request
Content:
```json
{
    "error": "Invalid parameters provided."
}
```
Description
Этот эндпоинт выполняет поиск продукции и категорий на основе переданных параметров и чекбоксов:

- `product_name`: Поиск по названию продукции, если установлен чекбокс checkbox_product в значении 1.
- `category_name`: Поиск по названию категории, если установлен чекбокс checkbox_category в значении 1.
- Если установлены оба чекбокса `checkbox_product` и `checkbox_category`, то будет применяться логика поиска по продукциям и категориям с учетом всех переданных параметров.

Errors
Invalid parameters: Если параметры запроса некорректны или отсутствуют, возвращается ошибка с кодом 400.

## Endpoint: `cars-logist/type-cars`

### URL

GET /api/v1/cars_logist/type-cars

### Method

`GET`

### Roles

- admin
- logist

### Response

#### Success Response

- **Code**: 200 OK
- **Content**:

```json
[
    {
        "id": 1,
        "name": "Sedan"
    },
    {
        "id": 2,
        "name": "SUV"
    },
    ...
]
```
**Description**

Возвращает список всех типов автомобилей из таблицы `TypeCars`.

## Endpoint: cars-logist/create
### URL
POST /api/v1/cars-logist/create
### Method
`POST`

### Roles
- admin
- logist

### Request Body
**Content-Type**: application/json or application/x-www-form-urlencoded

**Body:**

```json

{
    "name": "John Doe",
    "telephone": "1234567890",
    "email": "john.doe@example.com",
    "notes": "Some notes here",
}
```
**Response**
Success 

**Response Code**: 200 OK

Content:
```json
{
    "status": "success",
    "model": {
        "id": 1,
        "name": "John Doe",
        "telephone": "1234567890",
        "email": "john.doe@example.com",
        "notes": "Some notes here",
    }
}
```
**Error Response**

**Code**: 400 Bad Request

Content:
```json
{
    "status": "error",
    "errors": {
        "name": ["Name cannot be blank."],
        "telephone": ["Telephone is not a valid number."],
        ...
    }
}
```
### Description
Создает новую запись в таблице CarsLogist. При успешном сохранении возвращает статус success с данными созданной модели. В случае ошибки возвращает статус error и описание ошибок.

## Endpoint: cars-logist/search
### URL
```sql
GET /api/v1/cars-logist/search
```
### Method
`GET`

### Roles
- admin
- logist

### Query Parameters
- type_cars_id (integer, optional): ID типа автомобиля для фильтрации.
- district_id (integer, optional): ID района для фильтрации.
- region (string, optional): Название региона для фильтрации.
### Response
Success Response

Code: 200 OK

Content:
```json
[
    {
        "name": "John Doe",
        "telephone": "1234567890",
        "email": "john.doe@example.com",
        "notes": "Some notes here",
        "region_names": ["Region1", "Region2"],
        "car_type_names": ["Sedan", "SUV"]
    },
    ...
]
```
### Description
Выполняет поиск по таблице CarsLogist с возможностью фильтрации по типу автомобиля, району и региону. Возвращает список логистов с информацией о их типах автомобилей и регионах, в виде имен, объединенных в строки.

- region_names: Массив названий регионов, в которых находится логист.
- car_type_names: Массив названий типов автомобилей, связанных с логистом.

