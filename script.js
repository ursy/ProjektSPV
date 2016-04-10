userID = 18;
//prikaz vsebina tab - glavna stran...
function prikazi(elementi) {
    var tabContents = document.getElementsByClassName('tabContent');

    for (var i = 0; i < tabContents.length; i++)
	{
        tabContents[i].style.display = 'none';
    }

    var tabContentId = elementi.id + "content";
    document.getElementById(tabContentId).style.display = 'block';
	if (elementi.id == 'glavna_tab') {
	    get_znamke($('select').val());
    }
    else if (elementi.id == "uporabnik_tab") {
	   	get_user(userID);

	    $("#editProfileButton").hide();
    }
}

//prikaz znamk ko spremenis izbrano leto
$(document).ready(function() {
	get_znamke($('select').val());
    $('select').change(function(){
        var val = $(this).val();
        get_znamke(val);
    });
});

//prikaz podatkov o posamezni znamki
$(document).on("mousedown", "td.znamke_prikaz", function() {
	var id = $(this).attr("ID");
	$("#odvec").hide();
	znamka_podatki(id, "");
	get_znamka_user (id, userID);
});

//back button clicked
$(document).on("mousedown", "#backButton", function() {
	$('#znamka_podatki').html("");
	$('#leto_znamke').show();
	$('#znamka_profil').hide();
	$('#glavna_tabcontent').show();
});

//back button - zamenjava znamk
$(document).on("mousedown", "#backButton1", function() {
	$('#znamka_zamenjava').hide();
	$("#potrdiMenjavoB").show();
	$("#prekliciMenjavoB").show();
	$('#znamka_profil').show();
});

//zacni menjavo clicked
$(document).on("mousedown", "#ponudiButton", function() {
	$('#znamka_profil').hide();
	var id_znamke = $("tr.podatki").attr("id");
	//alert(id_znamke);
    var other_user_id = $(this).closest('tr').attr('id'); // table row ID 
    //$('#znamka_zamenjava').show();
	odpri_chat(userID, other_user_id, id_znamke, 0);
});

//imam znamko clicked
$(document).on("mousedown", "#imamZ_btn", function() {
	var id_znamke = $("tr.podatki").attr("id");
	oznaci_znamka_user (id_znamke, userID, "imam");
});

//nimam znamke clicked
$(document).on("mousedown", "#nimamZ_btn", function() {
	var id_znamke = $("tr.podatki").attr("id");
	oznaci_znamka_user (id_znamke, userID, "nimam");
});

//odvec znamka clicked
$(document).on("mousedown", "#odvecZ_btn", function() {
	var id_znamke = $("tr.podatki").attr("id");
	oznaci_znamka_user (id_znamke, userID, "odvec");
});

//ni odvec znamka clicked
$(document).on("mousedown", "#niodvecZ_btn", function() {
	var id_znamke = $("tr.podatki").attr("id");
	oznaci_znamka_user (id_znamke, userID, "odvec");
});

//prikazi ljudi ki imajo znamko odvec
$(document).on("mousedown", "#osebeOdvecButton", function() {
	if ($("#odvec").is(":visible") == false) {
		var id_znamke = $("tr.podatki").attr("id");
		odvec_znamka(id_znamke, userID);
	}
	else {
		$("#odvec").hide();
	}
});

//send button clicked
$(document).on("mousedown", "#sendButton", function() {
	var chat_id = $("textarea.message_input").attr("id");
	var msg = $('textarea.message_input').val();
	if (msg != "")
		poslji_sporocilo(userID, chat_id, msg);	
});

//potrdi menjavo button clicked
$(document).on("mousedown", "#potrdiMenjavoB", function() {
	var chat_id = $("tr.chat_title").attr("id");
	//$("textarea.message_input").attr("id");
	potrdi_menjavo(chat_id, userID);	
});

//preklici menjavo button clicked
$(document).on("mousedown", "#prekliciMenjavoB", function() {
	var chat_id = $("textarea.message_input").attr("id");
	preklici_menjavo(chat_id, userID);	
});

//uredi profil clicked
$(document).on("mousedown", "#editProfileButton", function() {
	$('#uporabnik_tabcontent').hide();
	$('#ime_uporabnika').val($('#user_fname').text());
	$('#priimek_uporabnika').val($('#user_lname').text());
	$('#email_uporabnika').val($('#user_email').text());
	var spol = $('.user_sex').attr('id');
	$('#spol_uporabnika').val(spol);
	$('#edit_uporabnik_tabcontent').show();
});

