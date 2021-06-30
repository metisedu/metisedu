<?php
include_once("./_common.php");
include_once("../adm/admin.lib.php");

function print_menu1($key, $no='')
{
    global $menu;

    $str = print_menu2($key, $no);

    return $str;
}

function print_menu2($key, $no='')
{
    global $menu, $auth_menu, $is_admin, $auth, $g5, $sub_menu;

    for($i=1; $i<count($menu[$key]); $i++)
    {
        if ($is_admin != 'super' && (!array_key_exists($menu[$key][$i][0],$auth) || !strstr($auth[$menu[$key][$i][0]], 'r')))
            continue;

        $auth_menu[$menu[$key][$i][0]] = $menu[$key][$i][1];
    }
}

foreach($amenu as $key=>$value) {
	if ($menu['menu'.$key][0][2]) {
		//
	} else {
		continue;
	}

	print_menu1('menu'.$key, 1);
}     //end foreach
?>
<div>
	<h2 id="modal1Title">관리권한 수정</h2>
	<form name="fauthedit" id="fauthedit">
	<input type="hidden" name="mb_id" value="<?php echo $mb_id; ?>">
	<input type="hidden" name="token" value="">

	<div class="tbl_frm01 tbl_wrap table-responsive">
		<table class="table">
		<colgroup>
			<col class="grid_4">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row"><label for="mb_id">회원아이디<strong class="sound_only">필수</strong></label></th>
			<td style="text-align: left;">
				<strong id="msg_mb_id" class="msg_sound_only"></strong>
				<?php echo $mb_id ?>
			</td>
		</tr>
		<tr>
			<th scope="row" style="vertical-align: top;">권한지정</th>
			<td style="text-align: left;">
				<?php
				foreach($auth_menu as $key=>$value)
				{
					if (!(substr($key, -3) == '000' || $key == '-' || !$key)){
						$sQuery = " SELECT *
									FROM han_auth
									WHERE mb_id = '".$mb_id."'
									AND   au_menu = '".$key."'
									";
						$auth_chk = sql_fetch($sQuery);
						if($auth_chk)
							$chk = "checked";
						else
							$chk = "";

						echo"<input type='checkbox' name='auth[]' id='r_".$key."' value='".$key."' id='r' ".$chk."> <label for='r_".$key."'>".$value."</label></br>";
					}
				}
				?>
				<input type="checkbox" name="g" value="g" id="g">
				<label for="g">강사등록</label>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
	</form>
</div>
<br>
<button class="remodal-confirm" id="auth_btn">확인</button>
<button data-remodal-action="cancel" class="remodal-cancel">취소</button>
<script>
$("#auth_btn").click(function(){
	$.ajax({
		type:"post",
		url:"/adm/auth_update2.php",
		data: $("#fauthedit").serialize(),
		success:function(data){
			//console.log(data);
			alert('수정 되었습니다.');
			parent.location.reload();
		}
	});
});
</script>