(function(window) {
	var body = document.body;
	var mask = document.createElement("div");
	var toggleSlideRight = document.querySelector(".toggle-slide-right");
	var slideMenuRight = document.querySelector(".slide-menu-right");
	var closeMenu = document.querySelector(".close-menu");

	var assetsPanel = document.getElementById("assets-panel");
	var syntaxPanel = document.getElementById("syntax-panel");

	var menuItemAssets = document.getElementById("menu-item-assets");
	var menuItemSyntax = document.getElementById("menu-item-syntax");

	mask.className = "mask";

	toggleSlideRight.addEventListener("click", function() {
		body.className = "smr-open";
		document.body.appendChild(mask);
	});

	mask.addEventListener("click", function() {
		body.className = "";
		document.body.removeChild(mask);
	});

	closeMenu.addEventListener("click", function() {
		body.className = "";
		document.body.removeChild(mask);
	});


	menuItemAssets.addEventListener("click", function() {
		assetsPanel.style.display = "block";
		syntaxPanel.style.display = "none";

		this.className = "active";
		menuItemSyntax.className = "";
	});

	menuItemSyntax.addEventListener("click", function() {
		assetsPanel.style.display = "none";
		syntaxPanel.style.display = "block";

		this.className = "active";
		menuItemAssets.className = "";
	});

})(window);