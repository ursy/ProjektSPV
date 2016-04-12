<?php
error_reporting(-1);
ini_set('display_errors', 'On');
include_once 'connToDatabase.php';
session_start();

//prikaz znamke v uporabniskem profilu
if ($_POST['method'] == "ima_znamka")
{
	$id_ima = $_POST['id'];

	$q = "SELECT distinct ID_znamka FROM znamka_uporabnik WHERE ima=1 AND ID_uporabnik = ".$id_ima."";

	$result = mysqli_query($conn, $q);

	echo "<tr>";
	while ($row = mysqli_fetch_assoc($result))
	{
		$str = $row['ID_znamka'];
		
		$q1 = "SELECT ID_znamke, slika, naslov FROM Znamka WHERE ID_znamke = ".$str."";

		$result1 = mysqli_query($conn, $q1);
		
		echo "<tr>";
		
		while ($row1 = mysqli_fetch_assoc($result1))
		{
			echo "<td ID='" .$row1["ID_znamke"]. "'><img style='box-shadow: 0px 0px 10px black;width:60px;height:60px;' src='". $row1['slika'] . "'/></td><td ID='" .$row1["ID_znamke"]. "' style='padding-left:25px;'><span style='font-family: comforta;font-size:14px; color: #333;'>" . $row1["naslov"] . "</span></td>";
		}
		
		echo "</tr>";
	}
	echo "</tr>";
}

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

		echo "<td style='cursor: pointer; cursor: hand;' class='znamke_prikaz' ID='" . $row["ID_znamke"] . "'><img width='90' height='130' src='". $row['slika'] . "'/></td><br>
		<td style='cursor: pointer; cursor: hand;' class='znamke_prikaz' ID='" . $row["ID_znamke"] . "' style='background-color: #FBFBFB;'><span style='font-family: comforta;font-size:14px; color: #333;'>" . $row["naslov"] . "</span><br><br><br><br>
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

if ($_POST['method'] == "odvec_z")
{
    $id =  $_POST['znamka_id'];
    $user_id =  $_POST['user_id'];
	$q = "SELECT * FROM znamka_uporabnik WHERE ID_znamka='$id' AND odvec=1 AND ID_uporabnik != '$user_id'";
    if(isset($_SESSION['user_id'])){
        $result = mysqli_query($conn, $q);
        if(mysqli_num_rows($result) > 0){
            echo "<tr id='$id' class='od_z'>";
            echo "<td colspan='3'><span style='font-family: comforta;font-size:20px; color: #119091; vertical-align:text-top;'>To znamko ima odveč...</span></td>";
            echo "</tr>";
            while ($row = mysqli_fetch_assoc($result))
            {
                $id_uporabnik = $row["ID_uporabnik"];
                //if ($id_uporabnik != $user_id) {
                $sql = "SELECT * FROM users WHERE id='$id_uporabnik'";
                $result2 = mysqli_query($conn, $sql);
                while ($row2 = mysqli_fetch_assoc($result2))
                {
                    echo "<tr class='od_z' id='$id_uporabnik'>";
                    $str = "";
                    $str .= "<td><img style='width:25px; height:25px;' src='". $row2['picture'] . "'/></td>";
                    $str .= "<td><span style='font-family: comforta;font-size:14px; color: #333;'>" . $row2["fname"] . "</span><br>";
                    $str .= "</td>";
                    $str .= "<td ><button type='button' class='btn3' id='ponudiButton' title='Pošlji sporočilo'><img src='images/sporocilo.png'/></td>";
                    echo ($str);
                    echo "</tr>";
                }
            }
        }
        else{
            echo "<tr>";
            echo "<td colspan='3'><span style='font-family: comforta;font-size:20px; color: #119091; vertical-align:text-top;'>Te znamke nima nihče odveč...</span></td>";
            echo "</tr>";
        }
    }
    else{
        echo "<tr>";
        echo "<td colspan='3'><span style='font-family: comforta;font-size:20px; color: #119091; vertical-align:text-top;'>Za to moraš biti prijavljen...</span></td>";
        echo "</tr>";
    }
}

