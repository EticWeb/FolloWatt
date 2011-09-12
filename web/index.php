<?php 
error_reporting(E_ALL);
ini_set('display_errors','On');

include 'webser.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="fr-FR">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Consommation instantanée - EticWeb</title>

<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="jqPlot/dist/excanvas.js"></script><![endif]-->

<link rel="stylesheet" type="text/css" href="jqPlot/dist/jquery.jqplot.css" />
<link rel="stylesheet" type="text/css" href="jqPlot/dist/examples/examples.css" />
<!-- BEGIN: load jquery -->

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js"></script>
<!-- END: load jquery -->

<!-- BEGIN: load jqplot -->
<script language="javascript" type="text/javascript" src="jqPlot/dist/jquery.jqplot.min.js"></script>
<script language="javascript" type="text/javascript" src="jqPlot/dist/plugins/jqplot.meterGaugeRenderer.js"></script>

<!-- ajouter pour graph courbe -->
<script language="javascript" type="text/javascript" src="jqPlot/dist/plugins/jqplot.cursor.min.js"></script>
<script language="javascript" type="text/javascript" src="jqPlot/dist/plugins/jqplot.dateAxisRenderer.min.js"></script>

<!-- END: load jqplot -->

<style type="text/css">
.plot {
	margin-bottom: 30px;
	margin-left: auto;
	margin-right: auto;
}

#jauge .jqplot-meterGauge-tick,#jauge .jqplot-meterGauge-label {
	font-size: 12pt;
}

/*<!-- ajouter pour graph courbe -->*/
.jqplot-cursor-legend {
	width: 160px;
	font-family: "Courier New";
	font-size: 0.85em;
}

td.jqplot-cursor-legend-swatch {
	width: 1.3em;
}

div.jqplot-cursor-legend-swatch { 
/*	 width: 15px;*/
}
</style>


<script type="text/javascript">
function CreateMeterGaugeRenderer() {
    var optionsObj = {
	    seriesDefaults: {
	        renderer: $.jqplot.MeterGaugeRenderer,
	        rendererOptions: {
	            label: 'Consommation instantanée',
	            labelHeightAdjust: 115,
	            intervalOuterRadius: 120,
	            ticks: [0, 100, 200, 300],
	            intervals:[110, 250, 300],
	            intervalColors:['#66cc66', '#E7E658', '#cc6666']
	        }
	    }
    };
    return optionsObj;
}
function CreateCourbeRenderer() {
    var optionsObj = {
    	title: 'Suivi de consommation en temps réel', 
    	series: [
    		{
    			yaxis:'y2axis', 
    			label: '',
    			showMarker: false, 			
    			fill: true, 					
    			neighborThreshold: 3,
    			fillColor: '#089cfe',      
    			fillAlpha: 0.2,
    			lineWidth: 1.2,
    			color: '#0571B6',
    			fillAndStroke: true
    		}
    	],
    	axes: {
    		xaxis: { 
    			renderer:$.jqplot.DateAxisRenderer, 
    			tickOptions:{formatString:'%H:%M:%S'},
    			numberTicks: 14
    		}, 
    		y2axis: {
    			tickOptions: {formatString: '%dW'},
    			numberTicks: 12
    		} 
    	},
    	cursor:{show:true, zoom:true},
    	highlighter: {
    		useAxesFormatters: false,
    		showMarker: false,
    		show: false
    	}
    };
    return optionsObj;
}

function refreshConIns() { 
	nbrequest++;
    $.ajax({
		url: 'webser.php',
		type: 'GET',
		data: { 'type': 'realtime', 'nbval' : '1', 'nocache' : Math.random() * 100 },
		dataType: 'json',
		success: function (data) {
			//alert(data[0]);
			if (myData[0][0] != data[0]) { // si date remontée différente de la dernière date 
				nbredraw++;
				$("#jauge").html("");
				$("#courbe").html("");
				$('*').unbind();
				// DEBUG info
	    		$("#code_5").html("Nb appel/actualisation : " + nbrequest + "/" + nbredraw + " ---- consommation t-6s : <b>" + myData[0][1] + "</b> watts à " + myData[0][0] + "  ---- consommation t : <b>" + data[1] + "</b> watts à " + data[0]);
	    		
	    		myData.pop();
	    		myData.unshift(data);
				$.jqplot('courbe', [myData], CreateCourbeRenderer());
				
	    		$.jqplot('jauge',[[data[1]]],CreateMeterGaugeRenderer());
	        	s1 = data[1];
			}
		},
		error: function (xhr, textStatus, errorThrown) {
			alert(errorThrown);
		}
    });
}

var nbrequest = 0;
var nbredraw = 0;
var w1 = "";
var d1 = "";
var cont_prive = "";
var myData = [<?php echo getWattsLastRealTime() ?>];

function stopTimer() {
	// pour arréter le timer faire un "clearTimeout(cont_prive);"
	clearTimeout(cont_prive);
	$("#action").html('<button onclick="startTimer();">Start</button>');
}
function startTimer() {
	$.ajax({
		url: 'webser.php',
		type: 'GET',
		data: { 'type': 'last_realtime', 'nbval' : '100', 'nocache' : Math.random() * 100 },
		dataType: 'json',
		success: function (data) {
			myData = data;
			$("#jauge").html("");
			$("#courbe").html("");
			$('*').unbind();
		   	repetAct();
		},
		error: function (xhr, textStatus, errorThrown) {
			alert(errorThrown);
		}
	});
}

function repetAct() {
	// Jauge
	$.jqplot('jauge',[[myData[0][1]]],CreateMeterGaugeRenderer());
	// Courbe
	$.jqplot('courbe', [myData], CreateCourbeRenderer());
	
	$("#code_5").html("consommation: <b>"+myData[0][1]+"</b> watts à " + myData[0][0]);
	$("#action").html('<button onclick="stopTimer();">Stop</button>');	
	cont_prive = setInterval("refreshConIns()", 6000);
	
}
$(document).ready(function(){
	$(document).unload(function() {$('*').unbind(); });
	repetAct();
});

</script>
</head>
<body>

<div id="jauge" class="plot" style="width: 372px; height: 225px;"></div>

<div id="action"></div>
<pre id="code_5" class="code-block"></pre>

<div id="courbe" class="jqplot"></div>
</body>
</html>
