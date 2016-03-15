<!DOCTYPE html>
<html>
<head>
  <title>ZNAMKARIJA</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="script.js"></script>
</head>
<body>

<div id="website">
	<div>
		<ul class="nav">
			<li style="float:left"><img class="banner" src="znamke.jpg"></img></li> <!--slika banner-->
		</ul>
	</div>

	<!--navigacija-->
	<div>
		<ul class="nav1">
			<li id="prijava_tab" onclick="prikazi(this)" style="float:right"><a href="#prijava">Prijava</a></li>
			<li id="registracija_tab" onclick="prikazi(this)" style="float:right"><a href="#registracija">Registracija</a></li>
			<li id="glavna_tab" onclick="prikazi(this)" style="float:right"><a href="#home">Glavna stran</a></li>
			<li id="uporabnik_tab" onclick="prikazi(this)" style="float:right"><a href="#home">Moj profil</a></li>
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
		<button type="button" class="btn3" id="backButton"><</button>
		<table style='margin-left:40px; margin-right:40px;' id="znamka_podatki">
		</table>
	</div>

	<!--prijava-->
	<div id="prijava_tabcontent" class="tabContent">
		<div style="box-shadow: -5px 5px 5px -5px #333, 5px 5px 5px -5px #333;border-radius: 5px;margin-bottom:70px; margin-top:70px; margin-left:200px; width:400px; height:500px; background-color: #333;">
			<h1>PRIJAVA</h1>
			<input type="text" placeholder="Uporabniško ime*" style="font-family: comforta;margin-left:37px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" />
			<input type="password" placeholder="Geslo*" style="font-family: comforta;margin-top:25px;margin-left:37px;text-align:center;padding:10px;width:300px; height:20px;font-size:18px;border:1px solid #3DD9C9; background-color:#333; color: #d9d9d9" />      
			<button type="button" class="btn">PRIJAVA</button>
			<h3>ali: </h3>
			<button type="button" class="btn1">Google</button>
			<button type="button" class="btn2">Facebook</button>
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
	</div>

</div>


</body>
</html>
