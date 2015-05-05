(function (window) {
    var commentsToggle = document.getElementById("comments-toggle");
    var commentsToggleText = document.getElementById("comments-toggle-text");
    var comments = document.getElementById("comments");
    var visible = false;

    commentsToggle.addEventListener("click", function () {
        // toggle visibility
        visible = !visible;

        if (visible) {
            comments.className = "comments-expanded";
            commentsToggleText.textContent = "Hide Comments";
        } else {
            comments.className = "comments-shrunk";
            commentsToggleText.textContent = "Comments";
        }
    });

})(window);