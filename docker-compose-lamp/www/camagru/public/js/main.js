var width = 1280;
var height = 720;
var streaming = false;

var video = null;
var canvas = null;
var photo = null;
var capture = null;
var img = null;

image = new Image;
image.src = "http://i.imgur.com/RV2a28T.png";
// image.src = "http://img3.wikia.nocookie.net/__cb20140805014958/monsterhunter/images/e/e6/MH10th-Rajang_Icon.png";
// image.src = "http://3.bp.blogspot.com/-GOT_6c04LPY/U9uA7aZqWmI/AAAAAAAAAZw/4hCLLQh_CJk/s1600/j.png";
image.crossOrigin = "anonymous";  // This enables CORS

function clearphoto() {
	var context = canvas.getContext('2d');
	context.fillStyle = "#AAA";
	context.fillRect(0, 0, canvas.width, canvas.height);

	var data = canvas.toDataURL('image/png');
	photo.setAttribute('src', data);
}


function takepicture() {
	var context = canvas.getContext('2d');
	if (width && height) {
		canvas.width = width;
		canvas.height = height;
		//context.drawImage(image, dx, dy, dWidth, dHeight);
		// CanvasRenderingContext2D.drawImage()
		context.drawImage(video, 0, 0, width, height);
		img = canvas.toDataURL('image/png');
		context.drawImage(image, 0, 0, 200, 200);
		var data = canvas.toDataURL('image/png');
		photo.setAttribute('src', data);
	} else {
		clearphoto();
	}
}

function startup() {
	// image = document.getElementById("photo");
	video = document.getElementById("video");
	canvas = document.getElementById("canvas");
	photo = document.getElementById("preview");
	capture = document.getElementById("capture");
	document.getElementById("capture").disabled = true;
		
	// check if getUserMedia is undefined
	if (navigator.mediaDevices.getUserMedia) {
		// requests the type of media wanted audio/video or both.
		navigator.mediaDevices.getUserMedia({video: {
											width: { min: 480, ideal: width},
											height: { min: 320, ideal: height}
												}})
		// getUserMedia returns a promise. on success we recieve a stream.
		.then(function (stream) {
			// connect <video> tag with the recieved stream then play by calling HTMLMediaElement.play().
			video.srcObject = stream;
			video.play();
		})
		.catch(function (err) {
			console.log("ERROR : " + err);
		});
	}

	video.addEventListener('canplay', function(event){
		if (!streaming) {
			height = video.videoHeight / (video.videoWidth/width);

			if (isNaN(height)) {
				height = width / (4/3);
			}
			video.setAttribute('width', width);
			video.setAttribute('height', height);
			canvas.setAttribute('width', width);
			canvas.setAttribute('height', height);
			streaming = true;
		}
	}, false);

	capture.addEventListener('click', function(event){
		takepicture();
		event.preventDefault(); // only take the event once per click.
	});

	// enables capture button when clicked on any sticker.
	var stickers = document.getElementsByClassName("sticker");
	for (var i = 0; i < stickers.length; i++){
		stickers[i].setAttribute("name", i + 1);	
		stickers[i].addEventListener('click', function(e){
			var name = document.getElementById('sticker').getAttribute('name');
			if (name == 1)
			image.src = "http://i.imgur.com/RV2a28T.png";
			else if (name == 2)
				image.src = "http://3.bp.blogspot.com/-GOT_6c04LPY/U9uA7aZqWmI/AAAAAAAAAZw/4hCLLQh_CJk/s1600/j.png";
			else if (name == 3)
				image.src = "http://img3.wikia.nocookie.net/__cb20140805014958/monsterhunter/images/e/e6/MH10th-Rajang_Icon.png";
			else if (name == 4)
				image.src = "https://shortcut-test2.s3.amazonaws.com/uploads/project/attachment/8605/default_chibiyandere.png";
			else if (name == 5)
				image.src = "https://i.imgur.com/ZZZ2VUn.png";
			document.getElementById("capture").disabled = false;
			document.getElementById("sticker").setAttribute("name", this.getAttribute("name"));
			document.getElementById("pic").setAttribute("name", this.getAttribute("name"));
		}, false);
	}

	// sends POST request 
	document.getElementById("pic").addEventListener('click', function(e) {
		var fd = new FormData();
		fd.append("image", img);
		fd.append("name", document.getElementById('sticker').getAttribute('name'));
		var xhr = new XMLHttpRequest();
	
		// xhr.addEventListener('load', function (e) {
		// 		response from server.
		// 		alert('hello world');
		// })
		xhr.open("POST", 'http://localhost/camagru/pages/upload');
		xhr.send(fd);
	}, false);
	
	clearphoto();
}
