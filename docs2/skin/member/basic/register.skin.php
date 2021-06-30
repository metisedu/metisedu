<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$sQuery = " SELECT *
			FROM han_route
			ORDER BY ro_name ASC
			";
$sql = sql_query($sQuery);

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

<script src="<?php echo G5_JS_URL ?>/jquery.register_form.js"></script>
<?php if($config['cf_cert_use'] && ($config['cf_cert_ipin'] || $config['cf_cert_hp'])) { ?>
<script src="<?php echo G5_JS_URL ?>/certify.js?v=<?php echo G5_JS_VER; ?>"></script>
<?php } ?>

<!-- 회원가입약관 동의 시작 { -->
<section id="sub_register">
	<div class="container">
		<div class="row">
			<div class="col-md-12 add_bottom_60 text-center">
				<span class="sub-top-title">안녕하세요! <?php echo $config['cf_title']; ?>입니다.</span>
				<span class="sub-top-stitle">소셜 회원가입은 하단에 있습니다.</span>
				<div class="sub-register-tab">
					<ul>
						<li><a href="/bbs/login.php" class="">로그인</a></li>
						<li><a href="javascript:void(0);" class="active">회원가입</a></li>
					</ul>
				</div>
				<div class="register-wrap">

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

					<label for="mb_id">아이디</label>
					<input type="email" name="mb_id" id="mb_id" <?php echo $required ?> <?php echo $readonly ?> class="frm_input add_bottom_30 <?php echo $required ?> <?php echo $readonly ?>" placeholder="이메일 주소를 입력해주세요." minlength="3" maxlength="100">
	                <span id="msg_mb_id"></span>

					<label for="mb_password">비밀번호</label>
					<input type="password" name="mb_password" id="reg_mb_password" <?php echo $required ?> class="frm_input <?php echo $required ?>" minlength="3" maxlength="20" placeholder="비밀번호를 입력해 주세요.">
					<span class="req">* 영문자+숫자 조합으로 입력해 주세요.</span>

					<label for="mb_password_re">비밀번호 확인</label>
					<input type="password" name="mb_password_re" id="reg_mb_password_re" <?php echo $required ?> class="frm_input <?php echo $required ?>" minlength="3" maxlength="20" placeholder="비밀번호를 다시 한번입력해 주세요.">

					<label for="mb_name">이름</label>
					<input type="text" id="reg_mb_name" name="mb_name" value="<?php echo get_text($member['mb_name']) ?>" <?php echo $required ?> <?php echo $readonly; ?> class="frm_input <?php echo $required ?> <?php echo $readonly ?>" size="10" placeholder="이름을 입력해 주세요.">

					<label for="mb_email">연락처</label>
					<input type="text" name="mb_tel" value="<?php echo get_text($member['mb_tel']) ?>" id="reg_mb_tel" <?php echo $config['cf_req_tel']?"required":""; ?> class="frm_input <?php echo $config['cf_req_tel']?"required":""; ?>" maxlength="20" placeholder="입력시 '-'는 제외하고 입력해 주세요." onKeyup="this.value=this.value.replace(/[^0-9]/g,'');">

					<div class="row">
						<div class="col-md-6">
							<label for="mb_company">회사명</label>
							<input type="text" name="mb_company" id="mb_company" value="<?php echo get_text($member['mb_company']); ?>" class="frm_input " placeholder="회사명을 입력해 주세요.">
						</div>
						<div class="col-md-6">
							<label for="mb_company">부서명</label>
							<input type="text" name="mb_partname" id="mb_partname" value="<?php echo get_text($member['mb_partname']); ?>" class="frm_input " placeholder="부서명을 입력해 주세요.">
						</div>
						<div class="col-md-6">
							<label for="mb_position">직급</label>
							<input type="text" name="mb_position" id="mb_position" value="<?php echo get_text($member['mb_position']); ?>" class="frm_input " placeholder="직급을 입력해 주세요.">
						</div>
						<div class="col-md-6">
							<label for="mb_route">인지경로</label>
							<select name="mb_route" id="mb_route" class="frm_input">
							<?php
							for($i = 0; $row = sql_fetch_array($sql); $i++){
								echo"<option value='".$row['ro_name']."'>".$row['ro_name']."</option>";
							}
							?>
							</select>
						</div>

						<?php /*
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
						*/ ?>
					</div>

					<div class="agree_wrap">
						<div class="agree_box">
							<ul>
								<li style="position:relative;">
									<a href="/content/서비스-이용약관/" target="_blank">플랫폼 이용약관(필수)</a>
									<span class="" style="position: absolute;top: 0px;right: 10px;">
									<label class="toggle" style="float:right;">
									  <input type="radio" name="agree1" value="0" id="agree11" class="selec_chk toggle__input">
									  <span class="toggle__label">
										<span class="toggle__text">비동의</span>
									  </span>
									</label>
									<label class="toggle" style="float:right;margin-right: 15px;">
									  <input type="radio" name="agree1" value="1" id="agree12" class="selec_chk toggle__input" checked>
									  <span class="toggle__label">
										<span class="toggle__text">동의</span>
									  </span>
									</label>
									</span>
								</li>
								<li style="position:relative;"><a href="/content/개인정보-처리방침/" target="_blank">개인정보 수집 및 이용동의(필수)</a>
									<span class="" style="position: absolute;top: 0px;right: 10px;">
									<label class="toggle" style="float:right;">
									  <input type="radio" name="agree2" value="0" id="agree21" class="selec_chk toggle__input">
									  <span class="toggle__label">
										<span class="toggle__text">비동의</span>
									  </span>
									</label>
									<label class="toggle" style="float:right;margin-right: 15px;">
									  <input type="radio" name="agree2" value="1" id="agree22" class="selec_chk toggle__input" checked>
									  <span class="toggle__label">
										<span class="toggle__text">동의</span>
									  </span>
									</label>
									</span>
								</li>
								<li style="position:relative;"><a href="/content/개인정보-처리방침/" target="_blank">개인정보 제 3자 제공 동의(필수)</a>
									<span class="" style="position: absolute;top: 0px;right: 10px;">
									<label class="toggle" style="float:right;">
									  <input type="radio" name="agree3" value="0" id="agree31" class="selec_chk toggle__input">
									  <span class="toggle__label">
										<span class="toggle__text">비동의</span>
									  </span>
									</label>
									<label class="toggle" style="float:right;margin-right: 15px;">
									  <input type="radio" name="agree3" value="1" id="agree32" class="selec_chk toggle__input" checked>
									  <span class="toggle__label">
										<span class="toggle__text">동의</span>
									  </span>
									</label>
									</span>
								</li>
								<li style="position:relative;"><a href="javascript:void(0)">마케팅 정보 수신 동의(선택) </a>
									<span class="" style="position: absolute;top: 0px;right: 10px;">
									<label class="toggle" style="float:right;">
									  <input type="radio" name="agree4" value="0" id="agree41" class="selec_chk toggle__input">
									  <span class="toggle__label">
										<span class="toggle__text">비동의</span>
									  </span>
									</label>
									<label class="toggle" style="float:right;margin-right: 15px;">
									  <input type="radio" name="agree4" value="1" id="agree42" class="selec_chk toggle__input" checked>
									  <span class="toggle__label">
										<span class="toggle__text">동의</span>
									  </span>
									</label>
									</span>
								</li>
							</ul>
						</div>
						<div class="agree_check">
							<div class="page__toggle">
							<label class="toggle">
							  <input type="checkbox" name="agree" value="1" id="agree1" class="selec_chk toggle__input" checked>
							  <span class="toggle__label">
								<span class="toggle__text">본 서비스의 이용약관과 개인정보처리방침에 모두 동의합니다</span>
							  </span>
							</label>
							</div>
						</div>
						<div class="reg_btn_wrap">
							<button type="submit" class="reg_btn" id="btn_submit">회원가입 하기</button>
						</div>

					</div>
				</form>

				<?php
				// 소셜로그인 사용시 소셜로그인 버튼
				@include_once(get_social_skin_path().'/social_login.skin.php');
				?>
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

	$("#agree1").click(function(){
		if ($("#agree1").prop('checked') === false) {
			$("#agree11").attr('checked', true);
			$("#agree21").attr('checked', true);
			$("#agree31").attr('checked', true);
			$("#agree41").attr('checked', true);
		}else{
			$("#agree12").attr('checked', true);
			$("#agree22").attr('checked', true);
			$("#agree32").attr('checked', true);
			$("#agree42").attr('checked', true);
		}
	});
});

