//city classify by name first word
document.getElementById("zones").innerHTML="<span id='zone1'>热点</span>"+
"<span id='zone2'>ABCDE</span>"+
"<span id='zone3'>FGHJ</span>"+
"<span id='zone4'>KLMNP</span>"+
"<span id='zone5'>QRST</span>"+
"<span id='zone6'>WXYZ</span>";

$(document).ready(function(){

  //upload footer content
  $("#footer").load("/lib/footer.php");

    $(".dropbtn").click(function(){
      $(".dropdown-content").slideToggle("quick");
        event.stopPropagation();
    });

    var lati;
    var longi;

    //check city session choosed by customer, then get city cookie
    var city = $(".location span").html();
    if(!/[\u3400-\u9FBF]+/.test(city)) {
       city=getCookie("city");
     }

    var user=getCookie("user");

  //find client city by Baidu API
  if(/[\u3400-\u9FBF]+/.test(city)){
      $(".location span").html(city);
     }
  else {
    //find city by latitude and longitude

    if (navigator.geolocation)
     {
        navigator.geolocation.getCurrentPosition(showPosition,showError);
      }
    else
     {
          $(".location span").load("/lib/cityip.php",{x:0,y:0},function(txt){
            
          });
      }
  
    function showPosition(position)
      {
        lati = position.coords.latitude;
        longi = position.coords.longitude;	
        if(typeof(lati)=="number" && typeof(longi)=="number") {
          var la = lati;
          var lo = longi;
          $(".location span").load("/lib/cityip.php",{x:la,y:lo},function(txt){
           
          });
        }
       else {
          $(".location span").load("/lib/cityip.php",{x:0,y:0},function(txt){
            
           });
        }
       }
    function showError(error)
      {
        switch(error.code) 
          {
           case error.PERMISSION_DENIED:
            {lati=0; longi=0;}
             break;
           case error.POSITION_UNAVAILABLE:
            {lati=0; longi=0;}
             break;
           case error.TIMEOUT:
            {lati=0; longi=0;}
             break;
           case error.UNKNOWN_ERROR:
            {lati=0; longi=0;}
             break;
           }
        if(lati==0 && longi==0) {
          $(".location span").load("/lib/cityip.php",{x:0,y:0},function(txt){
            
          });
        }
      }
/*   if(lati==0 && longi==0) {
       $.post("/lib/cityip.php",{x:0,y:0},function(txt){
        var text = txt.replace(/200/,"");
        obj = JSON.parse(text);
        if(typeof obj.content !== "undefined") {
          var city = obj.content.address_detail.city.replace(/市/,"");
          $(".location span").html(txt);
          $("#nimei").html(lati +"n"+ longi + txt + lati +'m'+ longi);
      }
     });
    } */
   }

  //load city into selection element
    $("#cities").load("/lib/showCityByPY.php",{q:"热点"});

   $("#zones span").click(function(){
      //load cities by pingying selection
      $("#zones span").css("background-color","#D0F8F8");
      $(this).css("background-color","white");
      $("#cities").load("/lib/showCityByPY.php",{q:$(this).html()});
   });

  //show city options with proper event
  $(".location").click(function(){
     event.stopPropagation();
     $("#city").slideToggle("quick");
     if($(this).find("i").last().css("display")=="none") {
       $(this).find("i").last().css("display","inline");
       $(this).find("i").first().css("display","none");
       
     }
     else {
       $(this).find("i").last().css("display","none");
       $(this).find("i").first().css("display","inline");
     }
  });
    $("#city").click(function(){
        event.stopPropagation();
      });
    //city area slide up when click other documents
    $(document).click(function(){
       $("#city").slideUp();
       $(".location").first().find("i").last().css("display","none");
       $(".location").first().find("i").first().css("display","inline");
       $(".location").last().find("i").last().css("display","none");
       $(".location").last().find("i").first().css("display","inline");
      $(".dropdown-content").slideUp();
    });
});

