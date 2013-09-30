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
	margin: 0;
	font-family: Arial;
	overflow: hidden;
	background-image:url('./images/background.jpg');
}

#search {
	position: absolute;
	top: 30px;
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

.popbox {
	display: none;
	position: absolute;
	z-index: 99999;
	width: 400px;
	padding: 10px;
	background: #EEEFEB;
	color: #000000;
	border: 1px solid #4D4F53;
	margin: 0px;
	-webkit-box-shadow: 0px 0px 5px 0px rgba(164, 164, 164, 1);
	box-shadow: 0px 0px 5px 0px rgba(164, 164, 164, 1);
}

.popbox h2 {
	background-color: #4D4F53;
	color: #E3E5DD;
	font-size: 14px;
	display: block;
	width: 100%;
	margin: -10px 0px 8px -10px;
	padding: 5px 10px;
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
	<div id="pop1" class="popbox" width="100" height="100">
		<h2>MOVIE INFO</h2>
		<img src="./images/front.jpg" id="popImg" width="100" height="150"></img>
		<p id="popName" >Name:</p>
		<p id="popGenre" >Genre:</p>
		<p id="popCast">Cast:</p>
		<p id="popDir">Director:</p>
		<p id="popYear">Year:</p>

	</div>

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
				request.open('GET', 'DB_music.php', true);
				request.send(null);

			}

			function onData(event) {

				var data = JSON.parse(event.target.responseText);
				var entries = data;
				var imgSrc;

			    var moveLeft = 20;
			    var moveDown = 10;
			
				
				for (var i = 0; i < entries.length; i++) {

					var entry = entries[i];
					//console.log("entry "+entry[3]);
					
					
					var element = document.createElement('div');
					element.style.width = '460px';
					element.style.height = '480px';
					element.style.border="1px solid black";
					element.style.boxShadow="5px 5px 1.2em black";

					var image = document.createElement('img');
					image.addEventListener('load', function(event) {

					var object = this.properties.object;
				   	var button = this.properties.button;

					button.style.visibility = 'visible';

						 //new TWEEN.Tween( object.position )
						//.to( { y: Math.random() * 2000 - 1000 }, 2000 )
						//	.easing( TWEEN.Easing.Exponential.Out )
						//	.start();

					}, false);
					image.style.position = 'absolute';
					image.style.width = '460px';
					image.style.height = '480px';
					imageName = entry[3];
					image.src='images/'+imageName;
					imgSrc = image.src;
					element.appendChild(image);

					var button = document.createElement('img');
					button.style.position = 'absolute';
					button.style.left = ((480 - 86 ) / 2 ) + 'px';
					button.style.top = ((360 - 61 ) / 2 ) + 'px';
					//button.style.visibility = 'hidden';
					//button.style.WebkitFilter = 'grayscale()';
					button.src = 'data:image/png;base64,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';
					element.appendChild(button);

					var blocker = document.createElement('div');
					blocker.style.position = 'absolute';
					blocker.style.width = '480px';
					blocker.style.height = '360px';
					blocker.style.background = 'rgba(0,0,0,0.5)';
					blocker.style.cursor = 'pointer';
					//element.appendChild(blocker);


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

					element.addEventListener('mouseover', function(e) {
						
					//this.properties.button.style.WebkitFilter = '';
					//this.properties.blocker.style.background = 'rgba(0,0,0,0)';
					
					  //PARSE THE IMAGE SRC URL	
					  var txt = $(this).html();
					  var re1='.*?';	// Non-greedy match on filler
				      var re2='(?:[a-z][a-z]+)';	// Uninteresting: word
				      var re3='.*?';	// Non-greedy match on filler
				      var re4='(?:[a-z][a-z]+)';	// Uninteresting: word
				      var re5='.*?';	// Non-greedy match on filler
				      var re6='((?:[a-z][a-z]+))';	// Word 1
				      var re7='((?:\\/[\\w\\.\\-]+)+)';	// Unix Path 1
				      var word1;
				      var unixpath1;
				      var fullSrc;

				      var p = new RegExp(re1+re2+re3+re4+re5+re6+re7,["i"]);
				      var m = p.exec(txt);
				      if (m != null)
				      {
				          word1=m[1];
				          unixpath1=m[2];
				      }

					  fullSrc = word1+unixpath1;

				      	//SHOW THE POP-UP			
						$('#pop1').show()

						.css('top', e.pageY + moveDown)
					      .css('left', e.pageX + moveLeft)
					      .appendTo('body');

						$("#popImg").attr('src',fullSrc);


					}, false);

					element.addEventListener('mouseout', function() {

						//element.properties.button.style.WebkitFilter = 'grayscale()';
						//element.properties.blocker.style.background = 'rgba(0,0,0,0.75)';
						$('#pop1').hide();
										        
					}, false);

					element.addEventListener('mousemove', function(e){
						$("#pop1").css('top', e.pageY + moveDown).css('left', e.pageX + moveLeft);
						
					  });
						

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
