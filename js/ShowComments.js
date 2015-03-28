(function (window) {
    var commentsToggle = document.getElementById("comments-toggle");
    var comments = document.getElementById("comments");
    var visible = false;

    commentsToggle.addEventListener("click", function () {
        // toggle visibility
        visible = !visible;

        if (visible) {
            comments.className = "comments-expanded";
            commentsToggle.textContent = "Hide Ponderings";
        } else {
            comments.className = "comments-shrunk";
            commentsToggle.textContent = "Other Ponderings";
        }
    });

})(window);