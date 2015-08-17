(function (window) {
    var commentsToggle = document.getElementById("comments-toggle");
    var commentsToggleText = document.getElementById("comments-toggle-text");
    var originalCommentsToggleText = document.getElementById("comments-toggle-text").textContent.trim();
    var comments = document.getElementById("comments");
    var visible = false;

    if (originalCommentsToggleText !== "No comments yet, be the first?") {
            commentsToggle.addEventListener("click", function () {
            // toggle visibility
            visible = !visible;

            if (visible) {
                comments.className = "comments-expanded";
                commentsToggleText.textContent = "Hide Comments";
            } else {
                comments.className = "comments-shrunk";
                commentsToggleText.textContent = originalCommentsToggleText;
            }
        });
    }   

})(window);