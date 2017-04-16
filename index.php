<?php session_start(); ?>
<html>
<head>
    <link rel="stylesheet" href="bs/css/bootstrap.min.css"
          integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <script src="js/dropzone.js"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>
    <script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>
    <script src="js/datepicker.js"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/main.css?v=4">
    <link rel="stylesheet" href="css/bs_overwrites.css?v=2">
    <link rel="stylesheet" href="css/dropzone.css">
    <link rel="stylesheet" href="css/datepicker.css">
    <link rel="icon" href="http://expense.renoi.de/favicon.ico?v=2">
    <link rel="apple-touch-icon" href="http://expense.renoi.de/favicon.ico?v=2">
    <?php
    if ($_GET['dev'] != "einblenden") {
        ?>
        <style>
            .devc {
                display: none !important;
            }
        </style>
        <?php
    }
    ?>
    <!-- Latest compiled and minified JavaScript -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

</head>
<body>
<?php
$thisaddress = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$thisurl = $thisaddress;
if (strpos($thisurl, "renoi.de") > 0) {
    include_once("db/db_login.php");
    $serverDiff = "http://expense.renoi.de/";
    $serverMail = "info@renoi.de";
}
else {
    include_once("db/hhbda_db_login.php");
    $serverDiff = "http://hhbda.de/hugo-hausz/";
    $serverMail = "noreply@hhbda.de";
}
echo "<div class=\"loginContainer\">";
include_once("login.php");
echo "</div>";

include("functions/text.php");
include("functions/file.php");
include("functions/calc.php");
$string1 = "Hallo";
$textclass = NEW text($string1);
$fileclass = NEW file($string1);
$calcclass = NEW calc($string1);

?>


<?php
// GET ID FROM ADDRESS BAR IF SET (thus you can send links and open objects via control panel)
//$last_object_id = 0;
$unique_object_key = md5(microtime().rand());
//echo $unique_object_key;
if (isset($_GET['id'])) {
    $last_object_id = $_GET['id'];
}

if (isset($_GET['uniqueID'])) {
    $last_unique_id = $_GET['uniqueID'];
//    echo "lui $last_unique_id";
    $sql = "SELECT * FROM object WHERE unique_object_id = '$last_unique_id'";
    $result = $db->query($sql);
    if (!$result) {
        die ('Etwas stimmte mit dem Query nicht: ' . $db->error);
    }
    while ($row = $result->fetch_assoc()) {
        $last_object_id = $row['id'];
        $object_id = $last_object_id;
    }
    $unique_object_key = $last_unique_id;
}

$user_id = $_SESSION['user_id'];

