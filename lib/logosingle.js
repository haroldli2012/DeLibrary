/*set canvas dimension width and height
and include <script> to use the logo
<canvas id='canvas' width='130' height='48'>
<script src="/share/lib/logosingle.js"></script>
background-color is white, logo is orangered
*/
var canvas = document.getElementById("canvas");
var ctx = canvas.getContext("2d");

var radius = canvas.width / 4;
var r = radius * 0.14;
ctx.translate(radius*3, radius+r*0.3);

ctx.font="25px Microsoft Yahei";
ctx.fillStyle="orangered";
ctx.fillText("和乐",-radius*2.5,0);

ctx.moveTo(-r*3,-r*3);
ctx.bezierCurveTo(-r*7,r*4,r*7,r*4,r*3,-r*3);
ctx.fillStyle = "orangered";
ctx.fill();

ctx.beginPath();
ctx.moveTo(-r*2,r*0.5);
ctx.quadraticCurveTo(0,r*1,r*2,r*0.5);
ctx.moveTo(-r*2,r*0.5);
ctx.quadraticCurveTo(0,r*2,r*2,r*0.5);
ctx.lineWidth=r*0.2;
ctx.lineCap="round";
ctx.strokeStyle="white";
ctx.stroke();

ctx.beginPath();
ctx.moveTo(-r*3.5,-r*1.5);
ctx.lineTo(-r*5.5, -r*1.8);
ctx.lineTo(-r*5.8, r*1.0);
ctx.strokeStyle="orangered";
ctx.lineCap="round";
ctx.lineJoin="round";
ctx.lineWidth=r*0.6;
ctx.stroke();

ctx.beginPath();
ctx.moveTo(-r*3.5,-r*0.6);
ctx.lineTo(-r*4.8, -r*0.6);
ctx.lineTo(-r*4.8, r*1.7);
ctx.strokeStyle="orangered";
ctx.lineCap="round";
ctx.lineJoin="round";
ctx.lineWidth=r*0.6;
ctx.stroke();

ctx.beginPath();
ctx.moveTo(-r*3.5,r*0.3);
ctx.lineTo(-r*4.1, r*0.6);
ctx.lineTo(-r*3.9, r*2.1);
ctx.strokeStyle="orangered";
ctx.lineCap="round";
ctx.lineJoin="round";
ctx.lineWidth=r*0.6;
ctx.stroke();

ctx.beginPath();
ctx.moveTo(r*3.5,-r*1.5);
ctx.lineTo(r*5.5, -r*1.8);
ctx.lineTo(r*5.8, r*1.0);
ctx.strokeStyle="orangered";
ctx.lineCap="round";
ctx.lineJoin="round";
ctx.lineWidth=r*0.6;
ctx.stroke();

ctx.beginPath();
ctx.moveTo(r*3.5,-r*0.6);
ctx.lineTo(r*4.8, -r*0.6);
ctx.lineTo(r*4.8, r*1.7);
ctx.strokeStyle="orangered";
ctx.lineCap="round";
ctx.lineJoin="round";
ctx.lineWidth=r*0.6;
ctx.stroke();

ctx.beginPath();
ctx.moveTo(r*3.5,r*0.3);
ctx.lineTo(r*4.1, r*0.6);
ctx.lineTo(r*3.9, r*2.1);
ctx.strokeStyle="orangered";
ctx.lineCap="round";
ctx.lineJoin="round";
ctx.lineWidth=r*0.6;
ctx.stroke();

ctx.beginPath();
ctx.arc(-r*1.5,-r*3.5,r*0.5,0,2*Math.PI);
ctx.lineWidth=r*0.5;
ctx.stroke();
ctx.beginPath();
ctx.arc(r*1.5,-r*3.5,r*0.5,0,2*Math.PI);
ctx.lineWidth=r*0.5;
ctx.stroke();

ctx.beginPath();
ctx.moveTo(-r*3.2,-r*2.4);
ctx.lineTo(-r*4.5, -r*3);
ctx.lineTo(-r*5.5, -r*4.5);
ctx.strokeStyle="orangered";
ctx.lineCap="round";
ctx.lineJoin="round";
ctx.lineWidth=r*0.8;
ctx.stroke();

ctx.beginPath();
ctx.arc(-r*5.5,-r*5.5,r*1.5,-0.2*Math.PI,0.8*Math.PI);
ctx.fillStyle="orangered";
ctx.fill();
ctx.beginPath();
ctx.arc(-r*5.5,-r*5.5,r*1.5,0.4*Math.PI,1.4*Math.PI);
ctx.fillStyle="orangered";
ctx.fill();

ctx.beginPath();
ctx.moveTo(r*3.2,-r*2.4);
ctx.lineTo(r*4.5, -r*3);
ctx.lineTo(r*5.5, -r*4.5);
ctx.strokeStyle="orangered";
ctx.lineCap="round";
ctx.lineJoin="round";
ctx.lineWidth=r*0.8;
ctx.stroke();

ctx.beginPath();
ctx.arc(r*5.5,-r*5.5,r*1.5,0.2*Math.PI,-0.8*Math.PI);
ctx.fillStyle="orangered";
ctx.fill();
ctx.beginPath();
ctx.arc(r*5.5,-r*5.5,r*1.5,-0.4*Math.PI,-1.4*Math.PI);
ctx.fillStyle="orangered";
ctx.fill();

$(document).ready(function(){
  $("#canvas").click(function(){
     window.open("/share/index.html")
  });
});