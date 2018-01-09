//Constructor
Cart = function(carttable){
	
	this.carttable = carttable;
	
	this.cartItemCount;
}
//Initializes the checkout table
Cart.prototype.initCheckoutTable = function(){
	var arrayRow = [];
 	
	$('#tablecart tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            this.carttable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
						
			arrayRow = carttable.row( this ).data();
			
			document.getElementById('txtCartAlbum').value = arrayRow[0];
			
			document.getElementById('txtCartYear').value = arrayRow[1];
			
			document.getElementById('txtCartPrice').value = arrayRow[2];
			
			document.getElementById('txtCartQuantity').value = arrayRow[3];

        }
    } );
}

Cart.prototype.viewCart = function(){
	$('#tablecart').empty();
	this.carttable
	    .destroy();
	var carttable = $('#tablecart').DataTable();
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
			for(var x=0; x<aryData.length; x++){
				var cartData = [];
			    cartData.push(aryData[x]["title"], aryData[x]["year"], aryData[x]["price"], aryData[x]["quantity"])
			    carttable.row.add(cartData).draw(false);
			}
			document.getElementById("cart").style.display = "block";
			document.getElementById("album").style.display = "none";
	        document.getElementById("playlist").style.display = "none";
		}
	});
}

Cart.prototype.addCart = function(aryCart){
		
    if(aryCart != null)
	    this.carttable.row.add(aryCart).draw(false);
    else{
		alert("No item selected to add to checkout");
	    return;
    }
		
	if(this.carttable.rows().count() > 0){
		document.getElementById("cart").style.display = "block";
	}
	else
		alert("No item selected to add to checkout");
}

Cart.prototype.checkoutCart = function(){
	var tableparams = [];
	
	for(var i=0; i < this.carttable.rows().count(); i++){
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

Cart.prototype.removeCart = function(){
	var rows = this.carttable
        .rows( '.selected' )
        .remove()
        .draw();
	
	var objtableRemove = {};
	
	var aryTempRemoveCart = [];
	
	var aryRemoveCart = [];
	
	aryTempRemoveCart = aggregateArrayRemoveCart();
	console.log(aryRemoveCart);
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
}

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
    carttableInstance = $('#tablecart').DataTable();
	var removeFromCart = new Cart(carttableInstance);
	removeFromCart.removeCart();
	
});


var aggregateArrayRemoveCart = function(){
	
	return [document.getElementById('txtCartAlbum').value, document.getElementById('txtCartYear').value, document.getElementById('txtCartPrice').value, document.getElementById('txtCartQuantity').value];
}

var displayCart = function(){
	carttableInstance = $('#tablecart').DataTable();
	var aryCart = [];
	aryCart = aggregateArrayAlbum();
	
	var addToCart = new Cart(carttableInstance);
	addToCart.addCart(aryCart);
}

$("#viewCart").click(function(){
	var viewCartContents = new Cart(carttableInstance);
	viewCartContents.viewCart();
	
});

//update back-end with user's cart details
$("#saveCartLater").click(function(){
	var pushToRemoteCart = new Cart(carttableInstance);
	pushToRemoteCart.checkoutCart();
});