if ($last_object_id > 0) {
$billCounter = 0;
    $sql = "SELECT * FROM bill WHERE user_id = '$user_id' AND object_id = '$object_id'";
    if ($admin > 0) {
        $sql = "SELECT * FROM bill WHERE object_id = '$object_id'";
    }
    $result = $db->query($sql);
    if (!$result) {
        die ('Etwas stimmte mit dem Query nicht: ' . $db->error);
    }
    while ($row = $result->fetch_assoc()) {
        $bill_url_object[] = $row['url'];
        $bill_object_id = $row['object_id'];
        $bill[$billCounter]['url'] = $row['url'];
        $bill[$billCounter]['object_id'] = $row['object_id'];
        $bill[$billCounter]['date'] = $row['date'];
        $bill[$billCounter]['area'] = $row['area'];
        $bill[$billCounter]['amount'] = $row['amount'];
        $bill[$billCounter]['type'] = $row['type'];
        $bill[$billCounter]['user'] = $row['user_id'];
        $billCounter++;
    }

    $sql = "SELECT * FROM object WHERE user_id = '$user_id' AND id = '$object_id'";
    if ($admin > 0) {
        $sql = "SELECT * FROM object WHERE id = '$object_id'";
    }
    $result = $db->query($sql);
    if (!$result) {
        die ('Etwas stimmte mit dem Query nicht: ' . $db->error);
    }
    while ($row = $result->fetch_assoc()) {
        $expense_month = $row['month'];
        $object_id = $row['id'];
        $object_user_id = $row['user_id'];
    }

    $tripCounter = 0;

    $sql = "SELECT * FROM trip WHERE user_id = '$user_id' AND object_id = '$object_id'";
    if ($admin > 0) {
        $sql = "SELECT * FROM trip WHERE object_id = '$object_id'";
    }
    $result = $db->query($sql);
    if (!$result) {
        die ('Etwas stimmte mit dem Query nicht: ' . $db->error);
    }
    while ($row = $result->fetch_assoc()) {
        $trip_partner = stripslashes($row['partner']);
        $trip_vehicle = $row['vehicle_type'];
        $trip_date = $row['date'];
        $trip_starttime = $row['starttime'];
        $trip_endtime = $row['endtime'];
        $trip_area = $row['area'];
        $trip_location = $row['location'];
        $trip_distance = $row['distance'];
        $trip_is_indatabase = $row['id'];

        $trip[$tripCounter]['partner'] = stripslashes($row['partner']);
        $trip[$tripCounter]['vehicle'] = $row['vehicle_type'];
        $trip[$tripCounter]['object_id'] = $row['object_id'];
        $trip[$tripCounter]['date'] = $row['date'];
        $trip[$tripCounter]['starttime'] = $row['starttime'];
        $trip[$tripCounter]['endtime'] = $row['endtime'];
        $trip[$tripCounter]['area'] = $row['area'];
        $trip[$tripCounter]['location'] = $row['location'];
        $trip[$tripCounter]['distance'] = $row['distance'];
        $tripCounter++;
    }
}
    if (!isset($_POST['formsave'])) {

        $editFeedback = "<h3>Projekt-Status: noch <b>nicht</b> gespeichert!</h3>";
        if (!isset($_SESSION['id'])) {
            $editFeedback = "<h2>Anmeldung</h2>";
        }
        $newObjectLink = "";
    }
    if (('POST' == $_SERVER['REQUEST_METHOD']) AND (isset($_POST['formsave']))) {
        // FORM VALUES OF USER
        $user_vorname = $_POST['user_vorname'];
        $user_nachname = $_POST['user_nachname'];
        $user_adresszeile1 = $_POST['user_adresszeile1'];
        $user_adresszeile2 = $_POST['user_adresszeile2'];
        $user_plz = $_POST['user_plz'];
        $user_ort = $_POST['user_ort'];
        $user_land = $_POST['user_land'];
        $user_tel1 = $_POST['user_tel1'];
        $user_tel2 = $_POST['user_tel2'];
        $user_email = $_POST['user_email'];
        $user_homepage = $_POST['user_homepage'];
        $user_titel = $_POST['user_titel'];
        $user_id = $_SESSION['user_id'];

        // FORM VALUES OF OBJECT
        $user_id = $_SESSION['user_id'];
        $expense_month = $_POST['expense_month'];

        // FORM VALUES OF TRIP

        $trip_partner = $_POST['trip_partner'];
        $trip_vehicle = $_POST['trip_type'];
        $trip_date = $_POST['trip_date'];
        $trip_starttime = $_POST['trip_starttime'];
        $trip_endtime = $_POST['trip_endtime'];
        $trip_area = $_POST['trip_area'];
        $trip_location = $_POST['trip_location'];
        $trip_distance = $_POST['trip_distance'];

        if ($last_object_id > 0) {
        $sql_user = "UPDATE user SET 
                vorname = '$user_vorname',
                nachname = '$user_nachname',
                adresszeile1 = '$user_adresszeile1',
                adresszeile2 = '$user_adresszeile2',
                plz = '$user_plz',
                ort = '$user_ort',
                land = '$user_land',
                telefon1 = '$user_tel1',
                telefon2 = '$user_tel2',
                email = '$user_email',
                homepage = '$user_homepage',
                anrede = '$user_titel'
                WHERE id = '$user_id'";

        // OBJEKT UPDATEN
        $sql = "UPDATE object SET 
                month = '$expense_month',
                date = NOW()
                WHERE id = '$last_object_id'";

        $db->query($sql);
        $editFeedback = "<h3>Projekt-Status: Objekt-Nummer <b>$last_object_id</b> wird bearbeitet.</h3>";
        $newObjectLink = "<h3><a href='index.php' target='_blank'>Neues Projekt anlegen</a></h3>";

    } else {
        echo "ELSE INSERT";
        // INSERT INTO TABLE OBJECT IN DATABASE
        $sql = "INSERT INTO
                object (unique_object_id, user_id, month)
        	    VALUES
                ('$unique_object_key', '$user_id', '$expense_month')";
        if ($db->query($sql) === TRUE) {
            $last_object_id = $db->insert_id;
            $editFeedback = "<h3>Projekt-Status: Objekt-Nummer $last_object_id wurde soeben angelegt.</h3>";
            $newObjectLink = "<a href='index.php' target='_blank'>Neues Projekt anlegen</a>";
            $_SESSION['object'] = $last_object_id;
        }

    }
    echo $db->error;
}
$sql = "SELECT * FROM object WHERE id >= 0 ORDER BY	id DESC	LIMIT 100";
$result = $db->query($sql);
if (!$result) {
    die ('Etwas stimmte mit dem Query nicht: ' . $db->error);
}
while ($row = $result->fetch_assoc()) {
    /*    $img_title = utf8_encode($row['title']);
        $img_date = $row['date'];
        $img_date = $textclass->dateger($img_date);
        $img_desc = utf8_encode($row['description']);
        $img_full_size = $row['src'];
        $img_thumb_size = $row['thumb'];
        $img_gallery = $row['gallery'];
        if ($gallery == $img_gallery) {
            // /images/photos/weich-gebettet.jpg
            echo '<article class="thumb">';
            //echo "<a href=\"/backend/phpThumb/phpThumb.php?src=/$img_full_size&h=1080&fltr[]=wmi|/backend/phpThumb/desi_watermark.png|TL||90\" class=\"image\">";
            echo '<a href="/backend/phpThumb2/watermark/'.$img_full_size.'" class="image">';
            //echo "/backend/phpThumb/phpThumb.php?src=$img_full_size&h=1080&fltr[]=wmi|/backend/phpThumb/desi_watermark.png|C|";
            //echo $img_full_size;
            echo '<img src="/backend/phpThumb2/thumbnail/'.$img_full_size.'" alt="' . $img_title . '" /></a>';
            echo "<h2 class='text-shadow'>$img_title</h2>";
            echo "<p class='text-shadow'>$img_desc</p>";
            echo "<span>$img_date</span>";
            echo '</article>';
        }*/
}

