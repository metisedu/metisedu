<?php
include_once('./_common.php');
define('_NOHEADER_',true);

if (!$is_member)
    goto_url(G5_BBS_URL."/login.php?url=".urlencode(G5_SHOP_URL."/mypage.php"));

$g5['title'] = '프로필';
include_once('./_head.php');
?>
<style>
.mypage-profile-wrap .btn_wrap button.mypage-modify-cancel {
    background-color: #171920;
    margin-left: 2%;
}

.mypage-profile-wrap .btn_wrap button {
	border:0px;
    display: inline-block;
    box-sizing: border-box;
    color: #ffffff;
    background-color: #0065af;
    padding: 16px 0;
    width: 25%;
    font-size: 1.2em;
}
</style>
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
					<form name="nForm" action="<?php echo G5_SHOP_URL; ?>/profile_update.php" method="post" >
						<div class="row add_bottom_30">
							<div class="col-md-12">
								<label for="">나의전문분야</label>
							</div>
							<div class="col-md-12">

								<select name="mb_interest1" id="mb_interest1" class="frm_input">
									<option value="배너광고" <?php if($member['mb_interest1'] == "배너광고") echo"selected"; ?>>배너광고</option>
									<option value="페이스북" <?php if($member['mb_interest1'] == "페이스북") echo"selected"; ?>>페이스북</option>
									<option value="기타" <?php if($member['mb_interest1'] == "기타") echo"selected"; ?>>기타</option>
								</select>

								<select name="mb_interest2" id="mb_interest2" class="frm_input">
									<option value="배너광고" <?php if($member['mb_interest2'] == "배너광고") echo"selected"; ?>>배너광고</option>
									<option value="페이스북" <?php if($member['mb_interest2'] == "페이스북") echo"selected"; ?>>페이스북</option>
									<option value="기타" <?php if($member['mb_interest2'] == "기타") echo"selected"; ?>>기타</option>
								</select>

								<select name="mb_interest3" id="mb_interest3" class="frm_input">
									<option value="배너광고" <?php if($member['mb_interest3'] == "배너광고") echo"selected"; ?>>배너광고</option>
									<option value="페이스북" <?php if($member['mb_interest3'] == "페이스북") echo"selected"; ?>>페이스북</option>
									<option value="기타" <?php if($member['mb_interest3'] == "기타") echo"selected"; ?>>기타</option>
								</select>
							</div>
							<div class="col-md-12">
								<textarea name="mb_profile" id="mb_profile" style="width:100%;height:500px;margin-top:20px;"><?php echo $member['mb_profile'] ?></textarea>
							</div>

						</div>
						<div class="btn_wrap text-center">
							<button type="submit" class="mypage-modify-ok">프로필 등록</button>
							<button type="button" class="mypage-modify-cancel" onclick="history.back();">취소하기</button>
						</div>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>

</section>

<?php
include_once("./_tail.php");
?>