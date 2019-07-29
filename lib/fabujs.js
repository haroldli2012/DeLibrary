$("[href='fabu.php']").addClass("active");

    //load countries under current city
    function findctry(c) {
       var sharecity = c.value;
       $("#country").load("/lib/selectCountry.php",{q: sharecity,z:"none"});
      }

$(document).ready(function(){
    var city=getCookie("city");
    var user=getCookie("user");
    var myzone = $("#countryselect").val();
    
    $("#country").load("/lib/selectCountry.php",{q: $("[name='customerCity']").val(),z:myzone});

    if(city!="") {

      }



    if(user!="") {

    }

    //upload all photos to server
    $("#forupload").click(function(){
         var sharecity = $("[name='customerCity']").val();
         var txt="";
         var x=$(".pic").length;
         var status=1;
         var y=0;
        if(x>0) {
         $(".pic").each(function(){
             $("#loadresult").load("/upload/fileupload.php",
                {url:$(this).attr("src"),title:$(this).attr("title"),c:sharecity},
                function(responseTxt,statusTxt){
                   txt += responseTxt;
                   $("#loadresult").html(txt); 
                  if((statusTxt!="success")||(txt.match("失败")!=null)) {
                    status=0;
                    return false;
                  }
                  y++;
                  if((y==x)&&(status!=0)) $("#formhandin").click();
               // $("#demo").html(y);
               });
         });
        }
        else $("#formhandin").click();
    });

    //choose the city by hint and show country options, then hide city hint
    $("#cityhint").on("click","span",function(){
        var c = $(this).text();
        $("[name='customerCity']").val(c);
        $("#country").load("/lib/selectCountry.php",{q: c,z:"none"});
        $("#cityhint").hide();
    });

    //hide city hint
    $("body").click(function(){
        $("#cityhint").hide();
    });

    

});


    //load cityhint under current input
    function cityhint(c) {
       var mycity = c.value;
       $("#cityhint").show();
       if(c.value!="") {
           $("#cityhint").load("/lib/selectCity.php",{q: mycity});
        }
      }

//function for photo preview
function handleFileSelect(evt) {
    document.getElementById('list').innerHTML="";
    var files = evt.target.files; // FileList object
    var j=0;
    var m="";
    var fname=new Array();
    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {
       
      // Only process image files.
      if (!f.type.match('image.*')) {
        continue;
      }
      var reader = new FileReader();

      // Closure to capture the file information.
      reader.onload = (function(theFile) {
        return function(e) {

      // avoid same iamge name in IOS system
        fname[j] = theFile.name;
       for(n=0;n<j;n++) {
          if(theFile.name==fname[n]) {m=j; break;}
          else m="";
         }

          // Render thumbnail.
          var span = document.createElement('span');
          span.innerHTML = ['<img class="pic" src="', e.target.result,
                            '" title="', m+encodeURI(theFile.name), '"/>'].join('');
          j++;
          document.getElementById('list').insertBefore(span, null);
        };
      })(f);

      // Read in the image file as a data URL.
      reader.readAsDataURL(f);
      f="";
    }
  }

  document.getElementById('files').addEventListener('change', handleFileSelect, false);

    var title;
    $(function() {

       $('.image-editor').cropit({

          imageBackground: true,

          imageBackgroundBorderWidth: 50,

        });

       //rotate the photo by click button
       $('.rotate-cw').click(function() {

          $('.image-editor').cropit('rotateCW');

        });

        $('.rotate-ccw').click(function() {

          $('.image-editor').cropit('rotateCCW');

        });



        //export photo
        $('.export').click(function() {

          var imageData = $('.image-editor').cropit('export');

              $("img[title='"+title+"']").attr("src",imageData);

          //close login dialog
          $("#crop").hide();
          $("#cropback").hide();

       });
   
   });


  $(document).ready(function(){
    $('#list').on("click","img",function(){
      var srctemp=$(this).attr("src");
      title=$(this).attr("title");

      //hide size error info after crop by added class number.
      $("#demo .doc-name").each(function(){
         var fname = $(this).html();
          if(fname.match(title)) {
            $(this).nextUntil("strong").css("display","none");
           }
      });

      //show crop interface
      $("#crop").show();
      $("#cropback").show();
      $('.image-editor').cropit('imageSrc', srctemp);
    });

   //delete the photo
   $(".delete").click(function(){
      $("#crop").hide();
      $("#cropback").hide();
      $("#demo .doc-name").each(function(){
         var fname = $(this).html();
          if(fname.match(title)) {
            $(this).after("<br/><span>提示：该文件已删除！</span>");
           }
      });
      //$("#demo").find(".error."+class_num+"").;
      //$("#demo").find(".error."+class_num+"").show();
      $("img[title='"+title+"']").remove();

   });


 });


//show upload file in html page

function myFunction(){
    var x = document.getElementById("files");
    var patt=/\.jpg|\.bmp|\.png|\.gif|\.JPG|\.jpeg/;
    //var patt=/\.pdf|\.swf|\.mp4|\.flv|\.ogg|\.webm|\.wav|\.mp3/;
    var txt = "";
    var size=0;
    if ('files' in x) {
        if (x.files.length == 0) {
            txt = "未选择任何文件。";
        } else {
            var j = x.files.length;
            for (var i = 0; i < x.files.length; i++) {
                j--;
                txt += "<strong>" + (i+1) + ". file</strong>";
                var file = x.files[i];
                if ('name' in file) {
                    txt += "<span class='doc-name'>文件名: " + file.name + "</span><br>";
                    if(!patt.test(file.name)) {
                         txt += "<span class='error'>提示：错误的文件类型无法上传！</span><br/>";
                      }
                }
                if ('size' in file) {
                    txt += "<span class='doc-size'>文件大小: " + file.size + " bytes </span><br/>";
                    size += file.size;
                    if((file.size>2000000)&&(file.size<20000000)) {
                        txt += "<span class='error'>提示：文件大于2M，请裁剪图片，否则会被自动裁剪！" + "</span><br/>";
                      }
                }
            }
            if(size>20000000) txt += "<br/><span class='error'>提示：上传文件总合超过20M，请减小文件数量或大小！</span>" + "<br/>";
        }
    } 
    else {
        if (x.value == "") {
            txt += "未选择任何文件。";
        } else {
            txt += "您的浏览器不支持上传文件！";
            txt  += "<br>文件路径: " + x.value; // If the browser does not support the files property, it will return the path of the selected file instead. 
        }
    }
    document.getElementById("demo").innerHTML = txt;
} 
