<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/home\view\login\register.html";i:1508459387;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<title>越光神镜</title>
<meta charset="utf-8">
<meta name="author" content="yy">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta http-equiv="Content-Type" content="text/html; charset=GBK">
<meta name="author" content="yy">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta name="description" content="" />
<meta name="Keywords" content="" />
<link rel="stylesheet" type="text/css" href="/resource/home/css/swiper.min.css">
<link rel="stylesheet" type="text/css" href="/resource/home/css/public.css">
<link rel="stylesheet" type="text/css" href="/resource/home/font/iconfont.css">
<script type="text/javascript" src="/resource/home/js/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="/resource/home/js/swiper.min.js"></script>
<script type="text/javascript" src="/resource/home/js/public.js"></script>
<link rel="stylesheet" href="/resource/home/css/main.css">
<style>
	::-webkit-input-placeholder { /* WebKit browsers */  
	    color: #999;  
	}  
	:-moz-placeholder { /* Mozilla Firefox 4 to 18 */  
	   color: #999;  
	   opacity:  1;  
	}  
	::-moz-placeholder { /* Mozilla Firefox 19+ */  
	   color: #999;  
	   opacity:  1;  
	}  
	:-ms-input-placeholder { /* Internet Explorer 10+ */  
	   color: #999;  
	} 
</style>
</head>
<body>
	<div class="register_top">
		<div class="header_login">
			<a href="javascript:history.go(-1);" class="head_left iconfont icon-mjiantou-copy"></a>
			<h3>注册</h3>
			<a href="<?php echo url('Login/login'); ?>" class="head_right">登录</a>
		</div>
	</div>
	<div class="login_body">
		<ul>
			<li class="li">
				<img class="login_icon login_icon2" src="/resource/home/images/login_icon1.png" alt="手机号">
				<div class="input_box">
					<input class="text_input phone" name="phone" type="number" placeholder="请输入您的手机号">
				</div>
			</li>
			<li class="li">
				<img class="login_icon login_icon1" src="/resource/home/images/login_icon2.png" alt="验证码">
				<div class="input_box">
					<input class="text_input text_input2 yzm" name="yzm"  type="text" placeholder="请输入短信验证码">
					<input class="yzm_btn yzbtn" id="code" type="button" value="获取验证码">
				</div>
			</li>
			<li class="li">
				<img class="login_icon" src="/resource/home/images/login_icon3.png" alt="登录密码">
				<div class="input_box">
					<input class="text_input login_pwd1" type="password" name="login_pwd1" placeholder="请设置您的密码">
				</div>
			</li>
			<li class="li">
				<img class="login_icon" src="/resource/home/images/login_icon3.png" alt="登录密码">
				<div class="input_box">
					<input class="text_input login_pwd2" type="password" name="login_pwd2" placeholder="请再次输入您的密码">
				</div>
			</li>
			<li class="li">
				<img class="login_icon" src="/resource/home/images/login_icon4.png" alt="支付密码">
				<div class="input_box">
					<input class="text_input pay_pwd" type="password" name="pay_pwd" placeholder="请设置6位纯数字的支付密码">
				</div>
			</li>
		</ul>

		<button class="login_btn true">立即注册</button>
		<p class="regist_xieyi">注册即同意<a href="<?php echo url('Login/registerProtocol'); ?>">《用户注册协议》</a></p>
	</div>

	<script>
		// $(".login_btn").click(function() {
		// 	window.location.href = 'login.html';
		// });


$('#code').on('click',function(){
    var phone = $(".phone").val();
    // if(!(/^1[34578]\d{9}$/.test(phone))){ 
    //       alert("手机号码有误!");  
    //       return false; 
    //   } 
    var count = 60;
    var countdown = setInterval(CountDown, 1000);
    function CountDown() {
        $("#code").css('pointer-events', 'none');;
        $("#code").text("重新发送("+count +")");
        if (count == 0) {
          $("#code").css('pointer-events', 'auto');
           $("#code").text("发送验证码");
            clearInterval(countdown);
        };
        count--;
    }
});



 $('.yzbtn').click(function(){
            var phone=$('.phone').val();
            if(phone==""){
                alert("请输入您的手机号码！");
                return false;
            }
            if(!phone.match(/^1[34578]\d{9}$/)){
                alert('手机号不符合规则！');
                return false;
            }
            var data={
                'phone':phone,
               
            }

            var url="<?php echo url('Login/sendyamcode'); ?>";
               $.ajax({
                'url':url,
                'data':data,
                'async':true,
                'type':'post',
                'dataType':'jsonp',
                success:function(data){
                   if(data.status){                   
                        if(data.status==200){
                           	alert(data.message);
                        }
                        if(data.status==401){
                        	alert(data.message);
                        }
                    }else{
                          alert(data.message);
                          window.location.reload();
                        }                  
                },
             
            })
            
        })



	  $('.true').click(function(){
            var phone=$('.phone').val();
            var yzm=$('.yzm').val();

            var login_pwd1=$('.login_pwd1').val();
            var login_pwd2=$('.login_pwd2').val();
            var pay_pwd=$('.pay_pwd').val();
            if(phone==""){
                alert("请输入您的手机号码！");
                //window.location.reload();
                return false;
            }
            if(!phone.match(/^1[34578]\d{9}$/)){
                alert('手机号不符合规则！');
                //window.location.reload();
                return false;
            }
            if(yzm==""){
            	alert('请输入验证码');
                //window.location.reload();
            	return false;
            }
            if(login_pwd1==""){
                alert("请输入密码！");
                //window.location.reload();
                return false;
            }
            if(login_pwd2==""){
                alert("请输入重复密码！");
                //window.location.reload();
                return false;
            }
            if(login_pwd1!= login_pwd2){
            	 alert("第一次密码与第二次密码不同！");
                //window.location.reload();
                return false;
            }
            if(pay_pwd==""){
                alert("请输入支付密码！");
                //window.location.reload();
                return false;
            }
            var data={
                'phone':phone,
                'yzm':yzm,
                'login_pwd1':login_pwd1,
                'login_pwd2':login_pwd2,
                'pay_pwd':pay_pwd,
               
            }
            var url="<?php echo url('Login/register'); ?>";
            sendAjax(data,url)
        })
        function sendAjax(data,url){
            $.ajax({
                'url':url,
                'data':data,
                'async':true,
                'type':'post',
                'dataType':'jsonp',
                success:function(data){
                	 if(data.status){ 
                				 
                	 		if(data.status==200){
                	 			alert(data.message); 
                	 			window.location.href="/home/Login/login.html";
                	 		}
                	 		if(data.status==401){
                	 			alert(data.message);
                	 			window.location.reload();                 	 			
                	 		}                 	
                           
                        }else{
                        	alert(data.message);
                        	window.location.reload();
                        }

                  
                },
             
            })
        }
	


	</script>
</body>
</html>