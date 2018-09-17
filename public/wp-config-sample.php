<?php
define('DB_NAME', 'database_name_here');

/** Имя пользователя MySQL */
define('DB_USER', 'username_here');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', 'password_here');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');
define('AUTH_KEY',         'впишите сюда уникальную фразу');
define('SECURE_AUTH_KEY',  'впишите сюда уникальную фразу');
define('LOGGED_IN_KEY',    'впишите сюда уникальную фразу');
define('NONCE_KEY',        'впишите сюда уникальную фразу');
define('AUTH_SALT',        'впишите сюда уникальную фразу');
define('SECURE_AUTH_SALT', 'впишите сюда уникальную фразу');
define('LOGGED_IN_SALT',   'впишите сюда уникальную фразу');
define('NONCE_SALT',       'впишите сюда уникальную фразу');

$table_prefix  = 'wp_';

define('WP_DEBUG', false);

if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');
