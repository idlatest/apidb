<?php

$jwtAuth = $container->get('jwt');

$app->post('/auth/register', 'RegisterController:register');
$app->post('/auth/login', 'LoginController:login');

$app->get('/user', 'UserController:show')->add($jwtAuth);
$app->get('/categories', 'CategoryController:index');