$("#returnToPlaylist").click(function(){
	$("#cart").hide();
	$("#playlist").show();
	$("#album").show();
});

//Adds selected albums to user's cart
$("#addCart").click(function(){
	document.getElementById("album").style.display = "none";
	document.getElementById("playlist").style.display = "none";
	document.getElementById("cart").style.display = "block";
	
	$.ajax({
		type:"GET",
		data:{route:"getSession", session:"isLoggedIn"},
		url:"/musicPlaylistStore/app/Http/Core/Router.php",
		success:function(data){
			if(!data){
				alert("The user has to be logged-in in order to perform this operation");
				return;
			}
			else
				displayCart();
		}
	})
	
});

//removes items from user cart
$("#removeCart").click(function(){
		
    var rows = carttableInstance
        .rows( '.selected' )
        .remove()
        .draw();
	
	var objtableRemove = {};
	
	var aryTempRemoveCart = [];
	
	var aryRemoveCart = [];
	
	aryTempRemoveCart = aggregateArrayRemoveCart();
	objtableRemove.title = aryTempRemoveCart[0];
	objtableRemove.year = aryTempRemoveCart[1];
	objtableRemove.price = aryTempRemoveCart[2];
	objtableRemove.quantity = aryTempRemoveCart[3];
	aryRemoveCart.push(objtableRemove);
	
	$.ajax({
		type:"GET",
		data:{route:"removeCart", checkout:aryRemoveCart},
		url:"/musicPlaylistStore/app/Http/Core/Router.php",
		Success:function(data){
			//console.log(data);
		}
	});
	
});

//Initializes the checkout table
function initCheckoutTable(){
	
	carttableInstance = $('#tablecart').DataTable();
	
	var arrayRow = [];
 	
	$('#tablecart tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            carttableInstance.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
						
			arrayRow = carttableInstance.row( this ).data();
			console.log(arrayRow);
			document.getElementById('txtCartAlbum').value = arrayRow[0];
			
			document.getElementById('txtCartYear').value = arrayRow[1];
			
			document.getElementById('txtCartPrice').value = arrayRow[2];
			
			document.getElementById('txtCartQuantity').value = arrayRow[3];

        }
    } );
}

var aggregateArrayRemoveCart = function(){
	
	return [document.getElementById('txtCartAlbum').value, document.getElementById('txtCartYear').value, document.getElementById('txtCartPrice').value, document.getElementById('txtCartQuantity').value];
}

var displayCart = function(){
	carttableInstance = $('#tablecart').DataTable();
	var aryCart = [];
	aryCart = aggregateArrayAlbum();
	if(aryCart != null)
	    carttableInstance.row.add(aryCart).draw(false)
	console.log(carttableInstance.rows().count())
	if(carttableInstance.rows().count() > 0){
		document.getElementById("cart").style.display = "block";
	}
	else
		alert("No item selected to add to checkout");
}

//Update backend with user's cart details
var pushToRemoteCart = function(){
	var carttable = $('#tablecart').DataTable();
	
	var tableparams = [];
	
	for(var i=0; i < carttable.rows().count(); i++){
		var aryTableFields = [];
		var objtableFields = new Object()//{};
		aryTableFields = carttable.row(i).data();
		objtableFields['title'] = aryTableFields[0];
		objtableFields['year'] = aryTableFields[1];
		objtableFields['price'] = aryTableFields[2];
		objtableFields['quantity'] = aryTableFields[3];
		objtableFields['genre'] = formerAnchor.text
		tableparams.push(objtableFields);
	}
	
	$.ajax({
		type:"GET",
		data:{route:"addCart", checkout:tableparams},
		url:"/musicPlaylistStore/app/Http/Core/Router.php"
	})
}

$("#viewCart").click(function(){
	$('#tablecart').empty();
	carttableInstance
	    .destroy();
	carttableInstance = $('#tablecart').DataTable();
	var aryData = [];
	$.ajax({
		type:"GET",
		url:"/musicPlaylistStore/app/Http/Core/Router.php",
		data:{route:"viewCart"},
		success:function(data){
			if(data == "You need to be logged in to perform this operation"){
				alert(data);
				return;
			}
			aryData = JSON.parse(data);
			for(var x=0; x<aryData.length; x++){//for(var x=0; x<Object.keys(data).length; x++)
				var cartData = [];
			    cartData.push(aryData[x]["title"], aryData[x]["year"], aryData[x]["price"], aryData[x]["quantity"])
			    carttableInstance.row.add(cartData).draw(false);
			}
			document.getElementById("cart").style.display = "block";
			document.getElementById("album").style.display = "none";
	        document.getElementById("playlist").style.display = "none";
		}
	});
});

$("#saveCartLater").click(function(){
	pushToRemoteCart();
});

