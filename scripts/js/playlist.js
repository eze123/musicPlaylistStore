$("document").ready(function(){
	
	clearAlbumBoxes();
	
	tableInstance = $('#tablezone').DataTable();
	
	var arrayRow = [];
 	
	$('#tablezone tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
			
			clearAlbumBoxes();
        }
        else {
            tableInstance.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
						
			arrayRow = tableInstance.row( this ).data();
			
			document.getElementById('txtAlbum').value = arrayRow[0];
			
			document.getElementById('txtArtist').value = arrayRow[1];
			
			document.getElementById('txtYear').value = arrayRow[2];
			
			document.getElementById('txtPrice').value = arrayRow[3];
        }
    } );
	
	carttableInstance = $('#tablecart').DataTable();
	var initCart = new Cart(carttableInstance);
	initCart.initCheckoutTable();
	
	//ajax call that retrieves the music genres for the app
	$.ajax({
		type:"GET",
		dataType:"json",
		url:"/musicplayliststore/app/Http/Core/Router.php",
		data:{route:"getAllPlaylist"},
		success: function(data){
			var playlistul = document.createElement("ul");
			playlistul.style.listStyleType = "circle";
			document.getElementById("playlist").appendChild(playlistul);
			
			for(var k = 0; k < Object.keys(data).length; k++)
			{
				var playlistli = document.createElement("li");
				var playlistanchor = document.createElement("a");
				playlistanchor.text = data[k]["title"];
				playlistanchor.id = data[k]["title"];
				
				playlistanchor.addEventListener('click', function(){
					if(typeof formerAnchor !== 'undefined')
						formerAnchor.style.background="none";
					this.style.background="#00ff00";
					populateAlbums(this.text);
					formerAnchor = this;
				});
				arrPlaylist[k] = data[k]["title"];
				playlistli.appendChild(playlistanchor);
				playlistul.appendChild(playlistli);
				
				document.getElementById(data[k]["title"]).style.cursor = "pointer";
					
			}
		}
	});
});

var clearAlbumBoxes = function(){
	document.getElementById('txtAlbum').value = "";
			
	document.getElementById('txtArtist').value = "";
			
	document.getElementById('txtYear').value = "";
	
	document.getElementById('txtPrice').value = "";
}

var aggregateArrayAlbum = function(){
	if(document.getElementById('txtAlbum').value == "" && document.getElementById('txtPrice').value == "")
		return null;
	
	var q = document.getElementById("comboQuantity");
    var strQuantity = q.options[q.selectedIndex].text;
	return [document.getElementById('txtAlbum').value, document.getElementById('txtYear').value, document.getElementById('txtPrice').value, strQuantity];
}

