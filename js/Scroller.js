(function(window) {
	var offset = 1;

	window.onscroll = function(event) {
		if ((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight) {
			// console.log(window.screen.height);
			// alert("Bottom of page");
			var chefHat = document.getElementById("chef-hat");
			// chefHat.style.transform = 'rotate(360deg)';
			chefHat.className = "rotate-chef-hat";

			getMoreArticles(offset);
			offset++;
		}
	};

})(window);


function getMoreArticles(offset) {
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
    		// document.getElementById("author").value = "";
    		// document.getElementById("comment").value = "";
    		// document.getElementById("grid-articles").innerHTML += httpRequest.responseText;
    		var responseJSON = JSON.parse(httpRequest.responseText);
    		// alert(JSON.parse(httpRequest.responseText).length);

    		for (var i = 0; i < responseJSON.length; i++) {
				alert(Object.keys(responseJSON[i]));    			

    			// for (var key in responseJSON[i]) {
   				// 	// alert(' name=' + key + ' value=' + responseJSON[i][key]);
   				// 	alert(key);
   				// }
    		}
    	}
    }

    httpRequest.open("POST", "/MyCMS/getMoreArticles.php");
    httpRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    httpRequest.send("offset=" + offset);
}