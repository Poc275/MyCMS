var acceptedTypes = {
	'image/png': true,
	'image/jpeg': true,
	'image/gif': true
}

function init() {
	var drop = document.getElementById("drop");

	drop.ondragover = function() {
		this.className = 'hover';
		return false;
	};

	drop.ondragend = function() {
		this.className = '';
		return false;
	};

	drop.ondrop = function(event) {
		event.preventDefault && event.preventDefault();
		this.className = '';

		var files = event.dataTransfer.files;
		var acceptedFiles = [];
		//console.log(files);

		// render a preview
		for (var i = 0; i < files.length; i++) {
			var file = files[i];

			if (acceptedTypes[file.type] === true) {
				acceptedFiles.push(file);
				var reader = new FileReader();
				reader.onload = function(event) {
					var image = new Image();
					image.src = event.target.result;
					image.height = 100;
					//drop.appendChild(image);
					
				};

				reader.readAsDataURL(file);
			}
		}

		uploadFiles(acceptedFiles);
		return false;
	};
}

/*
* Max file upload size is defined in php.ini - upload_max_filesize
* default is 2MB
*/
function uploadFiles(files) {
	var formData = new FormData();
	var progress = document.getElementById('progress');

	for (var i = 0; i < files.length; i++) {
		formData.append('file', files[i]);
	}

	// post a XHR request
	var xhr = new XMLHttpRequest();
	xhr.open('POST', '/MyCMS/uploadImages.php');
	xhr.onload = function() {
		progress.value = progress.innerHTML = 100;
		if (xhr.status === 200) {
			console.log('all done: ' + xhr.status);
			// TODO - render a preview now?
			// display thumbnail in home page
			updateThumbnailView();
		} else {
			console.log('something went awry...');
		}
	};

	// progress event listener
	xhr.upload.onprogress = function(event) {
		if (event.lengthComputable) {
			// | 0 is a bitwise floor shift, E.g. 2.69 becomes 2
			var complete = (event.loaded / event.total * 100 | 0);
			progress.value = progress.innerHTML = complete;
		}
	};

	xhr.send(formData);
}

function updateThumbnailView() {
	var httpRequest;

	// cross browser AJAX test
    if (window.XMLHttpRequest) {
        // Firefox, Chrome, Safari, Opera
        httpRequest = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        // IE
        try {
            httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e) {
            try {
                httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e) { }
        }
    }

    if (!httpRequest) {
        // cannot create an AJAX instance
    }

    httpRequest.onreadystatechange = function() {
    	if (httpRequest.readyState === 4 && httpRequest.status === 200) {
    		document.getElementById("assets").innerHTML = (httpRequest.responseText);
    	}
    }
    httpRequest.open("GET", "updateImagesView.php");
    httpRequest.send();
}

window.addEventListener('load', init, false);