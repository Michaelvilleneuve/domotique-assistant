<?php 
/*
* Copyright 2015 Michaël Villeneuve
* 
* Header.php 
*
* Layout
* Ce fichier correspond au Header de l'application.
* Il contient l'appel aux feuilles de styles, aux balises meta, etc
*
*/
?> 
<!DOCTYPE html>
<html>
  <head>
    <!-- Required meta tags-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="grey-translucent">
    <link rel="apple-touch-icon" href="img/icon.png" />
    <!-- Your app title -->
    <title>Domotique</title>
    <!-- Path to Framework7 Library CSS-->
    <link rel="stylesheet" href="/app/assets/css/framework7.min.css?<?php echo(mt_rand(10000000, 99999999)); ?>">
    <script type="text/javascript" src="/app/assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="/app/assets/js/framework7.min.js"></script>
    <!-- Path to your app js-->
    <script type="text/javascript" src="/app/assets/js/my-app.js"></script>
    <!-- Path to your custom app styles-->
    <link rel="stylesheet" href="/app/assets/css/my-app.css">
    <link rel="stylesheet" href="/app/assets/css/pe-icon-7-stroke.css">
  </head>
  <body>