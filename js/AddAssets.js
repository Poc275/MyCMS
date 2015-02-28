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
		//console.log(files);

		// render a preview
		for (var i = 0; i < files.length; i++) {
			var file = files[i];

			if (acceptedTypes[file.type] === true) {
				var reader = new FileReader();
				reader.onload = function(event) {
					var image = new Image();
					image.src = event.target.result;
					image.height = 100;
					drop.appendChild(image);
				};

				reader.readAsDataURL(file);
			}
		}

		uploadFiles(files);
		return false;
	};
}

function uploadFiles(files) {
	var formData = new FormData();

	for (var i = 0; i < files.length; i++) {
		formData.append('file', files[i]);
	}

	// post a XHR request
	var xhr = new XMLHttpRequest();
	xhr.open('POST', '/MyCMS/uploadImages.php');
	xhr.onload = function() {
		if (xhr.status === 200) {
			console.log('all done: ' + xhr.status);
			// TODO - render a preview now?
			// display thumbnail in home page
			updateThumbnailView();
		} else {
			console.log('something went awry...');
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