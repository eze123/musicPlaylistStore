//On checkout, pushes items in cart to Orders table
$("#proceedCheckout").click(function(){
	
	var carttable = $('#tablecart').DataTable();
	
	var tableparams = [];
	
	for(var i=0; i < carttable.rows().count(); i++){
		var aryTableFields = [];
		var objtableFields = new Object();
		aryTableFields = carttable.row(i).data();
		objtableFields['title'] = aryTableFields[0];
		objtableFields['year'] = aryTableFields[1];
		objtableFields['price'] = aryTableFields[2];
		objtableFields['quantity'] = aryTableFields[3];
		tableparams.push(objtableFields);
	}
	
	$.ajax({
		type:"GET",
		data:{route:"addOrder", purchase:tableparams},
		url:"/musicPlaylistStore/app/Http/Core/Router.php"
	})
});