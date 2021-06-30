<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>
<style>
button.reg_btn {
	border:0px;
    color: #ffffff;
    font-weight: 700;
    background-color: #edad58;
    display: block;
    width: 100%;
    padding: 15px 0;
    font-size: 1.2em;
}
</style>
<!-- 로그인 시작 { -->
<section id="sub_login">
	<div class="container">
		<div class="row">
			<div class="col-md-12 add_bottom_60 text-center">
				<span class="sub-top-title">Hello! This is <?php echo $config['cf_title']; ?>.</span>
				<span class="sub-top-stitle"><!-- 로그인 및 회원가입 안내 -->Sign-in and sign-up guidance</span>
				<div class="sub-register-tab">
					<ul>
						<li><a href="javascript:void(0);" class="active">Login</a></li>
						<li><a href="<?php echo G5_BBS_URL ?>/register.php" class="">Sign up</a></li>
					</ul>
				</div>
				<div class="register-wrap">
						<form name="flogin" action="<?php echo $login_action_url ?>" onsubmit="return flogin_submit(this);" method="post">
						<input type="hidden" name="url" value="<?php echo $login_url ?>">

						<input type="text" name="mb_id" id="mb_id" class="frm_input" placeholder="Enter a valid e-mail adress">
						<input type="password" name="mb_password" id="mb_password" class="frm_input mb_password" placeholder="Enter Password">

						<div class="login_etc">
							<div class="page__toggle">
							<label class="toggle">
							  <input type="checkbox" name="auto_login" id="login_auto_login" class="selec_chk toggle__input">
							  <span class="toggle__label">
								<span class="toggle__text">Auto login</span>
							  </span>
							</label>
							<a href="/bbs/password_lost.php" class="find_pass_btn">Search Password</a>
							</div>
						</div>
						<div class="reg_btn_wrap">
							<button type="submit" class="reg_btn">Login</button>
						</div>
						</form>

						<?php @include_once(get_social_skin_path().'/social_login.skin.php'); // 소셜로그인 사용시 소셜로그인 버튼 ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</section>

<script>
jQuery(function($){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?");
        }
    });

	$(".pay_agree > ul > li > a").on("click",function(){
		$(this).next(".agree_text").toggle();
	});
});

function flogin_submit(f)
{
    if( $( document.body ).triggerHandler( 'login_sumit', [f, 'flogin'] ) !== false ){
        return true;
    }
    return false;
}
</script>
<!-- } 로그인 끝 -->

