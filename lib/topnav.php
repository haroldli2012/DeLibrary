      <nav id="topnav">
        <ul class="menu"> 
           <li class="logo"><canvas class="canvas" width="130" height="48"></canvas></li>
           <li class="location" title="点击选择城市"><span><?php if(isset($_SESSION["city"])) echo $_SESSION["city"];?></span>
              <i class="iconfont">&#xe629</i>
              <i class="iconfont" style="display:none">&#xe627</i>
           </li>
           <li class=""><a href="index.php">首页</a></li>
           <li class=""><a href="search.php">搜索</a></li>
           <li class=""><a href="fabu.php">分享</a></li>
           <li class=""><a href="community.php">社区</a></li>
           <li class="right">
<?php        if($user_name=="") { ?>
              <span class="span col-8">登录|注册</span>
<?php        } else {    ?>
              <span class="user"><?php echo substr($user_name,0,12);?></span><span class="quit">退出</span>
<?php        }     ?>
           </li>
        </ul>

     <ul class="menu2"> 
       <li class="small">
         <div class="dropdown">
          <i class="fa fa-align-justify dropbtn" style="font-size:1.5em"></i>
          <div class="dropdown-content">
           <a href="index.php">首页</a>
           <a href="search.php">搜索</a>
           <a href="fabu.php">分享</a>
           <a href="community.php">社区</a>
          </div>
         </div>
         <div class="location" title="点击选择城市">
          <span><?php if(isset($_SESSION["city"])) echo $_SESSION["city"];?></span>
              <i class="iconfont">&#xe629</i>
              <i class="iconfont" style="display:none">&#xe627</i>
          </div>
        </li>

           <li class="logo small"><canvas class="canvas" width="130" height="48"></canvas></li>


           <li class="small right">
<?php        if($user_name=="") { ?>
              <span class="span">登录|注册</span>
<?php        } else {    ?>
              <span class="user"><?php echo substr($user_name,0,6);?></span><span class="quit">退出</span>
<?php        }     ?>
           </li>
        </ul>

     </nav>

      <div id="city">
           <div id="zones"></div>
           <div id="cities"></div>
      </div>

      <div style="margin-top:60px;width:100%;background-color:white;"></div>