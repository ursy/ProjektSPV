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
	   	get_user(15); // PRAVI USER ID IZ SESSIONA
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
	znamka_podatki(id, "");
	get_znamka_user (id, 1); //PRAVI USER ID 
});

//back button clicked
$(document).on("mousedown", "#backButton", function() {
	$('#znamka_podatki').html("");
	$('#leto_znamke').show();
	$('#znamka_profil').hide();
	$('#glavna_tabcontent').show();
});

//imam znamko clicked
$(document).on("mousedown", "#imamZ_btn", function() {
	var id_znamke = $("tr.podatki").attr("id");
	oznaci_znamka_user (id_znamke, 15, "imam"); //PRAVI USER ID namesto 1 (IZ SESSIONA)
});

//nimam znamke clicked
$(document).on("mousedown", "#nimamZ_btn", function() {
	var id_znamke = $("tr.podatki").attr("id");
	oznaci_znamka_user (id_znamke, 15, "nimam"); //PRAVI USER ID
});

//odvec znamka clicked
$(document).on("mousedown", "#odvecZ_btn", function() {
	var id_znamke = $("tr.podatki").attr("id");
	oznaci_znamka_user (id_znamke, 15, "odvec"); //PRAVI USER ID
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
				$("#imamZ_btn").text("imam");
				$("#nimamZ_btn").text("--");
			}
			else {
				$("#imamZ_btn").text("--");
				$("#nimamZ_btn").text("nimam");
			}
			
			if (odvec == 1) {
				$("#odvecZ_btn").text("odvec");
			}
			else {
				$("#odvecZ_btn").text("--");
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
				$("#imamZ_btn").text("imam");
				$("#nimamZ_btn").text("--");
			}
			else {
				$("#imamZ_btn").text("--");
				$("#nimamZ_btn").text("nimam");
			}
			
			if (odvec == 1) {
				$("#odvecZ_btn").text("odvec");
			}
			else {
				$("#odvecZ_btn").text("--");
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
            $('#user_podatki').html(result);
		},
		error: function (result) 
		{
			alert(result);
		}
	});
	$('#user_podatki').show();
}