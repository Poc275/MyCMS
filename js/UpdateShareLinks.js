(function (window) {
    // twitter supports a static endpoint without the need for a specific url
    document.getElementById("fb-link").href = "http://www.facebook.com/sharer/sharer.php?u=" + window.location.href;
    document.getElementById("google-link").href = "https://plus.google.com/share?url=" + window.location.href;
})(window);