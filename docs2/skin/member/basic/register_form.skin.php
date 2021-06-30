<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- 회원정보 입력/수정 시작 { -->

<div class="register">
<script src="<?php echo G5_JS_URL ?>/jquery.register_form.js"></script>
<?php if($config['cf_cert_use'] && ($config['cf_cert_ipin'] || $config['cf_cert_hp'])) { ?>
<script src="<?php echo G5_JS_URL ?>/certify.js?v=<?php echo G5_JS_VER; ?>"></script>
<?php } ?>
<style>
.mypage-modify-wrap .btn_wrap button.mypage-modify-cancel {
    background-color: #171920;
    margin-left: 2%;
}

.mypage-modify-wrap .btn_wrap button {
	border:0px;
    display: inline-block;
    box-sizing: border-box;
    color: #ffffff;
    background-color: #edad58;
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

			<div class="col-md-9 col-sm-12 col-xs-12 my_con">
				<div class="mypage-modfy">
					<div class="mypage-small-tit">
						회원정보수정
					</div>

					<div class="mypage-modify-wrap">
					<form id="fregisterform" name="fregisterform" action="<?php echo $register_action_url ?>" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
					<input type="hidden" name="w" value="<?php echo $w ?>">
					<input type="hidden" name="url" value="<?php echo $urlencode ?>">
					<input type="hidden" name="agree" value="<?php echo $agree ?>">
					<input type="hidden" name="agree2" value="<?php echo $agree2 ?>">
					<input type="hidden" name="cert_type" value="<?php echo $member['mb_certify']; ?>">
					<input type="hidden" name="cert_no" value="">
					<?php if (isset($member['mb_sex'])) {  ?><input type="hidden" name="mb_sex" value="<?php echo $member['mb_sex'] ?>"><?php }  ?>
					<?php if (isset($member['mb_nick_date']) && $member['mb_nick_date'] > date("Y-m-d", G5_SERVER_TIME - ($config['cf_nick_modify'] * 86400))) { // 닉네임수정일이 지나지 않았다면  ?>
					<input type="hidden" name="mb_nick_default" value="<?php echo get_text($member['mb_nick']) ?>">
					<input type="hidden" name="mb_nick" value="<?php echo get_text($member['mb_nick']) ?>">
					<?php }  ?>
					<input type="hidden" name="mb_id" value="<?php echo $member['mb_id'] ?>">

						<div class="row mypage-modify-contents">
							<div class="mypage-modify-tit">
								일반정보
							</div>
							<div class="row add_bottom_30">
								<div class="col-lg-2 col-md-4 col-sm-4">
									<label for="mb_name">이름</label>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-8 col-xs-12">
									<input type="text" id="reg_mb_name" name="mb_name" value="<?php echo get_text($member['mb_name']) ?>" <?php echo $required ?> <?php echo $readonly; ?> class="frm_input <?php echo $required ?> <?php echo $readonly ?>" size="10" placeholder="이름" style="background-color:#cccccc">
								</div>

								<div class="col-lg-2 col-md-4 col-sm-4">
									<label for="mb_tel">연락처</label>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-8 col-xs-12">
									<input type="text" name="mb_tel" value="<?php echo get_text($member['mb_tel']) ?>" id="reg_mb_tel" <?php echo $config['cf_req_tel']?"required":""; ?> class="frm_input <?php echo $config['cf_req_tel']?"required":""; ?>" maxlength="20" placeholder="전화번호">
								</div>

								<div class="col-lg-2 col-md-4 col-sm-4">
									<label for="mb_email">아이디</label>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-8 col-xs-12">
									<input type="hidden" name="old_email" value="<?php echo $member['mb_email'] ?>">
									<input type="text" name="mb_email" value="<?php echo isset($member['mb_email'])?$member['mb_email']:''; ?>" id="reg_mb_email" required class="frm_input email required" size="70" readonly maxlength="100" placeholder="E-mail">
									<!--<span class="mypage-modify-social-caution">
										소셜 가입회원은 이메일 변경이 불가능합니다
									</span>-->
								</div>

								<div class="col-lg-2 col-md-4 col-sm-4">
									<label for="mb_email">프로필 이미지</label>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-8 col-xs-12">
									<input type="file" name="mb_img" id="xreg_mb_img" class="frm_input" >
									<?php if ($w == 'u' && file_exists($mb_img_path)) {  ?>
									<div class="profile_img" style="margin-bottom: 0px;float: left;width:60px;margin-left: 15px;">
										<img src="<?php echo $mb_img_url ?>" alt="회원이미지">
									</div>
									<?php }  ?>
								</div>
							</div>

							<div class="mypage-modify-tit">
								비밀번호
							</div>
							<div class="row add_bottom_30">
								<!--<div class="col-lg-2 col-md-4 col-sm-4">
									<label for="mb_password">현재 비밀번호</label>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-8">
									<input type="password" name="mb_password" id="reg_mb_password" <?php echo $required ?> class="frm_input <?php echo $required ?>" minlength="3" maxlength="20" placeholder="비밀번호">
									<span class="mypage-modify-social-caution" style="display:none">
										현재 비밀번호와 맞지 않습니다
									</span>
								</div>-->
								<div class="col-lg-2 col-md-4 col-sm-4">
									<label for="mb_password">변경 비밀번호</label>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-8 col-xs-12">
									<input type="password" name="mb_password" id="mb_password" <?php echo $required ?> class="frm_input <?php echo $required ?>" minlength="3" maxlength="20" placeholder="비밀번호">
									<span class="mypage-modify-social-caution" style="display:none">
										영문+숫자형태로 기입해 주세요
									</span>
								</div>
								<div class="col-lg-2 col-md-4 col-sm-4">
									<label for="mb_password_re">비밀번호 확인</label>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-8 col-xs-12">
									<input type="password" name="mb_password_re" id="reg_mb_password_re" <?php echo $required ?> class="frm_input <?php echo $required ?>" minlength="3" maxlength="20" placeholder="비밀번호 확인">
									<span class="mypage-modify-social-caution" id="check_pass" style="display:none">
										비밀번호가 일치합니다.
									</span>
								</div>
							</div>
							<div class="mypage-modify-tit">
								회사정보
							</div>
							<div class="row add_bottom_60">
								<div class="col-lg-2 col-md-4 col-sm-4">
									<label for="mb_company">회사명</label>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-8 col-xs-12">
									<input type="text" name="mb_company" id="mb_company" value="<?php echo get_text($member['mb_company']); ?>" class="frm_input " placeholder="회사명을 입력해 주세요.">
								</div>
								<div class="col-lg-2 col-md-4 col-sm-4">
									<label for="mb_partname">부서명</label>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-8 col-xs-12">
									<input type="text" name="mb_partname" id="mb_partname" value="<?php echo get_text($member['mb_partname']); ?>" class="frm_input " placeholder="부서명을 입력해 주세요.">
								</div>
								<div class="col-lg-2 col-md-4 col-sm-4">
									<label for="mb_position">직급명</label>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-8 col-xs-12">
									<input type="text" name="mb_position" id="mb_position" value="<?php echo get_text($member['mb_position']); ?>" class="frm_input " placeholder="직급을 입력해 주세요.">
								</div>
							</div>
							<div class="mypage-modify-tit">
								SNS
							</div>
							<div class="row add_bottom_60">
								<div class="col-lg-2 col-md-4 col-sm-4">
									<label for="mb_fb">페이스북</label>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-8 col-xs-12">
									<input type="text" name="mb_fb" id="mb_fb" value="<?php echo get_text($member['mb_fb']); ?>" class="frm_input " placeholder="예)https://페이스북.com">
								</div>
								<div class="col-lg-2 col-md-4 col-sm-4">
									<label for="mb_in">인스타그램</label>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-8 col-xs-12">
									<input type="text" name="mb_in" id="mb_in" value="<?php echo get_text($member['mb_in']); ?>" class="frm_input " placeholder="예)https://인스타그램.com">
								</div>
								<div class="col-lg-2 col-md-4 col-sm-4">
									<label for="mb_yt">유튜브</label>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-8 col-xs-12">
									<input type="text" name="mb_yt" id="mb_yt" value="<?php echo get_text($member['mb_yt']); ?>" class="frm_input " placeholder="예)https://유튜브.com">
								</div>
								<div class="col-lg-2 col-md-4 col-sm-4">
									<label for="mb_ho">홈페이지</label>
								</div>
								<div class="col-lg-10 col-md-8 col-sm-8 col-xs-12">
									<input type="text" name="mb_ho" id="mb_ho" value="<?php echo get_text($member['mb_ho']); ?>" class="frm_input " placeholder="예)http://홈페이지.com">
								</div>
							</div>
						</div>
						<div class="btn_wrap text-center">
							<button type="submit" class="mypage-modify-ok" id="btn_submit">변경하기</button>
							<button type="button" onclick="location.href='/shop/mypage.php#class';" class="mypage-modify-cancel">취소하기</button>
						</div>

					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>


<script>
$(function() {
    $("#reg_zip_find").css("display", "inline-block");

    <?php if($config['cf_cert_use'] && $config['cf_cert_ipin']) { ?>
    // 아이핀인증
    $("#win_ipin_cert").click(function() {
        if(!cert_confirm())
            return false;

        var url = "<?php echo G5_OKNAME_URL; ?>/ipin1.php";
        certify_win_open('kcb-ipin', url);
        return;
    });

    <?php } ?>
    <?php if($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
    // 휴대폰인증
    $("#win_hp_cert").click(function() {
        if(!cert_confirm())
            return false;

        <?php
        switch($config['cf_cert_hp']) {
            case 'kcb':
                $cert_url = G5_OKNAME_URL.'/hpcert1.php';
                $cert_type = 'kcb-hp';
                break;
            case 'kcp':
                $cert_url = G5_KCPCERT_URL.'/kcpcert_form.php';
                $cert_type = 'kcp-hp';
                break;
            case 'lg':
                $cert_url = G5_LGXPAY_URL.'/AuthOnlyReq.php';
                $cert_type = 'lg-hp';
                break;
            default:
                echo 'alert("기본환경설정에서 휴대폰 본인확인 설정을 해주십시오");';
                echo 'return false;';
                break;
        }
        ?>

        certify_win_open("<?php echo $cert_type; ?>", "<?php echo $cert_url; ?>");
        return;
    });
    <?php } ?>

	$("#reg_mb_password_re").keyup(function(){
		var msg = "";
		if ($("#mb_password").val() != $("#reg_mb_password_re").val()) {
			msg = "비밀번호가 일치하지 않습니다.";
		}else if($("#mb_password").val() == $("#reg_mb_password_re").val()) {
			msg = "비밀번호가 일치합니다.";
		}

		$("#check_pass").text(msg);
		$("#check_pass").show();
	});

});

// submit 최종 폼체크
function fregisterform_submit(f)
{
    // 회원아이디 검사
    if (f.w.value == "") {
        var msg = reg_mb_id_check();
        if (msg) {
            alert(msg);
            f.mb_id.select();
            return false;
        }
    }

    if (f.w.value == "") {
        if (f.mb_password.value.length < 3) {
            alert("비밀번호를 3글자 이상 입력하십시오.");
            f.mb_password.focus();
            return false;
        }
    }

    if (f.mb_password.value != f.mb_password_re.value) {
        alert("비밀번호가 같지 않습니다.");
        f.mb_password_re.focus();
        return false;
    }

    if (f.mb_password.value.length > 0) {
        if (f.mb_password_re.value.length < 3) {
            alert("비밀번호를 3글자 이상 입력하십시오.");
            f.mb_password_re.focus();
            return false;
        }
    }

    // 이름 검사
    if (f.w.value=="") {
        if (f.mb_name.value.length < 1) {
            alert("이름을 입력하십시오.");
            f.mb_name.focus();
            return false;
        }

        /*
        var pattern = /([^가-힣\x20])/i;
        if (pattern.test(f.mb_name.value)) {
            alert("이름은 한글로 입력하십시오.");
            f.mb_name.select();
            return false;
        }
        */
    }

    <?php if($w == '' && $config['cf_cert_use'] && $config['cf_cert_req']) { ?>
    // 본인확인 체크
    if(f.cert_no.value=="") {
        alert("회원가입을 위해서는 본인확인을 해주셔야 합니다.");
        return false;
    }
    <?php } ?>

    // E-mail 검사
    if ((f.w.value == "") || (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {
        var msg = reg_mb_email_check();
        if (msg) {
            alert(msg);
            f.reg_mb_email.select();
            return false;
        }
    }

    document.getElementById("btn_submit").disabled = "disabled";

    return true;
}

jQuery(function($){
	//tooltip
    $(document).on("click", ".tooltip_icon", function(e){
        $(this).next(".tooltip").fadeIn(400).css("display","inline-block");
    }).on("mouseout", ".tooltip_icon", function(e){
        $(this).next(".tooltip").fadeOut();
    });
});

</script>

<!-- } 회원정보 입력/수정 끝 -->