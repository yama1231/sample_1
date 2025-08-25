<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'reservations';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['api/reservations']['get'] = 'reservations/index';
$route['api/reservations']['post'] = 'reservations/create';
$route['api/reservations/(:num)']['get'] = 'reservations/show/$1';
$route['api/reservations/(:num)']['put'] = 'reservations/update/$1';
$route['api/reservations/(:num)']['delete'] = 'reservations/delete/$1';