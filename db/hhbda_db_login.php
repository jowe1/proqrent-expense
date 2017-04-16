<?php
if (isset($_GET['err'])) {
    if ($_GET['err'] == 1) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }
}
else { ini_set('display_errors', 0); }
$db = new mysqli('mysql5.proqrent.de', 'db41315_60', 'bjg_dsez6fCd', 'db41315_60');
if (mysqli_connect_errno()) {
    die ('Konnte keine Verbindung zur Datenbank aufbauen: ' . mysqli_connect_error() . '(' . mysqli_connect_errno() . ')');
}
function get_my_db()
{
    static $db;
    if (!$db) {
        $db = new mysqli('mysql5.proqrent.de', 'db41315_60', 'bjg_dsez6fCd', 'db41315_60');
    }
    return $db;
}

$thisaddress = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$thisurl = $thisaddress;
$dateiurl = explode('/', $thisurl);
$datei = explode('.', $dateiurl[0]);
$shareurl = explode('&PHPSESSID', $thisaddress);
$shareurl2 = $shareurl[0];
