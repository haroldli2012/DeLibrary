  
 //confirm login status, assign to input, get credit and xiebi value  

  $(document).ready(function(){
  

    var my_xiebi;
    var city=getCookie("city");
    var user=getCookie("user");

    /*if(city!="") {
       $("[name='customerCity']").val(city);
    }*/


    if(user!="") {

    }

   //$(".pic").attr("src",$(".spic:first").attr("src"));
   //$(".pic").attr("title", $(".spic:first").attr("title"));

   $('.folio').on("click","img",function(){
       $(".pic").attr("src",$(this).attr("src"));
       $(".pic").attr("title", $(this).attr("title"));
    });


   // Get the modal
   var modal = document.getElementById('myModal');

   // Get the <span> element that closes the modal
   var span = document.getElementsByClassName("close")[0];

   // When the user clicks on <span> (x), close the modal
   span.onclick = function() { 
      modal.style.display = "none";
   }
  
    var modalImg = document.getElementById("img01");
       $(".pic").click(function(){
          modal.style.display = "block";
          modalImg.src = this.src;
          modalImg.title=this.title;
       });

       $(".nextpic").click(function(){
             var title=modalImg.title;
             if($(".folio img[title='"+title+"']").next().attr("src")) {
                modalImg.src=$(".folio img[title='"+title+"']").next().attr("src");
                modalImg.title=$(".folio img[title='"+title+"']").next().attr("title");
             }
             else {
                modalImg.src=$(".folio").children(":first").attr("src");
                modalImg.title=$(".folio").children(":first").attr("title");
             }
          });

       $(".prevpic").click(function(){
             var title=modalImg.title;
             if($(".folio img[title='"+title+"']").prev().attr("src")) {
                modalImg.src=$(".folio img[title='"+title+"']").prev().attr("src");
                modalImg.title=$(".folio img[title='"+title+"']").prev().attr("title");
             }
             else {
                modalImg.src=$(".folio").children(":last").attr("src");
                modalImg.title=$(".folio").children(":last").attr("title");
             }
          });

       //for photo slide
       var point=4;
       var folio = document.getElementsByClassName("spic");
       var leng = folio.length;

       $(".next").click(function(){
       for(var i=0;i <4;i++) {
         if((point+1)<=leng) {
             point++;
             $(".folio").children(":nth-child("+(point-4)+")").hide();
             $(".folio").children(":nth-child("+point+")").show();
          }
        }
        });

       $(".prev").click(function(){

         for(var i=0;i <4;i++) {
           if((point-4)>0) {
             $(".folio").children(":nth-child("+point+")").hide();
             $(".folio").children(":nth-child("+(point-4)+")").show();
             point--;
           }
        }
        });

     $(":radio").click(function(){
          $("#share").val($(this).val());
          $("#sharewith").html($(this).parent().next().html());
          $("#lexie").html($(this).parent().next().next().next().next().html());
     });
     
     $("#share").click(function(){
          if($(this).val() != "") 
             {
               $("#success").load("lib/exchangephp.php", {requestId: $(this).val()});
              }
          else {
              $("#success").html("请选择分享对象!");
             }
     });

     $("#update").click(function(){
        var id=$("[name=shareId]").val();
        window.location.assign("fabu.php?shareId="+id);
      });
     
   /*  $("#update").click(function(){
        $(".cont").each(function(){
           var inputtype = $(this).parent().attr("class");
           var inputvalue = $(this).text();
           if(inputtype=="textarea") {
             $(this).replaceWith("<textarea>"+inputvalue+"</textarea>");
              }
           else {
             $(this).replaceWith("<input name='cont' type="+inputtype+" value="+inputvalue+">");
            }
        });

        $(this).hide();
        $("#done").show();
     });

     $("#done").click(function(){
       var value = $("[type=number]").val();
       var detail = $("textarea").val();
       var date = $("[type=date]").val();
       var ele=$(":text");
       var title=ele[0].value;
       var addr=ele[1].value;
       var id=$("[name=shareId]").val();
       
       $("#modifyresult").load("lib/modifyphp.php",{sId:id, xiebi:value, sDetail:detail, sTitle:title, sDate:date, sAddr:addr});

       $("input[name='cont']").each(function(){
          var value = $(this).val();
          $(this).replaceWith("<span class='cont'>"+value+"</span>");
       });
       $("textarea").replaceWith("<span class='cont'>"+$("textarea").val()+"</span>")
       $(this).hide();
       $("#update").show();

     });     */

  });

