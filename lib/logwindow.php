     <div class="col-5" id="login">
        <div id="dialogbar"><h5>和乐分享<span id="x">&times;</span></h5></div>
        <div class="subnavbar col-9">
           <span id="sp1">帐号登录</span><span id="sp2">手机登录</span>
        </div>
    
        <div class="content col-9">

         <!-- already has account to log in -->
          <div class="account">
            <div id="loginput">
            
            <p>用户</p>
              
                <input name="myname" type="text" required placeholder="手机号码">
            <p>密码</p>
                <input name="pass" type="password" placeholder="登录密码" required>
            <p>验证码</p>
                <input name="code" type="text" placeholder="请输入下图中验证码" required>
                <img id="captcha" src="/securimage/securimage_show.php" alt="CAPTCHA Image"/>
                <span class="refresh">更新验证码</span>
            <span id="error"></span>
             <input type="button" value="登录" id="logbutton"><span id="forgetpass">忘记密码?</span>
            
          </div>

          <div id="loginelse">
            <p class="thick">共享帐号登录</p>
            <p><span id="qqLoginBtn"></span> | 微信 | <span id="wb_connect_btn" ></span> </p>
          </div>
        </div>


         <!-- use cellphone to register -->
         <div class="cellphone">
          
             <table>
		<tr>
		  <td>手机号</td>
		  <td>
			<input name="cellphone" type="text" pattern="^1(3[0-9]|4[57]|5[0-35-9]|70|8[0-9])\d{8}$"
title="请输入11位手机号码" onfocus="remind(this)" required onchange="inputCheck(this)"></td>
		  <td id="cellphone"></td>
		</tr>
		<tr>
		  <td>验证码</td>
		  <td><input name="phonecode" type="text" required ></td>
		  <td><button class="sms">发送验证码</button></td>
		</tr>
                <tr class="newuser">
                  <td></td>
                  <td><input type="checkbox" id="toRegist" value="regist"><span>新用户，我要注册帐号</span></td>
                  <td></td>
                </tr>
		<tr class="noRegist">
		  <td>密 码</td>
		  <td><input name="pass3" type="password" title="8位以上的字母或数字" onfocus="remind(this)" onchange="inputCheck(this)" required pattern="[a-zA-Z0-9]{8,}"></td>
		  <td></td>
		</tr>
		<tr class="noRegist">
		  <td>确认密码</td>
		  <td><input name="pass4" type="password" title="重复输入密码确认" onfocus="remind(this)" onchange="confirmPass3(this)" pattern="[a-zA-Z0-9]{8,}"></td>
		  <td></td>
		</tr>
                <tr class="noRegist">
                  <td></td>
                  <td><input type="checkbox" checked id="agree"><span>我阅读并同意接受和乐分享的</span><a href="service.php?term=3">服务协议</a></td>
                  <td id="noAgree"></td>
                </tr>
                <tr class="registback">
                  <td></td>
                  <td colspan="2" class="phoneerror"></td>
                </tr>
		<tr>
		  <td></td>
		  <td><input id="phoneregist" type="submit" name="phoneregist" value="登录"></td>
                  <td></td>
		</tr>
	    </table>   
         
        </div>
    </div>
    </div>

    <div class="loginback"></div>