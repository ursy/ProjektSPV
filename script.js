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
});

//back button clicked
$(document).on("mousedown", "#backButton", function() {
	$('#znamka_podatki').html("");
	$('#leto_znamke').show();
	$('#znamka_profil').hide();
	$('#glavna_tabcontent').show();
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