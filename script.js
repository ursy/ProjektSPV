//prikaz vsebina tab - glavna stran...
function prikazi(elementi)  
{
    var tabContents = document.getElementsByClassName('tabContent');
	
    for (var i = 0; i < tabContents.length; i++) 
	{ 
        tabContents[i].style.display = 'none';
    }
    
    var tabContentId = elementi.id.replace(/(\d)/g, '-$1');
    document.getElementById(tabContentId).style.display = 'block';
}

//prikaz leto v dropdown listi
$(document).ready(function() {
    $('select').change(function(){
        var val = $(this).val();
        $.ajax({
            url: "podatki_baza.php",
            type: "POST",
            data: 
			{
				id: val,
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
	  
    });
});

//prikaz podatkov o posamezni znamki
$(document).on("mousedown", "td.znamke_prikaz", function() {

	var id = $(this).attr("ID");
	//alert(id);
	znamka_podatki(id, "");

});

function znamka_podatki (id, naslov)
{
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
	$('#znamka_profil').show();
}