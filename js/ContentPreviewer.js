function convertMarkdownToHTML() {
	var httpRequest;
	var mdTextArea = document.getElementById("wmd-input");
	var htmlSource = document.getElementById("html-source");
	var htmlPreview = document.getElementById("html-preview");

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
    		htmlSource.textContent = httpRequest.responseText;
    		htmlPreview.innerHTML = httpRequest.responseText;
    	}
    }

    httpRequest.open("POST", "convertMarkdown.php", true);
    httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    httpRequest.send("markdown=" + mdTextArea.value);
}