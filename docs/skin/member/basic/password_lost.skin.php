<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
// add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>
<style>
button.reg_btn {
	border:0px;
    color: #ffffff;
    font-weight: 700;
    background-color: #0066b3;
    display: block;
    width: 100%;
    padding: 15px 0;
    font-size: 1.2em;
}
</style>
<!-- 회원정보 찾기 시작 { -->
<section id="sub_password_lost">
	<div class="container">
		<div class="row">
			<div class="col-md-12 add_bottom_60 text-center ">
				<div class="password-lost-wrap">
				<form name="fpasswordlost" action="<?php echo $action_url ?>" onsubmit="return fpasswordlost_submit(this);" method="post" autocomplete="off">
					<span class="sub-top-title"><!-- 비밀번호 찾기 -->Find Password</span>
					<span class="sub-top-stitle"><!-- 가입하신 이메일 주소와 핸드폰 번호를 입력해 주세요. -->Please enter the email address you signed up.<br >
	<!-- 핸드폰 인증 완료 시 입력하신 이메일 주소로 임시 비밀번호가 전송됩니다. -->The temporary password will be sent to the email address you entered.<br >
	<!-- 반드시 마이페이지 내 회원정보에서 변경해 주세요. -->Please make sure to change it in the password in My Page.</span>


					<label for="mb_email" class="first"><!-- 이메일 -->E-mail</label>
					<input type="text" name="mb_email" id="mb_email" required class="required frm_input full_input email" size="30" placeholder="Please enter the email address you signed up">
					
					<!--
					<label for="mb_tel">핸드폰번호</label>
					<input type="text" name="mb_tel" id="mb_tel" class="frm_input mb_tel" placeholder="입력시 '-'는 제외하고 입력해 주세요">
					<button type="button" class="mb_confirm_btn">인증</button>

					<label for="mb_confirm_num">인증번호</label>
					<input type="text" name="mb_confirm_num" id="mb_confirm_num" class="frm_input" placeholder="인증번호를 입력해 주세요." >
					<button type="button" class="mb_confirm_btn">인증확인</button>
					-->

					<!--<span class="confirm_msg"> 인증 되었습니다.</span>-->

					<div class="reg_btn_wrap">
						<button type="submit" class="reg_btn"><!-- 비밀번호 찾기 -->Find Password</button>
					</div>
				</form>
				</div>

			</div>
		</div>
	</div>
</div>

<script>
function fpasswordlost_submit(f)
{
    return true;
}
/*
$(function() {
    var sw = screen.width;
    var sh = screen.height;
    var cw = document.body.clientWidth;
    var ch = document.body.clientHeight;
    var top  = sh / 2 - ch / 2 - 100;
    var left = sw / 2 - cw / 2;
    moveTo(left, top);
});
*/
</script>
<!-- } 회원정보 찾기 끝 -->