//uredi profil done clicked
$(document).on("mousedown", "#editDoneButton", function() {
	var ime = $('#ime_uporabnika').val();
	var priimek = $('#priimek_uporabnika').val();
	var email = $('#email_uporabnika').val();
	var spol = $('#spol_uporabnika').val();
	change_user_data(userID); // PRAVI USER ID
});

//vstavi novo znamko clicked
$(document).on("mousedown", "#vstavi_znamko_btn", function() {
	if ($("input#slika_znamke").val() != "" && $("input#naslov_znamke").val() != "" && $("input#datum_znamke").val() != "" && $("input#leto_znamke").val() != "") {
		$.ajax({
		    url: "podatki_baza.php",
		    type: "POST",
		    data:
			{
				naslov: $("input#naslov_znamke").val(),
				datum: $("input#datum_znamke").val(),
				leto: parseInt($("input#leto_znamke").val(),10),
				oblikovanje: $("input#oblikovanje_znamke").val(),
				motiv: $("input#motiv_znamke").val(),
				tisk: $("input#tisk_znamke").val(),
				izvedba: $("input#izvedba_znamke").val(),
				pola: $("input#pola_znamke").val(),
				papir: $("input#papir_znamke").val(),
				velikost: $("input#velikost_znamke").val(),
				zobci: $("input#zobci_znamke").val(),
				zobcanje: $("input#zobcanje_znamke").val(),
				opomba: $("input#opomba_znamke").val(),
				slika: $("input#slika_znamke").val(),
				method: "vstavi_znamko"
			},
		    cache: false,
		    success: function(data)
			{
				location.reload();
		    },
		    error: function(data)
			{
				alert(data);
		    }
		});
	}
	else {
		alert("Izpolnite obvezna polja!");
	}
});

//prikaz vseh znamk za izbrano leto
function get_znamke(leto) {
	$("#znamka").html("");
	$('#znamka_podatki').html("");
	$.ajax({
	    url: "podatki_baza.php",
	    type: "POST",
	    data:
		{
			id: leto,
			method: "prikaz_znamke"
		},
	    cache: false,
	    success: function(data)
		{
	         $("#znamka").html(data);
	    }
	});
	$('#leto_znamke').show();
	$('#znamka_profil').hide();
}

function znamka_podatki (id, naslov) {
	$('#znamka_podatki').html("");
	$.ajax({
		type: "POST",
		url: "podatki_baza.php",
		data:
		{
			ime_znamka: naslov,
			znamka_id: id,
			method: "z_podatki"
		},
		cache: false,
		success: function (result)
		{
            $('#znamka_podatki').html(result);
		},
		error: function (result)
		{
			alert(result);
		}
	});
	$('#leto_znamke').hide();
	$('#glavna_tabcontent').hide();
	$('#znamka_profil').show();
}

function odvec_znamka (id, id_user) {
	$('#odvec').html("");
	$.ajax({
		type: "POST",
		url: "podatki_baza.php",
		data:
		{
			znamka_id: id,
			user_id: id_user,
			method: "odvec_z"
		},
		cache: false,
		success: function (result)
		{
			if (result != "") {
            	$('#odvec').html(result);
				$("#odvec").show();
            }
		},
		error: function (result)
		{
			console.log(result);
		}
	});
}

function get_znamka_user (id_znamka, id_user) {
	$.ajax({
		type: "POST",
		url: "podatki_baza.php",
		data:
		{
			id_user: id_user,
			id_znamka: id_znamka,
			method: "get_znamka_user"
		},
		cache: false,
		success: function (result)
		{
            var values=result.split('-');
			var ima=values[0];
			var nima=values[1];
			var odvec=values[2];

			if (ima == 1) {
				$("#imamZ_btn").hide();
				$("#nimamZ_btn").show();
			}
			else {
				$("#imamZ_btn").show();
				$("#nimamZ_btn").hide();
			}

			if (odvec == 1) {
				$("#odvecZ_btn").hide();
				$("#niodvecZ_btn").show();
			}
			else {
				$("#odvecZ_btn").show();
				$("#niodvecZ_btn").hide();
			}
		},
		error: function (result)
		{
			alert(result);
		}
	});
}

