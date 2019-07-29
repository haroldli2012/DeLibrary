<?php
   $options['show_image_url']="/share/securimage/";
        require '../securimage/securimage.php';
        echo Securimage::getCaptchaHtml();
?>
