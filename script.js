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