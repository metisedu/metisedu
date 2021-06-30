<?php
$sub_menu = "200100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

if ($w == '')
{
    $required_mb_id = 'required';
    $required_mb_id_class = 'required alnum_';
    $required_mb_password = 'required';
    $sound_only = '<strong class="sound_only">필수</strong>';

    $mb['mb_mailling'] = 1;
    $mb['mb_open'] = 1;
    $mb['mb_level'] = $config['cf_register_level'];
    $html_title = '추가';
}
else if ($w == 'u')
{
    $mb = get_member($mb_id);
    if (!$mb['mb_id'])
        alert('존재하지 않는 회원자료입니다.');

    if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level'])
        alert('자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.');

    $required_mb_id = 'readonly';
    $required_mb_password = '';
    $html_title = '수정';

    $mb['mb_name'] = get_text($mb['mb_name']);
    $mb['mb_nick'] = get_text($mb['mb_nick']);
    $mb['mb_email'] = get_text($mb['mb_email']);
    $mb['mb_homepage'] = get_text($mb['mb_homepage']);
    $mb['mb_birth'] = get_text($mb['mb_birth']);
    $mb['mb_tel'] = get_text($mb['mb_tel']);
    $mb['mb_hp'] = get_text($mb['mb_hp']);
    $mb['mb_addr1'] = get_text($mb['mb_addr1']);
    $mb['mb_addr2'] = get_text($mb['mb_addr2']);
    $mb['mb_addr3'] = get_text($mb['mb_addr3']);
    $mb['mb_signature'] = get_text($mb['mb_signature']);
    $mb['mb_recommend'] = get_text($mb['mb_recommend']);
    $mb['mb_profile'] = get_text($mb['mb_profile']);
    $mb['mb_1'] = get_text($mb['mb_1']);
    $mb['mb_2'] = get_text($mb['mb_2']);
    $mb['mb_3'] = get_text($mb['mb_3']);
    $mb['mb_4'] = get_text($mb['mb_4']);
    $mb['mb_5'] = get_text($mb['mb_5']);
    $mb['mb_6'] = get_text($mb['mb_6']);
    $mb['mb_7'] = get_text($mb['mb_7']);
    $mb['mb_8'] = get_text($mb['mb_8']);
    $mb['mb_9'] = get_text($mb['mb_9']);
    $mb['mb_10'] = get_text($mb['mb_10']);
	$mb['mb_company'] = get_text($mb['mb_company']);
    $mb['mb_partname'] = get_text($mb['mb_partname']);
    $mb['mb_route'] = get_text($mb['mb_route']);
    $mb['mb_position'] = get_text($mb['mb_position']);
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');

// 본인확인방법
switch($mb['mb_certify']) {
    case 'hp':
        $mb_certify_case = '휴대폰';
        $mb_certify_val = 'hp';
        break;
    case 'ipin':
        $mb_certify_case = '아이핀';
        $mb_certify_val = 'ipin';
        break;
    case 'admin':
        $mb_certify_case = '관리자 수정';
        $mb_certify_val = 'admin';
        break;
    default:
        $mb_certify_case = '';
        $mb_certify_val = 'admin';
        break;
}

// 본인확인
$mb_certify_yes  =  $mb['mb_certify'] ? 'checked="checked"' : '';
$mb_certify_no   = !$mb['mb_certify'] ? 'checked="checked"' : '';

// 성인인증
$mb_adult_yes       =  $mb['mb_adult']      ? 'checked="checked"' : '';
$mb_adult_no        = !$mb['mb_adult']      ? 'checked="checked"' : '';

//메일수신
$mb_mailling_yes    =  $mb['mb_mailling']   ? 'checked="checked"' : '';
$mb_mailling_no     = !$mb['mb_mailling']   ? 'checked="checked"' : '';

// SMS 수신
$mb_sms_yes         =  $mb['mb_sms']        ? 'checked="checked"' : '';
$mb_sms_no          = !$mb['mb_sms']        ? 'checked="checked"' : '';

// 정보 공개
$mb_open_yes        =  $mb['mb_open']       ? 'checked="checked"' : '';
$mb_open_no         = !$mb['mb_open']       ? 'checked="checked"' : '';

if (isset($mb['mb_certify'])) {
    // 날짜시간형이라면 drop 시킴
    if (preg_match("/-/", $mb['mb_certify'])) {
        sql_query(" ALTER TABLE `{$g5['member_table']}` DROP `mb_certify` ", false);
    }
} else {
    sql_query(" ALTER TABLE `{$g5['member_table']}` ADD `mb_certify` TINYINT(4) NOT NULL DEFAULT '0' AFTER `mb_hp` ", false);
}

if(isset($mb['mb_adult'])) {
    sql_query(" ALTER TABLE `{$g5['member_table']}` CHANGE `mb_adult` `mb_adult` TINYINT(4) NOT NULL DEFAULT '0' ", false);
} else {
    sql_query(" ALTER TABLE `{$g5['member_table']}` ADD `mb_adult` TINYINT NOT NULL DEFAULT '0' AFTER `mb_certify` ", false);
}

// 지번주소 필드추가
if(!isset($mb['mb_addr_jibeon'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_addr_jibeon` varchar(255) NOT NULL DEFAULT '' AFTER `mb_addr2` ", false);
}

// 건물명필드추가
if(!isset($mb['mb_addr3'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_addr3` varchar(255) NOT NULL DEFAULT '' AFTER `mb_addr2` ", false);
}

// 중복가입 확인필드 추가
if(!isset($mb['mb_dupinfo'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_dupinfo` varchar(255) NOT NULL DEFAULT '' AFTER `mb_adult` ", false);
}

// 이메일인증 체크 필드추가
if(!isset($mb['mb_email_certify2'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_email_certify2` varchar(255) NOT NULL DEFAULT '' AFTER `mb_email_certify` ", false);
}

if ($mb['mb_intercept_date']) $g5['title'] = "차단된 ";
else $g5['title'] .= "";
$g5['title'] .= '회원 '.$html_title;
include_once('./admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js
?>
<form name="fmember" id="fmember" action="./member_form_update.php" onsubmit="return fmember_submit(this);" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="mb_level" value="<?php echo $mb['mb_level'] ?>">
<input type="hidden" name="token" value="">
<div class="card card-inverse card-flat">
	<div class="card-header">
		<div class="card-title"></div>
	</div>

	<div class="card-block">

		<div class="row">
			<div class="col-md-12">

				<div class="tbl_frm01 tbl_wrap table-responsive">
					<table class="table">
					<caption><?php echo $g5['title']; ?></caption>
					<colgroup>
						<col class="grid_4">
						<col>
						<col class="grid_4">
						<col>
					</colgroup>
					<tbody>
					<tr>
						<th scope="row"><label for="mb_id">이름(실명)/아이디<?php echo $sound_only ?></label></th>
						<td>
							<input type="text" name="mb_name" value="<?php echo $mb['mb_name'] ?>" id="mb_name" required class="required frm_input" size="15"  maxlength="20"> /
							<input type="text" name="mb_id" value="<?php echo $mb['mb_id'] ?>" id="mb_id" <?php echo $required_mb_id ?> class="frm_input <?php echo $required_mb_id_class ?>" size="15"  maxlength="20">
						</td>
						<th scope="row"><label for="mb_email">E-mail<strong class="sound_only">필수</strong></label></th>
						<td><input type="text" name="mb_email" value="<?php echo $mb['mb_email'] ?>" id="mb_email" maxlength="100" required class="required frm_input email" size="30"></td>
					</tr>
					<tr>
						<th scope="row"><label for="mb_birth">생년월일<?php echo $sound_only ?></label></th>
						<td><input type="text" name="mb_birth" id="mb_birth" class="frm_input" size="15" maxlength="20"></td>
						<th scope="row"><label for="mb_password">비밀번호<?php echo $sound_only ?></label></th>
						<td><input type="password" name="mb_password" id="mb_password" <?php echo $required_mb_password ?> class="frm_input <?php echo $required_mb_password ?>" size="15" maxlength="20"></td>
					</tr>

					<tr>
						<th scope="row"><label for="mb_company">회사/기관</label></th>
						<td><input type="text" name="mb_company" value="<?php echo $mb['mb_company'] ?>" id="mb_company" class="frm_input" size="15" maxlength="20"></td>
						<th scope="row"><label for="mb_partname">부서명</label></th>
						<td><input type="text" name="mb_partname" value="<?php echo $mb['mb_partname'] ?>" id="mb_partname" class="frm_input" size="15" maxlength="20"></td>
					</tr>

					<tr>
						<th scope="row"><label for="mb_hp">연락처</label></th>
						<td><input type="text" name="mb_hp" value="<?php echo $mb['mb_hp'] ?>" id="mb_hp" class="frm_input" size="15" maxlength="20"></td>
						<th scope="row"><label for="mb_tel">유선전화</label></th>
						<td><input type="text" name="mb_tel" value="<?php echo $mb['mb_tel'] ?>" id="mb_tel" class="frm_input" size="15" maxlength="20"></td>
					</tr>

					<tr>
						<th scope="row"><label for="mb_route">인지경로</label></th>
						<td><input type="text" name="mb_route" value="<?php echo $mb['mb_route'] ?>" id="mb_route" class="frm_input" size="15" maxlength="20"></td>
						<th scope="row"><label for="mb_position">직무</label></th>
						<td><input type="text" name="mb_position" value="<?php echo $mb['mb_position'] ?>" id="mb_position" class="frm_input" size="15" maxlength="20"></td>
					</tr>

					<tr>
						<th scope="row"><label for="mb_sms_yes">SMS 수신</label></th>
						<td>
							<input type="radio" name="mb_sms" value="1" id="mb_sms_yes" <?php echo $mb_sms_yes; ?>>
							<label for="mb_sms_yes">예</label>
							<input type="radio" name="mb_sms" value="0" id="mb_sms_no" <?php echo $mb_sms_no; ?>>
							<label for="mb_sms_no">아니오</label>
						</td>
						<th scope="row">메일 수신</th>
						<td>
							<input type="radio" name="mb_mailling" value="1" id="mb_mailling_yes" <?php echo $mb_mailling_yes; ?>>
							<label for="mb_mailling_yes">예</label>
							<input type="radio" name="mb_mailling" value="0" id="mb_mailling_no" <?php echo $mb_mailling_no; ?>>
							<label for="mb_mailling_no">아니오</label>
						</td>
					</tr>




					<?php if ($w == 'u') { ?>
					<tr>
						<th scope="row">회원가입일</th>
						<td><?php echo $mb['mb_datetime'] ?></td>
						<th scope="row">최근접속일</th>
						<td><?php echo $mb['mb_today_login'] ?></td>
					</tr>
					<tr>
						<th scope="row">IP</th>
						<td colspan="3"><?php echo $mb['mb_ip'] ?></td>
					</tr>
					<?php if ($config['cf_use_email_certify']) { ?>
					<tr>
						<th scope="row">인증일시</th>
						<td colspan="3">
							<?php if ($mb['mb_email_certify'] == '0000-00-00 00:00:00') { ?>
							<?php echo help('회원님이 메일을 수신할 수 없는 경우 등에 직접 인증처리를 하실 수 있습니다.') ?>
							<input type="checkbox" name="passive_certify" id="passive_certify">
							<label for="passive_certify">수동인증</label>
							<?php } else { ?>
							<?php echo $mb['mb_email_certify'] ?>
							<?php } ?>
						</td>
					</tr>
					<?php } ?>
					<?php } ?>

					<?php if ($config['cf_use_recommend']) { // 추천인 사용 ?>
					<tr>
						<th scope="row">추천인</th>
						<td colspan="3"><?php echo ($mb['mb_recommend'] ? get_text($mb['mb_recommend']) : '없음'); // 081022 : CSRF 보안 결함으로 인한 코드 수정 ?></td>
					</tr>
					<?php } ?>

					<tr>
						<th scope="row"><label for="mb_leave_date">탈퇴일자</label></th>
						<td>
							<input type="text" name="mb_leave_date" value="<?php echo $mb['mb_leave_date'] ?>" id="mb_leave_date" class="frm_input" maxlength="8">
							<input type="checkbox" value="<?php echo date("Ymd"); ?>" id="mb_leave_date_set_today" onclick="if (this.form.mb_leave_date.value==this.form.mb_leave_date.defaultValue) {
				this.form.mb_leave_date.value=this.value; } else { this.form.mb_leave_date.value=this.form.mb_leave_date.defaultValue; }">
							<label for="mb_leave_date_set_today">탈퇴일을 오늘로 지정</label>
						</td>
						<th scope="row">접근차단일자</th>
						<td>
							<input type="text" name="mb_intercept_date" value="<?php echo $mb['mb_intercept_date'] ?>" id="mb_intercept_date" class="frm_input" maxlength="8">
							<input type="checkbox" value="<?php echo date("Ymd"); ?>" id="mb_intercept_date_set_today" onclick="if
				(this.form.mb_intercept_date.value==this.form.mb_intercept_date.defaultValue) { this.form.mb_intercept_date.value=this.value; } else {
				this.form.mb_intercept_date.value=this.form.mb_intercept_date.defaultValue; }">
							<label for="mb_intercept_date_set_today">접근차단일을 오늘로 지정</label>
						</td>
					</tr>

					<?php
					//소셜계정이 있다면
					if(function_exists('social_login_link_account') && $mb['mb_id'] ){
						if( $my_social_accounts = social_login_link_account($mb['mb_id'], false, 'get_data') ){ ?>

					<tr>
					<th>소셜계정목록</th>
					<td colspan="3">
						<ul class="social_link_box">
							<li class="social_login_container">
								<h4>연결된 소셜 계정 목록</h4>
								<?php foreach($my_social_accounts as $account){     //반복문
									if( empty($account) ) continue;

									$provider = strtolower($account['provider']);
									$provider_name = social_get_provider_service_name($provider);
								?>
								<div class="account_provider" data-mpno="social_<?php echo $account['mp_no'];?>" >
									<div class="sns-wrap-32 sns-wrap-over">
										<span class="sns-icon sns-<?php echo $provider; ?>" title="<?php echo $provider_name; ?>">
											<span class="ico"></span>
											<span class="txt"><?php echo $provider_name; ?></span>
										</span>

										<span class="provider_name"><?php echo $provider_name;   //서비스이름?> ( <?php echo $account['displayname']; ?> )</span>
										<span class="account_hidden" style="display:none"><?php echo $account['mb_id']; ?></span>
									</div>
									<div class="btn_info"><a href="<?php echo G5_SOCIAL_LOGIN_URL.'/unlink.php?mp_no='.$account['mp_no'] ?>" class="social_unlink" data-provider="<?php echo $account['mp_no'];?>" >연동해제</a> <span class="sound_only"><?php echo substr($account['mp_register_day'], 2, 14); ?></span></div>
								</div>
								<?php } //end foreach ?>
							</li>
						</ul>
						<script>
						jQuery(function($){
							$(".account_provider").on("click", ".social_unlink", function(e){
								e.preventDefault();

								if (!confirm('정말 이 계정 연결을 삭제하시겠습니까?')) {
									return false;
								}

								var ajax_url = "<?php echo G5_SOCIAL_LOGIN_URL.'/unlink.php' ?>";
								var mb_id = '',
									mp_no = $(this).attr("data-provider"),
									$mp_el = $(this).parents(".account_provider");

									mb_id = $mp_el.find(".account_hidden").text();

								if( ! mp_no ){
									alert('잘못된 요청! mp_no 값이 없습니다.');
									return;
								}

								$.ajax({
									url: ajax_url,
									type: 'POST',
									data: {
										'mp_no': mp_no,
										'mb_id': mb_id
									},
									dataType: 'json',
									async: false,
									success: function(data, textStatus) {
										if (data.error) {
											alert(data.error);
											return false;
										} else {
											alert("연결이 해제 되었습니다.");
											$mp_el.fadeOut("normal", function() {
												$(this).remove();
											});
										}
									}
								});

								return;
							});
						});
						</script>

					</td>
					</tr>

					<?php
						}   //end if
					}   //end if

					run_event('admin_member_form_add', $mb, $w, 'table');
					?>

					</tbody>
					</table>
				</div>

				<style>
					.tbl_wrap th, .tbl_wrap td {text-align:center;}
				</style>
				<?php
				if($mb_id != ''){
				?>
				<div class="mv_option_addfrm" id="mv_option_addfrm">
					<div class="btn_list01 btn_list">
						<button type="button" class="btn btn_01 spl_move_apply" data-page="add_lec" data-id="<?php echo $mb['mb_id']; ?>">강의추가</button>
					</div>
					<div class="tbl_frm01 tbl_wrap table-responsive" id="order_list"></div>
				</div>

				<div class="mv_option_addfrm" id="mv_option_addfrm">
					<div class="tbl_frm01 tbl_wrap table-responsive" id="lec_list"></div>
				</div>
				<?php } ?>

				<div class="btn_fixed_top">
					<a href="./member_list.php?<?php echo $qstr ?>" class="btn btn_02">목록</a>
					<input type="submit" value="확인" class="btn_submit btn" accesskey='s'>
				</div>

			</div>
		</div>
	</div>

</div>

</form>

<script>
function fmember_submit(f)
{
    if (!f.mb_icon.value.match(/\.(gif|jpe?g|png)$/i) && f.mb_icon.value) {
        alert('아이콘은 이미지 파일만 가능합니다.');
        return false;
    }

    if (!f.mb_img.value.match(/\.(gif|jpe?g|png)$/i) && f.mb_img.value) {
        alert('회원이미지는 이미지 파일만 가능합니다.');
        return false;
    }

    return true;
}

// 구매내역
function order_list(page){
	$.ajax({
		type:"post",
		url:"/ajax/member_order_list.php",
		data: {
			mb_id: '<?php echo $mb_id; ?>',
			page : page
		},
		success:function(data){
			$("#order_list").html(data);
		}
	});
}

// 강의 수강내역
function lec_list(page){
	$.ajax({
		type:"post",
		url:"/ajax/member_lec_list.php",
		data: {
			mb_id: '<?php echo $mb_id; ?>',
			page : page
		},
		success:function(data){
			$("#lec_list").html(data);
		}
	});
}

$(window).load(function(){
	order_list();
	lec_list();
});

// 강의추가
$(document).on("click","#add_lec_btn",function(){
	if($(".chk_it_id:checked").length == 0){
		alert('추가 하실 강의를 선택해 주세요.');
		return false;
	}

	if($("#fr_date").val() == ""){
		alert('지급기한을 입력해 주세요.');
		return false;
	}

	if($("#to_date").val() == ""){
		alert('지급기한을 입력해 주세요.');
		return false;
	}

	if(confirm('정말 강의 추가 하시겠습니까?')){
		$.ajax({
			type:"post",
			url:"/ajax/add_lec_update.php",
			data: $("#schlist").serialize(),
			success:function(data){
				if(data == "same"){
					alert('이미 등록된 강의 입니다.');
					return false;
				}else if(data == "nomember"){
					alert('회원을 다시 확인 부탁드립니다.');
					return false;
				}else if(data == "noitem"){
					alert('강의를 다시 확인 부탁드립니다.');
					return false;
				}else if(data == "nocart"){
					alert('개발자에게 연락부탁드립니다.');
					return false;
				}else if(data == "nocart"){
					alert('개발자에게 연락부탁드립니다.');
					return false;
				}else if(data == "noinsert"){
					alert('추가실패! 개발자에게 연락부탁드립니다.');
					return false;
				}else{
					alert('추가완료!');
					location.replace('/adm/member_form.php?sst=<?php echo $sst; ?>&sod=<?php echo $sst; ?>&sfl=<?php echo $sfl; ?>&stx=<?php echo $stx; ?>&page=<?php echo $page; ?>&w=u&mb_id=<?php echo $mb_id; ?>');
				}
			}
		});
	}
});

// 환불신청
$(document).on("click","#refund_btn",function(){
	if($("#od_refund_memo").val() == ""){
		alert('환불 사유를 입력해 주세요.');
		return false;
	}

	if($("#od_refund_sub_price").val() == ""){
		alert('환불 금액을 입력해 주세요.');
		return false;
	}

	if($("#od_refund_name").val() == ""){
		alert('예금주를 입력해 주세요.');
		return false;
	}

	if($("#od_refund_bank").val() == ""){
		alert('은행명을 입력해 주세요.');
		return false;
	}

	if($("#od_refund_account").val() == ""){
		alert('계좌번호를 입력해 주세요.');
		return false;
	}

	if(confirm('정말 환불신청 하시겠습니까?')){
		$.ajax({
			type:"post",
			url:"/ajax/od_refund_update.php",
			data: $("#schlist").serialize(),
			success:function(data){
				if(data == "nopay"){
					alert('입금된 상품만 환불신청이 가능합니다.');
					return false;
				}else if(data == "uppay"){
					alert('실결제금액 보다 활불금액이 높을 수 없습니다.');
					return false;
				}else if(data == "noinsert"){
					alert('추가실패! 개발자에게 연락부탁드립니다.');
					return false;
				}else if(data == "noupdate"){
					alert('추가실패! 개발자에게 연락부탁드립니다.');
					return false;
				}else{
					alert('환불 신청 완료 되었습니다.');
					//console.log(data);
					location.reload();
				}
			}
		});
	}
});
</script>
<?php
run_event('admin_member_form_after', $mb, $w);

include_once('./admin.tail.php');
?>
