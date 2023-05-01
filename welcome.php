<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <script src="functions.js"></script>
</head>

<body>

<div id="inline">
    <div class="one">
        <h1>Game - Auto_Bot</h1>
    </div>
    <div class="two">
        <button id="login" onclick="login_function()">Login</button>
    </div>
</div>

<hr id="divider">


<!-- PRIDAME GRAFIK -->

<div id="chart"></div>

<?php
$ids = ['Data point 1', 'Data point 2', 'Data point 3', 'Data point 4', 'Data point 5'];
$values = [10, 20, 15, 30, 25];

include 'chart.php';
?>

<!-- KONIEC GRAFIKU -->


</body>

