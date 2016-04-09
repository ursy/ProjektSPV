<?php
    error_reporting(-1);
    ini_set('display_errors', 'On');
    session_start();
    require_once './facebook-php-sdk-v4-5.0.0/src/Facebook/autoload.php';
    require_once './config.php';
    //destroy facebook session if user clicks reset
    $fb = new Facebook\Facebook([
        'app_id' => $appId,
        'app_secret' => $appSecret,
        'default_graph_version' => 'v2.5'
    ]);

    $helper = $fb->getRedirectLoginHelper();
    $permissions = ['email']; // optional
    $loginUrl = $helper->getLoginUrl('http://znamkarija.hotbit.eu:88/login-callback-facebook.php', $permissions);

?>
<!DOCTYPE html>
<html>
<head>
  <title>ZNAMKARIJA</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script>
        var userID = "<?php  echo (isset($_SESSION['user_id']))? $_SESSION['user_id'] :"-1";  ?>";
    </script>
  <script src="script.js"></script>
</head>
<body>
    <div id="website">
        <div>
            <ul class="nav">
                <li style="float:left"><img class="banner" src="znamke.jpg"/></li> <!--slika banner-->
            </ul>
        </div>

	<!--navigacija-->
	<div>
		<ul class="nav1">
            <?php if(isset($_SESSION['user_id'])) {?>
                <li id="odjava_tab" onclick="prikazi(this)" style="float:right"><a href="#odjava">Odjava</a></li>
            <?php } else {?>
                <li id="prijava_tab" onclick="prikazi(this)" style="float:right"><a href="#prijava">Prijava</a></li>
            <?php }?>
			<li id="registracija_tab" onclick="prikazi(this)" style="float:right"><a href="#registracija">Registracija</a></li>
			<li id="glavna_tab" onclick="prikazi(this)" style="float:right"><a href="#home">Glavna stran</a></li>
			<li id="uporabnik_tab" onclick="prikazi(this)" style="float:right"><a href="#profile">Moj profil</a></li>
			<li id="novaznamka_tab" onclick="prikazi(this)" style="float:right"><a href="#home">Nova znamka</a></li>
		</ul>
	</div>

	<!--glavna stran(vsebina)-->
	<div id="glavna_tabcontent" class="tabContent active">
		<label>Izberite leto znamke: </label>
		<select id="leto">
			<?php
				include_once 'connToDatabase.php';

				$query = "SELECT distinct leto FROM Znamka ORDER BY leto DESC" ;
				$result = mysqli_query($conn, $query);
				while($row = mysqli_fetch_assoc( $result ))
				{
					echo '<option value="'.$row['leto'].'">' . $row['leto'] . '</option>';
				}

			?>
		</select>
		<hr>
	</div>

	<!--prikaz znamke za vsako leto-->
	<div id="leto_znamke" class="tabContent">
		<table style='margin-left:40px;margin-right:40px;' id="znamka" cellspacing="10">
		</table>
	</div>
    
	<!--prikaz podatkov o posamezni znamki-->
	<div id="znamka_profil" class="tabContent">
		<button type="button" class="btn3" id="backButton"><img src="images/nazaj.png"/></button>
		<button type="button" class="btn3" id="imamZ_btn" title="Znamko že imam"><img src="images/kljukica.png"/></button>
		<button type="button" class="btn3" id="nimamZ_btn" title="Znamke nimam"><img src="images/kriz.png"/></button>
		<button type="button" class="btn3" id="odvecZ_btn" title="Znamko imam odveč in jo želim dati v menjavo"><img src="images/menjava.png"/></button>
		<button type="button" class="btn3" id="niodvecZ_btn" title="Znamke nimam odveč in je ne želim nuditi v menjavi"><img src="images/ne-menjava.png"/></button>
		<button type="button" class="btn3" id="osebeOdvecButton" title="Prikaži ljudi, ki imajo to znamko odveč"><img src="images/osebe.png"/></button>
	
		<div id="odvec_znamke" style="margin-top:20px; margin-left:20px;">
            <table id="odvec"></table>
        </div>
		<table style='margin-left:40px; margin-right:40px;' id="znamka_podatki"></table>
	</div>
	
	<!--prikaz zamenjava znamk-->
	<div id="znamka_zamenjava" class="tabContent">
		<button type="button" class="btn3" id="backButton1"><img src="images/nazaj.png"/></button>
		<table style='margin-left:40px; margin-right:40px; width:720px;' id="chat_content" cellpadding="10" cellspacing="0"></table>
	</div> 

    <div class="fb-login-button" data-max-rows="1" data-size="large" data-show-faces="false" data-auto-logout-link="false"></div>

	<!--prijava-->
	<div id="prijava_tabcontent" class="tabContent">
		<div style="box-shadow: -5px 5px 5px -5px #333, 5px 5px 5px -5px #333;border-radius: 5px;margin-bottom:70px; margin-top:70px; margin-left:200px; width:400px; height:500px; background-color: #333;">
			<h1>PRIJAVA</h1>
            <input type="text" placeholder="Uporabniško ime*" style="font-family: comforta;margin-left:37px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" />
            <input type="password" placeholder="Geslo*" style="font-family: comforta;margin-top:25px;margin-left:37px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" />
            <button type="button" class="btn">PRIJAVA</button>
            <h3>ali: </h3>
            <button type="button" class="btn1">Google</button>
            <?php echo '<a href="' . $loginUrl . '"><button type="button" class="btn2" >Facebook</button></a>';?>
		</div>
	</div>

    <!--odjava-->
	<div id="odjava_tabcontent" class="tabContent">
        <div style="box-shadow: -5px 5px 5px -5px #333, 5px 5px 5px -5px #333;border-radius: 5px;margin-bottom:70px; margin-top:70px; margin-left:200px; width:400px; height:100px; background-color: #333;">
            <a href="logout.php?logout"><button type="button" class="btn">LOGOUT</button></a>
        </div>
	</div>

	<!--registracija-->
	<div id="registracija_tabcontent" class="tabContent">
		<div style="box-shadow: -5px 5px 5px -5px #333, 5px 5px 5px -5px #333;border-radius: 5px;margin-bottom:70px; margin-top:70px; margin-left:200px; width:400px; height:570px; background-color: #333;">
			<h1>REGISTRACIJA</h1>
			<input type="text" placeholder="Ime*" style="font-family: comforta;margin-left:37px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" />
			<input type="text" placeholder="Priimek*" style="font-family: comforta;margin-top:25px;margin-left:37px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" />
			<input type="text" placeholder="Kraj*" style="font-family: comforta;margin-top:25px;margin-left:37px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" />
			<input type="text" placeholder="Uporabniško ime*" style="font-family: comforta;margin-top:25px;margin-left:37px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" />
			<input type="password" placeholder="Geslo*" style="font-family: comforta;margin-top:25px;margin-left:37px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" />
			<select id="spol">
				<option value="moski">Moški</option>
				<option value="zenski">Ženski</option>
			</select>
			<button type="button" class="btn">REGISTRACIJA</button>
		</div>
	</div>

	<!--uporabnikov profil-->
	<div id="uporabnik_tabcontent" class="tabContent">
		<!--<button type="button" class="btn3" id="editProfileButton">Uredi</button>-->
		<table style='margin-left:40px; margin-right:40px; width:720px;' id="user_podatki">
		</table>
	</div>

	<!--urejanje uporabnikovega profila-->
	<div id="edit_uporabnik_tabcontent" class="tabContent">
		<div style="box-shadow: -5px 5px 5px -5px #333, 5px 5px 5px -5px #333;border-radius: 5px;margin-bottom:70px; margin-top:70px; margin-left:200px; width:400px; height:500px; background-color: #333;">
			<h1>Uredi profil</h1>
			<input type="text" placeholder="Ime*" style="font-family: comforta;margin-left:37px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" id='ime_uporabnika'/>
			<input type="text" placeholder="Priimek*" style="font-family: comforta;margin-top:25px;margin-left:37px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" id='priimek_uporabnika'/>
			<input type="text" placeholder="Kraj" style="font-family: comforta;margin-top:25px;margin-left:30px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" id='kraj_uporabnika'/>
			<input type="text" placeholder="E-mail" style="font-family: comforta;margin-top:25px;margin-left:37px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" id='email_uporabnika'/>
			<select id="spol_uporabnika">
				<option value="male">Moški</option>
				<option value="female">Ženski</option>
			</select>
			<button type="button" class="btn" id="editDoneButton">OK</button>
		</div>
	</div>

	<!--dodajanje novih znamk-->
	<div id="novaznamka_tabcontent" class="tabContent">
		<div style="box-shadow: -5px 5px 5px -5px #333, 5px 5px 5px -5px #333;border-radius: 5px;margin-bottom:70px; margin-top:70px; width:800px; height:660px; background-color: #333;">
			<h1>Dodaj novo znamko</h1>
			<input type="text" placeholder="Naslov*" style="font-family: comforta;margin-left:50px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" id='naslov_znamke'/>
			<input type="text" placeholder="Leto*" style="font-family: comforta;margin-top:25px;margin-left:50px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" id='leto_znamke'/>
			<input type="text" placeholder="Datum izdaje* npr. 30.12.2003" style="font-family: comforta;margin-top:25px;margin-left:50px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" id='datum_znamke'/>
			<input type="text" placeholder="Povezava do slike*" style="font-family: comforta;margin-top:25px;margin-left:50px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" id='slika_znamke'/>
			<input type="text" placeholder="Oblikovanje" style="font-family: comforta;margin-top:25px;margin-left:50px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" id='oblikovanje_znamke'/>
			<input type="text" placeholder="Motiv" style="font-family: comforta;margin-top:25px;margin-left:50px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" id='motiv_znamke'/>
			<input type="text" placeholder="Tisk" style="font-family: comforta;margin-top:25px;margin-left:50px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" id='tisk_znamke'/>
			<input type="text" placeholder="Izvedba" style="font-family: comforta;margin-top:25px;margin-left:50px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" id='izvedba_znamke'/>
			<input type="text" placeholder="Pola" style="font-family: comforta;margin-top:25px;margin-left:50px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" id='pola_znamke'/>
			<input type="text" placeholder="Papir" style="font-family: comforta;margin-top:25px;margin-left:50px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" id='papir_znamke'/>
			<input type="text" placeholder="Velikost" style="font-family: comforta;margin-top:25px;margin-left:50px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" id='velikost_znamke'/>
			<input type="text" placeholder="Zobci" style="font-family: comforta;margin-top:25px;margin-left:50px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" id='zobci_znamke'/>
			<input type="text" placeholder="Zobčanje" style="font-family: comforta;margin-top:25px;margin-left:50px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" id='zobcanje_znamke'/>
			<input type="text" placeholder="Opomba" style="font-family: comforta;margin-top:25px;margin-left:50px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" id='opomba_znamke'/>

			<button type="button" class="btn" id='vstavi_znamko_btn'>VSTAVI</button>
		</div>
	</div>
</div>

<?php
    if(isset($_GET['page'])){
        if ($_GET['page'] == "profile") {
            echo "<script>
                    $(document).ready(function() {
                        var element = document.getElementById('uporabnik_tab');
                        prikazi(element);
                    });
                </script>";
        }
    }
?>
</body>
</html>