?>
<div class="wrapper">
    <div class="header">
        <a href="http://expense.renoi.de" class="finv">
            <!-- <div class="logo">
                 <div class="circle">
                     <div class="circle circle2">
                         <div class="circle circle3">
                             <div class="circle circle4">
                             </div>
                         </div>
                     </div>
                 </div>
             </div>-->
            <img src="images/lingner-people_logo.png">
        </a>
        <!--        <span class="devc col-xs-6">das logo besitzt einen hovereffekt (mit der maus über das logo gehen). dabei ist jeder ring einzeln markierbar. die orangene farbe dient nur der illustration.
                diese kann auch durch ein seichtes blau ersetzt werden etc. hierzu bitte einfach die farben im hexcode an uns senden (#0000FF wäre bspw. vollblau).</span>-->

        <h1>Spesenabrechnung <?php if (isset($expense_month)) { echo $expense_month; } ?></h1>
        <h3><?php if (isset($editFeedback)) { echo $editFeedback; echo $newObjectLink; } ?></h3>

    </div>
    <form action="?uniqueID=<?php echo $unique_object_key; ?>" method="post" role="form"
          data-toggle="validator" style="margin-bottom: 0;">
        <div id="tabs-menu-box2">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation"  <?php if (!isset($last_object_id)) { ?>class="active" <?php } ?>><a href="#info" aria-controls="info" role="tab"
                                                          class="glow" data-toggle="tab">Info</a></li>
<!--                <li role="presentation" class="active"><a href="#einreicher" aria-controls="einreicher" role="tab"
                                                          class="glow" data-toggle="tab">Einreicher</a></li>-->
                <li role="presentation"><a href="#expense" aria-controls="expense" role="tab" class="glow"
                                           data-toggle="tab">Spesenabrechnung</a></li>
                <li role="presentation"><a href="#trip" aria-controls="trip" role="tab" class="glow"
                                           data-toggle="tab">Fahrtenbuch</a></li>
                <!--            <li role="presentation"><a href="#layout" aria-controls="layout" role="tab" class="glow" data-toggle="tab">Layout</a></li>-->
               <?php if ($last_object_id > 0) { ?>
                <li role="presentation" class="active"><a href="#bill" aria-controls="bill" role="tab" class="glow"
                                           data-toggle="tab">Rechnungen</a></li>
                <?php
                }
                ?>
                <li role="presentation"><a href="#imprint" aria-controls="imprint" role="tab" class="glow"
                                           data-toggle="tab">Impressum</a></li>
                <!--            <li role="presentation"><a href="#bilder_preisgericht" aria-controls="bilder_preisgericht" role="tab" class="glow" data-toggle="tab">Bilder für Preisgericht</a></li>-->
<!--                <li role="presentation"><a href="#objektbeschreibung" aria-controls="objektbeschreibung" role="tab"-->
<!--                                           class="glow" data-toggle="tab">Objektbeschreibung</a></li>-->
            </ul>
            <?php

            if ($_SESSION["login"] == 0) {
                {
                    echo "<br /><br /><b>Bitte melden Sie sich an, um dieses Formular zu sehen und absenden zu können.</b>";
                    include_once("register.php");
                }
            } else {
            ?>
            <!-- Tab panes -->
            <div class="tab-content innerwrapper">
                <div role="tabpanel" class="tab-pane <?php if (!isset($last_object_id)) { ?> active <?php } ?>" fade in" id="info">
                    <h2>Informationen zum Formular</h2>
                    <h3>Sicherheitshinweise</h3>
                        <p class="">Bitte senden Sie keine Links (URLs) an Dritte, die den unique_object_key enthalten, da dies den Zugang zu den Daten gewährt. Dies gilt für alle PDFs,
                        aber auch generell für dieses Formular!</p>
                <img src="images/unique_object_key_security.jpg">
                    <h3>Kurzanleitung</h3>
                    <p>Benötigt werden:</p>
                    <ul>
                        <li>Auswahl des richtigen Monats für die Spesenabrechnung</li>
                        <li>alle Rechnungen (PDF, Bilder (jpg, png, gif...))</li>
                        <li>Fahrten inkl. Kilometer im Formular Fahrtenbuch</li>
                        <li>Kontaktdaten inkl. IBAN</li>
                    </ul>
                    <br />
                    <h4>Vorgehensweise</h4>
                    <ol>
                        <li>Pflichtfelder (mit *) auf jeder Seite ausfüllen. Die anderen Felder sind optional.</li>
                        <li>Daten auf jeder Seite „Abschicken“.</li>
                        <li>Anschließend alle Rechnungen hochladen (diese können per Drag & Drop aus dem Explorer als Mehrfachauswahl in die Dropzone eingefügt werden oder per Klick auf die Dropzone
                        auch einzeln hinzugefügt werden)</li>
                        <li><b>Der Reiter "Rechnungen" wird erst angezeigt, sobald das Formular erfolgreich abgeschickt wurde</b> und somit ein Projekt angelegt ist.</li>
                        <li>Wenn man mit einer Spesenabrechnung fertig ist, kann man im Kontrollzentrum den jeweiligen Monat komplett an Markus senden, der dann ein PDF mit allen Einträgen und den Rechnungen zugesandt bekommt.</li>
                        <li>Änderungen danach müssen vorab kommuniziert werden</li>
                        <li>Es ist möglich Teilabrechnungen zu erstellen, hierfür wird im nächsten Teil der bereits überwiesene Betrag im entsprechenden Feld eingepflegt. Dieser zieht
                        sich dann automatisch vom Gesamtbetrag ab</li>
                        <li>Das Fahrtenbuch wird automatisch berechnet (max(Kilometer * Fahrtkostenpauschale, DB 50 Preis). Bitte hier die richtige Fahrzeugauswahl (Privat / Firma) pro Reise wählen!</li>
                        <li>Im „Kontrollzentrum“, Link oben rechts, können Sie Ihr/e gespeicherten Spesenabrechnungen mit „Edit“ bearbeiten.</li>
                        <li>Falls wider Erwarten etwas nicht funktioniert, können Sie das Formular unendlich oft absenden. Gleiches gilt für die Bilder.
                        Sollte also eines der PDFs Ihre im Formular eingegebenen Daten / Bilder nicht anzeigen, drücken Sie nochmals auf Absenden und / oder laden Sie
                        die Bilder erneut hoch.</li>
                    </ol>
                    <br>
                    <h2>FAQ</h2>
                <?php
                $sql = "SELECT * FROM faq ORDER BY id DESC";
                $result = $db->query($sql);
                if (!$result) {
                    die ('Etwas stimmte mit dem Query nicht: ' . $db->error);
                }
                while ($row = $result->fetch_assoc()) {
                    $faq_frage = $row['frage'];
                    $faq_antwort = $row['antwort'];
                    echo "<h3>$faq_frage</h3>";
                    echo "<p>$faq_antwort</p>";
                }
                ?>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe src="https://player.vimeo.com/video/211282874" class="embed-responsive-item" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    </div>
                    <p><a href="https://vimeo.com/211282874">Anleitung zum Anmeldeportal Hugo-H&auml;ring-Auszeichnung 2017</a> from <a href="https://vimeo.com/bunddeutscherarchitekten">BDA Bund Deutscher Architekten</a> on <a href="https://vimeo.com">Vimeo</a>.</p>
                </div>
                <div role="tabpanel" class="tab-pane fade in" id="trip">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="hidden-xs col-md-4">
                                <h2>Label</h2>
                            </div>
                            <div class="hidden-xs col-md-6">
                                <h2>Feld</h2>
                            </div>
                            <div class="hidden-xs col-md-2">
                                <h2>Beispiel</h2>
                            </div>
                        </div>

                        <?php
                        $tripCounter = 0;
                        echo "<table class='table'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>Nr.</th>";
                        echo "<th>Datum</th>";
                        echo "<th>Start</th>";
                        echo "<th>Ende</th>";
                        echo "<th>Bereich</th>";
                        echo "<th>Reise</th>";
                        echo "<th>Distanz</th>";
                        echo "<th>Einsatz</th>";
                        echo "<th>Betrag</th>";
                        echo "</tr>";
                        echo "</thead>";
                            foreach($trip as $item) {
                                $tpartner = $textclass->text($trip[$tripCounter]['partner']);
                                $tid = $trip[$tripCounter]['object_id'];
                                $tdate = $textclass->dateger($trip[$tripCounter]['date']);
                                $tstart = $trip[$tripCounter]['starttime'];
                                $tend = $trip[$tripCounter]['endtime'];
                                $tarea = $trip[$tripCounter]['area'];
                                $tloc = $textclass->text($trip[$tripCounter]['location']);
                                $tloc = $textclass->shorten($tloc, "20");
                                $tdis = $trip[$tripCounter]['distance'];
                                $ttype = $trip[$tripCounter]['vehicle'];
                                $tamount = $calcclass->trip($tdis, $ttype);
                                echo "<tr>";
                                echo "<td>$tid</td>";
                                echo "<td>$tdate</td>";
                                echo "<td>$tstart</td>";
                                echo "<td>$tend</td>";
                                echo "<td>$tarea</td>";
                                echo "<td>$tloc</td>";
                                echo "<td>$tdis</td>";
                                echo "<td>$tpartner</td>";
                                echo "<td>$tamount €</td>";
                                echo "</tr>";
                            }
                        echo "</table>";
                        $tripCounter++;
                        ?>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-12 col-md-4">
                                    <label>Fahrzeug</label>
                                </div>
                                <div class="col-xs-6">
                                    <select name="trip_type" size="1" class="form-control selectpicker">
                                        <?php
                                        $sqls = "SELECT * FROM vehicle_type";
                                        $results = $db->query($sqls);
                                        if (!$results) {
                                            die ('Etwas stimmte mit dem Query nicht: ' . $db->error);
                                        }
                                        while ($rows = $results->fetch_assoc()) {
                                            $vehicle_option = utf8_encode($rows['type']);
                                            $vehicle_id = $rows['id'];
                                            if ($vehicle_option == $trip_vehicle) {
                                                $vehicle_selected = "selected=\"selected\"";
                                            }
                                            else { $vehicle_selected = ""; }
                                            echo '<option value="'.$vehicle_option.'" '.$vehicle_selected.'>'.$vehicle_id.' '.$vehicle_option.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-error">
                            <div class="row">
                                <div class="col-xs-12 col-md-4">
                                    <label>Datum *</label>
                                </div>
                                <div class="col-xs-6">
                                    <div class="input-append date" id="datepicker" data-date="01.03.2017"
                                         data-date-format="dd.mm.yyyy">
                                        <input type="text" name="trip_date" id="trip_date" class="form-control"
                                               placeholder="01.03.2017" maxlength="10" value="<?php
                                        if (isset($trip_date)) {
                                            echo $trip_date;
                                        } ?>" required/>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-2">
                                    <i>03.2017</i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-error">
                            <div class="row">
                                <div class="col-xs-12 col-md-4">
                                    <label>Reiseweg *</label>
                                </div>
                                <div class="col-xs-6">
                                    <input type="text" name="trip_location" id="trip_location" class="form-control" placeholder=""
                                           value="<?php
                                           if (isset($trip_location)) {
                                               echo $trip_location;
                                           } ?>" required/>
                                </div>
                                <div class="col-xs-2">
                                    München, Arnulfstr. 32 - Stuttgart, Neckarstr. 132
                                </div>
                            </div>
                        </div>
                        <iframe src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d2591994.945951924!2d8.628949473661093!3d50.6225641499405!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e6!4m5!1s0x4799e06d5b6a92e7%3A0x1aedba589ecdc8ac!2sOtto-Lilienthal-Stra%C3%9Fe+36%2C+71034+B%C3%B6blingen!3m2!1d48.6810597!2d8.9735695!4m5!1s0x47a853f955555555%3A0x64b97d7d67bf2aea!2sFlughafen+Berlin-Tegel%2C+Berlin!3m2!1d52.558832699999996!2d13.2884374!5e0!3m2!1sde!2sde!4v1491825275092" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>                        <div class="form-group has-error">
                            <div class="row">
                                <div class="col-xs-12 col-md-4">
                                    <label>Distanz (in km) *</label>
                                </div>
                                <div class="col-xs-6">
                                    <input type="number" step="0.01" name="trip_distance" id="trip_distance" class="form-control" placeholder=""
                                           value="<?php
                                           if (isset($trip_distance)) {
                                               echo $trip_distance;
                                           } ?>" required/>
                                </div>
                                <div class="col-xs-2">
                                    302.88
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-4">
                                    Kosten:
                                </div>
                                <div class="col-xs-6">
                                    <h2><?php echo $calcclass->trip($trip_distance, $trip_vehicle); ?> Euro</h2>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-12 col-md-4">
                                    <label>Firma / Gesprächspartner / Zweck</label>
                                </div>
                                <div class="col-xs-6">
                                    <textarea name="trip_partner" class="form-control" rows="5" maxlength="104" id="trip_partner" placeholder=""><?php
                                        if (isset($trip_partner)) {
                                            $trip_partner = str_replace("\\r", "", $trip_partner);
                                            $trip_partner = str_replace("\\n", "", $trip_partner);
                                            echo stripslashes($trip_partner);
                                        } ?></textarea>
                                </div>
                                <div class="col-xs-12 col-md-2">
                                    <i>Einsatz intern (Heilbronn)</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade in" id="expense">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="hidden-xs col-md-4">
                                <h2>Label</h2>
                            </div>
                            <div class="hidden-xs col-md-6">
                                <h2>Feld</h2>
                            </div>
                            <div class="hidden-xs col-md-2">
                                <h2>Beispiel</h2>
                            </div>
                        </div>
                        <?php if (isset($last_object_id)) { ?>
                            <input type="hidden" name="last_object_id" id="last_object_id" class="form-control"
                                   placeholder="" value="<?php
                            echo $last_object_id; ?>"/>
                            <?php
                        }
                        ?>
                        <div class="form-group has-error">
                            <div class="row">
                                <div class="col-xs-12 col-md-4">
                                    <label>Abrechnungszeitraum *</label>
                                </div>
                                <div class="col-xs-6">
                                    <div class="input-append date" id="datepicker" data-date="03.2017"
                                                           data-date-format="mm-yyyy">
                                        <input type="text" name="expense_month" id="datepicker2" class="form-control"
                                               placeholder="03.2017" maxlength="7" value="<?php
                                        if (isset($expense_month)) {
                                            echo $expense_month;
                                        } ?>" required/>
                                    </div>
                                    <script type="text/javascript">
                                        $(function () {
                                            $("#datepicker2").datepicker( {
                                                format: "mm.yyyy",
                                                startView: "months",
                                                minViewMode: "months"
                                            });
                                        });
                                    </script>
                                </div>

                                <div class="col-xs-12 col-md-2">
                                    <i>03.2017</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade in" id="imprint">
                    <h2>proQrent GmbH // Lingner.People</h2>
                    <br /><br />
                    <p>Otto-Lilienthal-Str. 36</p>
                    <p>70174 Böblingen</p>
                    <p>Tel. <a href="tel:07116404039">0711. 640 40 39</a></p>
                    <p>Fax 0711. 60 29 50</p>
                    <p><a href="http://proqrent.de/impressum/" target="_blank">www.proqrent.de</a></p>
                    <p><a href="mailto:j.wetzel@lingner.com?subject=proQrent%20Spesenabrechnung">j.wetzel@lingner.com</a></p>
                </div>
                <button type="submit" name="formsave" id="submitButton" value="0">Absenden</button>
                <span class="devc">dieser button wird noch anders gelayoutet und die position wechselt sich. er sendet immer alle daten des formulars ab, nicht nur die eines reiters!</span>

    </form>
    <div role="tabpanel" class="tab-pane <?php if ($last_object_id > 0) { ?> active <?php } ?> fade in" id="bill">
        <?php
        $billCounter = 0;
        echo "<table class='table'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Nr.</th>";
        echo "<th>Datum</th>";
        echo "<th>Bereich</th>";
        echo "<th>Typ</th>";
        echo "<th>Betrag</th>";
        echo "<th>Rechnung</th>";
        echo "</tr>";
        echo "</thead>";
        foreach($bill as $item) {
            $burl = $bill[$billCounter]['url'];
            $bid = $bill[$billCounter]['object_id'];
            $bdate = $textclass->dateger($bill[$billCounter]['date']);
            $barea = $bill[$billCounter]['area'];
            $bamount = $textclass->text($bill[$billCounter]['amount']);
            $btype = $bill[$billCounter]['type'];
            $buser = $bill[$billCounter]['user'];
            echo "<tr>";
            echo "<td>$bid</td>";
            echo "<td>$bdate</td>";
            echo "<td>$barea</td>";
            echo "<td>$btype</td>";
            echo "<td>$bamount €</td>";
            echo "<td><a href='bill/$buser/$bid/$burl' target='_blank'>$burl</a></td>";
            echo "</tr>";
        }
        echo "</table>";
        $billCounter++;
        ?>
        <div id="dropz">

            <?php
            if (('POST' == $_SERVER['REQUEST_METHOD']) AND ($_FILES['file']['name'] != "")) {
                $upnow = $fileclass->filedate();
                $user_id = $_SESSION['user_id'];
                $upnow = "";
                $filename = $_FILES['file']['tmp_name'];
//                list($width, $height) = getimagesize($filename);
                // IF IMAGE DO THIS
                $filenameOriginal = $_FILES['file']['name'];
                $sizes = getimagesize($filename);
                $image_width = $sizes[0];

                    $filename = $fileclass->filename($_FILES['file']['name']);
    
                    $newfolder = "bill/$user_id";
                    mkdir($newfolder, 0777);
                    $newfolder = "bill/$user_id/$last_object_id";
                    mkdir($newfolder, 0777);
                    $filenameFolder = "$newfolder/" . $filename;
    //                            $thumb = "../backend/phpThumb2/thumbnail/$upnow" . $_FILES['file']['name'];
                    $bill_date = $_POST['bill_date'];
                    $bill_area = $_POST['bill_area'];
                    $bill_amount = $_POST['bill_amount'];
                    $bill_type = $_POST['bill_type'];
                    $filename = "$newfolder/" . $_FILES['file']['name'];
    
                    move_uploaded_file($_FILES['file']['tmp_name'], $filename);

                $filenameclear = $_FILES['file']['name'];
                // INSERT IMAGES INTO DATABASE
                $sql_img = "INSERT INTO
                                    bill (object_id, user_id, url, date, area, amount, type)
                                    VALUES
                                    ('$last_object_id', '$user_id', '$filenameclear', '$bill_date', '$bill_area', '$bill_amount', '$bill_type')";
                $db->query($sql_img);
                echo $db->error;



            }
            $billCounter = 1;
            $sql = "SELECT * FROM bill WHERE user_id = '$user_id' AND object_id = '$object_id'";
            $result = $db->query($sql);
            if (!$result) {
                die ('Etwas stimmte mit dem Query nicht: ' . $db->error);
            }
            while ($row = $result->fetch_assoc()) {
                $bill[$billCounter]['url_object'] = $row['url'];
                $bill[$billCounter]['object_id'] = $row['object_id'];
                $bill[$billCounter]['date'] = $row['date'];
                $bill[$billCounter]['area'] = $row['area'];
                $bill[$billCounter]['amount'] = $row['amount'];
                $bill[$billCounter]['type'] = $row['type'];
                $billCounter++;
            }
            ?>

            <hr>
            <a name="upload"></a>
            <form action="<?php echo $serverDiff; ?>/index.php?id=<?php echo $object_id; ?>"
                  class="dropzone"
                  id="my-awesome-dropzone">
                <div class="row">
                    <div class="col-xs-12 col-md-4">
                        <label>Rechnungsdatum *
                            <input type="date" name="bill_date" id="bill_date"
                                   placeholder=""/>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <select name="bill_area" size="1" class="form-control selectpicker" id="bill_area">
                            <option value="1" selected>R&D</option>
                            <option value="2">CSD</option>
                            <option value="3">QA</option>
                            <option value="4">Allgemein</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-4">
                                            <label>Rechnunsbetrag (brutto) *
                                                <input type="number" name="bill_amount" id="bill_amount"
                                                       placeholder="100.99"/>
                                            </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-4">
                                            <label>Rechnungsart *
                                                <input type="text" name="bill_type" id="bill_type"
                                                       placeholder="Zugticket HN-S"/>
                                            </label>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-xs-6">
                    <h2>Bild 1</h2>
                    <div class="bild" id="showBild1">
                        <img src="images/photos/<?php
                        if ($img1 == "muster1.jpg") { echo "$img1"; } else {
                        echo "$user_id/$object_id/$img1"; }
                        ?>" width="100%">
                    </div>
                </div>
                <div class="col-xs-6">
                    <h2>Bild 2</h2>
                    <div class="bild bild2" id="showBild2">
                        <img src="images/photos/<?php
                        if ($img2 == "muster2.jpg") { echo "$img2"; } else {
                            echo "$user_id/$object_id/$img2"; }
                        ?>" width="100%">
                    </div>
                </div>
            </div>
            <br />
            <p>Rechnungen können Sie einfach per Drag & Drop (Maustaste im Explorer gedrückt halten und dann in die Box
                unten im Browser ziehen) hochladen.
                <br/>
                Oder Sie klicken die Box und wählen ein oder mehrere Rechnungen auf Ihrer Festplatte / Ihrem Mobilgerät aus.
                Die Dokumente werden beim Hochladen in der Datenbank gespeichert. Ein zusätzliches Absenden danach ist nicht notwendig.</p>
        </div>
    </div>

</div>

    </div>
    <button type="submit" id="submitButtonVisible">Absenden</button>
<?php }
if (('POST' == $_SERVER['REQUEST_METHOD']) AND (isset($_POST['formsave']))) {
    echo "<a href=\"pdf/pdf_export_portrait.php?unique_object_key=$unique_object_key\" target=\"_blank\"><button>PDF Layout</button></a>";
    echo "<a href=\"pdf/pdf_export.php?unique_object_key=$unique_object_key\" target=\"_blank\"><button>PDF Anmeldung</button></a>";
}
?>

</div>
<div class="col-xs-12 footer" style="display:none;">
    <h2>Hugo-Häring Auszeichnung Anmeldeportal</h2>
    <p><b>Landesverband</b></p>
    <p><b>Baden-Württemberg</b></p>
    <br /><br />
    <p>Zeppelin Carré</p>
    <p>Friedrichstr. 5</p>
    <p>71034 Stuttgart</p>
    <p>Tel. <a href="tel:07116404039">0711. 640 40 39</a></p>
    <p>Fax 0711. 60 29 50</p>
    <p><a href="http://bda-bawue.de/impressum/" target="_blank">www.bda-bawue.de</a></p>
    <p><a href="mailto:info@bda-bawue.de?subject=HH-Auszeichnung%20Anmeldeportal">Info@bda-bawue.de</a></p>
</div>
</body>
<script>
    $('#tabs-menu-box a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
    Dropzone.options.myAwesomeDropzone = {
        init: function () {
            this.on("complete", function (file) {

                console.log(file["name"]);
                filename = file["name"];
                $('#imgLayout').val(); // will give you the selected value of the drop down element. Use this to get the selected options text.
                var imgLayout = $('#imgLayout option:selected').text();
                console.log(imgLayout);
                alert(imgLayout + " hinzugefügt. ("+ filename +")");
                if (imgLayout == "Bild 1 (links)") {
                    $('#dropz #showBild1').append('<a href="bill/<?php echo "$user_id/$last_object_id/"; ?>' + filename + '" class="billLink" width="100%" />');
                    document.getElementById("imgLayout").selectedIndex = 1;
                }
                else {
                    $('#dropz #showBild2').html('<img src="images/photos/<?php echo "$user_id/$last_object_id/"; ?>' + filename + '" class="smallImg" width="100%" />');
                    document.getElementById("imgLayout").selectedIndex = 0;
                }
            });

        }
    };

    $(function () {


        $("#my-awesome-dropzone").on("complete", function (file) {
            console.log(file);
//            $("body").css('background-image', 'url(' + file + ')');
        })
    });
    $('#submitButton').click(function () {
        $('input:invalid').each(function () {
            // Find the tab-pane that this element is inside, and get the id
            var $closest = $(this).closest('.tab-pane');
            var id = $closest.attr('id');

            // Find the link that corresponds to the pane and have it show
            $('.nav a[href="#' + id + '"]').tab('show');

            // Only want to do it once
            return false;
        });
    });
    $('#submitButtonVisible').click(function () {
        $('#submitButton').click();
    });
    // disable mousewheel on a input number field when in focus
    // (to prevent Cromium browsers change the value when scrolling)
    $('form').on('focus', 'input[type=number]', function (e) {
        $(this).on('mousewheel.disableScroll', function (e) {
            e.preventDefault()
        })
    });
    $('form').on('blur', 'input[type=number]', function (e) {
        $(this).off('mousewheel.disableScroll')
    });
    window.addEventListener("dragover",function(e){
        e = e || event;
        e.preventDefault();
    },false);
    window.addEventListener("drop",function(e){
        e = e || event;
        e.preventDefault();
    },false);


</script>
</html>