$(document).ready(function(){
   //choose city and set cookies
   $("#cities").on("click","td",function(){
      setCookie("city",$(this).html(),3);
      $.post("/lib/newsession.php",{name:"city",value:$(this).html()},function(data,status){
         if(status=="success")  location.reload();
      });

   });

   //show login interface, refresh sucrity image
   $(".span").click(function(){
      $("#login").show();
      $(".loginback").show();
      $("#captcha").attr("src","/securimage/securimage_show.php?"+Math.random());
   });

   //close login dialog
   $("#x").click(function(){
      $("#login").hide();
      $(".loginback").hide();
   });

   //security image load
  // $("#secur").load("/lib/mysecurimage.php");

   //login input and action
   $("#logbutton").click(function(){
      var n=document.getElementsByName("myname")[0].value;
      var p=document.getElementsByName("pass")[0].value;
      var s=document.getElementsByName("code")[0].value;
      $("#error").load("/lib/login.php",{name:n,pass:p,secur:s});
      
   });

    //login by name
    $("#sp1").click(function(){
       $(".cellphone").css("display","none");
       $(".subnavbar span").css("border","none");
       $("#sp1").css("border-bottom","3px solid orangered");
       $(".account").css("display","block");
       
   });

   //login by cellphone
    $("#sp2").click(function(){
       $(".cellphone").css("display","block");
       $(".subnavbar span").css("border","none");
       $("#sp2").css("border-bottom","3px solid orangered");
       $(".account").css("display","none"); 
   });
   
   //refresh the securimage code
    $(".refresh").click(function(){
      $("#captcha").attr("src","/securimage/securimage_show.php?"+Math.random());
      //return false;
    });

   //send the phone security code
    $(".sms").click(function(){
      var i=60;
      var cellphone=$("[name='cellphone']").val();
      var reg = /^1(3[0-9]|4[57]|5[0-35-9]|70|8[0-9])\d{8}$/;
      if(reg.test(cellphone)) {
        $("#error").load("/lib/smsSend.php",{phone:cellphone},function(txt){
          if(txt==0) {
            $(".sms").attr("disabled",true);
            var smsT = setInterval(timer,1000);
           }
          function timer() {
            $(".sms").html("验证码已发送..." + i);
            i--;
            if(i==0) {
              clearInterval(smsT);
              $(".sms").html("发送验证码");
              $(".sms").attr("disabled",false);
            }
          }
        });
      }
      else
        $("#cellphone").html("号码有误,请确认！");

    });

});

     //cellphone active code to login or regist

       function remind(elem){
          
          elem.parentNode.nextElementSibling.innerHTML="<i class='iconfont'>&#xe615;</i> " + elem.getAttributeNode("title").value;
               elem.parentNode.nextElementSibling.style.color="black";
        }

       function inputCheck(elem){
           var inputname=elem.getAttributeNode("name").value;
           var value=elem.value;
          //use hidden cbridge to stop login once check failed
          if(elem.checkValidity()==false) {
              elem.parentNode.nextElementSibling.innerHTML="<i class='iconfont'>&#xe62d;</i> " + elem.getAttributeNode("title").value;
              elem.parentNode.nextElementSibling.style.color="red";
            }
          else {
               elem.parentNode.nextElementSibling.innerHTML="<i class='iconfont'>&#xe717;</i>";
               elem.parentNode.nextElementSibling.style.color="black";
               if(inputname=="cellphone") {
                  $("#cellphone").load("/lib/namevalidation.php",{cellphone:value});
                 }
            }
         }

       function confirmPass3(elem){
          var pass=document.getElementsByName("pass3")[0].value;
          if(elem.value==pass) {
              if(elem.checkValidity()!=false) {
                elem.parentNode.nextElementSibling.innerHTML="<i class='iconfont'>&#xe717;</i>";
                elem.parentNode.nextElementSibling.style.color="black";
               }
              else {
                elem.parentNode.nextElementSibling.innerHTML="<i class='iconfont'>&#xe62d;</i> " + "8位以上数字或字母！";
              elem.parentNode.nextElementSibling.style.color="red";
               }
             }
          else {
              elem.parentNode.nextElementSibling.innerHTML="<i class='iconfont'>&#xe62d;</i> " + "密码不一致";
              elem.parentNode.nextElementSibling.style.color="red";
             }             
       }

