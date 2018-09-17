<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Главная страница</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/css/libs.min.css">
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/media.css">
</head>
<body>
<div class="wrapper">
    <header class="main-header">
        <div class="top-header">
            <div class="top-header__wrap">
                <div class="logotype-block">
                    <div class="logo-wrap"><a href="/"><img src="/img/logo.svg" alt="Логотип" class="logo-wrap__logo-img"></a></div>
                </div>
                <nav class="main-navigation">
                    <ul class="nav-list">
                       <?php wp_nav_menu();?>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="bottom-header">
            <div class="search-form-wrap">
                <form class="search-form" action="/" method = "post">
                    <input type="text" placeholder="Поиск..." class="search-form__input" name="s">
                    <button class="search-form__btn-search"><i class="icon icon-search"></i></button>
                </form>
            </div>
        </div>
    </header>
    <!-- header_end-->
    <div class="main-content">
        <div class="content-wrapper">