//prikaz mejav v uporabniškem profilu
if ($_POST['method'] == "get_menjave")
{
   if(isset($_SESSION['user_id'])){
        $id = $_SESSION['user_id'];
        echo $id;
        $q = "SELECT * FROM Chat WHERE ID_User1 = '$id' OR ID_User2 = '$id' GROUP BY ID_znamka";
        $result = mysqli_query($conn, $q);
         echo "<tr id='$id' class='od_z'>";
        echo "<td colspan='3'><span style='font-family: comforta;font-size:20px; color: #119091; vertical-align:text-top;'>Zgodovina menjav</span></td>";
        echo "</tr>";
        while ($row = mysqli_fetch_assoc($result))
        {
            if($row['ID_User1'] == $id){
                $other_user = $row['ID_User2'];
                echo $other_user;
                $sql = "SELECT * FROM users WHERE id='$other_user'";
                $result2 = mysqli_query($conn, $sql);
                $id_znamke = $row["ID_znamka"];
                while ($row2 = mysqli_fetch_assoc($result2)){
                    $sql_znamk = "SELECT * FROM Znamka WHERE ID_znamke='$id_znamke'";
                    $result3 = mysqli_query($conn, $sql_znamk);
                    $row3 = mysqli_fetch_assoc($result3);
                    echo "<tr class='od_z' id='$other_user'>";
                    $str = "";
                    $str .= "<td><img style='width:80px; height:80px;' src='". $row3['slika'] . "'/></td>";
                    $str .= "<td><span style='font-family: comforta;font-size:14px; color: #333;'>" . $row2["fname"] . "</span><br>";
                    $str .= "<td><span style='font-family: comforta;font-size:14px; color: #333;'>" . $row["chat_status"] . "</span><br>";
                    $str .= "</td>";
                     echo ($str);
                    echo "</tr>";
                }
            }
            else{
                $other_user = $row['ID_User1'];
                echo $other_user;
                $sql = "SELECT * FROM users WHERE id='$other_user'";
                $result2 = mysqli_query($conn, $sql);
                while ($row2 = mysqli_fetch_assoc($result2)){
                    $sql_znamk = "SELECT * FROM Znamka WHERE ID_znamke='$id_znamke'";
                    $result3 = mysqli_query($conn, $sql_znamk);
                    $row3 = mysqli_fetch_assoc($result3);
                    echo "<tr class='od_z' id='$other_user'>";
                    $str = "";
                    $str .= "<td><img style='width:80px; height:80px;' src='". $row3['slika'] . "'/></td>";
                    $str .= "<td><span style='font-family: comforta;font-size:14px; color: #333;'>" . $row2["fname"] . "</span><br>";
                    $str .= "<td><span style='font-family: comforta;font-size:14px; color: #333;'>" . $row["chat_status"] . "</span><br>";
                    $str .= "</td>";
                     echo ($str);
                    echo "</tr>";
                }
            }
            /*$id_chat = $row['ID_Chat'];
            $sql = "SELECT * FROM chatContent WHERE ID_chat='$id_chat'";
            $result2 = mysqli_query($conn, $sql);
            while ($row2 = mysqli_fetch_assoc($result2))
            {
                $str = "";
                $str .=
            }*/
        }

    }
}

//pridobivanje uporabnikovega profila
if ($_POST['method'] == "user_podatki")
{
	$id =  $_POST['user_id'];
	#echo $id;
	$q = "SELECT picture, fname, lname, email, gender, isEditable FROM users WHERE id = '$id'";

	$result = mysqli_query($conn, $q);

	while ($row = mysqli_fetch_assoc($result))
	{
		$isEditable = "0";
		if ($row['isEditable'] == 1)
			$isEditable = "1";
		$str = $isEditable . "<tr>";
		if ($row['picture'] != "") {
			$str .= "<td><img style='box-shadow: 0px 0px 10px black;' src='". $row['picture'] . "'/></td>";
		}
		$str .= "<td>";
		if ($row["fname"] != "") {
			$str .= "<span style='font-family: comforta;font-size:26px; color: #333;'> <span id='user_fname'>" . $row["fname"];
		}
		if ($row["lname"] != "") {
			$str .= "</span> <span id='user_lname'> " . $row["lname"];
		}
		$str .= "</span></span><br><br><br>";
		if ($row["email"] != "") {
			$str .= "<span style='font-family: comforta;font-size:14px; color: #333;'><span style='color:#119091;'>E-mail: </span><span id='user_email'>".$row["email"]."</span></span><hr style='margin-left:0px;margin-right:0px;'>";
		}
		if ($row["gender"] != "") {
			$spol = "";
			if ($row["gender"] == "male")
				$spol = "moški";
			else
				$spol = "ženski";
			$str .= "<span style='font-family: comforta;font-size:14px; color: #333;'><span style='color:#119091;'>Spol: </span><span class='user_sex' id='".$row["gender"]. "'>". $spol ."</span></span><hr style='margin-left:0px;margin-right:0px;'>";
		}

		$str .= "<button type='button' class='btn3' id='moje_znamke_btn'>Moje znamke</button><button type='button' class='btn3' id='moje_menjave_btn'>Moje menjave</button><hr style='margin-left:0px;margin-right:0px;'></td></tr>";

		echo ($str);
	}
}