$(document).ready(function(){
   $("#toRegist").click(function(){
       $(".noRegist").toggle();
       //$(":password").prop("required",!$(":password").prop("required"));
       //$("#agree").prop("checked",!$("#agree").prop("checked"));
   });

   $("#agree").click(function(){
       if($("#agree").is(":checked")) $("#noAgree").hide();
       else $("#noAgree").show();
   });
   
   $("#phoneregist").click(function(){
       
       elem1=document.getElementsByName("cellphone")[0];
       if(elem1.checkValidity()!= false) {var myphone = elem1.value;}
       else {var myphone = ""; }

       elem2=document.getElementsByName("phonecode")[0];
       if(elem2.checkValidity()!= false) {var mycode = elem2.value;}
       else {var mycode = ""; }

       elem3=document.getElementsByName("pass3")[0];
       if(elem3.checkValidity()!= false) {var mypass1 = elem3.value;}
       else {var mypass1 = ""; }

       elem4=document.getElementsByName("pass4")[0];
       if(elem4.checkValidity()!= false) {var mypass2 = elem4.value;}
       else {var mypass2 = ""; }

       //complete regist and login
       if(($("#toRegist").is(":checked"))&&($("#agree").is(":checked"))){
          if((myphone!="")&&(mycode!="")&&(mypass1!="")&&(mypass2!="")&&(mypass1==mypass2)) {
             $.post("/lib/registphp.php",{cellphone:myphone,code:mycode,pass1:mypass1,pass2:mypass2}, function(txt, sta){
  if(sta=="success") {
   switch(Number(txt))
     {
       case 0:
         $(".phoneerror").html("成功注册！");
         $(".phoneerror").css("color","black");
         setCookie("user",myphone,30);
         setCookie("pass",mypass2,30);
         location.reload();
         break;
       case 1:
         $(".phoneerror").html("手机号或密码不匹配！");
         break;
       case 2:
         $(".phoneerror").html("两个密码不一致！");
         break;
       case 3:
         $(".phoneerror").html("请输入手机号和验证码！");
         break;
       case 4:
         $(".phoneerror").html("手机号和验证码不匹配！");
         break;
       case 5:
         $(".phoneerror").html("验证码不对或超时！");
         break;
       case 6:
         $(".phoneerror").html("验证码不存在！");
         break;
       default:
         $(".phoneerror").html("验证失败，请重试！");
      }
     }
             });
           }
           else if((myphone=="")||(mycode=="")) {
            $(".phoneerror").html("请输入正确的手机号和验证码！");
           }
           else $(".phoneerror").html("请正确设置密码！");
       }
      else if(($("#toRegist").is(":checked"))&&!($("#agree").is(":checked"))) {
         $("#noAgree").html("请确认同意服务协议!");
         $("#noAgree").css("color","red");
      }
      else if(!($("#toRegist").is(":checked"))) {
          //save user info without password as not-register status, and login
          if((myphone!="")&&(mycode!="")) {
             $.post("/lib/registphp.php",{cellphone:myphone,code:mycode},function(txt,sta){
  if(sta=="success") {
   switch(Number(txt))
     {
       case 0:
         $(".phoneerror").html("成功登录！");
         $(".phoneerror").css("color","black");
         location.reload();
         break;
       case 1:
         $(".phoneerror").html("手机号或密码不匹配！");
         break;
       case 2:
         $(".phoneerror").html("两个密码不一致！");
         break;
       case 3:
         $(".phoneerror").html("请输入手机号和验证码！");
         break;
       case 4:
         $(".phoneerror").html("手机号和验证码不匹配！");
         break;
       case 5:
         $(".phoneerror").html("验证码不对或超时！");
         break;
       case 6:
         $(".phoneerror").html("验证码不存在！");
         break;
       default:
         $(".phoneerror").html("验证失败，请重试！");     
      }
    }
              });
           }
           else $(".phoneerror").html("请输入正确的手机号和验证码！");
      }
      else {}
   });

  //transfer to user profile page
    $(".right").on("click",".user",function(){
       window.location.assign("user.php");
    });

  //quit login
    $(".right").on("click",".quit",function(){
       setCookie("user",1,-1);
       setCookie("pass",1,-1);
       $.post("/lib/sessionDel.php",function(txt){
         if(txt=="0") window.location.replace("index.php");
         else window.location.replace("index.php");
       });

      WB2.logout(function(){ });
      document.getElementsByClassName("login_a")[0].click();
    });


});



