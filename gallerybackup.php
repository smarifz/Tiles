<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport"
	content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<title>Thales RnD - Prototype - Tiles</title>
<style>
html,body {
	height: 100%;
}

body {
	background-color: #000000;
	margin: 0;
	font-family: Arial;
	overflow: hidden;
}

#search {
	position: absolute;
	bottom: 30px;
	width: 100%;
	text-align: center;
}

#search input {
	color: #ffffff;
	background-color: transparent;
	border: 1px solid #0080ff;
	padding: 10px;
	font-size: 20px;
	text-transform: uppercase;
	-webkit-border-radius: 0px; /* workaround for ios safari */
}

#search button {
	color: #0080ff;
	background-color: transparent;
	border: 1px solid #0080ff;
	padding: 10px;
	font-size: 20px;
	text-transform: uppercase;
	cursor: pointer;
}
</style>
</head>
<body>
	<script src="js/three.min.js"></script>

	<script src="js/tween.min.js"></script>
	<script src="js/CSS3DRenderer.js"></script>
	<script src="js/jquery-1.6.1.min.js" type="text/javascript"></script>
	<script src="js/jquery.popupwindow.js" type="text/javascript"></script>

	<div id="container"></div>
	<div id="search">
		<input id="query" type="text" value="zedd">
		<button id="button">search</button>
	</div>

	<!-- DATABASE  -->
	<?php

// 	//  Connect to mysql database
// 	$host = "localhost";
// 	$user = "smarifz_syed";
// 	$pass = "test1";
// 	$databaseName = "smarifz_prototype";
// 	$tableName = "movies";

// 	$con=mysqli_connect($host,$user,$pass,$databaseName);

// 	// Check connection
// 	if (mysqli_connect_errno($con))
// 	{
// 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
// 	}

// 	//Query
// 	$sql="SELECT * from movies";
// 	$result=mysqli_query($con,$sql);
// 	$jsonArray = array();

// 	//Adding all the rows to an array
// 	while($row=mysqli_fetch_array($result,MYSQLI_NUM))
// 	{
// 		$jsonArray[] = $row;
// 	}

// 	echo json_encode($jsonArray);

