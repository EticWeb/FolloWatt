<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.2.custom.css" />

<style type="text/css">
.text1 {font-family: Arial, Helvetica, sans-serif;font-size: 12px;color: gray;text-align : left;}
.text2 {font-family: Arial, Helvetica, sans-serif;font-size: 8px;color: Silver;text-align : left;}
.titre1 {font-family: Arial, Helvetica, sans-serif;font-size: 24px;font-weight: bold;color: #555555;}
.titre2 {font-family: Arial, Helvetica, sans-serif;font-size: 18px;font-weight: bold;color: #cccccc;}
</style>

<!--<META http-equiv="Refresh" content="10; URL=http://www.eticweb.com">-->
<title>EticWeb conseils TIC et Dévelopement Durable en paca</title>
</head>

<body>

<div style="text-align: center;">

<a href="http://www.eticweb.com" title="EticWeb conseils en TIC et Dévelopement Durable en paca"><img style="border:solid 1px #000000" alt="EticWeb conseils en TIC et Dévelopement Durable en paca" src="http://eticweb.fr/logos/EticWeb-conseils-tic.jpg" border=""></a><br>

<span class="titre1">EticWeb optimisation de vos projets internet.</span><br>

<span class="titre2"><a href="http://www.eticweb.com">Venez d&eacute;couvrir nos solutions</a><br>

<span class="text2">EticWeb</span><span class="text1">&reg;</span><span class="text2"> est une marque d&eacute;pos&eacute;e.</span>
</div>
<!-- Debut code pour graph -->
<div id="container">
<ul>
<?php
$basdir = 'dist/examples';
$rep=opendir($basdir);
$bAuMoinsUnRepertoire = false;
while ($file = readdir($rep)){
	if($file != '..' && $file !='.' && $file !=''){ 
		if (!is_dir($file)) {
			$infofile = pathinfo($file);
			if ($infofile['extension']=="html")
				print('<li><a href="'.$basdir.'/'.$file.'">'.$infofile['filename'].'</a></li>');
		}
	}
}

closedir($rep);
clearstatcache();
?>
<li>------------FAVORIS-------------</li>
<li><a href="dist/examples/barTest.html">Barres graph avec zone info (click ou hover)</a></li>
<li><a href="dist/examples/bubbleChart2.html">Bulles avec zone info (2 em ex)</a></li>
<li><a href="dist/examples/zoomProxy.html">Fonction ZOOM</a></li>
<li><a href="dist/examples/zoomOptions.html">Fonction ZOOM2</a></li>
<li><a href="dist/examples/zoom1.html">Fonction ZOOM3</a></li>
<li><a href="dist/examples/ui.html">Utilisation dans les Onglets et Slider</a></li>
<li><a href="dist/examples/stackedBar3.html">Barre pourcentage</a></li>
<li><a href="dist/examples/pieTest3.html">Camembert</a></li>
<li><a href="dist/examples/meterGauge.html">Affichage style joge essence à aiguille</a></li>
<li><a href="dist/examples/highlighter2.html">Info Bulle</a></li>
<li><a href="dist/examples/donutTest.html">Bégnet</a></li>
</ul>
</body>
</html>



</head>
<script type="text/javascript" src="http://jqueryui.com/themeroller/themeswitchertool/"></script>

<body>
