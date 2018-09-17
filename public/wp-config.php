<?php
define('DB_NAME', 'wp');

/** Имя пользователя MySQL */
define('DB_USER', 'databaseuser');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', '111111');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8mb4');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

define('AUTH_KEY',         'dS=VssQ,CG9YtA{r+eZ3rpATS-bf0/@aCYZP!,/wtx9|8b-5ND`-3o%Q]o%RELOH');
define('SECURE_AUTH_KEY',  ']ak9Z:#v%3:UEdSkmbEz6P/9J<txpU.()(Y^G}1(,djTp`b{S^6.fQkgRF)@R32?');
define('LOGGED_IN_KEY',    'g4gU[l0N<dA$S_I7Axg1p7xgKK6h`^4WHy>bKuKbk;0 r~IX[9;iq+4QuiUGC%m@');
define('NONCE_KEY',        '*hz,WC<CmR<#W~QO&s,iG-tGrIb}o8,AHecZU`k)D^SkT^T$x_rmiLH7;(^?VWAV');
define('AUTH_SALT',        'Ny>.@Fog.x)T?:.CEFQiQjjKG.U4hB{(Z4;,X}[dV&L-_U$}`Q*z? .f,[+E)a0h');
define('SECURE_AUTH_SALT', '5Jw_~J0mE^~GYX&x]p]{6Jnc`%6OUbpuoBVkSP%p/@:9a=Hfni<%Pqd?nVpbsW^/');
define('LOGGED_IN_SALT',   'XIB?_U4Or_R#;Vq*RiSb;wKC]A=]D0:smu]U>Qd317kS7hch!z),?Wzz@  e_WvZ');
define('NONCE_SALT',       '5J3o~!V-7tCdRCgdMAQ%7kUs}#i9g:W)5>^SI,/f%J9MRc)S4+=[-iLe0~x-H:#h');

$table_prefix  = 'wp_';

define('WP_DEBUG', false);
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

require_once(ABSPATH . 'wp-settings.php');
