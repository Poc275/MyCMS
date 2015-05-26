function editBlogEntry(id) {
	var httpRequest;
    var articleIdInput = document.getElementById("article-id");
	var titleInput = document.getElementById("title");
	var summaryInput = document.getElementById("summary");
	var tagsInput = document.getElementById("tags");
	var bannerImagePathInput = document.getElementById("banner-image-path");
	var instructionsInput = document.getElementById("wmd-instructions");
	var contentInput = document.getElementById("wmd-input");

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

    		// populate form with article info we are editing
            articleIdInput.value = responseJSON.articleId;
    		titleInput.value = responseJSON.articleTitle;
    		summaryInput.value = responseJSON.articleSummary;
    		tagsInput.value = responseJSON.articleTags;
    		bannerImagePathInput.value = responseJSON.articleBannerImage;
    		instructionsInput.value = responseJSON.articleInstructions;
    		contentInput.value = responseJSON.articleContent;

    		modifyFormButtonForEditing();
    	}
    }

    httpRequest.open("POST", "getBlogEntryForEditing.php", true);
    httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    httpRequest.send("id=" + id);
}


// modify submit button to become an edit button, and add a cancel button
function modifyFormButtonForEditing() {
	var submitButton = document.getElementById("submit-btn");
	submitButton.value = "edit";
	submitButton.name = "edit";
	submitButton.innerHTML = "Edit";

	// check if the cancel button exists (!! forces a boolean return)
	if (!!document.getElementById("cancel-btn") !== true) {
		var cancelButton = document.createElement("button");
		cancelButton.setAttribute("id", "cancel-btn");
		cancelButton.value = "reset";
		cancelButton.type = "reset";
		cancelButton.innerHTML = "Cancel";
		cancelButton.addEventListener("click", resetFormButton);

		var form = document.getElementsByTagName("form");
		form[0].appendChild(cancelButton);
	}	
}


// reset form button to submit when editing is cancelled, and remove the cancel button
function resetFormButton() {
	var submitButton = document.getElementById("submit-btn");
	submitButton.value = "submit";
	submitButton.name = "submit";
	submitButton.innerHTML = "Submit";

	// clear form
	document.getElementsByTagName("form")[0].reset();

	var cancelButton = document.getElementById("cancel-btn");
	var cancelButtonParentNode = cancelButton.parentNode;
	cancelButtonParentNode.removeChild(cancelButton);
}