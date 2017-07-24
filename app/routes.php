<?php

use Fermi\Response;

$r->addRoute('GET', '/admin', 'App\Controllers\AdminController::index');

$r->addRoute('GET', '/admin/query', 'App\Controllers\AdminController::query');

$r->addRoute('POST', '/admin/add', 'App\Controllers\AdminController::add');

$r->addRoute('POST', '/admin/update', 'App\Controllers\AdminController::update');

$r->addRoute('POST', '/admin/delete', 'App\Controllers\AdminController::delete');

$r->addRoute('POST', '/admin/weights', 'App\Controllers\AdminController::weights');

$r->addRoute('GET', '/login', 'App\Controllers\LoginController::index');

$r->addRoute('POST', '/login', 'App\Controllers\LoginController::login');

$r->addRoute('GET', '/logout', 'App\Controllers\LoginController::logout');
