<?php 
define('DB_USER','root');
define('DB_PASSWORD','asnow06');
define('DB_NAME','conso_watt_et_temp');

function connectSql() {
	$conn = mysql_connect("localhost", DB_USER, DB_PASSWORD);
	if (!$conn) {
	    throw "Impossible de se connecter à  la base de données : " . mysql_error();
	}
	if (!mysql_select_db(DB_NAME)) {
	    throw "Impossible de sélectionner la base ".DB_NAME." : " . mysql_error();
	}
	
	return $conn;
}

function getWattsRealTime() {
	$link = connectSql();
	$sql = "SELECT date,watts FROM xmldataRT ORDER BY date DESC LIMIT 0,1";
	$result = mysql_query($sql);
	
	if (!$result) {
	   echo "Impossible d'exécuter la requête ($sql) dans la base : " . mysql_error();
	   exit;
	}
	
	if (mysql_num_rows($result) == 0) {
	   echo "Aucune ligne trouvée.";
	   exit;
	}
	$row = mysql_fetch_assoc($result);
	//echo "<br/>Nombre de lignes traitées : ".$cpt;
	mysql_free_result($result);
	mysql_close($link);
	return $row['watts'];
}

function getWattsLastRealTime($limit = 100) {
	$link = connectSql();
	$sql = "SELECT date,watts FROM xmldataRT ORDER BY date DESC LIMIT 0,".$limit;
	$result = mysql_query($sql);
	
	if (!$result) {
	   echo "Impossible d'exécuter la requête ($sql) dans la base : " . mysql_error();
	   exit;
	}
	
	if (mysql_num_rows($result) == 0) {
	   echo "Aucune ligne trouvée.";
	   exit;
	}
	$jsonRet = "";
	while ($row = mysql_fetch_assoc($result)) {
		$jsonRet .= '["'.$row['date'].'",'.$row['watts'].'],';
	}
	//echo "<br/>Nombre de lignes traitées : ".$cpt;
	mysql_free_result($result);
	mysql_close($link);
	return $jsonRet;
}

// TRAITEMENT DES REQUETES HHTP //
if (isset($_GET['type']) && $_GET['type']=='realtime')
	echo '['.getWattsRealTime().']';
else if (isset($_GET['type']) && $_GET['type']=='last_realtime')
	echo '['.getWattsLastRealTime(@$_GET['nbval']).']';
?>