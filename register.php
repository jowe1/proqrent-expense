<?php
/**
 * Created by PhpStorm.
 * User: Lightningsoul
 * Date: 24.03.2017
 * Time: 09:57
 */

/* create user manually
 * $password = password_hash('Test937!ifG', PASSWORD_DEFAULT);
$user_token = md5(microtime().rand());
$sql = "INSERT INTO
                user (token, user, email, password, admin, date)
        	    VALUES
                ('$user_token', 'angenendt@ifgroup.org', 'angenendt@ifgroup.org', '$password', '1', NOW())";
if ($db->query($sql) === TRUE) {
    $last_object_id = $db->insert_id;
    $last_user_object_id = $last_object_id;
    echo "<h2>Sie sind registriert mit der Nutzer ID: " . $last_object_id."</h2>";
    include_once("formmailer.php");
}*/
if (('POST' == $_SERVER['REQUEST_METHOD']) AND (isset($_POST['Register']))) {
    // INSERT INTO TABLE OBJECT IN DATABASE
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = password_hash($password, PASSWORD_DEFAULT);
    $user_token = md5(microtime().rand());
    $sql = "INSERT INTO
                user (token, user, email, password)
        	    VALUES
                ('$user_token', '$email', '$email', '$password')";
    if ($db->query($sql) === TRUE) {
        $last_object_id = $db->insert_id;
        $last_user_object_id = $last_object_id;
        echo "<h2>Sie sind registriert mit der Nutzer ID: " . $last_object_id."</h2>
        <h3>Sie haben eine Mail zum Aktivieren des Passwortes erhalten. Schauen Sie bitte in Ihrem E-Mail Spamordner, falls Sie keine im Posteingang finden.</h3>";
        include_once("formmailer.php");
    }
    else {
        die ('<h2>Etwas stimmte mit dem Query nicht: ' . $db->error.'</h2>');
    }
}
if (('POST' == $_SERVER['REQUEST_METHOD']) AND (isset($_POST['Changepass']))) {
    // INSERT INTO TABLE OBJECT IN DATABASE
    $user_email = $_POST['email'];
    $password = $_POST['password'];
    $password = password_hash($password, PASSWORD_DEFAULT);
    $hidden_user_token = $_POST['token'];
    $sql_user = "UPDATE user SET 
                password = '$password'
                WHERE user = '$user_email'
                AND token = '$hidden_user_token'";
    if ($db->query($sql_user) === TRUE) {
        echo "<h2>Sie haben Ihr Passwort erfolgreich geändert.</h2>";
        include_once("formmailer.php");
    }
}

if (('POST' == $_SERVER['REQUEST_METHOD']) AND (isset($_POST['passwordreset']))) {
    // INSERT INTO TABLE OBJECT IN DATABASE
    $user_email = $_POST['email'];
    $user_token = md5(microtime().rand());
    $sql_user = "UPDATE user SET 
                token = '$user_token'
                WHERE email = '$user_email'";
    if ($db->query($sql_user) === TRUE) {
        echo "<h2>Sie haben eine Mail zum Zurücksetzen des Passwortes erhalten. Schauen Sie bitte in Ihrem E-Mail Spamordner, falls Sie keine im Posteingang finden.</h2>";
        include_once("formmailer.php");
    }
}

if ($_GET['action'] == "passwordreset") {
    $pw_user_token = $_GET['token'];
//    var_dump($_GET);
//    echo $pw_user_token;
        if ($pw_user_token != "") {
        $sql = "SELECT * FROM user WHERE token = '$pw_user_token'";

        $result = $db->query($sql);
        if (!$result) {
            die ('Etwas stimmte mit dem Query nicht: ' . $db->error);
        }
        while ($row = $result->fetch_assoc()) {
            $user_email = $row['email'];
        }
    }
}
if (isset($_GET['activate'])) {
    $user_token_verify = $_GET['activate'];
    $sql_user = "UPDATE user SET 
                token = '',
                active = 1
                WHERE token = '$user_token_verify'";
    $db->query($sql_user);
    echo $db->error;
    echo "<h3>Nutzer erfolgreich aktiviert.</h3>";
}
?>

<form method="post" action="#">
    <div class="col-xs-12">
        <div class="form-group has-error">
            <label for="inputEmail" class="control-label">E-Mail</label>
            <?php if ($_GET['action'] != "passwordreset") { ?>
            <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Email" data-error="Diese E-Mail Adresse ist nicht valide (bspw.: max.mustermann@web.de)" required>
            <?php } else { ?>
            <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Email"
                   value="<?php echo $user_email; ?>" readonly="readonly">
                <input type="hidden" class="form-control" id="token" name="token"
                       value="<?php echo $pw_user_token; ?>">
            <?php } ?>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group has-error">
            <label for="inputPassword" class="control-label">Passwort</label>
            <div class="form-inline row">
                <div class="form-group col-sm-12">
                    <input type="password" name="password" data-minlength="6" class="form-control" id="inputPassword" placeholder="Passwort" required>
                    <div class="help-block">Mindestens 6 Zeichen</div>
                </div>
                <div class="form-group col-sm-12">
                    <input type="password" class="form-control" id="inputPasswordConfirm" data-match="#inputPassword" data-match-error="Die Passwörter sind nicht gleich" placeholder="Passwort wiederholen" required>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>
        <br />
        <input type="submit" name="<?php if (isset($pw_user_token)) { echo 'Changepass'; } else { echo 'Register'; } ?>" class="special" value="<?php if (isset($pw_user_token)) { echo 'Changepass'; } else { echo 'Registrieren'; } ?>" />
    </div>
</form>
<br />

<?php if (($_GET['action'] == "passwordreset") AND (!isset($pw_user_token))) { ?>
    <form method="post" action="#">
        <div class="col-xs-12">
            <div class="form-group has-error">
                <label for="inputEmail" class="control-label">E-Mail</label>
                <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Email" data-error="Diese E-Mail Adresse ist nicht valide (bspw.: max.mustermann@web.de)" required>
            </div>
            <input type="hidden" class="form-control" id="token" name="token"
                   value="<?php echo $pw_user_token; ?>">
            <input type="submit" name="passwordreset" class="special" value="Passwort zurücksetzen" />
        </div>
    </form>
<?php } else { ?>
    <a href="?action=passwordreset">Passwort zurücksetzen</a>
<?php } ?>
