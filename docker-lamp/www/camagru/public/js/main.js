var width = 1280;
var height = 720;
var streaming = false;

var video = null;
var canvas = null;
var photo = null;
var capture = null;
var img = null;

image = new Image;
// image.src = "http://i.imgur.com/RV2a28T.png";
// image.crossOrigin = "anonymous";
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
	document.getElementById("pic").disabled = true;
	document.getElementById("sticker").disabled = true;

	// check if getUserMedia is undefined
	if (navigator.mediaDevices.getUserMedia) {
		// requests the type of media wanted audio/video or both.
		navigator.mediaDevices.getUserMedia({video: true})
		// getUserMedia returns a promise. on success we recieve a stream.
		.then(function (stream) {
			// connect <video> tag with the recieved stream then play by calling HTMLMediaElement.play().
			if ('srcObject' in video)
				video.srcObject = stream;
			else
				video.src = URL.createObjectURL(stream);
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
		event.preventDefault();
	});

	// enables capture button when clicked on any sticker.
	var stickers = document.getElementsByClassName("sticker");
	for (var i = 0; i < stickers.length; i++){
		stickers[i].setAttribute("name", i + 1);	
		stickers[i].addEventListener('click', function(e){
			var name = document.getElementById('sticker').getAttribute('name');
			if (name == 1)
				image.src = im[0];
			else if (name == 2)
				image.src = im[1];
			else if (name == 3)
				image.src = im[2];
			else if (name == 4)
				image.src = im[3];
			else if (name == 5)
				image.src = im[4];
			document.getElementById("capture").disabled = false;
			document.getElementById("pic").disabled = false;
			document.getElementById("sticker").disabled = false;
			document.getElementById("sticker").setAttribute("name", this.getAttribute("name"));
			document.getElementById("pic").setAttribute("name", this.getAttribute("name"));
		}, false);
	}
	document.getElementById("pic").addEventListener('click', function(e) {
		var fd = new FormData();
		fd.append("image", img);
		fd.append("name", document.getElementById('sticker').getAttribute('name'));
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200)	{
				var res = JSON.parse(this.responseText);
				var newpost = document.createElement('div');
				newpost.className = 'previewholder';
				newpost.setAttribute('name', res[0]);;
				newpost.innerHTML = `<div class="hundred row margin-auto wrapperr">
					<img class="photo margin-auto" src="` + res[1] + `">
					<div class="buttonn">
						<input type="submit" value="Delete" id="delete2" name="`+ res[0] +`" class="btn btn-danger btn-sm deleteb">
					</div>
				</div>`;
				var deleteb = newpost.getElementsByClassName('deleteb')[0];
				deleteb.addEventListener('click', function () {
							var fd = new FormData();
							fd.append(this.getAttribute('name'), "image_id");
							var xhr = new XMLHttpRequest();
							xhr.parent = this;
							xhr.onreadystatechange = function() {
								if (this.readyState == 4 && this.status == 200) {
									if (this.responseText === 'deleted'){
									var arr;
										arr = document.getElementsByClassName("previewholder");
										for(var i = 0; i < arr.length; i++){
											if(arr[i].getAttribute('name') === this.parent.getAttribute('name'))
												arr[i].parentNode.removeChild(arr[i]);
										}
									}
									else
										console.log('don\'t delete image html');
								}
							}
							xhr.open("POST", 'http://localhost/camagru/pages/delete');
							xhr.send(fd);
						}, false)
				var gallery = document.getElementsByClassName('personal-gallery')[0];
				gallery.insertBefore(newpost, gallery.childNodes[0]);
			}
			// console.log('here2');
		}
		xhr.open("POST", 'http://localhost/camagru/pages/upload');
		xhr.send(fd);

	}, false);
	clearphoto();




	var deletes = document.getElementsByClassName('deleteb');
	for (var i = 0; i < deletes.length; i++){
		
		deletes[i].addEventListener('click', function(){
			var fd = new FormData();
			fd.append(this.getAttribute('name'), "image_id");
			var xhr = new XMLHttpRequest();
			xhr.parent = this;
			xhr.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					if (this.responseText === 'deleted'){
					var arr;
						arr = document.getElementsByClassName("previewholder");
						for(var i = 0; i < arr.length; i++){
							if(arr[i].getAttribute('name') === this.parent.getAttribute('name'))
								arr[i].parentNode.removeChild(arr[i]);
						}
					}
					else
						console.log('don\'t delete image html');
				}
			}
			xhr.open("POST", 'http://localhost/camagru/pages/delete');
			xhr.send(fd);
		}, false);
	}
}
