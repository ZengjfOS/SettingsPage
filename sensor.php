
<!DOCTYPE html>
<!-- saved from url=(0071)http://www.gbtags.com/technology/democenter/20120823-gauge-justgage-js/ -->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GBK">
<title>AplexOS</title>
<!-- <link rel="shortcut icon" type="image/x-icon" -->
<!-- 	href="http://www.gbtags.com/gbin1/themes/gbin1_demo/images/gbin1.ico"> -->
<script src="./js/jquery-2.1.1.min.js"></script>
<link type="text/css" rel="stylesheet" href="./css/meshcms.css">
<link type="text/css" rel="stylesheet" href="./css/main.css">
<style>
body {
	text-align: center;
	font-family: Arial;
}
#g1,#g2,#g3,#g4,#g5,#g6{
	width: 400px;
	height: 320px;
	display: inline-block;
	margin: 1em;
	border: 1px soild #202020;
	box-shadow: 0px 0px 15px #101010;
	margin-top: 30px;
	border-radius: 8px;
}
p {
	display: block;
	width: 400px;
	margin: 2em auto;
	text-align: center;
	border-top: 1px soild #CCC;
	border-bottom: 1px soild #CCC;
	background: #333333;
	padding: 10px 0px;
	color: #CCC;
	text-shadow: 1px 1px 25px #000000;
	border-radius: 0px 0px 5px 5px;
	box-shadow: 0px 0px 10px #202020;
}
</style>

<script src="./js/raphael.2.1.0.min.js"></script>
<script src="./js/justgage.1.0.1.min.js"></script>
<script>
var g1,g2,g3,g4,g5,g6;
var host;

function getSensorInfo(){
// 	var uri = "http://"+host+"/sensordata.php?data="+new Date().getTime();
	var uri = "sensordata.php?data="+new Date().getTime();
 	$.get(uri, function(data){
		//alert(data);
		//把json字符串解析成json对象;
	   	var jsonData = JSON.parse(data);
	   	var code = jsonData['code'];
	   	var mysqlData = jsonData['data'];
	   	if(code == 200){
	   		var value = "";
	   		for (var key in mysqlData){
		   		value = mysqlData[key];
	 	  		switch (key) {
		   		case "temperature":
   	             //alert(value);	
		   			g1.refresh(value);
		  				break;
		   		case "humidity":
		  			g2.refresh(value);	
		   			break;
		   		case "brightness":
		   			g3.refresh(value);	
  					break;
				case "noise":
	  	 			value = value; 
	  	 			g4.refresh(value);	
	  	 			break;
	 	  		case "pm2dot5":
		   			g5.refresh(value);	
	 	  			break;
	 	  		case "airPress":
	   		 		g6.refresh(value);	
	 	  			break;
	   			default:
	 	  			break;
	   			}
            }
	   	}
	});
}

$(document).ready(function() {
	var isExt = false;
	var param_id = "<?php echo $_GET["id"]?>";
	$.getJSON("json/room.json",function(data){
		$.each(data,function(i,item){
			if(item['id']==param_id){
				host = item['host'];
// 				alert(host);
				isExt = true;
			}
		});
		g1 = new JustGage({
	    	id: "g1", 
	    	value: 0, 
	    	min: 0,
	   		max: 100,
	    	title: "temperature",
	    	label: "℃",
	    });
	      
		g2 = new JustGage({
	        id: "g2", 
	        value: 0, 
	        min: 0,
	        max: 100,
	        title: "humidity",
	        label: "%",
	  		levelColors: [
	  			"#222222",
	  			"#555555",
	  			"#CCCCCC"
	  		]    
		});
		g3 = new JustGage({
	        id: "g3", 
	        value: 0, 
	        min: 0,
	        max: 100,
	        title: "brightness",
	        label: "L",
	  		levelColors: [
	  			"#222222",
	  			"#555555",
	  			"#CCCCCC"
	  		]    
		});
		g4 = new JustGage({
	        id: "g4", 
	        value: 0, 
	        min: 30,
	        max: 120,
	        title: "noise",
	        label: "dB",
	  		levelColors: [
	  			"#222222",
	  			"#555555",
	  			"#CCCCCC"
	  		]
		});
		g5 = new JustGage({
	        id: "g5", 
	        value: 0, 
	        min: 0,
	        max: 250,
	        title: "pm2dot5",
	        label: "μg/m³",
		}); 
		g6 = new JustGage({
	        id: "g6", 
	        value: 0, 
	        min: 30,
	        max: 110,
	        title: "ATM",
	        label: "kpa",
	  		levelColors: [
	  			"#222222",
	  			"#555555",
	  			"#CCCCCC"
	  		]    
		}); 
		getSensorInfo();
	 	window.setInterval("getSensorInfo()",3000);
	});	
});

</script>
</head>
<body>
	<section>
		<div id="g1"></div>
		<div id="g2"></div>
		<div id="g3"></div>
		<div id="g4"></div>
		<div id="g5"></div>
		<div id="g6"></div>
	</section>
	<script src="./js/h.js" type="text/javascript"></script>
</body>
<div></div>
</html>
