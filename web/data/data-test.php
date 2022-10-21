<?php

use Taskforce\dataConverter\DataConverter;

require_once __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', 'On');
error_reporting(E_ALL);

$categories = new DataConverter(
    "data/categories.csv",
    ['name', 'icon'],
    'categories',
    'categories-sql'
);
$categories->converting();

$cities = new DataConverter(
    "data/cities.csv",
    ['name', 'latitude', 'longitude'],
    'cities',
    'cities-sql'
);
$cities->converting();
