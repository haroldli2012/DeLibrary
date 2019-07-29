$(document).ready(function(){
    $("#sp1").click(function(){
       $(".cellphone").css("display","block");
       $(".subnavbar span").css("border","none");
       $("#sp1").css("border-bottom","3px solid orangered");
       $(".mail").css("display","none");
       $(".account").css("display","none");
       $(".wechat").css("display","none");
       
   });

    $("#sp2").click(function(){
       $(".cellphone").css("display","none");
       $(".mail").css("display","block");
       $(".subnavbar span").css("border","none");
       $("#sp2").css("border-bottom","3px solid orangered");
       $(".account").css("display","none");
       $(".wechat").css("display","none");
       
   });

    $("#sp3").click(function(){
       $(".cellphone").css("display","none");
       $(".mail").css("display","none");
       $(".account").css("display","none");
       $(".wechat").css("display","block");
       $(".subnavbar span").css("border","none");
       $("#sp3").css("border-bottom","3px solid orangered");
       
   });

    $("#sp4").click(function(){
       $(".cellphone").css("display","none");
       $(".mail").css("display","none");
       $(".account").css("display","block");
       $(".subnavbar span").css("border","none");
       $("#sp4").css("border-bottom","3px solid orangered");
       $(".wechat").css("display","none");
   });

   $("#secur").load("/share/lib/mysecurimage.php");

   $("#logbutton").click(function(){
      var n=document.getElementsByName("myname")[0].value;
      var p=document.getElementsByName("pass")[0].value;
      var s=document.getElementsByName("code")[0].value;
      $("#error").load("/share/lib/login.php",{name:n,pass:p,secur:s});
      
   });
});