function oznaci_znamka_user (id_znamka, id_user, oznacba) {
	$.ajax({
		url: "podatki_baza.php",
		type: "POST",
		data:
		{
			uporabnik: id_user,
			znamka: id_znamka,
			oznacba: oznacba,
			method: "oznaci_znamko"
		},
		cache: false,
		success: function(result)
		{
			var values=result.split('-');
			var ima=values[0];
			var nima=values[1];
			var odvec=values[2];

			if (ima == 1) {
				$("#imamZ_btn").hide();
				$("#nimamZ_btn").show();
			}
			else {
				$("#imamZ_btn").show();
				$("#nimamZ_btn").hide();
			}

			if (odvec == 1) {
				$("#odvecZ_btn").hide();
				$("#niodvecZ_btn").show();
			}
			else {
				$("#odvecZ_btn").show();
				$("#niodvecZ_btn").hide();
			}
		},
		error: function(data)
		{
			alert(data);
		}
	});
}


function get_user(id) {
	$('#user_podatki').html("");
	$.ajax({
		type: "POST",
		url: "podatki_baza.php",
		data:
		{
			user_id: id,
			method: "user_podatki"
		},
		cache: false,
		success: function (result)
		{
			/*
			if (result[0] == "0")
				$('#editProfileButton').hide();
			else if (result[0] == "1")
				$('#editProfileButton').show();
			*/
			alert(result )
            $('#user_podatki').html(result.substr(0));
		},
		error: function (result)
		{
			alert(result);
		}
	});
	$('#user_podatki').show();
}

function change_user_data(id) {
	$.ajax({
		type: "POST",
		url: "podatki_baza.php",
		data:
		{
			user_id: id,
			method: "change_user_podatki"
		},
		cache: false,
		success: function (result)
		{
			$('#uporabnik_tabcontent').show();
			$('#edit_uporabnik_tabcontent').hide();
		},
		error: function (result)
		{
			alert(result);
		}
	});
}

function odpri_chat(my_user_id, other_user_id, znamka_id, chat_id) {
	//$('#chat_content').html("");
	$.ajax({
		type: "POST",
		url: "podatki_baza.php",
		data:
		{
			my_user_id: my_user_id,
			other_user_id: other_user_id,
			znamka_id: znamka_id,
			chat_id: chat_id,
			method: "odpri_chat"
		},
		cache: false,
		success: function (result)
		{
			if (result[result.length-1] == "1") {
				$("#potrdiMenjavoB").hide();
				$("#prekliciMenjavoB").hide();
			}
			var res = result.substr(result.length-2, 1);
			$('#chat_content').html(result);
			$('#znamka_zamenjava').show();
		},
		error: function (result)
		{
			alert(result);
		}
	});
}

function poslji_sporocilo(my_user_id, chat_id, msg) {
	$.ajax({
		type: "POST",
		url: "podatki_baza.php",
		data:
		{
			my_user_id: my_user_id,
			chat_id: chat_id,
			msg: msg,
			method: "poslji_sporocilo"
		},
		cache: false,
		success: function (result)
		{
			odpri_chat(my_user_id, 0, 0, chat_id);
			//alert(result);
		},
		error: function (result)
		{
			alert(result);
		}
	});
}

function potrdi_menjavo(chat_id, my_user_id) {
	$.ajax({
		type: "POST",
		url: "podatki_baza.php",
		data:
		{
			chat_id: chat_id,
			my_user_id: my_user_id,
			method: "potrdi_menjavo"
		},
		cache: false,
		success: function (result)
		{
			if (result == "done1"){
				odpri_chat(my_user_id, 0, 0, chat_id);
			}
			else {
				$('#znamka_zamenjava').hide();
				$("#odvec").hide();
				$('#znamka_profil').show();
			}
		},
		error: function (result)
		{
			alert(result);
		}
	});
}

function preklici_menjavo(chat_id, my_user_id) {
	$.ajax({
		type: "POST",
		url: "podatki_baza.php",
		data:
		{
			chat_id: chat_id,
			my_user_id: my_user_id,
			method: "preklici_menjavo"
		},
		cache: false,
		success: function (result)
		{
			$('#znamka_zamenjava').hide();
			$('#znamka_profil').show();
			//alert(result);
		},
		error: function (result)
		{
			alert(result);
		}
	});
}
