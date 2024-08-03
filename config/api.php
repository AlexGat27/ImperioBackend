<?php

return [
    'POST api/v1/login' => 'user/login',
    'GET api/v1/logout' => 'user/logout',
    'GET api/v1/profile' => 'user/profile',
    'GET api/v1/refresh-tokens' => 'user/refresh-tokens',

    // Пользователи
    'GET api/v1/users' => 'user/index',
    'POST api/v1/users/create' => 'user/register',
    'POST api/v1/users/update/<id:\d+>' => 'user/update',
    'POST api/v1/users/delete/<id:\d+>' => 'user/delete',
    'GET api/v1/users/<id:\d+>/get-role' => 'user/get-role',

    // Роли
    'GET api/v1/roles' => 'role/index',
    'GET api/v1/roles/view' => 'role/view',
    'POST api/v1/roles' => 'role/create',
    'PUT api/v1/roles' => 'role/update',
    'DELETE api/v1/roles' => 'role/delete',

    // Производители
    'GET api/v1/manufactures' => 'manufacture/index',
    'GET api/v1/manufactures/<id:\d+>' => 'manufacture/view',
    'POST api/v1/manufactures' => 'manufacture/create',
    'PUT api/v1/manufactures/<id:\d+>' => 'manufacture/update',
    'DELETE api/v1/manufactures/<id:\d+>' => 'manufacture/delete',
    'GET api/v1/manufactures/search-in-manufactures' => 'manufacture/search-in-manufactures',

    // Контакты производителей
    'POST api/v1/manufacture-contacts' => 'manufacture-contact/create',
    'GET api/v1/manufacture-contacts' => 'manufacture-contact/index',
    'PUT api/v1/manufacture-contacts/<id:\d+>' => 'manufacture-contact/update',
    'DELETE api/v1/manufacture-contacts/<id:\d+>' => 'manufacture-contact/delete',

    // Города
    'GET api/v1/cities' => 'city/index-parentid',

    // Продукты
    'GET api/v1/products' => 'product/search',

    // Логистика
    'POST api/v1/cars-logist' => 'cars-logist/create',
    'GET api/v1/cars-logist' => 'cars-logist/search',
    'GET api/v1/cars-logist/type-cars' => 'cars-logist/index-type-cars',
];
