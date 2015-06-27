<?php

require 'db_config.php';
include 'CommonFunctions.php';

// Название компании
$companyName = $database->getValueByShortName('COMPANY_NAME')['VALUE'];

// Постфикс заголовка окна панели управления
$adminPanelTitle = '— Панель управления '.$companyName;

// Соц. сети и их названия
$loginOptions = $database->getLoginOptions();
$socialNetworksNames = CommonFunctions::extractSingleValueFromMultiValueArray($loginOptions, 'NAME');

// Параметры отображения страниц
$dashboardTablePreviewSize 	= $database->getValueByShortName('DASHBOARD_TABLE_PREVIEW_LIMIT')['NUMBER_VALUE'];
$tablePageLimit 			= $database->getValueByShortName('TABLE_PAGE_LIMIT')['NUMBER_VALUE'];

// Цвета графиков (и текста в таблицах)
$chartColors = $database->getColors();

// Сглаживать ли график
$curveMainStatsChart = $database->getValueByShortName('CURVE_CHARTS')['VALUE'];

// Отрисовывать ли на страницу полное содержимое в нормальном виде.
// Менять на false в нужных блоках в страницах-агрегаторах
// (Dashboard, в частности).
$drawFullContent = true;

// Количество соц сетей
$numberOfSocialNetworks = count($socialNetworksNames);


?>