//odpri chat z uporabnikom
if ($_POST['method'] == "odpri_chat")
{
	$my_user_id =  $_POST['my_user_id'];
	$other_user_id =  $_POST['other_user_id'];
	$znamka_id =  $_POST['znamka_id'];
	$chat_id = $_POST['chat_id'];
	#echo $id;
	
	if ($chat_id != 0) {
		$sql = "SELECT * FROM Chat WHERE ID_Chat='$chat_id'";
	    $result2 = mysqli_query($conn, $sql);
	    while ($row2 = mysqli_fetch_assoc($result2))
	    {	
		    $id1 = $row2["ID_User1"];
		    $id2 = $row2["ID_User2"];
		    if ($id1 != $my_user_id) {
			    $other_user_id = $id1;
		    }
		    else {
			    $other_user_id = $id2;
		    }
		    $znamka_id = $row2["ID_znamka"];			
	        break;
	    }
	}
	
    $sql = "SELECT * FROM users WHERE id='$other_user_id'";
    $result2 = mysqli_query($conn, $sql);
    $str = "";
    while ($row2 = mysqli_fetch_assoc($result2))
    {	
	    $fname = $row2["fname"];
	    $lname = $row2["lname"];			
        break;
    }
	
	$q = "SELECT ID_Chat, chat_status, person_done_ID FROM Chat WHERE ((ID_User1 = '$my_user_id' AND ID_User2 = '$other_user_id') OR (ID_User2 = '$my_user_id' AND ID_User1 = '$other_user_id')) AND ID_znamka = '$znamka_id' AND (chat_status = 'running' OR chat_status='done1')";

	$result = mysqli_query($conn, $q);
	$chat_id = 0;
	$chat_status = 'running';
	$person_done = 0;
	
	//CHAT OBSTAJA
	if(mysqli_num_rows($result) > 0){
		while ($row = mysqli_fetch_assoc($result))
		{
			// PRIKAŽI CHAT
			$chat_id = $row['ID_Chat'];
			$chat_status = $row['chat_status'];
			$person_done_ID = $row['person_done_ID'];
			if ($person_done_ID == $my_user_id){
				$person_done = 1;
			}
			
			echo "<tr id='$chat_id' class='chat_title'><td colspan='4'><span style='font-family: comforta; font-size:22px; color: #119091; vertical-align:text-top; text-align:center;'>Pogovor: $fname $lname</span></td></tr>";
			
			$q = "SELECT * FROM chatContent WHERE ID_chat = $chat_id ORDER BY Cas_posiljanja ASC";
			$result = mysqli_query($conn, $q);
			while ($row = mysqli_fetch_assoc($result))
			{
				$id_posiljatelj = $row['ID_posiljatelj'];
				$cas_posiljanja = $row['Cas_posiljanja'];
				$sporocilo = $row['Sporocilo'];
				
				$chat = "";
				$color = "#333";
				$background = "#f1f4f4";
				if ($id_posiljatelj == $my_user_id) {
					$color = "#5e5e5e";
					$background = "white";
				}
				$chat .= "<tr style='background-color:$background'>";
				$sql = "SELECT * FROM users WHERE id='$id_posiljatelj'";
		        $result2 = mysqli_query($conn, $sql);
		        $str = "";
		        while ($row2 = mysqli_fetch_assoc($result2))
			    {			
					$str = "<td><img style='width:25px; height:25px;' src='". $row2['picture'] . "'/></td>";
					$str .= "<td><span style='font-family: comforta;font-size:14px; color: $color;'>" . $row2["fname"] . "</span><br></td>";
					$chat .= $str . "<td><span style='font-family: comforta;font-size:14px; color: $color; width:100%;'>$sporocilo</span></td><td><span style='font-family: comforta;font-size:10px; color: $color;'>($cas_posiljanja)</span></td></tr>";				
		            break;
		        }
		        echo($chat);
			}
			break;
		}
	}
	//CHAT NE OBSTAJA
	else {
		$q = "INSERT INTO Chat (ID_User1, ID_User2, ID_znamka, chat_status) VALUES ('$my_user_id', '$other_user_id', '$znamka_id', 'running')";
		if (mysqli_query($conn, $q)) {
	        $q = "SELECT ID_Chat FROM Chat WHERE (ID_User1 = '$my_user_id' AND ID_User2 = '$other_user_id') AND ID_znamka = '$znamka_id' AND chat_status = 'running'";
			$result = mysqli_query($conn, $q);
			while ($row = mysqli_fetch_assoc($result))
			{
				$chat_id = $row['ID_Chat'];
				echo "<tr id='$chat_id' class='chat_title'><td colspan='4'><span style='font-family: comforta; font-size:22px; color: #119091; vertical-align:text-top; text-align:center;'>Pogovor: $fname $lname</span></td></tr>";
				break;
			}
	    } else {
	        echo "Error";
	    }
	}	
	
	//$chat_id
	if ($chat_status == 'running') {
		echo '<tr><td colspan="3"><textarea class="message_input" id="'.$chat_id.'" type="text" placeholder="Vaše sporočilo..." style="font-family: comforta; text-align:left; height:100px; font-size:16px; border:1px solid #119091; background-color:#f1f4f4; color: black; width:100%;"/></td><td><button type="button" class="btn3" id="sendButton"><img src="images/poslji.png"/></button></td></tr>';
	}
	echo($person_done);
}

