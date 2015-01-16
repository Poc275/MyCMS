(function () {
	var converter1 = Markdown.getSanitizingConverter();
    var editor1 = new Markdown.Editor(converter1);
    editor1.run();
})();

function convertMarkdownToHTML() {
	var previewDiv = document.getElementById("wmd-preview");
	var htmlCode = document.createTextNode(previewDiv.innerHTML);
	document.getElementById("html-preview").appendChild(htmlCode);
}