// 	?>

	<!-- TILES -->
	<script type='text/javascript'>
			var camera, scene, renderer;
			var objects = [], player;
				
			var auto = true;

			init();
			animate();

			function init() {

				camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 1, 5000);
				camera.position.y = -25;

				scene = new THREE.Scene();

				renderer = new THREE.CSS3DRenderer();
				renderer.setSize(window.innerWidth, window.innerHeight);
				renderer.domElement.style.position = 'absolute';
				renderer.domElement.style.top = 0;
				document.getElementById('container').appendChild(renderer.domElement);

				//Search Parameters
				var query = document.getElementById('query');
				query.addEventListener('keyup', function(event) {

					if (event.keyCode === 13) {

						search(query.value);

					}

				}, false);

				//Search button
				var button = document.getElementById('button');
				button.addEventListener('click', function(event) {

					search(query.value);

				}, false);

				if (window.location.hash.length > 0) {

					query.value = window.location.hash.substr(1);

				}

				search(query.value);

				document.body.addEventListener('mousewheel', onMouseWheel, false);

				//Video window
				document.body.addEventListener('click', function(event) {

					auto = true;

					if (player !== undefined) {

						player.parentNode.removeChild(player);
						player = undefined;
						new TWEEN.Tween(camera.position).to({
							x : 0,
							y : 0
						}, 1000).easing(TWEEN.Easing.Exponential.Out).start();

					}

				}, false);

				window.addEventListener('resize', onWindowResize, false);

			}

			function search(query) {

				window.location.hash = query;

				for (var i = 0, l = objects.length; i < l; i++) {

					var object = objects[i];
					var delay = Math.random() * 1000;

					new TWEEN.Tween(object.position).to({
						y : -3000
					}, 1000).delay(delay).easing(TWEEN.Easing.Exponential.In).start();

					new TWEEN.Tween(object).to({}, 1000).delay(delay).onComplete(function() {

						scene.remove(this);
						renderer.cameraElement.removeChild(this.element);

						var index = objects.indexOf(this);
						objects.splice(index, 1);

					}).start();

				}

				var request = new XMLHttpRequest();
				request.addEventListener('load', onData, false);
				request.open('GET', 'https://gdata.youtube.com/feeds/api/videos?v=2&alt=json&max-results=50&q=' + query, true);
				request.send(null);

			}

			function onData(event) {

				var data = JSON.parse(event.target.responseText);
				var entries = data.feed.entry;
				//var dbInfo = [];

				//Getting each row ------- result = all the rows 
				//$.getJSON("DB.php",function(result){
				//    $.each(result, function(i, field){
				    	//console.log("omg: " +result);
				    	//console.log("line sent "+result[i]);
				 //   	dbInfo[i] = result[i];
				    	//dbInfo = regEx(result[i]);
				    	//console.log("second element of the returned array "+dbInfo[1]);
				    	
				//    });
			//	  });

				  
				//console.log("d"+result[i]);

				for (var i = 0; i < entries.length; i++) {

					var entry = entries[i];

					var element = document.createElement('div');
					element.style.width = '480px';
					element.style.height = '360px';

					var image = document.createElement('img');
					image.addEventListener('load', function(event) {

						var object = this.properties.object;
						var button = this.properties.button;

						//button.style.visibility = 'visible';

						//new TWEEN.Tween( object.position )
						//.to( { y: Math.random() * 2000 - 1000 }, 2000 )
						//	.easing( TWEEN.Easing.Exponential.Out )
						//	.start();

					}, false);
					image.style.position = 'absolute';
					image.style.width = '480px';
					image.style.height = '360px';
					//image.src = entry.media$group.media$thumbnail[2].url;
					image.src='images/front.jpg';
					element.appendChild(image);

					var button = document.createElement('img');
					button.style.position = 'absolute';
					button.style.left = ((480 - 86 ) / 2 ) + 'px';
					button.style.top = ((360 - 61 ) / 2 ) + 'px';
					//button.style.visibility = 'hidden';
					button.style.WebkitFilter = 'grayscale()';
					button.src = 'data:image/png;base64,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';
					element.appendChild(button);

					var blocker = document.createElement('div');
					blocker.style.position = 'absolute';
					blocker.style.width = '480px';
					blocker.style.height = '360px';
					blocker.style.background = 'rgba(0,0,0,0.5)';
					blocker.style.cursor = 'pointer';
					element.appendChild(blocker);


					//Layout of the Grid
					var object = new THREE.CSS3DObject(element);
					object.position.x = ((i % 3 ) * 800 ) - 800;
					object.position.y = (-(Math.floor(i / 2) % 5 ) * 400 ) + 800;
					object.position.z = ( Math.floor(i / 2) ) * 1000 - 2000;
					scene.add(object);
					objects.push(object);


					var properties = {
						data : entry,
						blocker : blocker,
						button : button,
						object : object
					}

					element.properties = properties;
					image.properties = properties;

					element.addEventListener('mouseover', function() {

						this.properties.button.style.WebkitFilter = '';
						this.properties.blocker.style.background = 'rgba(0,0,0,0)';

					}, false);

					element.addEventListener('mouseout', function() {

						this.properties.button.style.WebkitFilter = 'grayscale()';
						this.properties.blocker.style.background = 'rgba(0,0,0,0.75)';

					}, false);

					element.addEventListener('click', function(event) {

						//event.stopPropagation();

						var data = this.properties.data;
						var object = this.properties.object;

						auto = false;
					
					/*	if (player !== undefined) {

							player.parentNode.removeChild(player);
							player = undefined;

						}

						player = document.createElement('iframe');
						player.style.position = 'absolute';
						player.style.width = '480px';
						player.style.height = '360px';
						player.style.border = '0px';
						player.src = 'videos/battleship.mp4';
						this.appendChild(player);
					*/
					console.log('pop up activated');
					$.popupWindow('./videoWindow.php', {
					    height: 720,
					    width: 1280,
					    toolbar: false,
					    scrollbars: false, // safari always adds scrollbars
					    status: false,
					    resizable: true,
					    center: true, // auto-center
					    createNew: true, // open a new window, or re-use existing popup
					    name: null, // specify custom name for window (overrides createNew option)
					    location: false,
					    menubar: false,
					    onUnload: function() { // callback when window closes
					        alert('Window closed!');
					    } 
					});
						//

						var prev = object.position.z + 400;

						new TWEEN.Tween(camera.position).to({
							x : object.position.x,
							y : object.position.y - 25
						}, 1500).easing(TWEEN.Easing.Exponential.Out).start();

						new TWEEN.Tween({
							value : prev
						}).to({
							value : 0
						}, 2000).onUpdate(function() {

							move(this.value - prev);
							prev = this.value;

						}).easing(TWEEN.Easing.Exponential.Out).start();

					}, false);

				}

			}

			function move(delta) {

				for (var i = 0; i < objects.length; i++) {

					var object = objects[i];

					object.position.z += delta;

					if (object.position.z > 0) {

						object.position.z -= 5000;

					} else if (object.position.z < -5000) {

						object.position.z += 5000;

					}

				}

			}

			function onMouseWheel(event) {

				move(event.wheelDelta);

			}

			function onWindowResize() {

				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();

				renderer.setSize(window.innerWidth, window.innerHeight);

			}

			function regEx(line){

				var line;

			      var re1='(\\d+)';	// Integer Number 1
			      var re2='.*?';	// Non-greedy match on filler
			      var re3='((?:[a-z][a-z]+))';	// Word 1
			      var re4='.*?';	// Non-greedy match on filler
			      var re5='((?:[a-z][a-z]+))';	// Word 2
			      var re6='.*?';	// Non-greedy match on filler
			      var re7='((?:[a-z][a-z\\.\\d\\-]+)\\.(?:[a-z][a-z\\-]+))(?![\\w\\.])';	// Fully Qualified Domain Name 1

			      var p = new RegExp(re1+re2+re3+re4+re5+re6+re7,["i"]);
			      var m = p.exec(line);
			      if (m != null)
			      {
			          var int1=m[1];
			          var word1=m[2];
			          var word2=m[3];
			          var fqdn1=m[4];
			          console.log("("+int1+")"+"("+word1+")"+"("+word2.replace(/</,"&lt;")+")"+"("+fqdn1.replace(/</,"&lt;")+")"+"\n");
			          return [int1, word1, word2, fqdn1];
			      }
				
			}

			function animate() {

				requestAnimationFrame(animate);

				TWEEN.update();

				if (auto === true) {

					move(10);

				}

				renderer.render(scene, camera);

			}

		</script>

</body>
</html>
