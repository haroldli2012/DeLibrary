<?php
//only used by login by cellphone
//if cellphone is existing, then feedback pass the cp to brige input
//if not existing, further check and show if user want to regist
//pass cellphone to cbridge if cellphone is valid

    $cellphone=$_REQUEST["cellphone"];

  $path_req = $_SERVER["DOCUMENT_ROOT"];
    require $path_req."/lib/linktodb.php";

  if($cellphone!="") {
        $sql=sprintf("SELECT 1 FROM customers WHERE cellphone='%s'AND password!=''",mysqli_real_escape_string($conn,$cellphone));
        $result=mysqli_query($conn,$sql);
        if($result===false) die("Could not query database to confirm user");
        if((mysqli_num_rows($result)==1))
          {
            echo "<i class='iconfont'>&#xe717;</i>";
?>
            <script>
            $("#cellphone").css("color","black");
            $(".newuser").hide();  
            </script>

<?php
          }
        else {
            echo "<i class='iconfont'>&#xe717;</i>";
?>
            <script>
            $("#cellphone").css("color","black");
            $(".newuser").show();     
            //$("#toRegist").attr("checked", "checked");
            //$("#agree").attr("checked","checked");
            //$(":password").attr("required","required");
            </script>

<?php
             }
     }
   $conn->close();
?>
