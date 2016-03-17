<?php
include_once 'connToDatabase.php';

//vstavljanje nove znamke
if ($_POST['method'] == "vstavi_znamko")
{
	$naslov = $_POST['naslov'];
	
	$time = strtotime($_POST['datum']);
	$newformat = date('Y-m-d',$time);
	$datum = $newformat;
	echo $datum;
	
	$leto = $_POST['leto'];
	$slika = $_POST['slika'];
	$oblikovanje = $_POST['oblikovanje'];
	$motiv = $_POST['motiv'];
	$tisk = $_POST['tisk'];
	$izvedba = $_POST['izvedba'];
	$pola = $_POST['pola'];
	$papir = $_POST['papir'];
	$velikost = $_POST['velikost'];
	$zobci = $_POST['zobci'];
	$zobcanje = $_POST['zobcanje'];
	$opomba = $_POST['opomba'];
	$query = "INSERT INTO Znamka (naslov, leto, datum_izdaje, slika, oblikovanje, motiv, tisk, izvedba, pola, papir, velikost, zobci, zobcanje, opomba) VALUES ('$naslov', '$leto', '$datum', '$slika', '$oblikovanje', '$motiv', '$tisk', '$izvedba', '$pola', '$papir', '$velikost', '$zobci', '$zobcanje', '$opomba')";
	if (mysqli_query($conn, $query)) {
        echo "OK";
    } else {
        echo "Error";
    }
}

//oznaci znamko z imam, nimam, odvec
if ($_POST['method'] == "oznaci_znamko")
{
	$oznacba = $_POST["oznacba"];
	$id_upor = $_POST["uporabnik"];
	$id_znamka = $_POST["znamka"];
	
	$imam = 0;
	$nimam = 0;
	$odvec = 0;
	
	if ($oznacba == "imam") {
		$imam = 1;
	}
	else if ($oznacba == "nimam") {
		$nimam = 1;
	}
	else if ($oznacba == "odvec") {
		$odvec = 1;
	}

	$query = "SELECT * FROM znamka_uporabnik WHERE ID_znamka = '$id_znamka' AND ID_uporabnik = '$id_upor'";
	$result = mysqli_query($conn, $query);

	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		
		if ($oznacba == "imam") {
			if ($row['ima'] == 0) {
				$imam = 1;
				$nimam = 0;
			}
			else {
				$imam = 0;
				$nimam = 1;
			}
			$odvec = $row['odvec'];
			$q1 = "UPDATE znamka_uporabnik SET ima = '$imam', nima = '$nimam' WHERE ID_znamka = '$id_znamka' AND ID_uporabnik = '$id_upor'";
		}
		else if ($oznacba == "nimam") {
			if ($row['nima'] == 0) {
				$imam = 0;
				$nimam = 1;
			}
			else {
				$imam = 1;
				$nimam = 0;
			}
			$odvec = $row['odvec'];
        	$q1 = "UPDATE znamka_uporabnik SET ima = '$imam', nima = '$nimam' WHERE ID_znamka = '$id_znamka' AND ID_uporabnik = '$id_upor'";
		}
		else if ($oznacba == "odvec"){
			if ($row['odvec'] == 0) {
				$odvec = 1;
			}
			else {
				$odvec = 0;
			}
			$imam = $row['ima'];
			$nimam = $row['nima'];
			$q1 = "UPDATE znamka_uporabnik SET odvec = '$odvec' WHERE ID_znamka = '$id_znamka' AND ID_uporabnik = '$id_upor'";
		}
        if (mysqli_query($conn, $q1)) {
            echo("$imam-$nimam-$odvec");
        } else {
            echo "update Error'$imam', '$nimam', '$odvec'";
        }
    }
    else 
    {
        $q1 = "INSERT INTO znamka_uporabnik (ID_uporabnik, ID_znamka, ima, nima, odvec) VALUES ('$id_upor', '$id_znamka', '$imam', '$nimam', '$odvec')";
        if (mysqli_query($conn, $q1)) {
            echo("$imam-$nimam-$odvec");
        } else {
            echo "insert Error '$imam', '$nimam', '$odvec'";
        }
    }
}

//prikaz oznacb znamke za uporabnika
if ($_POST['method'] == "get_znamka_user")
{
	$id_upor = $_POST["id_user"];
	$id_znamka = $_POST["id_znamka"];
	
	$query = "SELECT * FROM znamka_uporabnik WHERE ID_znamka = '$id_znamka' AND ID_uporabnik = '$id_upor'";
	$result = mysqli_query($conn, $query);

	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$ima = $row['ima'];
		$nima = $row['nima'];
		$odvec = $row['odvec'];
		echo("$ima-$nima-$odvec");
	}
}

