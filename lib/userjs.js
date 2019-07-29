$("#user").addClass("active");

$(document).ready(function(){
  
    var city=getCookie("city");
    var user=getCookie("user");

    var myzone = $("#countryselect").val();
    
    $("#country").load("/lib/selectCountry.php",{q: $("[name='city']").val(),z:myzone});


    $(".baseinfo").click(function(){
       $(".baseinfo-detail").css("display","block");
       $(".panxie-detail").css("display","none");
       $(".history-detail").css("display","none");
    });

    $(".panxie").click(function(){
       $(".baseinfo-detail").css("display","none");
       $(".panxie-detail").css("display","block");
       $(".history-detail").css("display","none");
    });

    $(".history").click(function(){
       $(".baseinfo-detail").css("display","none");
       $(".panxie-detail").css("display","none");
       $(".history-detail").css("display","block");
    });

    $("#modify-info").click(function(){
       $(".personal-info").css("display","none");
       $(".modify-personal-info").css("display","block");
    });

    $("#cancell-modify").click(function(){
       $(".personal-info").css("display","block");
       $(".modify-personal-info").css("display","none");
    });

    $("#modify-pswd").click(function(){
       $(".modify-password").show();
       $(".modify-personal-info").hide();
    });

    $("#pswd-cancell").click(function(){
       $(".modify-password").hide();
       $(".modify-personal-info").show();
    });

    //modify password by adjax
    $("#pswd-kick").click(function(){
       var id = $("[name='cid']").val();
       var oldps = $("[name='oldpswd']").val();
       var newps1 = $("[name='newpswd']").val();
       var newps2 = $("[name='repeatpswd']").val();
       if(oldps!=""&&newps1!=""||newps2!="") {
         $.post("/lib/psmodify.php",{cid:id,ops:oldps,np1:newps1,np2:newps2},
           function(txt){
            if(txt==1) {
               $("#modify-pswd").html("修改成功");
               $("#pswd-remind").html("");
               $(".modify-password").hide();
               $(".modify-personal-info").show();
               $("[name='oldpswd']").val("");
               $("[name='newpswd']").val("");
               $("[name='repeatpswd']").val("");
             } else if(txt==0) {
                $("#pswd-remind").html("密码错误，请重新输入。");
                $("#pswd-modify").attr("disabled","disabled");
             } else if(txt==2) {
                $("#pswd-remind").html("新密码格式错误。");
                $("#pswd-modify").attr("disabled","disabled");
             } else if(txt==3) {
                $("#pswd-remind").html("新密码两次输入不一致。");
                $("#pswd-modify").attr("disabled","disabled");
             }  else {
                $("#pswd-remind").html("密码更新失败，请等会再试。"+txt);
                $("#pswd-modify").attr("disabled","disabled");
             }
          });
      } else {
        $("#pswd-remind").html("请输入密码！");
      }
    });

    //choose the city by hint and show country options, then hide city hint
    $("#cityhint").on("click","span",function(){
        var c = $(this).text();
        $("[name='city']").val(c);
        $("#country").load("/lib/selectCountry.php",{q: c,z:"none"});
        $("#cityhint").hide();
    });

    //hide city hint
    $("body").click(function(){
        $("#cityhint").hide();
    });

});

    //load countries under current city
    function findctry(c) {
       var city = c.value;
       $("#country").load("/lib/selectCountry.php",{q: city,z:"none"});

       //c.value = $("#cityhint").children().first().html();
      }

    //load cityhint under current input
    function cityhint(c) {
       var mycity = c.value;
       $("#cityhint").show();
       if(c.value!="") {
           $("#cityhint").load("/lib/selectCity.php",{q: mycity});
        }
      }

     //password filter by pattern
     var pswderr = ["empty"]; //password can't be empty
     function psCheck(elem){
           var inputname=elem.getAttributeNode("name").value;
           var value=elem.value;
           var caution = "<i class='iconfont'>&#xe62d;</i> " + elem.getAttributeNode("title").value;
           var e = elem.parentNode.parentNode.nextElementSibling.lastChild;
           var newpswd = $("[name='newpswd']").val();
           var repswd = $("[name='repeatpswd']").val();
           e.style.color="red";

          if(elem.checkValidity()==false) {

              $("#pswd-remind").css("color","red");
              $("#pswd-kick").attr("disabled","disabled");
              pswderr.push(inputname);
              e.innerHTML=caution;
           }
          else {
                 pswderr = pswderr.filter(deleteit);
                 e.innerHTML="";

             if((inputname=="repeatpswd")||((inputname=="newpswd")&&(repswd!=""))) {
                if(repswd!=newpswd) {
                  $("#pswd-remind").css("color","red");
                  $("#pswd-kick").attr("disabled","disabled");
                  pswderr.push("unidentical");
                  $("[name='repeatpswd']").parent().parent().next().children().last().html("<i class='iconfont'>&#xe62d;</i> 输入密码不一致!");
                 }
                else {
                  pswderr = pswderr.filter(delsame);
                  pswderr = pswderr.filter(empty);
                  $("[name='repeatpswd']").parent().parent().next().children().last().html("");
                 }
              }

           }

          if(pswderr.length==0) {
              $("#pswd-kick").attr("disabled",false);
            }

          function deleteit(na) {
              return na != inputname;
            }

          function delsame(na) {
             return na != "unidentical";
            }

          function empty(na) {
             return na != "empty";
            }

      }

     //js input filter by pattern
     var able = [];
     function inputCheck(elem){
           var inputname=elem.getAttributeNode("name").value;
           var value=elem.value;
           var remind = "<i class='iconfont'>&#xe62d;</i> " + elem.getAttributeNode("title").value;
           var id = $("[name='cid']").val();
           var e = elem.parentNode.parentNode.nextElementSibling.lastChild;

          if(elem.checkValidity()==false) {
              able.push(inputname);
              e.innerHTML = remind;
              e.style.color="red";
              $("#ok-modify").attr("disabled","disabled");

             }
          else {
               if((inputname=="username")||(inputname=="cellphone")) {
                   
                 $.post("/lib/valiphp.php",{vali:value,cid:id},
                  function(txt){
                    if(txt==1) {
                       e.innerHTML = "已存在,请重新输入";
                       e.style.color="red";
                       $("#ok-modify").attr("disabled","disabled");
                       able.push(inputname);
                      }
                    else {
                       able = able.filter(removeit);
                       e.innerHTML = "";
                       if(able.length==0) {
                         $("#ok-modify").attr("disabled",false);
                        }
                      }
                    });
                 }
               else {
                  able = able.filter(removeit);
                  e.innerHTML = "";
                  if(able.length==0) {
                    $("#ok-modify").attr("disabled",false);
                   }
                 } 
             }

          function removeit(name) {
             return name != inputname;
            }

         }