//function register() { window.open("/register.php");}

function setCookie(cname,cvalue,exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires ="expires=" + d.toGMTString();
    document.cookie = cname + "=" + cvalue + ";" + expires;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function checkCookie() {
    var city=getCookie("city");
    var user=getCookie("user");
    var pass=getCookie("pass");

    if(/^[\u3400-\u9FBF]+$/.test(city)) {
        setCookie("city",city,30);
    } 

    if (user != "") {
        setCookie("user",user,30);
    }
    else {
  //微博登录
  WB2.anyWhere(function (W) {

    W.widget.connectButton({

        id: "wb_connect_btn",

        type: '4,5',

        callback: {

            login: function (o) { //登录后的回调函数

   $.post("/lib/wbloginphp.php",
     {wbname:o.screen_name,city:o.city,gender:o.gender},
      function(txt,sta){
        if(sta=="success") {
           $("#login").hide();
           $(".loginback").hide();
           if(txt==100 )  {
              setCookie("user",o.screen_name,30);
              $(".right").html("<span class='user'>"+o.screen_name+"</span> <span class='quit'>退出</span>");
           }
         }
     });
            },

            logout: function () { //退出后的回调函数

            }
        }

    });

});

   //QQ登录，调用QC.Login方法，指定btnId参数将按钮绑定在容器节点中
   QC.Login({
       //btnId：插入按钮的节点id，必选
       btnId:"qqLoginBtn",    
       //用户需要确认的scope授权项，可选，默认all
       scope:"all",
       //按钮尺寸，可用值[A_XL| A_L| A_M| A_S|  B_M| B_S| C_S]，可选，默认B_S
       size: "B_S"
   }, function(reqData, opts){//登录成功

   $.post("/lib/wbloginphp.php",
     {wbname:reqData.nickname,city:"中国",gender:reqData.gender},
      function(txt,sta){
        if(sta=="success") {
           $("#login").hide();
           $(".loginback").hide();
           if(txt==100 )  {
              setCookie("user",reqData.nickname,30);
              $(".right").html("<span class='user'>"+reqData.nickname+"</span> <span class='quit'>退出</span>");
           }
         }
     });

       //根据返回数据，更换按钮显示状态方法
       var dom = document.getElementById(opts['btnId']),
       _logoutTemplate=[
            //头像
            '<span><img src="{figureurl}" class="{size_key}"/></span>',
            //昵称
            '<span>{nickname}</span>',
            //退出
            '<span><a href="javascript:QC.Login.signOut();">退出</a></span>'    
       ].join("");
       dom && (dom.innerHTML = QC.String.format(_logoutTemplate, {
           nickname : QC.String.escHTML(reqData.nickname), //做xss过滤
           figureurl : reqData.figureurl
       }));
   }, function(opts){//注销成功
         alert('QQ登录 注销成功');
         location.reload();
   }
);

    }
    
    if(pass !="") {
       setCookie("pass",pass,30);
    }
}




/*
var cbLoginFun = function(oInfo, oOpts){
    alert(oInfo.nickname); // 昵称

    alert(oOpts.btnId);    // 点击登录的按钮Id
};


var cbLogoutFun = function(){
    alert("注销成功!");
};

QC.Login({btnId:"qqLoginBtn"}, cbLoginFun, cbLogoutFun);

*/