//prikaz vseh znamk za vsako leto
if ($_POST['method'] == "prikaz_znamke")
{
	$id1 = $_POST["id"];
	$query = "SELECT ID_znamke, slika, naslov, datum_izdaje FROM Znamka WHERE leto = $id1" ;
	$result = mysqli_query($conn, $query);

	$i = 0; 
	while ($row = mysqli_fetch_assoc($result))
	{
		if($i == 0) {
			echo "<tr>";
		}

		echo "<td class='znamke_prikaz' ID='" . $row["ID_znamke"] . "'><img width='90' height='130' src='". $row['slika'] . "'/></td><br>
		<td class='znamke_prikaz' ID='" . $row["ID_znamke"] . "' style='background-color: #FBFBFB;'><span style='font-family: comforta;font-size:14px; color: #333;'>" . $row["naslov"] . "</span><br><br><br><br>
		<span style='font-family:comforta;font-size:14px;color: #A58600;'>".$row["datum_izdaje"]."</span>
		</td>";
				
		if(++$i == 2) {
			echo "</tr>";
			$i = 0;
		}	
	}
			
	if($i % 2 > 0) {
		echo "</tr>"; 
	}
}

//prikaz podatkov o posamezni znamki
if ($_POST['method'] == "z_podatki")
{
	$naslov = $_POST['ime_znamka'];
    $id =  $_POST['znamka_id'];
        
	if ($id != "-")
        $q = "SELECT slika, naslov, datum_izdaje, oblikovanje, motiv, tisk, izvedba, pola, papir, velikost, zobci, zobcanje, opomba FROM Znamka WHERE ID_znamke = '$id'";
    else 
		$q = "SELECT slika, naslov, datum_izdaje, oblikovanje, motiv, tisk, izvedba, pola, papir, velikost, zobci, zobcanje, opomba FROM Znamka WHERE naslov = '$naslov'";
	
	$result = mysqli_query($conn, $q);
	
	echo "<tr id='$id' class='podatki'>";
	
	while ($row = mysqli_fetch_assoc($result))
	{
		$str = ""; 
		if ($row['slika'] != "") {
			$str .= "<td><img style='box-shadow: 0px 0px 10px black;' src='". $row['slika'] . "'/></td>";
		}
		$str .= "<td>";
		if ($row["naslov"] != "") {
			$str .= "<span style='font-family: comforta;font-size:26px; color: #333;'>" . $row["naslov"] . "</span><br><br><br>";
		}
		if ($row["datum_izdaje"] != "") {
			$str .= "<span style='font-family: comforta;font-size:14px; color: #333;'><span style='color:#119091;'>Datum izdaje: </span>".$row["datum_izdaje"]."</span><hr style='margin-left:0px;margin-right:0px;'>";
		}
		if ($row["oblikovanje"] != "") {
			$str .= "<span style='font-family: comforta;font-size:14px; color: #333;'><span style='color:#119091;'>Oblikovanje: </span>".$row["oblikovanje"]."</span><hr style='margin-left:0px;margin-right:0px;'>";
		}
		if ($row["motiv"] != "") {
			$str .= "<span style='font-family: comforta;font-size:14px; color: #333;'><span style='color:#119091;'>Motiv: </span>".$row["motiv"]."</span><hr style='margin-left:0px;margin-right:0px;'>";
		}
		if ($row["tisk"] != "") {
			$str .= "<span style='font-family: comforta;font-size:14px; color: #333;'><span style='color:#119091;'>Tisk: </span>".$row["tisk"]."</span><hr style='margin-left:0px;margin-right:0px;'>";
		} //////
		if ($row["izvedba"] != "") {
			$str .= "<span style='font-family: comforta;font-size:14px; color: #333;'><span style='color:#119091;'>Izvedba: </span>".$row["izvedba"]."</span><hr style='margin-left:0px;margin-right:0px;'>";
		}
		if ($row["pola"] != "") {
			$str .= "<span style='font-family: comforta;font-size:14px; color: #333;'><span style='color:#119091;'>Pola: </span>".$row["pola"]."</span><hr style='margin-left:0px;margin-right:0px;'>";
		}
		if ($row["papir"] != "") {
			$str .= "<span style='font-family: comforta;font-size:14px; color: #333;'><span style='color:#119091;'>Papir: </span>".$row["papir"]."</span><hr style='margin-left:0px;margin-right:0px;'>";
		}
		if ($row["velikost"] != "") {
			$str .= "<span style='font-family: comforta;font-size:14px; color: #333;'><span style='color:#119091;'>Velikost: </span>".$row["velikost"]."</span><hr style='margin-left:0px;margin-right:0px;'>";
		}
		if ($row["zobci"] != "") {
			$str .= "<span style='font-family: comforta;font-size:14px; color: #333;'><span style='color:#119091;'>Zobci: </span>".$row["zobci"]."</span><hr style='margin-left:0px;margin-right:0px;'>";
		}
		if ($row["zobcanje"] != "") {
			$str .= "<span style='font-family: comforta;font-size:14px; color: #333;'><span style='color:#119091;'>Zobčanje: </span>".$row["zobcanje"]."</span><hr style='margin-left:0px;margin-right:0px;'>";
		}
		if ($row["opomba"] != "") {
			$str .= "<span style='font-family: comforta;font-size:14px; color: #333;'><span style='color:#119091;'>Opomba: </span>".$row["opomba"]."</span><hr style='margin-left:0px;margin-right:0px;'>";
		}
		$str .= "</td>";
		
		echo ($str);
	}
			
	echo "</tr>"; 
}
?>