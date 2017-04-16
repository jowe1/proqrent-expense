<?php


/*	$conn = new mysqli("rdbms.strato.de", "U1278361", "DBArningStrat01X1", "DB1278361");
	if($conn->connect_error)
	{
		die("Datenbank nicht erreichbar ".$conn->connect_error);
	}*/

	$error="";
/*$generatepw = password_hash("adbda394§_1!", PASSWORD_DEFAULT);
echo $generatepw;*/
	//logout
	if (isset($_POST["logout"]))
	{
		$_SESSION["login"] = 0;
		$_SESSION["user"] = "";
		$user = "";
	}

	if (isset($_POST["user"]) && isset($_POST["pass"]))
	{
		//user versucht sich anzumelden
		$typeduser = $_POST["user"];
		$typedpass = $_POST["pass"];
		$typeduser = $db->real_escape_string($typeduser);
		$typedpass = $db->real_escape_string($typedpass);

//		$logindb = $conn->query("SELECT * FROM login_bewerbung where user LIKE '$typeduser';");

$sql = "SELECT * FROM user WHERE user  LIKE '$typeduser'";
$result = $db->query($sql);
if (!$result) {
	die ('Etwas stimmte mit dem Query nicht: ' . $db->error);
}
while ($row = $result->fetch_assoc()) {
	$login_user = $row['user'];
	$login_pw = $row['password'];
	$user_id = $row['id'];
	$admin_get = $row['admin'];
	$user_active = $row['active'];
}

		$sql = "SELECT * FROM user WHERE id = '$user_id'";

		$result = $db->query($sql);
		if (!$result) {
			die ('Etwas stimmte mit dem Query nicht: ' . $db->error);
		}
		while ($row = $result->fetch_assoc()) {
			$user_vorname = $row['vorname'];
			$user_name = $row['nachname'];
			$user_adresszeile1 = $row['adresszeile1'];
			$user_adresszeile2 = $row['adresszeile2'];
			$user_plz = $row['plz'];
			$user_ort = $row['ort'];
			$user_land = $row['land'];
			$user_tel1 = $row['telefon1'];
			$user_tel2 = $row['telefon2'];
			$user_email = $row['email'];
			$user_homepage = $row['homepage'];
			$user_anrede = $row['anrede'];
			$user_login = $row['user'];
			$user_active = $row['active'];
			echo "Nutzer ID: $user_id";
			if ($user_active == 0) { die("<h2>Sie müssen Ihren Nutzer Account aktivieren. Schauen Sie hierzu bitte in Ihrem E-Mail Postfach nach der entsprechenden Aktivierungsmail.</h2>"); }
		}
/*		echo "<br />";
echo $login_user;
		echo "<br />";
		echo $typedpass;*/
//		if($logindata = $logindb->fetch_array()) {
		if($login_user == $typeduser) {
			$hashedpassdb=$login_pw;
			if (password_verify($typedpass,$hashedpassdb))
			{
				//login war erfolgreich, wir merken uns den usernamen und die restlichen Daten in der Session
				$_SESSION["login"] = 1;
				$_SESSION["user"] = $typeduser;
				$_SESSION["user_id"] = $user_id;
				$_SESSION["admin"] = $admin_get;
				$user = $typeduser;
				// login loggen
			}
			else
			{
				$error="Bitte überprüfen Sie das Passwort.";
				$_SESSION["login"] = 0;
				$_SESSION["user"] = "";
				$user = "";
			}
		}
		else
		{
			$error = "Bitte überprüfen Sie den Benutzernamen.";
			$_SESSION["login"] = 0;
			$_SESSION["user"] = "";
			$user = "";
		}
	}

	if ($_SESSION["login"] == 0)
	{
		?>


		<h3 class="login">Login</h3>

		<?php if($error != "") { ?>
			<p style="color:red"><?php echo"$error"; ?></p>
		<?php } ?>
		
		<form method="post" action="#">
			<div class="col-xs-12">
				<div class="row">
					<div class="col-xs-12"><input type="text" name="user" id="user" placeholder="Benutzername (E-Mail)" /></div>
				</div>
				<div class="row">
					<div class="col-xs-12"><input type="password" name="pass" id="pass" placeholder="Passwort" /></div>
				</div>
				<br />
					<input type="submit" class="special" value="Login" />
			</div>
		</form>
		
		<?php
	}
	else
	{
		$user = $_SESSION["user"];
//		echo var_dump($_SESSION);
		if (isset($_SESSION["admin"])) {
			$admin = $_SESSION["admin"];
		}
		?>
			
		<!--hier html für eingeloggte user-->

		<form method="post" action="#">
			<input type="hidden" name="logout" id="logout" />
			Sie sind angemeldet als <i><?php echo "$user"; ?></i>
			<br />
			<input type="submit" class="btn btn-primary logout" value="Logout" />
		</form>
		<p><?php if ($admin == 1) { echo "<b>Administrator</b>"; } ?></p>
		<p><?php if ($admin == 2) { echo "<b>Super-Administrator</b>"; } ?></p>
		<?php if (strpos($thisurl, "kontrollzentrum") > 0) { ?>
		<a href="index.php" title="Neues Projekt anlegen">Neues Projekt</a><br/>
			<?php } else { ?>
			<a href="kontrollzentrum.php" title="Bearbeiten Sie Ihre Objekte, Userdaten etc. hier">Kontrollzentrum</a><br/>

			<?php
	} ?>
<?php
/*if ($_GET['dev'] != "einblenden") {
	*/?><!--
		<a href="?dev=einblenden" title="Entwicklerkommentare einblenden">Entwicklerkommentare einblenden</a>
	<?php /*}
		else {
			*/?>
			<a href="?dev=ausblenden" title="Entwicklerkommentare ausblenden">Entwicklerkommentare ausblenden</a>
			--><?php
/*		} */?>
		<?php
	}

?>
