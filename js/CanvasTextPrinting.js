// original code courtesy of K3N
// http://stackoverflow.com/questions/29911143/how-can-i-animate-the-drawing-of-text-on-a-web-page
function printTagName(tag) {
  var ctx = document.querySelector("canvas").getContext("2d");
  var dashLen = 220;
  var dashOffset = dashLen;
  var speed = 5;
  var txt = tag;
  var i = 0;

  ctx.font = "3.157em eraserregular";
  ctx.lineWidth = 1;
  ctx.lineJoin = "round";
  ctx.globalAlpha = 2/3;
  ctx.strokeStyle = ctx.fillStyle = "#ffffff";

  var x = 400 - ctx.measureText(tag).width / 2;
  
  ctx.textAlign = "center";
  ctx.fillText("TODAY'S SPECIAL:", 400, 150);

  ctx.textAlign = "left";

  var chalkSound = new Audio("assets/chalk-sound.ogg");
  chalkSound.play();

  (function loop() {
    // ctx.clearRect(x, 0, 60, 50);
    ctx.setLineDash([dashLen - dashOffset, dashOffset - speed]);                  // create a long dash mask
    dashOffset -= speed;                                                          // reduce dash length
    ctx.strokeText(txt[i], x, 220);                                               // stroke letter

    if (dashOffset > 0) requestAnimationFrame(loop);                              // animate
    else {
      ctx.fillText(txt[i], x, 220);                                               // fill final letter
      dashOffset = dashLen;                                                       // prep next char
      x += ctx.measureText(txt[i++]).width + ctx.lineWidth * Math.random() + 2;
      ctx.setTransform(1, 0, 0, 1, 0, 1 * Math.random());                         // random y-delta
      ctx.rotate(Math.random() * 0.005);                                          // random rotation
      
      if (i < txt.length) requestAnimationFrame(loop);
      else {
        chalkSound.pause();
        chalkSound.src = '';
      }
    }

  })();
}