<?php
if (isset($_GET['err'])) {
    if ($_GET['err'] == 1) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }
}
else { ini_set('display_errors', 0); }
$db = new mysqli('mysql5.renoi.de', 'db406064_18', 'brrah*y1bdeK', 'db406064_18');
if (mysqli_connect_errno()) {
    die ('Konnte keine Verbindung zur Datenbank aufbauen: ' . mysqli_connect_error() . '(' . mysqli_connect_errno() . ')');
}
function get_my_db()
{
    static $db;
    if (!$db) {
        $db = new mysqli('mysql5.renoi.de', 'db406064_18', 'brrah*y1bdeK', 'db406064_18');
    }
    return $db;
}


$dateiurl = explode('/', $thisurl);
$datei = explode('.', $dateiurl[0]);
$shareurl = explode('&PHPSESSID', $thisaddress);
$shareurl2 = $shareurl[0];