//pošlji sporočilo uporabniku
if ($_POST['method'] == "poslji_sporocilo")
{
	$my_user_id =  $_POST['my_user_id'];
	$chat_id = $_POST['chat_id'];
	$msg = $_POST['msg'];
	$q = "INSERT INTO chatContent (ID_chat, ID_posiljatelj, Sporocilo) VALUES ('$chat_id', '$my_user_id', '$msg')";
	if (mysqli_query($conn, $q)) {
		echo("ok");
	}
	else{
		echo "error";
	}
}

if ($_POST['method'] == "preklici_menjavo")
{
	$chat_id = $_POST['chat_id'];
	$my_user_id =  $_POST['my_user_id'];
	$q = "UPDATE Chat SET chat_status = 'canceled', person_done_ID = '$my_user_id' WHERE ID_Chat = '$chat_id'";
	if (mysqli_query($conn, $q)) {
		echo("ok");
	}
	else{
		echo "error";
	}
}

if ($_POST['method'] == "potrdi_menjavo")
{
	$chat_id = $_POST['chat_id'];
	$my_user_id =  $_POST['my_user_id'];
	$q = "UPDATE Chat SET chat_status = 'done2' WHERE ID_Chat = '$chat_id' AND chat_status='done1'";
	if (mysqli_query($conn, $q)) {		
		$q = "UPDATE Chat SET chat_status = 'done1', person_done_ID = '$my_user_id'  WHERE ID_Chat = '$chat_id' AND chat_status='running'";
		if (mysqli_query($conn, $q)) {
			$q = "SELECT * FROM Chat WHERE ID_Chat = '$chat_id'";
			$result = mysqli_query($conn, $q);
			while ($row = mysqli_fetch_assoc($result))
			{
				$status = $row['chat_status'];
				echo $status;
				
				$u2 = $row['ID_User2'];
				$znamka_id = $row['ID_znamka'];
				$q = "UPDATE znamka_uporabnik SET odvec = 0 WHERE ID_uporabnik = '$u2' AND ID_znamka='$znamka_id'";
				mysqli_query($conn, $q);
			}
		}
		else {
			echo("error");
		}
	}
	else{
		echo "error";
	}
}


?>
