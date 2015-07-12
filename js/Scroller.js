(function(window) {
	var offset = 1;

	window.onscroll = function(event) {
		if ((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight) {
			var chefHat = document.getElementById("chef-hat");
      chefHat.classList.remove("rotate-chef-hat");
      chefHat.offsetWidth = chefHat.offsetWidth;
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
    		var gridArticles = document.getElementById("grid-articles");
    		var responseJSON = JSON.parse(httpRequest.responseText);

    		for (var i = 0; i < responseJSON.length; i++) {
   				var bgImageUrl = "url('/MyCMS/img/" + responseJSON[i].articleBannerImage + "')";
   				var url = '/MyCMS/articles/' + responseJSON[i].articleUrl;

   				var newAnchorTag = document.createElement('a');
   				newAnchorTag.setAttribute('href', url);
   				newAnchorTag.style.backgroundImage = bgImageUrl;
   				newAnchorTag.className = "no-decoration";

   				var newArticleTag = document.createElement('article');

   				var newDivTag = document.createElement('div');
   				newDivTag.className = "article-info";

   				var newHeaderTag = document.createElement('h3');
   				var headerText = document.createTextNode(responseJSON[i].articleTitle);
   				newHeaderTag.appendChild(headerText);

   				var newParaTag = document.createElement('p');
   				var paraText = document.createTextNode(responseJSON[i].articleSummary);
   				newParaTag.appendChild(paraText);

   				newDivTag.appendChild(newHeaderTag);
   				newDivTag.appendChild(newParaTag);

   				newArticleTag.appendChild(newDivTag);

   				newAnchorTag.appendChild(newArticleTag);

   				gridArticles.appendChild(newAnchorTag);
    		}
    	}
    }

    httpRequest.open("POST", "/MyCMS/getMoreArticles.php");
    httpRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    httpRequest.send("offset=" + offset);
}