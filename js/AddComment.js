(function(window) {
	var postCommentButton = document.getElementById("post-comment-btn");

	postCommentButton.addEventListener("click", function(event) {
		event.preventDefault();

		var httpRequest;
	    var commentAuthor = document.getElementById("author").value;
		var comment = document.getElementById("comment").value;
		var articleNumber = document.getElementById("article-num").innerHTML.trim();

		// document.writeln(articleNumber);

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
	    		document.getElementById("comments").innerHTML += httpRequest.responseText;
	    	}
	    }

	    httpRequest.open("POST", "/MyCMS/addBlogComment.php");
	    httpRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	    httpRequest.send("author=" + commentAuthor + "&comment=" + comment + "&article=" + articleNumber);
	});

})(window);