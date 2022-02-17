<?php

use libs\Response;

Router::post('accounts/login', 'app\controllers\AuthController@login');
Router::post('accounts/register', 'app\controllers\AuthController@register');
Router::delete('accounts/logout', 'app\controllers\AuthController@logout');
Router::post('accounts/hasEmail', 'app\controllers\AuthController@hasEmail');
Router::post('accounts/refreshToken', 'app\controllers\AuthController@refreshToken');
Router::put('accounts/resetPassword', 'app\controllers\UserController@resetPassword');
Router::put('users/{id}', 'app\controllers\UserController@update', ['checkToken']);
Router::get('categories', 'app\controllers\CategoryController@getAll');
Router::post('categories/checkName', 'app\controllers\CategoryController@checkName', ['checkToken']);
Router::post('categories/create', 'app\controllers\CategoryController@create', ['checkToken']);
Router::put('categories/update/{id}', 'app\controllers\CategoryController@update', ['checkToken']);
Router::delete('categories/delete', 'app\controllers\CategoryController@delete', ['checkToken']);
Router::post('companies/checkName', 'app\controllers\CompanyController@checkName', ['checkToken']);
Router::post('companies/create', 'app\controllers\CompanyController@create', ['checkToken']);
Router::get('companies/{page}/{limit}', 'app\controllers\CompanyController@get');
Router::delete('companies/delete', 'app\controllers\CompanyController@delete', ['checkToken']);
Router::put('companies/update/{id}', 'app\controllers\CompanyController@update', ['checkToken']);
Router::post('news/checkTitle', 'app\controllers\NewsController@checkTitle', ['checkToken']);
Router::post('news/create', 'app\controllers\NewsController@create', ['checkToken']);
Router::get('news/{page}/{limit}', 'app\controllers\NewsController@get');
Router::delete('news/delete', 'app\controllers\NewsController@delete', ['checkToken']);
Router::get('news/{slug}', 'app\controllers\NewsController@getBySlug');
Router::put('news/update/{id}', 'app\controllers\NewsController@update', ['checkToken']);
Router::get('home/hot', 'app\controllers\HomeController@getSlide');
Router::get('home/read-a-lot', 'app\controllers\HomeController@getNewsReadALot');
Router::get('home/company', 'app\controllers\HomeController@getCompany');
Router::get('home/new', 'app\controllers\HomeController@getNews');
Router::get('home/comment', 'app\controllers\HomeController@getNewsComment');
Router::get('search/{category}/{keyword}/{page}/{limit}', 'app\controllers\SearchController@getNews');
?>