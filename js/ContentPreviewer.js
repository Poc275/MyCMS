function convertMarkdownToHTML() {
	var httpRequest;
	var mdTextArea = document.getElementById("wmd-input");
    var mdInstructions = document.getElementById("wmd-instructions");
	var htmlSource = document.getElementById("html-source");
	var htmlPreview = document.getElementById("main-article");
    var instructions = document.getElementById("instructions");

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
    		var responseJSON = JSON.parse(httpRequest.responseText);

            instructions.innerHTML = responseJSON.instructions;

            htmlSource.textContent = responseJSON.article;
    		htmlPreview.innerHTML = responseJSON.article;
            
            updatePreview();
    	}
    }

    httpRequest.open("POST", "convertMarkdown.php", true);
    httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    httpRequest.send("markdown=" + mdTextArea.value + "&instructions=" + mdInstructions.value);
}


function updatePreview() {
    var date = new Date();
    var articleHeader = document.getElementById("article-header");
    var articleInfoAside = document.getElementById("article-info");

    // form values
    var articleTitle = document.getElementById("title").value;
    var articleSummary = document.getElementById("summary").value;
    var articleTags = document.getElementById("tags").value;
    var articleBannerImage = document.getElementById("banner-image-path").value;

    // set header preview
    articleHeader.style.backgroundImage = 
        "linear-gradient(to bottom, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.6)), url('img/" + articleBannerImage + "')";
    articleHeader.getElementsByTagName("h1")[0].innerHTML = articleTitle;
    articleHeader.getElementsByTagName("p")[0].innerHTML = "tldr&#59; </abbr>" + articleSummary;

    // set article info preview
    document.getElementById("article-date").innerHTML = " " + date.toDateString();
    document.getElementById("article-tags").innerHTML = " " + articleTags;
}


function quickPick(pick) {
    var mdOutput = "";

    switch (pick) {

        case 'phil':
            mdOutput = "<p class=\"phil\" markdown=\"1\">This is Phil...</p>";
            break;

        case 'pete':
            mdOutput = "<p class=\"pete\" markdown=\"1\">This is Pete...</p>";
            break;

        case 'img-caption':
            mdOutput = "<p class=\"caption\" markdown=\"1\">This is an image caption</p>";
            break;

        case 'pull-quote':
            mdOutput = "<p class=\"pull-quote\"><i class=\"fa fa-quote-left fa-2x fa-pull-left\"></i>Quote goes here</p>";
            break;
    }

    insertAtCursor(mdOutput);
}


// source: http://stackoverflow.com/questions/11076975/insert-text-into-textarea-at-cursor-position-javascript
function insertAtCursor(content) {
    var mdInputTextArea = document.getElementById("wmd-input");

    //IE support
    if (document.selection) {
        mdInputTextArea.focus();
        sel = document.selection.createRange();
        sel.text = content;
    }

    //MOZILLA and others
    else if (mdInputTextArea.selectionStart || mdInputTextArea.selectionStart == '0') {
        var startPos = mdInputTextArea.selectionStart;
        var endPos = mdInputTextArea.selectionEnd;
        mdInputTextArea.value = mdInputTextArea.value.substring(0, startPos)
            + content
            + mdInputTextArea.value.substring(endPos, mdInputTextArea.value.length);
    } else {
        mdInputTextArea.value += content;
    }
}