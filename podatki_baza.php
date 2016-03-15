<?php
include_once 'connToDatabase.php';

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
	
	echo "<tr>";
	
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