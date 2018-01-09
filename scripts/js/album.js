Album = function(genreAnchor, albumtable){
	this.genre = genreAnchor;
	
	this.albumtable = albumtable;
}

Album.prototype.displayAlbum = function(){
	clearAlbumBoxes();
	
	this.albumtable
	    .destroy();
	
	this.albumtable = $('#tablezone').DataTable({
        "processing": false,
        "serverSide": true,
        "retrieve": true,
        "ajax": {
            url:"/musicplayliststore/app/Http/Core/Router.php",
            "type": "GET",
            "data": {
                "route": "getGenreAlbum",
                "genre":this.genre
            },
            "dataSrc":function(json){

                return json["recordsTotal"] > 0 ?  json["data"] : 0;
			},
            "columns"    : [
                {'data': 'title'},
                {'data': 'artist'},
                {'data': 'year'}
            ]
		}
	});
}

Album.prototype.addToAlbum = function(){
	var iterator = [];
	
	var objtableFields = new Object();
	
	for(var i=0; i < this.albumtable.rows().count(); i++){
		var aryTableFields = [];
		aryTableFields = this.albumtable.row(i).data();

		for(var x=0; x < iterator.length; x++){
			if(iterator[x][0] == aryTableFields[0] && iterator[x][1] == aryTableFields[1] && iterator[x][2] == aryTableFields[2]){
				alert("The album you're trying to add already exists");
				return;
			}
		}
		iterator.push(aryTableFields);
	}
	
	var title = document.getElementById("txtAlbum").value;
	var artist = document.getElementById("txtArtist").value;
	var year = document.getElementById("txtYear").value;
	var price = document.getElementById("txtPrice").value;
	$.ajax({
		type:"POST",
		dataType:"json",
		data:{
			route:"addAlbum", 
			genre:formerAnchor.text, 
			title:title, 
			artist:artist,
			year:year,
			price:price
		},
		url:"/musicplayliststore/app/Http/Core/Router.php",
		success:function(data){
			console.log(data);
			var data1 = [];
			if(data > 0){
				/** Refresh table with updated values **/
		        data1.push({title:document.getElementById("txtAlbum").value, artist:document.getElementById("txtArtist").value});//data1.push(document.getElementById("txtAlbum").value);

				tableInstance.data(data1);
				
				// Hide a column
                tableInstance.column( 3 ).visible( false );
				
				tableInstance.columns([{data:"title"}, {data:"artist"}, {data:"year"}]);
                tableInstance.draw();
			}
		}
	});
}

Album.prototype.removeAlbum = function(){
	
	this.albumtable.row('.selected').remove().draw( false );
}

var populateAlbums = function(thisanchor){	
	clearAlbumBoxes();
	
	tableInstance = $('#tablezone').DataTable();
	
	var album = new Album(thisanchor, tableInstance);
	album.displayAlbum();

}

//function retrieves the albums for the playlist genre clicked
$("#addAlbum").click(function(){
	
	if(typeof formerAnchor == 'undefined'){
		alert("You need to select the genre to which the album belongs");
		return;
	}
	
	if(document.getElementById("txtAlbum").value == "" || document.getElementById("txtArtist").value == "" || document.getElementById("txtYear").value == ""){
		alert("You need to provide the album's title, artist and year in order to add one!");
		return;
	}
	
	var albumtable = $('#tablezone').DataTable();
	
	var album = new Album(formerAnchor, albumtable);
	album.addToAlbum();
	
	
});

$("#removeAlbum").click(function(){
	var albumtable = $('#tablezone').DataTable();
	
	var album = new Album(null, albumtable);
	album.removeAlbum();
});