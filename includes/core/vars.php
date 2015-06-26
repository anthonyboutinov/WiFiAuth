<?php

require 'db_config.php';
include 'CommonFunctions.php';

// Название компании
$companyName = $database->getValueByShortName("COMPANY_NAME")["VALUE"];

// Постфикс заголовка окна панели управления
$adminPanelTitle="— Панель управления $companyName";

// Названия соц сетей
$loginOptions = $database->getLoginOptions();
$socialNetworksNames = CommonFunctions::extractSingleValueFromMultiValueArray($loginOptions, 'NAME');

$dashboardTablePreviewSize = $database->getValueByShortName("DASHBOARD_TABLE_PREVIEW_LIMIT")["NUMBER_VALUE"];
$tablePageLimit = $database->getValueByShortName('TABLE_PAGE_LIMIT')['NUMBER_VALUE'];

// Цвета графиков (и текста в таблицах)
$chartColors = CommonFunctions::extractSingleValueFromMultiValueArray($loginOptions, 'COLOR');

// Сглаживать ли график
$curveMainStatsChart = false;


// -----------------------------------------------------------------------------
// --------ДАЛЬШЕ НЕ ТРОГАТЬ!---------------------------------------------------

// Отрисовывать ли на страницу полное содержимое в нормальном виде.
// Менять на false в нужных блоках в страницах-агрегаторах
// (Dashboard, в частности).
$drawFullContent = true;

// Количество соц сетей
$numberOfSocialNetworks = count($socialNetworksNames);


?>