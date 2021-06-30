<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
define('_NOHEADER_',true);

include_once(G5_THEME_PATH.'/head.php');
?>

<section id="sub_login">
	<div class="container">
		<div class="row">
			<div class="col-md-12 add_bottom_60 text-center">
				<span class="sub-top-title">안녕하세요! 한컴 MDS 아카데미입니다.</span>
				<span class="sub-top-stitle">로그인 및 회원가입 안내</span>
				<div class="sub-register-tab">
					<ul>
						<li><a href="/sub/login/" class="active">로그인</a></li>
						<li><a href="/sub/register/" class="">회원가입</a></li>
					</ul>
				</div>
				<div class="register-wrap">					
					<input type="text" name="mb_id" id="mb_id" class="frm_input" placeholder="아이디 또는 이메일을 입력해주세요">
					<input type="password" name="mb_password" id="mb_password" class="frm_input mb_password" placeholder="비밀번호를 입력해 주세요.">					
				
						<div class="login_etc">
							<div class="page__toggle">
							<label class="toggle">
							  <input class="toggle__input" type="checkbox" name="diff" value="1">
							  <span class="toggle__label">
								<span class="toggle__text">자동로그인</span>
							  </span>
							</label>
							<a href="/sub/password_lost/" class="find_pass_btn">비밀번호 찾기</a>
							</div>
						</div>
						<div class="reg_btn_wrap">
							<a href="/sub/register_complete/" class="reg_btn">로그인</a>
						</div>
						<div class="reg_social_wrap">
							<a href="/sub/social_register/" class="social naver">네이버</a>
							<a href="/sub/social_register/" class="social kakaotalk">카카오톡</a>
							<a href="/sub/social_register/" class="social facebook">페이스북</a>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(".pay_agree > ul > li > a").on("click",function(){
		$(this).next(".agree_text").toggle();
	});
</script>
</section>
<?php
include_once(G5_THEME_PATH.'/tail.php');
?>