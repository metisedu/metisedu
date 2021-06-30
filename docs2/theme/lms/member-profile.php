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
						프로필 등록
					</div>

					<div class="mypage-profile-wrap">
						<div class="row add_bottom_30">
							<div class="col-md-12">
								<label for="">나의전문분야</label>
							</div>
							<div class="col-md-12">

								<select name="mb_interest1" id="mb_interest1" class="frm_input">
									<option value="배너광고">배너광고</option>
									<option value="페이스북">페이스북</option>
									<option value="기타">기타</option>
								</select>

								<select name="mb_interest2" id="mb_interest2" class="frm_input">
									<option value="배너광고">배너광고</option>
									<option value="페이스북">페이스북</option>
									<option value="기타">기타</option>
								</select>

								<select name="mb_interest3" id="mb_interest3" class="frm_input">
									<option value="배너광고">배너광고</option>
									<option value="페이스북">페이스북</option>
									<option value="기타">기타</option>
								</select>
							</div>
							<div class="col-md-12">
								<textarea name="mb_profile" style="width:100%;height:500px;margin-top:20px;">프로필</textarea>
							</div>

						</div>
						<div class="btn_wrap text-center">
							<a href="#" class="mypage-modify-ok">프로필 등록</a>
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