<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Music Playlist App</title>

    <!-- Styles 
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">-->
	<link rel="stylesheet" href="css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="css/index.css">
	<script src="lib/jquery-3.2.1.min.js"></script>
    <script src="lib/jquery.dataTables.min.js"></script>
    <script src="lib/dataTables.buttons.min.js"></script>
    <script src="lib/dataTables.select.min.js"></script>
</head>
<body>
<?php 
    include "views/header.php";
    include "views/content.php"; 
	include "views/footer.php"; 
	include "views/cart.php";
?>
</body>
<script>
    //variables in global scope
    var arrPlaylist = [];
	var formerAnchor;
	var tableInstance;
	var carttableInstance;
</script>
<script src="scripts/js/playlist.js"></script>
<script src="scripts/js/album.js"></script>
<script src="scripts/js/cart.js"></script>
<script src="scripts/js/orders.js"></script>
</html>