// submit 최종 폼체크
function fregisterform_submit(f)
{
	if ($("#agree12").prop('checked') === false) {
		alert("본 서비스의 플랫폼 이용약관 내용에 동의하셔야 회원가입 하실 수 있습니다.");
		$("#agree11").select();
		return false;
	}

	if ($("#agree32").prop('checked') === false) {
		alert("본 서비스의 개인정보 수집 및 이용동의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
		$("#agree21").select();
		return false;
	}

	if ($("#agree32").prop('checked') === false) {
		alert("본 서비스의 개인정보 제 3자 제공 동의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
		$("#agree31").select();
		return false;
	}

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

	// E-mail 검사
	/*
    if ((f.w.value == "") || (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {
        var msg = reg_mb_email_check();
        if (msg) {
            alert(msg);
            f.reg_mb_email.select();
            return false;
        }
    }
	*/

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

	// 모두선택
	$("input[name=chk_all]").click(function() {
		if ($(this).prop('checked')) {
			$("input[name^=agree]").prop('checked', true);
		} else {
			$("input[name^=agree]").prop("checked", false);
		}
	});

	$(".pay_agree > ul > li > a").on("click",function(){
		$(this).next(".agree_text").toggle();
	});
});

</script>
<!-- } 회원가입 약관 동의 끝 -->
