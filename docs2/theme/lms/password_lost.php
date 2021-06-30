<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
define('_NOHEADER_',true);

include_once(G5_THEME_PATH.'/head.php');
?>

<section id="sub_password_lost">
	<div class="container">
		<div class="row">
			<div class="col-md-12 add_bottom_60 text-center ">
				<div class="password-lost-wrap">
					<span class="sub-top-title">비밀번호 찾기</span>
					<span class="sub-top-stitle">가입하신 이메일 주소와 핸드폰 번호를 입력해 주세요.<br >
	핸드폰 인증 완료 시 입력하신 이메일 주소로 임시 비밀번호가 전송됩니다.<br >
	반드시 마이페이지 내 회원정보에서 변경해 주세요.</span>
					
					
					<label for="mb_email" class="first">이메일</label>
					<input type="text" name="mb_email" id="mb_email" class="frm_input" placeholder="회원가입 시 등록된 이메일 정보를 입력해 주세요.">
					<label for="mb_tel">핸드폰번호</label>
					<input type="text" name="mb_tel" id="mb_tel" class="frm_input mb_tel" placeholder="입력시 '-'는 제외하고 입력해 주세요">	
					<button type="button" class="mb_confirm_btn">인증</button>
					

					<label for="mb_confirm_num">인증번호</label>
					<input type="text" name="mb_confirm_num" id="mb_confirm_num" class="frm_input" placeholder="인증번호를 입력해 주세요." >	
					<button type="button" class="mb_confirm_btn">인증확인</button>



					<span class="confirm_msg"> 인증 되었습니다.</span>
				
					
					<div class="reg_btn_wrap">
						<a href="/action/password_lost" class="reg_btn">비밀번호 찾기</a>
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