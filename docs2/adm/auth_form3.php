<?php
$sub_menu = "100210";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

if ($w == '')
{
    $required_mb_id = 'required';
    $required_mb_id_class = 'required alnum_-';
    $required_mb_password = 'required';
    $sound_only = '<strong class="sound_only">필수</strong>';

    $mb['mb_mailling'] = 1;
    $mb['mb_open'] = 1;
    $mb['mb_level'] = $config['cf_register_level'];
    $html_title = '추가';

	$id_check = "N";
}
else if ($w == 'u')
{
    $mb = get_member($mb_id);
    if (!$mb['mb_id'])
        alert('존재하지 않는 강사자료입니다.');

    if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level'])
        alert('자신보다 권한이 높거나 같은 강사은 수정할 수 없습니다.');

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

	$id_check = "Y";
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');

// 분류리스트
$category_select = '';
$script = '';
$sql = " select * from {$g5['g5_shop_category_table']} ";
if ($is_admin != 'super')
    $sql .= " where ca_mb_id = '{$member['mb_id']}' ";
$sql .= " order by ca_order, ca_id ";
$result = sql_query($sql);

$ca_name1 = "";
for ($i=0; $row=sql_fetch_array($result); $i++)
{
    $len = strlen($row['ca_id']) / 2 - 1;

    $nbsp = "";
    for ($i=0; $i<$len; $i++)
        $nbsp .= "&nbsp;&nbsp;&nbsp;";

	if($len == 1){
		if($ca_name1 != $row['ca_name']){
			$ca_name1 = $row['ca_name'];
		}
	}

	if($len == 1){
		$category_select .= "<option value=\"{$row['ca_id']}\">$nbsp{$row['ca_name']}</option>\n";
	}else{
		$category_select .= "<option value=\"{$row['ca_id']}\">$nbsp{$ca_name1}=>{$row['ca_name']}</option>\n";
	}
}

if ($mb['mb_intercept_date']) $g5['title'] = "차단된 ";
else $g5['title'] .= "";
$g5['title'] .= '강사 '.$html_title;
include_once('./admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js
?>
<form name="fmember" id="fmember" action="./auth_form3_update.php" onsubmit="return fmember_submit(this);" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">
<input type="hidden" name="id_check" id="id_check" value="<?php echo $id_check; ?>">
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
						<th scope="row"><label for="mb_id">아이디</label></th>
						<td>
							<input type="text" name="mb_email" value="<?php echo $mb['mb_email'] ?>" id="mb_id" <?php echo $required_mb_id ?> class="frm_input <?php echo $required_mb_id_class ?>" size="15"  maxlength="20">
							<?php if ($w == ''){ ?>
							<button type="button" class="btn btn_02 id_check">아이디 체크</button>
							</br><span id="id_txt"></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="mb_img">이미지 등록</label></th>
						<td colspan="3">
							<?php echo help('이미지는 정사각형 사이즈로 jpg, gif, png 형식의 파일을 업로드 해주세요.') ?>
							<input type="file" name="mb_img" id="mb_img">
							<?php
							$mb_dir = substr($mb['mb_id'],0,2);
							$icon_file = G5_DATA_PATH.'/member_image/'.$mb_dir.'/'.$mb['mb_id'].'.gif';
							if (file_exists($icon_file)) {
								$icon_url = G5_DATA_URL.'/member_image/'.$mb_dir.'/'.$mb['mb_id'].'.gif';
								echo '<img src="'.$icon_url.'" alt="">';
								echo '<input type="checkbox" id="del_mb_img" name="del_mb_img" value="1">삭제';
							}
							?>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="mb_profile">강사 분야</label></th>
						<td colspan="3">
							<?php echo help('소 분류는 중 분류의 하위 분류 개념이 아니므로 중 분류 선택시 해당 분야가 포함될 최하위 분류만 선택하시면 됩니다.') ?>
							<select name="ca_id2" id="ca_id2">
								<option value="">선택하세요</option>
								<?php echo conv_selected_option($category_select, $mb['ca_id2']); ?>
							</select>
							<select name="ca_id3" id="ca_id3">
								<option value="">선택하세요</option>
								<?php echo conv_selected_option($category_select, $mb['ca_id3']); ?>
							</select>
							<br>어떤 역할을 하는 것인지?
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="mb_profile">강사 소개</label></th>
						<td colspan="3"><textarea name="mb_profile" id="mb_profile"><?php echo $mb['mb_profile'] ?></textarea></td>
					</tr>

					</tbody>
					</table>
				</div>

				<style>
					.tbl_wrap th, .tbl_wrap td {text-align:center;}
				</style>

				<div class="btn_fixed_top">
					<a href="./auth_list3.php?<?php echo $qstr ?>" class="btn btn_02">목록</a>
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
	if($("#id_check").val() == 'N'){
		alert('아이디 체크를 눌러 주세요.');
		return false;
	}

    if (!f.mb_img.value.match(/\.(gif|jpe?g|png)$/i) && f.mb_img.value) {
        alert('강사이미지는 이미지 파일만 가능합니다.');
        return false;
    }

    return true;
}

$(".id_check").click(function(){
	if($("#mb_id").val() == ""){
		alert('아이디를 입력해 주세요.');
		$("#mb_id").select();
		return false;
	}

	$.ajax({
		type:"post",
		url:"./auth_id_check.php",
		data: {
			mb_id: $("#mb_id").val()
		},
		success:function(data){
			if(data != "해당 아이디로 가입된 회원이 없습니다."){
				$("#id_check").val("Y");
			}
			$("#id_txt").text(data);
		}
	});
});

$("#mb_id").keyup(function(){
	$("#id_check").val("N");
});
</script>
<?php
run_event('admin_member_form_after', $mb, $w);

include_once('./admin.tail.php');
?>
