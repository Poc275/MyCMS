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
   				var imageUrl = "img/" + responseJSON[i].articleBannerImage;
   				var url = 'articles/' + responseJSON[i].articleUrl;

          var newArticleTag = document.createElement('article');

   				var newAnchorTag = document.createElement('a');
   				newAnchorTag.setAttribute('href', url);
   				newAnchorTag.className = "no-decoration";

          var newImageTag = document.createElement('img');
          newImageTag.setAttribute('src', imageUrl);
          newImageTag.setAttribute('alt', responseJSON[i].articleTitle);
   				
   				var newDivTag = document.createElement('div');
   				newDivTag.className = "article-info";

   				var newHeaderTag = document.createElement('h3');
   				var headerText = document.createTextNode(responseJSON[i].articleTitle);
   				newHeaderTag.appendChild(headerText);

   				var newParaTag = document.createElement('p');
   				var paraText = document.createTextNode(responseJSON[i].articleSummary);
   				newParaTag.appendChild(paraText);

          var newSmallTag = document.createElement('small');
          var newClockIconTag = document.createElement('i');
          newClockIconTag.className = "fa fa-clock-o";
          var smallText = document.createTextNode(' ' + responseJSON[i].articleDate);
          newSmallTag.appendChild(newClockIconTag);
          newSmallTag.appendChild(smallText);

   				newDivTag.appendChild(newHeaderTag);
   				newDivTag.appendChild(newParaTag);
          newDivTag.appendChild(newSmallTag);

   				newAnchorTag.appendChild(newImageTag);
          newAnchorTag.appendChild(newDivTag);

          newArticleTag.appendChild(newAnchorTag);

   				gridArticles.appendChild(newArticleTag);
    		}
    	}
    }

    httpRequest.open("POST", "getMoreArticles.php");
    httpRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    httpRequest.send("offset=" + offset);
}