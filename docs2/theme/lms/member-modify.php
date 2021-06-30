<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
define('_NOHEADER_',true);

include_once(G5_THEME_PATH.'/head.php');
?>

<section id="sub_mypage">
	<div class="container">
		<div class="row">
			<?php
			include_once(G5_THEME_PATH.'/mypage.left.php');
			?>
			
			<div class="col-md-9">
				<div class="mypage-modfy">
					<div class="mypage-small-tit">
						회원정보수정						
					</div>
					
					<div class="mypage-modify-wrap">
						
						<div class="row mypage-modify-contents">
							<div class="mypage-modify-tit">
								일반정보
							</div>
							<div class="row add_bottom_30">
								<div class="col-lg-2 col-md-4 col-sm-4">
									<label for="mb_name">이름</label>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-8">
									<input type="text" name="mb_name" id="mb_name" class="frm_input" value="이두희" readonly=readonly style="background-color:#cccccc">
								</div>
								
								<div class="col-lg-2 col-md-4 col-sm-4">
									<label for="mb_tel">연락처</label>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-8">
									<input type="text" name="mb_tel" id="mb_tel" class="frm_input" value="01012341234">
								</div>
								<div class="col-lg-2 col-md-4 col-sm-4">
									<label for="mb_email">이메일</label>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-8">
									<input type="hidden" name="mb_email">
									<input type="text" name="mb_email1" id="mb_email1" class="frm_input" value="123" style="width:20%"> <em>@</em> <input type="text" name="mb_email2" id="mb_email2" class="frm_input" value="naver.com" style="margin-left:0">
									<span class="mypage-modify-social-caution">
										소셜 가입회원은 이메일 변경이 불가능합니다
									</span>
								</div>							
							</div>

							<div class="mypage-modify-tit">
								비밀번호
							</div>
							<div class="row add_bottom_30">
								<div class="col-lg-2 col-md-4 col-sm-4">
									<label for="mb_password">현재 비밀번호</label>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-8">
									<input type="password" name="mb_password" id="mb_password" class="frm_input" value="" >
									<span class="mypage-modify-social-caution" style="display:none">
										현재 비밀번호와 맞지 않습니다
									</span>
								</div>
								<div class="col-lg-2 col-md-4 col-sm-4">
									<label for="mb_password_new">변경 비밀번호</label>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-8">
									<input type="password" name="mb_password_new" id="mb_password_new" class="frm_input" value="" >
									<span class="mypage-modify-social-caution" style="display:none">
										영문+숫자형태로 기입해 주세요
									</span>
								</div>
								<div class="col-lg-2 col-md-4 col-sm-4">
									<label for="mb_password_re">비밀번호 확인</label>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-8">
									<input type="password" name="mb_password_re" id="mb_password_re" class="frm_input" value="" >
									<span class="mypage-modify-social-caution" style="display:none">
										비밀번호가 일치합니다.
									</span>
								</div>
							</div>
							<div class="mypage-modify-tit">
								회사정보
							</div>
							<div class="row add_bottom_60">
								<div class="col-lg-2 col-md-4 col-sm-4">
									<label for="mb_password">회사명</label>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-8">
									<input type="text" name="mb_password" id="mb_password" class="frm_input" value="" >
								</div>
								<div class="col-lg-2 col-md-4 col-sm-4">
									<label for="mb_password">부서명</label>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-8">
									<input type="text" name="mb_password" id="mb_password" class="frm_input" value="" >
								</div>
								<div class="col-lg-2 col-md-4 col-sm-4">
									<label for="mb_password">직급명</label>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-8">
									<input type="text" name="mb_password" id="mb_password" class="frm_input" value="" >
								</div>
							</div>
						</div>
						<div class="btn_wrap text-center">
							<a href="#" class="mypage-modify-ok">변경하기</a>
							<a href="#" class="mypage-modify-cancel">취소하기</a>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>

</section>
<?php
include_once(G5_THEME_PATH.'/tail.php');
?>