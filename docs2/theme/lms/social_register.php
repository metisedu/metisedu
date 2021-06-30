<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
define('_NOHEADER_',true);

include_once(G5_THEME_PATH.'/head.php');
?>

<section id="sub_register">
	<div class="container">
		<div class="row">
			<div class="col-md-12 add_bottom_60 text-center">
				<span class="sub-top-title">안녕하세요! 한컴 MDS 아카데미입니다.</span>
				<span class="sub-top-stitle">소셜 회원가입 추가 정보 입력</span>
				<div class="sub-register-tab">
					<ul>
						<li><a href="/login" class="">로그인</a></li>
						<li><a href="/register" class="active">회원가입</a></li>
					</ul>
				</div>
				<div class="register-wrap">
					<label for="mb_id">아이디</label>
					<input type="text" name="mb_id" id="mb_id" class="frm_input add_bottom_30" placeholder="이메일 주소를 입력해주세요.">

					<label for="mb_password">비밀번호</label>
					<input type="password" name="mb_password" id="mb_password" class="frm_input " placeholder="비밀번호를 입력해 주세요.">
					<span class="req">* 영문자+숫자 조합으로 입력해 주세요.</span>

					<label for="mb_password_re">비밀번호 확인</label>
					<input type="password" name="mb_password_re" id="mb_password_re" class="frm_input  "  placeholder="비밀번호를 다시 한번입력해 주세요.">

					<label for="mb_name">이름</label>
					<input type="text" name="mb_name" id="mb_name" class="frm_input "  placeholder="이름을 입력해 주세요.">

					<label for="mb_email">이메일</label>
					<input type="text" name="mb_email" id="mb_email" class="frm_input " placeholder="이메일을 입력해 주세요.">

					<label for="mb_email">연락처</label>
					<input type="text" name="mb_tel" id="mb_tel" class="frm_input " placeholder="입력시 '-'는 제외하고 입력해 주세요.">

					<div class="row">
						<div class="col-md-6">
							<label for="mb_company">회사명</label>
							<input type="text" name="mb_company" id="mb_company" class="frm_input " placeholder="회사명을 입력해 주세요.">
						</div>
						<div class="col-md-6">
							<label for="mb_company">부서명</label>
							<input type="text" name="mb_partname" id="mb_partname" class="frm_input " placeholder="부서명을 입력해 주세요.">
						</div>
						<div class="col-md-6">
							<label for="mb_position">직급</label>
							<input type="text" name="mb_position" id="mb_position" class="frm_input " placeholder="직급을 입력해 주세요.">
						</div>
						<div class="col-md-6">
							<label for="mb_route">인지경로</label>
							<select name="mb_route" id="mb_route" class="frm_input">
								<option value="배너광고">배너광고</option>
								<option value="페이스북">페이스북</option>
								<option value="기타">기타</option>
							</select>
						</div>
						<div class="col-md-12">
							<label>관심분야</label>
						</div>
					
						<div class="col-md-4">
							
							<select name="mb_interest1" id="mb_interest1" class="frm_input">
								<option value="배너광고">배너광고</option>
								<option value="페이스북">페이스북</option>
								<option value="기타">기타</option>
							</select>
						</div>
						<div class="col-md-4">
							<select name="mb_interest2" id="mb_interest2" class="frm_input">
								<option value="배너광고">배너광고</option>
								<option value="페이스북">페이스북</option>
								<option value="기타">기타</option>
							</select>
						</div>
						<div class="col-md-4">
							<select name="mb_interest3" id="mb_interest3" class="frm_input">
								<option value="배너광고">배너광고</option>
								<option value="페이스북">페이스북</option>
								<option value="기타">기타</option>
							</select>
						</div>

					</div>
					
					<div class="agree_wrap">
						<div class="agree_box">
							<ul>
								<li><a href="javascript://">플랫폼 이용약관 <span class="">보기 <i class="fa fa-angle-down" aria-hidden="true"></i></a></span>
									<div class="agree_text">
									쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기
									</div>
								</li>
								<li><a href="javascript://">개인정보 수집 및 이용동의 <span class="">보기 <i class="fa fa-angle-down" aria-hidden="true"></i></a></span>
									<div class="agree_text">
									쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기
									</div>
								</li>
								<li><a href="javascript://">개인정보 제 3자 제공 동의 <span class="">보기 <i class="fa fa-angle-down" aria-hidden="true"></i></a></span>
									<div class="agree_text">
									쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기
									</div>
								</li>
								<li><a href="javascript://">마케팅 정보 수신 동의 </a></span>
									<div class="agree_text">
									쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기쇼핑몰 구매 약관 동의 상세보기
									</div>
								</li>
							</ul>
						</div>
						<div class="agree_check">
							<div class="page__toggle">
							<label class="toggle">
							  <input class="toggle__input" type="checkbox" name="diff" value="1">
							  <span class="toggle__label">
								<span class="toggle__text">본 서비스의 이용약관과 개인정보처리방침에 모두 동의합니다</span>
							  </span>
							</label>
							</div>
						</div>
						<div class="reg_btn_wrap">
							<a href="#" class="reg_btn">가입완료 하기</a>
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