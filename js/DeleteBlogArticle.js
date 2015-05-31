function deleteBlogEntry(id) {
	// show confirmation popup
    if (window.confirm("Do you really want to delete article " + id + "?")) {
        requestDelete(id);
    }
}


function requestDelete(id) {
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
            if (httpRequest.responseText === "success") {
                window.location.assign("content.php");
            } else {
                alert("Error deleting the article");
            }
        }
    }

    httpRequest.open("POST", "deleteBlogEntry.php", true);
    httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    httpRequest.send("id=" + id);
}