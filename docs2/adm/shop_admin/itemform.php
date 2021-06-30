<?php
$sub_menu = '400300';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);
include_once(G5_LIB_PATH.'/iteminfo.lib.php');

auth_check($auth[$sub_menu], "w");

$html_title = "판매용 상품 ";

if ($w == "")
{
    $html_title .= "입력";

    // 옵션은 쿠키에 저장된 값을 보여줌. 다음 입력을 위한것임
    //$it[ca_id] = _COOKIE[ck_ca_id];
    $it['ca_id'] = get_cookie("ck_ca_id");
    $it['ca_id2'] = get_cookie("ck_ca_id2");
    $it['ca_id3'] = get_cookie("ck_ca_id3");
    if (!$it['ca_id'])
    {
        $sql = " select ca_id from {$g5['g5_shop_category_table']} order by ca_order, ca_id limit 1 ";
        $row = sql_fetch($sql);
		/*
        if (!$row['ca_id'])
            alert("등록된 분류가 없습니다. 우선 분류를 등록하여 주십시오.", './categorylist.php');
        */
		$it['ca_id'] = $row['ca_id'];
    }
    //$it[it_maker]  = stripslashes($_COOKIE[ck_maker]);
    //$it[it_origin] = stripslashes($_COOKIE[ck_origin]);
    $it['it_maker']  = stripslashes(get_cookie("ck_maker"));
    $it['it_origin'] = stripslashes(get_cookie("ck_origin"));

	$it_id = time();
}
else if ($w == "u")
{
    $html_title .= "수정";

    if ($is_admin != 'super')
    {
        $sql = " select it_id from {$g5['g5_shop_item_table']} a, {$g5['g5_shop_category_table']} b
                  where a.it_id = '$it_id'
                    and a.ca_id = b.ca_id
                    and b.ca_mb_id = '{$member['mb_id']}' ";
        $row = sql_fetch($sql);
        if (!$row['it_id'])
            alert("\'{$member['mb_id']}\' 님께서 수정 할 권한이 없는 상품입니다.");
    }

    $it = get_shop_item($it_id);

    if(!$it)
        alert('상품정보가 존재하지 않습니다.');

    if (!$ca_id)
        $ca_id = $it['ca_id'];

    $sql = " select * from {$g5['g5_shop_category_table']} where ca_id = '$ca_id' ";
    $ca = sql_fetch($sql);
}
else
{
    alert();
}

$qstr  = $qstr.'&amp;sca='.$sca.'&amp;page='.$page;

$g5['title'] = $html_title;
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

function cint($n) {
	return (floor($n)+ceil($n))/2==$n?floor($n):round($n);
}

// 분류리스트
$category_select = '';
$script = '';
$sql = " select * from {$g5['g5_shop_category_table']} ";
if ($is_admin != 'super')
    $sql .= " where ca_mb_id = '{$member['mb_id']}' ";
$sql .= " order by ca_order, ca_id ";
$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++)
{
    $len = strlen($row['ca_id']) / 2 - 1;

    $nbsp = "";
    for ($i=0; $i<$len; $i++)
        $nbsp .= "&nbsp;&nbsp;&nbsp;";

    $category_select .= "<option value=\"{$row['ca_id']}\">$nbsp{$row['ca_name']}</option>\n";

    $script .= "ca_use['{$row['ca_id']}'] = {$row['ca_use']};\n";
    $script .= "ca_stock_qty['{$row['ca_id']}'] = {$row['ca_stock_qty']};\n";
    //$script .= "ca_explan_html['$row[ca_id]'] = $row[ca_explan_html];\n";
    $script .= "ca_sell_email['{$row['ca_id']}'] = '{$row['ca_sell_email']}';\n";
}

// 재입고알림 설정 필드 추가
if(!sql_query(" select it_stock_sms from {$g5['g5_shop_item_table']} limit 1 ", false)) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_item_table']}`
                    ADD `it_stock_sms` tinyint(4) NOT NULL DEFAULT '0' AFTER `it_stock_qty` ", true);
}

// 추가옵션 포인트 설정 필드 추가
if(!sql_query(" select it_supply_point from {$g5['g5_shop_item_table']} limit 1 ", false)) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_item_table']}`
                    ADD `it_supply_point` int(11) NOT NULL DEFAULT '0' AFTER `it_point_type` ", true);
}

// 상품메모 필드 추가
if(!sql_query(" select it_shop_memo from {$g5['g5_shop_item_table']} limit 1 ", false)) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_item_table']}`
                    ADD `it_shop_memo` text NOT NULL AFTER `it_use_avg` ", true);
}

// 지식쇼핑 PID 필드추가
// 상품메모 필드 추가
if(!sql_query(" select ec_mall_pid from {$g5['g5_shop_item_table']} limit 1 ", false)) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_item_table']}`
                    ADD `ec_mall_pid` varchar(255) NOT NULL AFTER `it_shop_memo` ", true);
}

$pg_anchor ='<ul class="nav nav-tabs bg-warning-a800" role="tab-list">
<li class="nav-item"><a href="#anc_sitfrm_cate" class="nav-link">상품분류</a></li>
<li class="nav-item"><a href="#anc_sitfrm_ini" class="nav-link">기본정보</a></li>
<li class="nav-item"><a href="#anc_sitfrm_cost" class="nav-link">가격 및 재고</a></li>
<li class="nav-item"><a href="#anc_sitfrm_img" class="nav-link">상품이미지</a></li>
<li class="nav-item"><a href="#anc_sitfrm_relation" class="nav-link">관련상품</a></li>
</ul>
';


// 쿠폰적용안함 설정 필드 추가
if(!sql_query(" select it_nocoupon from {$g5['g5_shop_item_table']} limit 1", false)) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_item_table']}`
                    ADD `it_nocoupon` tinyint(4) NOT NULL DEFAULT '0' AFTER `it_use` ", true);
}

// 스킨필드 추가
if(!sql_query(" select it_skin from {$g5['g5_shop_item_table']} limit 1", false)) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_item_table']}`
                    ADD `it_skin` varchar(255) NOT NULL DEFAULT '' AFTER `ca_id3`,
                    ADD `it_mobile_skin` varchar(255) NOT NULL DEFAULT '' AFTER `it_skin` ", true);
}

$productDcPrice = $it['it_dc_price'];
$productDcRate = $it['it_dc_rate'];
$productDcFlg = $it['it_dc_flg'];
?>
<link rel="stylesheet" href="/css/remodal.css">
<style>
#lectureTable tr th {text-align:center;}

.icon_btn {
	color: #fff;
    opacity: 0.6;
    border: 0px;
    font-size: 0.7em;
    padding: 0px 3px;
}

.icon_btn.bg01 {background-color: #383838;}
.icon_btn.bg02 {background-color: #5e5e5e}
.icon_btn.bg03 {background-color: #2c3247}
.icon_btn.bg04 {background-color: #5b647e}
.icon_btn.bg05 {background-color: #6313a0}
.icon_btn.bg06 {background-color: #2b149e}
.icon_btn.bg07 {background-color: #1322e4}
.icon_btn.bg08 {background-color: #1381a0}
.icon_btn.bg09 {background-color: #0e8674}
.icon_btn.bg10 {background-color: #00ab4c}
.icon_btn.bg11 {background-color: #0e7808}
.icon_btn.bg12 {background-color: #2fa40b}
.icon_btn.bg13 {background-color: #7da013}
.icon_btn.bg14 {background-color: #c0b61d}
.icon_btn.bg15 {background-color: #ae7214}
.icon_btn.bg16 {background-color: #9d3f11}
.icon_btn.bg17 {background-color: #ac2009}
.icon_btn.bg18 {background-color: #970b0b}
.icon_btn.bg19 {background-color: #c2104f}
.icon_btn.bg20 {background-color: #c122c7}
.icon_btn.bg21 {background-color: #842e8e}
.icon_btn.bg22 {background-color: #383787}
.icon_btn.bg23 {background-color: #3a6465}
.icon_btn.bg24 {background-color: #54343f}
.icon_btn.bg25 {background-color: #000000}
.icon_btn.bg26 {background-color: #f00}
.icon_btn.bg27 {background-color: #0f7}

#sch_txt_list {
	overflow: hidden;
	margin:0px;
	padding:0px;
}
#sch_txt_list li {
	float: left;
    display: inline-block;
    padding: 5px 10px;
    border-radius: 15px;
    background: #d5d5d5;
	margin-right:7px;
}

#sch_txt_list li span {color:red;cursor:pointer;}
#sch_txt_list li.empty_sch {background:#fff;}
</style>
<script type="text/javascript">
<!--
	//- 숫자만 입력 하기.
	jQuery.fn.onlyNumber = function(){
		this.css("ime-mode", "disabled");
		this.keypress(function(event){
			if(event.which && (event.which < 48 || event.which > 57) ) {
				event.preventDefault();
			}
		}).keyup(function(){
			if(jQuery(this).val() != null && jQuery(this).val() != '' ) {
				jQuery(this).val(jQuery(this).val().replace(/[^0-9]/g, '') );
			}
		});
	};

	$(function(){
		//- 수강일은 숫자형만 입력이 가능하다
		$(".onlynumber").onlyNumber();

		$("input[name=productDateGubun]").click(function(){
			$("#productTeam").val("");
			$("#productEndDate").val("");
			$("#hh").val("");
			$("#mi").val("");
			$("#ss").val("");
		});

		//- 할인율 플래그 바꿨을대
		$("#productDcFlg").change(function(){
			$("#productDcRate").val("");
			$("#productDcPrice").val("");
			$("#productRealPrice").val("");

			$("#dcRate").hide();
			$("#dcPrice").hide();

			if($(this).val() == "1"){
				$("#dcRate").show();
			}else{
				$("#dcPrice").show();
			}
		});

		//- 할인금액으로 판매금액설정
		$("#productDcPrice").blur(function(){
			var productPrice = parseInt($("#productPrice").val());
			var productDcPrice = parseInt($(this).val());

			if(productDcPrice != ""){
				//- ((원금액-할인금액)/절삭할단위)*절삭할단위
				$("#productRealPrice").val( (parseInt((productPrice-productDcPrice) / 100) * 100));
			}else{
				$("#productRealPrice").val(productPrice);
			}

		});

		//- 할인율로 판매금액설정
		$("#productDcRate").change(function(){
			var productPrice = parseFloat($("#productPrice").val());
			var productDcRate = $(this).val();

			if(productDcRate != "" && productDcRate>0 ){
				//- 할인금액 = 원금액 *(할인율 /100)
				//- ((원금액-할인금액)/절삭할단위)*절삭할단위
				var productDcPrice = productPrice*(productDcRate/100);
				$("#productRealPrice").val( parseInt(((productPrice*1)-(productDcPrice*1) ) / 100) * 100 );
			}else{
				$("#productRealPrice").val(productPrice);
			}
		});

		//- 구분을 변경하면 밑에 설명이 바뀐다.
		/*
		$("#productGubun").change(function(){
			if($(this).val() == "N"){
				$("#gubunEtc").html("※ 단강좌상품 : 강좌가 1개만 포함한 상품")
			}else if ($(this).val() == "P"){
				$("#gubunEtc").html("※ 패키지상품 : 강좌가 2개 이상이 포함한 상품")
			}else if ($(this).val() == "B"){
				$("#gubunEtc").html("※ 일반상품 : 강좌가 포함되지 않은 상품")
			}
		});
		$("#productGubun").change();
		*/
	})

	/*-----------------------------------------------------------
	 * 강좌 리스트 가져오기
	*/

	//콤마찍기
    function comma(str) {
        str = String(str);
        return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
    }

	//- 강좌를 저장하고 강좌리스트 가져오기
	//- 건별저장으로 넘기고
	function fnItemActionOne(itemCode, lectureName, teacherName, lecturePrice){
		var trlength = jQuery("#lectureTable tr").length
		var gubunName = ""

		var tr = "";
			tr += "<tr>                                                                                                 ";
			tr += "	<td><input type='checkbox' name='vItemIdx[]' class='vItemIdx' value='' itemCode = '"+itemCode+"' lecturePrice = '"+lecturePrice+"' /></td> ";
			tr += "	<td class='txt-left'>"+lectureName+"</td>                                                           ";
			tr += "	<td><input type='text' name='insertItemOrder[]' class='frm_input onlynumber' id = 'insertItemOrder' style='width:50px;' value='0'></td>             ";
			tr += "	<td>"+teacherName+"</td>                                                                            ";
			tr += "	<td>"+comma(lecturePrice)+" 원</td>                                                                           ";
			tr += "	<td>"+itemCode+"</td>                                                                               ";
			tr += "	<td class='no-padding' style='text-align:center;'>                                                                             ";
			tr += "		<a href='javascript:void(0);' onclick='tableDeleteRow(jQuery(this))' class='btn_submit btn'>삭제</a> ";
			tr += "	</td>                                                                                               ";
			tr += "</tr>                                                                                                ";

		var lectureCheck = 0;

		jQuery(".vItemIdx").each(function(){
			if(jQuery(this).attr("itemCode") == itemCode){
				lectureCheck++;
			}
		});

		if(lectureCheck == 0){
			jQuery('#lectureTable').append(tr);
			jQuery("#idTableNull").hide();
		}

		jQuery("#idLectureCode"+itemCode).html("이미등록");
		fnPrice();
	}

	//- tr삭제함.
	function tableDeleteRow(obj){
		jQuery(obj).parent().parent().remove().fadeOut('slow');
		fnPrice();
	}

	//- tr삭제함.
	function tableDeleteRow2(obj){
		jQuery(obj).parent().parent().remove().fadeOut('slow');
	}

	function fnPrice(){
		var varPrice = 0;
		var insertItemCode = "";
		var insertItemGubun = "";

		jQuery(".vItemIdx").each(function(){
			varPrice += jQuery(this).attr("lecturePrice")*1

			if (insertItemCode == ""){
				insertItemCode = jQuery(this).attr("itemCode");
			}else{
				insertItemCode += ",";
				insertItemCode += jQuery(this).attr("itemCode");
			}

			if (insertItemGubun == ""){
				insertItemGubun = jQuery(this).attr("itemGubun");
			}else{
				insertItemGubun += ",";
				insertItemGubun += jQuery(this).attr("itemGubun");
			}
		});

		jQuery("#productPrice").val(varPrice);
		jQuery("#insertItemCode").val(insertItemCode);
		jQuery("#insertItemGubun").val(insertItemGubun);

		if(jQuery("#productDcFlg option:selected").val()  == "1"){
			$("#productDcRate").change();
		}else{
			$("#productDcPrice").blur();
		}
	}
//-->
</script>
<form name="fitemform" action="./itemformupdate.php" method="post" enctype="MULTIPART/FORM-DATA" autocomplete="off" onsubmit="return fitemformcheck(this)">

<input type="hidden" name="codedup" value="<?php echo $default['de_code_dup_use']; ?>">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="sca" value="<?php echo $sca; ?>">
<input type="hidden" name="sst" value="<?php echo $sst; ?>">
<input type="hidden" name="sod"  value="<?php echo $sod; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx"  value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<div class="card card-inverse card-flat">
	<div class="card-header">
		<div class="card-title">상품분류</div>
	</div>
	<div class="card-block">
		<div class="row">
			<div class="col-md-12">

				<section id="anc_sitfrm_cate">
					<?php echo $pg_anchor; ?>
					<div class="local_desc02 local_desc">
						<p>상품분류는 반드시 선택하셔야 합니다.</p>
					</div>

					<div class="tbl_frm01 tbl_wrap table-responsive">
						<table class="table">
						<caption>상품분류 입력</caption>
						<colgroup>
							<col class="grid_4">
							<col>
						</colgroup>
						<tbody>
						<tr>
							<th scope="row"><label for="ca_id">기본분류</label></th>
							<td>
								<?php if ($w == "") echo help("기본분류를 선택하면, 판매/재고/HTML사용/판매자 E-mail 등을, 선택한 분류의 기본값으로 설정합니다."); ?>
								<select name="ca_id" id="ca_id" onchange="categorychange(this.form)">
									<option value="">선택하세요</option>
									<?php echo conv_selected_option($category_select, $it['ca_id']); ?>
								</select>
								<script>
									var ca_use = new Array();
									var ca_stock_qty = new Array();
									//var ca_explan_html = new Array();
									var ca_sell_email = new Array();
									var ca_opt1_subject = new Array();
									var ca_opt2_subject = new Array();
									var ca_opt3_subject = new Array();
									var ca_opt4_subject = new Array();
									var ca_opt5_subject = new Array();
									var ca_opt6_subject = new Array();
									<?php echo "\n$script"; ?>
								</script>
							</td>
						</tr>
						<?php for ($i=2; $i<=3; $i++) { ?>
						<tr>
							<th scope="row"><label for="ca_id<?php echo $i; ?>"><?php echo $i; ?>차 분류</label></th>
							<td>
								<?php echo help($i.'차 분류는 기본 분류의 하위 분류 개념이 아니므로 기본 분류 선택시 해당 상품이 포함될 최하위 분류만 선택하시면 됩니다.'); ?>
								<select name="ca_id<?php echo $i; ?>" id="ca_id<?php echo $i; ?>">
									<option value="">선택하세요</option>
									<?php echo conv_selected_option($category_select, $it['ca_id'.$i]); ?>
								</select>
							</td>
						</tr>
						<?php } ?>
						<tr>
							<th scope="row"><label for="it_level">난이도</label></th>
							<td>
								<select name="it_level" id="it_level">
									<option value="">선택</option>
									<option value="3" <?php if($it['it_level'] == "3") echo"selected"; ?>>상</option>
									<option value="2" <?php if($it['it_level'] == "2") echo"selected"; ?>>중</option>
									<option value="1" <?php if($it['it_level'] == "1") echo"selected"; ?>>하</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="it_zoom">실시간 ZOOM 사용여부</label></th>
							<td>
								<select name="it_zoom" id="it_zoom">
									<option value="0">미사용</option>
									<option value="1" <?php if($it['it_zoom'] == "1") echo"selected"; ?>>사용</option>
								</select>
							</td>
						</tr>
						<tr class="zoom_area">
							<th scope="row"><label for="it_zoom_url">실시간 수강 URL</label></th>
							<td>
								<input type="text" name="it_zoom_url" value="<?php echo get_text($it['it_zoom_url']); ?>" id="it_zoom_url" class="frm_input" size="95">
							</td>
						</tr>
						<tr class="zoom_area">
							<th scope="row"><label for="it_zoom_pw">실시간 수강 비밀번호</label></th>
							<td>
								<input type="text" name="it_zoom_pw" value="<?php echo get_text($it['it_zoom_pw']); ?>" id="it_zoom_pw" class="frm_input" size="30">
							</td>
						</tr>
						<tr class="zoom_area">
							<th scope="row"><label for="it_zoom_img">영상영역 이미지</label></th>
							<td>
								<input type="file" name="it_zoom_img" id="it_zoom_img">
								<?php
								$it_img = G5_DATA_PATH.'/item/'.$it['it_zoom_img'];
								$it_img_exists = run_replace('shop_item_image_exists', (is_file($it_img) && file_exists($it_img)), $it, $i);

								if($it_img_exists) {
									$thumb = get_it_thumbnail($it['it_zoom_img'], 25, 25);
									$img_tag = run_replace('shop_item_image_tag', '<img src="'.G5_DATA_URL.'/item/'.$it['it_zoom_img'].'" class="shop_item_preview_image" >', $it, $i);
								?>
								<label for="it_zoom_img_del"><span class="sound_only">이미지 <?php echo $i; ?> </span>파일삭제</label>
								<input type="checkbox" name="it_zoom_img_del" id="it_zoom_img_del" value="1">
								<span class="sit_wimg_limg"><?php echo $thumb; ?></span>
								<div id="limg" class="banner_or_img">
									<?php echo $img_tag; ?>
									<button type="button" class="sit_wimg_close">닫기</button>
								</div>
								<script>
								$('<button type="button" id="it_limg_view" class="btn_frmline sit_wimg_view">영상영역이미지 확인</button>').appendTo('.sit_wimg_limg');
								</script>
								<?php } ?>
							</td>
						</tr>
						<script>
							function chk_zoom(){
								if($("#it_zoom").val() == '1'){
									$(".zoom_area").show();
								}else{
									$(".zoom_area").hide();
								}
							}

							$("#it_zoom").change(function(){
								chk_zoom();
							});

							chk_zoom();
						</script>
						</tbody>
						</table>
					</div>
				</section>
			</div>
		</div>
	</div>
</div>

<div class="card card-inverse card-flat">
	<div class="card-header">
		<div class="card-title">기본정보</div>
	</div>
	<div class="card-block">
		<div class="row">
			<div class="col-md-12">

				<section id="anc_sitfrm_ini">
					<?php echo $pg_anchor; ?>
					<div class="tbl_frm01 tbl_wrap table-responsive">
						<table class="table">
						<caption>기본정보 입력</caption>
						<colgroup>
							<col class="grid_4">
							<col>
							<col class="grid_3">
						</colgroup>
						<tbody>
						<tr>
							<th scope="row">상품코드</th>
							<td colspan="2">
								<?php if ($w == '') { // 추가 ?>
									<!-- 최근에 입력한 코드(자동 생성시)가 목록의 상단에 출력되게 하려면 아래의 코드로 대체하십시오. -->
									<!-- <input type=text class=required name=it_id value="<?php echo 10000000000-time()?>" size=12 maxlength=10 required> <a href='javascript:;' onclick="codedupcheck(document.all.it_id.value)"><img src='./img/btn_code.gif' border=0 align=absmiddle></a> -->
									<?php echo help("상품의 코드는 10자리 숫자로 자동생성합니다. <b>직접 상품코드를 입력할 수도 있습니다.</b>\n상품코드는 영문자, 숫자, - 만 입력 가능합니다."); ?>
									<input type="text" name="it_id" value="<?php echo $it_id; ?>" id="it_id" required class="frm_input required" size="20" maxlength="20">
									<!-- <?php if ($default['de_code_dup_use']) { ?><button type="button" class="btn_frmline" onclick="codedupcheck(document.all.it_id.value)">중복검사</a><?php } ?> -->
								<?php } else { ?>
									<input type="hidden" name="it_id" value="<?php echo $it['it_id']; ?>">
									<span class="frm_ca_id"><?php echo $it['it_id']; ?></span>
									<a href="<?php echo shop_item_url($it_id); ?>" class="btn_frmline">상품확인</a>
									<a href="<?php echo G5_ADMIN_URL; ?>/shop_admin/itemuselist.php?sfl=a.it_id&amp;stx=<?php echo $it_id; ?>" class="btn_frmline">사용후기</a>
									<a href="<?php echo G5_ADMIN_URL; ?>/shop_admin/itemqalist.php?sfl=a.it_id&amp;stx=<?php echo $it_id; ?>" class="btn_frmline">상품문의</a>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="it_name">상품명</label></th>
							<td colspan="2">
								<?php echo help("HTML 입력이 불가합니다."); ?>
								<input type="text" name="it_name" value="<?php echo get_text(cut_str($it['it_name'], 250, "")); ?>" id="it_name" required class="frm_input required" size="95">
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="it_basic">기본설명</label></th>
							<td>
								<?php echo help("수강하기-상품명 하단에 상품에 대한 추가적인 설명이 필요한 경우에 입력합니다. HTML 입력도 가능합니다."); ?>
								<input type="text" name="it_basic" value="<?php echo get_text(html_purifier($it['it_basic'])); ?>" id="it_basic" class="frm_input" size="95">
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="it_t_name">강사명</label></th>
							<td colspan="2">
								<?php echo help("HTML 입력이 불가합니다."); ?>
								<input type="text" name="it_t_name" value="<?php echo get_text(cut_str($it['it_t_name'], 250, "")); ?>" id="it_t_name" required class="frm_input required" size="20">
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="it_score">평점</label></th>
							<td colspan="2">
								<input type="text" name="it_score" value="<?php echo $it['it_score']; ?>" id="it_score" required class="frm_input required" size="10"> / 5.0
							</td>
						</tr>
						<tr>
							<th scope="row">스티커</th>
							<td colspan="2">
								<?php echo help("메인화면에 유형별로 출력할때 사용합니다.\n이곳에 체크하게되면 상품리스트에서 유형별로 정렬할때 체크된 상품이 가장 먼저 출력됩니다."); ?>
								<input type="checkbox" name="it_type1" value="1" <?php echo ($it['it_type1'] ? "checked" : ""); ?> id="it_type1">
								<label for="it_type1">신규 <button type="button" class="icon_btn bg01">신규</button></label>
								<input type="checkbox" name="it_type2" value="1" <?php echo ($it['it_type2'] ? "checked" : ""); ?> id="it_type2">
								<label for="it_type2">인기 <button type="button" class="icon_btn bg02">인기</button></label>
								<input type="checkbox" name="it_type3" value="1" <?php echo ($it['it_type3'] ? "checked" : ""); ?> id="it_type3">
								<label for="it_type3">무료 <button type="button" class="icon_btn bg03">무료</button></label>
								<input type="checkbox" name="it_type4" value="1" <?php echo ($it['it_type4'] ? "checked" : ""); ?> id="it_type4">
								<label for="it_type4">마감임박 <button type="button" class="icon_btn bg04">마감임박</button></label>
								<input type="checkbox" name="it_type5" value="1" <?php echo ($it['it_type5'] ? "checked" : ""); ?> id="it_type5">
								<label for="it_type5">얼리버드 <button type="button" class="icon_btn bg05">얼리버드</button></label>
								<input type="checkbox" name="it_type6" value="1" <?php echo ($it['it_type6'] ? "checked" : ""); ?> id="it_type6">
								<label for="it_type6">할인 <button type="button" class="icon_btn bg06">할인</button></label>
								<input type="checkbox" name="it_type7" value="1" <?php echo ($it['it_type7'] ? "checked" : ""); ?> id="it_type7">
								<label for="it_type7">추천 <button type="button" class="icon_btn bg07">추천</button></label>
								<input type="checkbox" name="it_type8" value="1" <?php echo ($it['it_type8'] ? "checked" : ""); ?> id="it_type8">
								<label for="it_type8">예정 <button type="button" class="icon_btn bg08">예정</button></label>
								<input type="checkbox" name="it_type9" value="1" <?php echo ($it['it_type9'] ? "checked" : ""); ?> id="it_type9">
								<label for="it_type9">코스 <button type="button" class="icon_btn bg09">코스</button></label>
								<input type="checkbox" name="it_type10" value="1" <?php echo ($it['it_type10'] ? "checked" : ""); ?> id="it_type10">
								<label for="it_type10">플립러닝 <button type="button" class="icon_btn bg10">플립러닝</button></label>
								<input type="checkbox" name="it_type11" value="1" <?php echo ($it['it_type11'] ? "checked" : ""); ?> id="it_type11">
								<label for="it_type11">기업전용 <button type="button" class="icon_btn bg11">기업전용</button></label>
								<input type="checkbox" name="it_type12" value="1" <?php echo ($it['it_type12'] ? "checked" : ""); ?> id="it_type12">
								<label for="it_type12">사업주 <button type="button" class="icon_btn bg12">사업주</button></label>
								<input type="checkbox" name="it_type13" value="1" <?php echo ($it['it_type13'] ? "checked" : ""); ?> id="it_type13">
								<label for="it_type13">한정 <button type="button" class="icon_btn bg13">한정</button></label>
								<input type="checkbox" name="it_type14" value="1" <?php echo ($it['it_type14'] ? "checked" : ""); ?> id="it_type14">
								<label for="it_type14">온라인 <button type="button" class="icon_btn bg14">온라인</button></label>
								<input type="checkbox" name="it_type15" value="1" <?php echo ($it['it_type15'] ? "checked" : ""); ?> id="it_type15">
								<label for="it_type15">패키지 <button type="button" class="icon_btn bg15">패키지</button></label>
								<input type="checkbox" name="it_type16" value="1" <?php echo ($it['it_type16'] ? "checked" : ""); ?> id="it_type16">
								<label for="it_type16">프리패스 <button type="button" class="icon_btn bg16">프리패스</button></label>
								<input type="checkbox" name="it_type17" value="1" <?php echo ($it['it_type17'] ? "checked" : ""); ?> id="it_type17">
								<label for="it_type17">환급 <button type="button" class="icon_btn bg17">환급</button></label>
								<input type="checkbox" name="it_type18" value="1" <?php echo ($it['it_type18'] ? "checked" : ""); ?> id="it_type18">
								<label for="it_type18">무료배송 <button type="button" class="icon_btn bg18">무료배송</button></label>
								<input type="checkbox" name="it_type19" value="1" <?php echo ($it['it_type19'] ? "checked" : ""); ?> id="it_type19">
								<label for="it_type19">LIVE <button type="button" class="icon_btn bg19">LIVE</button></label>
								<input type="checkbox" name="it_type20" value="1" <?php echo ($it['it_type20'] ? "checked" : ""); ?> id="it_type20">
								<label for="it_type20">BEST <button type="button" class="icon_btn bg20">BEST</button></label>
								<input type="checkbox" name="it_type21" value="1" <?php echo ($it['it_type21'] ? "checked" : ""); ?> id="it_type21">
								<label for="it_type21">NEW <button type="button" class="icon_btn bg21">NEW</button></label>
								<input type="checkbox" name="it_type22" value="1" <?php echo ($it['it_type22'] ? "checked" : ""); ?> id="it_type22">
								<label for="it_type22">트렌드 <button type="button" class="icon_btn bg22">트렌드</button></label>
								<input type="checkbox" name="it_type23" value="1" <?php echo ($it['it_type23'] ? "checked" : ""); ?> id="it_type23">
								<label for="it_type23">강연 <button type="button" class="icon_btn bg23">강연</button></label>
								<input type="checkbox" name="it_type24" value="1" <?php echo ($it['it_type24'] ? "checked" : ""); ?> id="it_type24">
								<label for="it_type24">자기계발 <button type="button" class="icon_btn bg24">자기계발</button></label>
								<input type="checkbox" name="it_type25" value="1" <?php echo ($it['it_type25'] ? "checked" : ""); ?> id="it_type25">
								<label for="it_type25">토론 <button type="button" class="icon_btn bg25">토론</button></label>
								<input type="checkbox" name="it_type26" value="1" <?php echo ($it['it_type26'] ? "checked" : ""); ?> id="it_type26">
								<label for="it_type26">저자직강 <button type="button" class="icon_btn bg26">저자직강</button></label>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="it_use">판매가능</label></th>
							<td colspan="2">
								<?php echo help("잠시 판매를 중단하거나 재고가 없을 경우에 체크를 해제해 놓으면 출력되지 않으며, 주문도 받지 않습니다."); ?>
								<input type="checkbox" name="it_use" value="1" id="it_use" <?php echo ($it['it_use']) ? "checked" : ""; ?>> 예
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="it_nocoupon">쿠폰적용안함</label></th>
							<td colspan="2">
								<?php echo help("설정에 체크하시면 쿠폰 생성 때 상품 검색 결과에 노출되지 않습니다."); ?>
								<input type="checkbox" name="it_nocoupon" value="1" id="it_nocoupon" <?php echo ($it['it_nocoupon']) ? "checked" : ""; ?>> 예
							</td>
						</tr>
						<tr>
							<th scope="row">수강기간</th>
							<td colspan="2">
								<input type="radio" name="it_lect_type" value="기간" id="lect_type1" <?php if($it['it_lect_type'] == "기간") echo"checked"; ?>>
								<label for="lect_type1" style="display:inline-block;width:100px;text-align:left;">수강기간 선택</label>
								<input type="text" name="it_lect_date" value="<?php echo $it['it_lect_date']; ?>" class="frm_input" /> 일</br>

								<input type="radio" name="it_lect_type" value="일수" id="lect_type2" <?php if($it['it_lect_type'] == "일수") echo"checked"; ?>>
								<label for="lect_type2" style="display:inline-block;width:100px;text-align:left;">수강일 선택</label>
								<input type="text" name="it_lect_day" id="it_lect_day" value="<?php echo $it['it_lect_day']; ?>" class="frm_input" autocomplate=off /></br>

								<input type="radio" name="it_lect_type" value="평생" id="lect_type3" <?php if($it['it_lect_type'] == "평생") echo"checked"; ?>>
								<label for="lect_type3" style="display:inline-block;width:100px;text-align:left;">평생수강</label>
							</td>
						</tr>

						<tr>
							<th scope="row">
								검색어 등록<br><br>
							</th>
							<td colspan="2">
								<input type="hidden" name="it_keyword" id="it_keyword" value="" />
								<input type="text" name="it_sch_txt" id="it_sch_txt" value="" class="frm_input" />
								<button type="button" class="btn btn_03" id="sch_add2">등록</button><br><br>
								<div>
									<ul id="sch_txt_list">
										<?php
										$sQuery = " SELECT *
													FROM han_shop_item
													WHERE it_id = '".$it_id."'
													";
										$it = sql_fetch($sQuery);

										$keyword = explode("||", $it['it_keyword']);
										if(is_array($keyword) && $it['it_keyword']){
											for($i = 0; $i < count($keyword); $i++){
												if(!$keyword[$i])
													continue;

												echo"<li class='sch_txt'>#".$keyword[$i]." <span data-id='".$keyword[$i]."'><i class='fa fa-close'></i></span></li>";
											}
										}else{
											echo "<li class='empty_sch'>등록된 검색어가 없습니다.</li>";
										}
										?>
									</ul>
								</div>
							</td>
						</tr>

						<tr>
							<th scope="row">
								강의등록<br><br>
								<button type="button" class="btn btn_03" id="sch_lec">강의추가</button><br><br>
								<button type="button" class="btn_submit btn" id="del_slt_lec">선택삭제</button>
							</th>
							<td colspan="2">
								<table class="txt-center" id="lectureTable">
									<colgroup>
										<col style="width:6%;" />
										<col style="width:auto;" />
										<col style="width:8%;" />
										<col style="width:8%;" />

										<col style="width:8.5%;" />
										<col style="width:8.5%;" />
										<col style="width:10%;" />

										<col style="width:8.5%;" />
									</colgroup>
									<thead>
										<tr>
											<th><input type="checkbox" id="Allchk" /></th>
											<th>제목/영상</th>
											<th>강의순서</th>

											<th>강사명</th>
											<th>가격</th>
											<th>강좌코드</th>
											<th class="no-padding">관리</th>
										</tr>
									</thead>
									<tbody>
									<?php
									$sQuery = " SELECT *
												FROM han_shop_item_list
												WHERE it_id = '".$it_id."'
												";
									$sql = sql_query($sQuery);
									for($i = 0; $row = sql_fetch_array($sql); $i++){
										$varItemCode .= ",".$row['it_id2'];

										$sQuery = " SELECT *
													FROM han_shop_lec
													WHERE lt_id = '".$row['it_id2']."'
													";
										$lec = sql_fetch($sQuery);
										$tt = get_member($lec['mb_id']);
									?>
										<tr>
											<td>
												<input type="checkbox" name="vItemIdx[]" class="vItemIdx" value="<?php echo $row['it_id2']; ?>" itemCode='<?php echo $row['it_id2']; ?>' lecturePrice="<?php echo $lec['it_price']; ?>" />
											</td>
											<td><?php echo $lec['it_name']; ?></td>
											<td><input type="text" name="insertItemOrder[]" class='frm_input onlynumber'  id="insertItemOrder" style="width:50px;" value="<?php echo $row['ir_order']; ?>"></td>

											<td class="txt-left"><?php echo $tt['mb_name']; ?></td>
											<td><?php echo number_format($lec['it_price']); ?> 원</td>
											<td><?php echo $row['it_id2']; ?></td>

											<td class="no-padding" style='text-align:center;'>
												<a href="javascript:void(0);" onclick='tableDeleteRow(jQuery(this))'  class="btn btn_01">삭제</a>
											</td>
										</tr>
									<?php
									}
									if($i == 0){
									?>
										<input type="hidden" name="lectureTableCheckVal" id="lectureTableCheckVal" value="B">
										<tr id="idTableNull">
											<td colspan ="7" style="text-align:center;">선택된 강좌가 없습니다.</td>
										</tr>
									<?php
									}
									$varItemCode = substr($varItemCode, 1);
									?>
									</tbody>
								</table>
							</td>
						</tr>
						<input type="hidden" name="insertItemCode" id="insertItemCode" value="<?php echo $varItemCode; ?>">
						<input type="hidden" name="insertItemGubun" id="insertItemGubun" value="<?php echo $varItemGubun; ?>">
						<script>
						$("#sch_lec").click(function() {
							var opt = "left=50,top=50,width=520,height=600,scrollbars=1";
							var url = "./lec_list_pop.php";
							window.open(url, "win_member", opt);
						});

						$("#del_slt_lec").click(function(){
							$(".vItemIdx:checked").each(function(){
								$(this).parent().parent().remove().fadeOut('slow');
							});
							fnPrice();
						});

						$("#Allchk").click(function(){
							if($("#Allchk").prop("checked") === false){
								$(".vItemIdx").attr("checked", false);
							}else{
								$(".vItemIdx").attr("checked", true);
							}
						});
						</script>
						</tbody>
						</table>
					</div>
				</section>
			</div>
		</div>
	</div>
</div>

<div class="card card-inverse card-flat">
	<div class="card-header">
		<div class="card-title">가격 및 재고</div>
	</div>
	<div class="card-block">
		<div class="row">
			<div class="col-md-12">
				<section id="anc_sitfrm_cost">
					<?php echo $pg_anchor; ?>

					<div class="tbl_frm01 tbl_wrap table-responsive">
						<table style="margin-top:10px">
							<colgroup>
								<col style="width:12%;" />
								<col style="*" />
							</colgroup>
							<table style="margin-top:10px">
							<colgroup>
								<col style="width:12%;" />
								<col style="*" />
							</colgroup>
							<tbody>
								<tr>
									<th scope="row"><label for="it_cust_price">판매금액</label></th>
									<td>
										<p style="margin:10px">
											<span style="width:80px;display:inline-block;">강의 원 금 액</span>:
											<input type="text" class="frm_input onlynumber" name="it_cust_price" id="productPrice" readOnly value=""  style="width:80px;background-color:#ebebeb;" />
											<?php if($it['it_cust_price'] > 0){ ?>(현재 판매중인 원 금 액 : <?php echo number_format($it['it_cust_price']); ?>)<?php } ?>
										</p>
										<p style="margin:10px">
											<span style="width:80px;display:inline-block;">할 인 율</span>:
											<select  name="productDcFlg" id="productDcFlg" style="min-width:100px !important;">
												<option value="1" <?php if($productDcFlg == "1" ){ echo"selected"; } ?>>할인율</option>
												<option value="2" <?php if($productDcFlg == "2" ){ echo"selected"; } ?>>할인금액</option>
											</select>
											<span id="dcRate" <?php if($productDcFlg == "2"){ ?>style="display:none"<?php } ?>>
												<select  name="productDcRate" id="productDcRate" style="min-width:100px !important;">
													<option value="">선택</option>
													<option value="0" <?php if($productDcRate == 0){ echo"selected"; } ?>>0%</option>
													<?php
													for($rate = 2; $rate <= 20; $rate++){
														$viewrate = ($rate * 5);
														$slt = "";
														if($productDcRate == $viewrate){
															$slt = "selected";
														}
														echo'<option value="'.$viewrate.'" '.$slt.'>'.$viewrate.'%</option>';
													}
													?>
												</select>
											</span>
											<span id="dcPrice" <?php if($productDcFlg == "1" Or $productDcFlg == "" Or is_null($productDcFlg) ){ ?>style="display:none"<?php } ?>>
												<input type="text" class="frm_input onlynumber"  name="productDcPrice" id="productDcPrice" value="<?php echo $productDcPrice; ?>" style="width:80px;" /> 원
											</span>
										</p>
										<p style="margin:10px">
											<span style="width:80px;display:inline-block;">배송비</span>:
											<input type="text" class="frm_input onlynumber" name="it_sc_price" id="productDeliveryPrice" value="<?php echo $it['it_sc_price']; ?>" style="width:80px;" /> 원
										</p>
										<p style="margin:10px">
											<span style="width:80px;display:inline-block;">판매금액</span>:
											<input type="text" class="frm_input onlynumber" readOnly name="it_price" id="productRealPrice" value="<?php echo cint($it['it_price']); ?>" style="width:80px;background-color:#ebebeb;" /> (* 100원단위 절삭 <?php if($it['it_price'] > 0){ ?>※ 현재 판매중인 원 금 액 : <?php echo number_format(cint($it['it_price'])); } ?>)
										</p>
									</td>
								</tr>
							</tbody>
						</table>
						</table>
					</div>
				</section>
			</div>
		</div>
	</div>
</div>

<div class="card card-inverse card-flat">
	<div class="card-header">
		<div class="card-title">이미지</div>
	</div>
	<div class="card-block">
		<div class="row">
			<div class="col-md-12">
				<section id="anc_sitfrm_img">
					<?php echo $pg_anchor; ?>

					<div class="tbl_frm01 tbl_wrap table-responsive">
						<table class="table">
						<caption>이미지 업로드</caption>
						<colgroup>
							<col class="grid_4">
							<col>
						</colgroup>
						<tbody>
						<?php
						for($i=1; $i<=2; $i++) {
							if($i == '1'){
								$img_title = "썸네일 이미지";
							}else{
								$img_title = "강의 커버 이미지";
							}
						?>
						<tr>
							<th scope="row">
								<label for="it_img<?php echo $i; ?>"><?php echo $img_title; ?> 업로드</label>
								<?php
								if($i == '1'){
									echo "</br>285px X 285px";
								}else{
									echo "</br>1199px X 328px";
								}
								?>
							</th>
							<td>
								<input type="file" name="it_img<?php echo $i; ?>" id="it_img<?php echo $i; ?>">
								<?php
								$it_img = G5_DATA_PATH.'/item/'.$it['it_img'.$i];
								$it_img_exists = run_replace('shop_item_image_exists', (is_file($it_img) && file_exists($it_img)), $it, $i);

								if($it_img_exists) {
									$thumb = get_it_thumbnail($it['it_img'.$i], 25, 25);
									$img_tag = run_replace('shop_item_image_tag', '<img src="'.G5_DATA_URL.'/item/'.$it['it_img'.$i].'" class="shop_item_preview_image" >', $it, $i);
								?>
								<label for="it_img<?php echo $i; ?>_del"><span class="sound_only">이미지 <?php echo $i; ?> </span>파일삭제</label>
								<input type="checkbox" name="it_img<?php echo $i; ?>_del" id="it_img<?php echo $i; ?>_del" value="1">
								<span class="sit_wimg_limg<?php echo $i; ?>"><?php echo $thumb; ?></span>
								<div id="limg<?php echo $i; ?>" class="banner_or_img">
									<?php echo $img_tag; ?>
									<button type="button" class="sit_wimg_close">닫기</button>
								</div>
								<script>
								$('<button type="button" id="it_limg<?php echo $i; ?>_view" class="btn_frmline sit_wimg_view"><?php echo $img_title; ?> 확인</button>').appendTo('.sit_wimg_limg<?php echo $i; ?>');
								</script>
								<?php } ?>
							</td>
						</tr>
						<?php } ?>

						<tr>
							<th scope="row"><label>랜딩페이지 등록<label></th>
							<td colspan="2"> <?php echo editor_html('it_explan', get_text(html_purifier($it['it_explan']), 0)); ?></td>
						</tr>
						</tbody>
						</table>
					</div>
				</section>
			</div>
		</div>
	</div>
</div>

<div class="card card-inverse card-flat">
	<div class="card-header">
		<div class="card-title">함께 수강하면 좋은 강의</div>
	</div>
	<div class="card-block">
		<div class="row">
			<div class="col-md-12">
				<section id="anc_sitfrm_relation" class="srel">
					<?php echo $pg_anchor; ?>

					<div class="local_desc02 local_desc">
						<p>
							등록된 전체상품 목록에서 상품분류를 선택하면 해당 상품 리스트가 연이어 나타납니다.<br>
							상품리스트에서 관련 상품으로 추가하시면 선택된 관련상품 목록에 <strong>함께</strong> 추가됩니다.<br>
							예를 들어, A 상품에 B 상품을 관련상품으로 등록하면 B 상품에도 A 상품이 관련상품으로 자동 추가되며, <strong>확인 버튼을 누르셔야 정상 반영됩니다.</strong>
						</p>
					</div>

					<div class="compare_wrap">
						<section class="compare_left">
							<h3>등록된 전체상품 목록</h3>
							<label for="sch_relation" class="sound_only">상품분류</label>
							<span class="srel_pad">
								<select id="sch_relation">
									<option value=''>분류별 상품</option>
									<?php
										$sql = " select * from {$g5['g5_shop_category_table']} ";
										if ($is_admin != 'super')
											$sql .= " where ca_mb_id = '{$member['mb_id']}' ";
										$sql .= " order by ca_order, ca_id ";
										$result = sql_query($sql);
										for ($i=0; $row=sql_fetch_array($result); $i++)
										{
											$len = strlen($row['ca_id']) / 2 - 1;

											$nbsp = "";
											for ($i=0; $i<$len; $i++)
												$nbsp .= "&nbsp;&nbsp;&nbsp;";

											echo "<option value=\"{$row['ca_id']}\">$nbsp{$row['ca_name']}</option>\n";
										}
									?>
								</select>
								<label for="sch_name" class="sound_only">상품명</label>
								<input type="text" name="sch_name" id="sch_name" class="frm_input" size="15">
								<button type="button" id="btn_search_item" class="btn_frmline">검색</button>
							</span>
							<div id="relation" class="srel_list">
								<p>상품의 분류를 선택하시거나 상품명을 입력하신 후 검색하여 주십시오.</p>
							</div>
							<script>
							$(function() {
								$("#btn_search_item").click(function() {
									var ca_id = $("#sch_relation").val();
									var it_name = $.trim($("#sch_name").val());
									var $relation = $("#relation");

									if(ca_id == "" && it_name == "") {
										$relation.html("<p>상품의 분류를 선택하시거나 상품명을 입력하신 후 검색하여 주십시오.</p>");
										return false;
									}

									$("#relation").load(
										"./itemformrelation.php",
										{ it_id: "<?php echo $it_id; ?>", ca_id: ca_id, it_name: it_name }
									);
								});

								$(document).on("click", "#relation .add_item", function() {
									// 이미 등록된 상품인지 체크
									var $li = $(this).closest("li");
									var it_id = $li.find("input:hidden").val();
									var it_id2;
									var dup = false;
									$("#reg_relation input[name='re_it_id[]']").each(function() {
										it_id2 = $(this).val();
										if(it_id == it_id2) {
											dup = true;
											return false;
										}
									});

									if(dup) {
										alert("이미 선택된 상품입니다.");
										return false;
									}

									var cont = "<li>"+$li.html().replace("add_item", "del_item").replace("추가", "삭제")+"</li>";
									var count = $("#reg_relation li").size();

									if(count > 0) {
										$("#reg_relation li:last").after(cont);
									} else {
										$("#reg_relation").html("<ul>"+cont+"</ul>");
									}

									$li.remove();
								});

								$(document).on("click", "#reg_relation .del_item", function() {
									if(!confirm("상품을 삭제하시겠습니까?"))
										return false;

									$(this).closest("li").remove();

									var count = $("#reg_relation li").size();
									if(count < 1)
										$("#reg_relation").html("<p>선택된 상품이 없습니다.</p>");
								});
							});
							</script>
						</section>

						<section class="compare_right">
							<h3>선택된 관련상품 목록</h3>
							<span class="srel_pad"></span>
							<div id="reg_relation" class="srel_sel">
								<?php
								$str = array();
								$sql = " select b.ca_id, b.it_id, b.it_name, b.it_price
										   from {$g5['g5_shop_item_relation_table']} a
										   left join {$g5['g5_shop_item_table']} b on (a.it_id2=b.it_id)
										  where a.it_id = '$it_id'
										  order by ir_no asc ";
								$result = sql_query($sql);
								for($g=0; $row=sql_fetch_array($result); $g++)
								{
									$it_name = get_it_image($row['it_id'], 50, 50).' '.$row['it_name'];

									if($g==0)
										echo '<ul>';
								?>
									<li>
										<input type="hidden" name="re_it_id[]" value="<?php echo $row['it_id']; ?>">
										<div class="list_item"><?php echo $it_name; ?></div>
										<div class="list_item_btn"><button type="button" class="del_item btn_frmline">삭제</button></div>
									</li>
								<?php
									$str[] = $row['it_id'];
								}
								$str = implode(",", $str);

								if($g > 0)
									echo '</ul>';
								else
									echo '<p>선택된 상품이 없습니다.</p>';
								?>
							</div>
							<input type="hidden" name="it_list" value="<?php echo $str; ?>">
						</section>

					</div>

				</section>

			</div>
		</div>
	</div>
</div>

<div class="btn_fixed_top">
    <a href="./itemlist.php?<?php echo $qstr; ?>" class="btn btn_02">목록</a>
    <a href="<?php echo shop_item_url($it_id); ?>" class="btn_02  btn">상품보기</a>
    <input type="submit" value="확인" class="btn_submit btn" accesskey="s">
</div>
</form>

<script>
$(function(){
    $("#it_lect_day").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", minDate: "+0d" });
});

var f = document.fitemform;

<?php if ($w == 'u') { ?>
$(".banner_or_img").addClass("sit_wimg");
$(function() {
    $(".sit_wimg_view").bind("click", function() {
        var sit_wimg_id = $(this).attr("id").split("_");
        var $img_display = $("#"+sit_wimg_id[1]);

        $img_display.toggle();

        if($img_display.is(":visible")) {
            $(this).text($(this).text().replace("확인", "닫기"));
        } else {
            $(this).text($(this).text().replace("닫기", "확인"));
        }

        var $img = $("#"+sit_wimg_id[1]).children("img");
        var width = $img.width();
        var height = $img.height();
        if(width > 700) {
            var img_width = 700;
            var img_height = Math.round((img_width * height) / width);

            $img.width(img_width).height(img_height);
        }
    });
    $(".sit_wimg_close").bind("click", function() {
        var $img_display = $(this).parents(".banner_or_img");
        var id = $img_display.attr("id");
        $img_display.toggle();
        var $button = $("#it_"+id+"_view");
        $button.text($button.text().replace("닫기", "확인"));
    });
});
<?php } ?>

function codedupcheck(id)
{
    if (!id) {
        alert('상품코드를 입력하십시오.');
        f.it_id.focus();
        return;
    }

    var it_id = id.replace(/[A-Za-z0-9\-_]/g, "");
    if(it_id.length > 0) {
        alert("상품코드는 영문자, 숫자, -, _ 만 사용할 수 있습니다.");
        return false;
    }

    $.post(
        "./codedupcheck.php",
        { it_id: id },
        function(data) {
            if(data.name) {
                alert("코드 '"+data.code+"' 는 '".data.name+"' (으)로 이미 등록되어 있으므로\n\n사용하실 수 없습니다.");
                return false;
            } else {
                alert("'"+data.code+"' 은(는) 등록된 코드가 없으므로 사용하실 수 있습니다.");
                document.fitemform.codedup.value = '';
            }
        }, "json"
    );
}

function fitemformcheck(f)
{
    if (!f.ca_id.value) {
        alert("기본분류를 선택하십시오.");
        f.ca_id.focus();
        return false;
    }

	if($("input[name=it_lect_type]:checked").size() == 0){
		alert('수강기간을 선택해 주세요!');
		$("input[name=it_lect_type]").eq(0).focus();
		return false;
	}

	if( $("input[name=it_lect_type]:checked").val() == '기간' && $("input[name=it_lect_date]").val() == "" ){
		alert('수강기간을 입력해 주세요.');
		$("input[name=it_lect_date]").select();
		return false;
	}else if( $("input[name=it_lect_type]:checked").val() == '일수' && $("input[name=it_lect_day]").val() == "" ){
		alert('수강일을 선택해 주세요.');
		$("input[name=it_lect_day]").select();
		return false;
	}

	var insertItemMainPoduct = true;

	if (jQuery("#lectureTable tr").length > 1 || $("#lectureTableCheckVal").val() =="A" ){
		jQuery("input[name=insertItemMainPoduct]").each(function(){
			if(jQuery(this).prop("checked") == true){
				insertItemMainPoduct = false
			}
		});
	}else{
		alert("상품을 선택해주세요")
		return false;

		insertItemMainPoduct = false;
	}

	//- 총 강좌의 갯수를 가져온다
	var lectureCnt = 0;
	var gubunCnt = 0;
	$("input[name=vItemIdx]").each(function(){
		if ($(this).attr("itemGubun") == "lecture" ){
			lectureCnt++;
		}
	});

	//- 구분에서 단강좌일때
	if($("#productGubun option:selected").val() == "N" && lectureCnt != 1){
		alert("단강좌 상품은 1개의 강좌가 포함되어야합니다.");
		return false;
	}

	//- 구분에서 패키지일때
	if($("#productGubun option:selected").val() == "P" && lectureCnt < 2){
		alert("패키지 상품은 2개의 이상의 강좌가 포함되어야합니다.");
		return false;
	}

	//- 구분에서 일반상품일때
	if($("#productGubun option:selected").val() == "B" && lectureCnt > 0){
		alert("일반상품은 강좌를 포함할수 없습니다.");
		return false;
	}

	if($("#productDcFlg option:selected").val() == "1"){
		if ($("#productDcRate option:selected").val() ==""){
			alert("할인율을 선택해주세요")
			return false;
		}
	}else{
		if($("#productDcPrice").val() ==""){
			alert("할인금액을 입력해주세요")
			return false;
		}

	}

	if($("#productRealPrice").val() == ""){
		alert("할인율을 입력해주세요");
		return false;
	}

	if(($("#productRealPrice").val() *1) < 0){
		alert("판매금액을 확인해주세요");
		return false;
	}

    if (f.w.value == "") {
        var error = "";
        $.ajax({
            url: "./ajax.it_id.php",
            type: "POST",
            data: {
                "it_id": f.it_id.value
            },
            dataType: "json",
            async: false,
            cache: false,
            success: function(data, textStatus) {
                error = data.error;
            }
        });

        if (error) {
            alert(error);
            return false;
        }
    }

	/*
    if(f.it_point_type.value == "1" || f.it_point_type.value == "2") {
        var point = parseInt(f.it_point.value);
        if(point > 99) {
            alert("포인트 비율을 0과 99 사이의 값으로 입력해 주십시오.");
            return false;
        }
    }


    if(parseInt(f.it_sc_type.value) > 1) {
        if(!f.it_sc_price.value || f.it_sc_price.value == "0") {
            alert("기본배송비를 입력해 주십시오.");
            return false;
        }

        if(f.it_sc_type.value == "2" && (!f.it_sc_minimum.value || f.it_sc_minimum.value == "0")) {
            alert("배송비 상세조건의 주문금액을 입력해 주십시오.");
            return false;
        }

        if(f.it_sc_type.value == "4" && (!f.it_sc_qty.value || f.it_sc_qty.value == "0")) {
            alert("배송비 상세조건의 주문수량을 입력해 주십시오.");
            return false;
        }
    }
	*/

    // 관련상품처리
    var item = new Array();
    var re_item = it_id = "";

    $("#reg_relation input[name='re_it_id[]']").each(function() {
        it_id = $(this).val();
        if(it_id == "")
            return true;

        item.push(it_id);
    });

    if(item.length > 0)
        re_item = item.join();

    $("input[name=it_list]").val(re_item);

	<?php echo get_editor_js('it_explan'); ?>

    return true;
}

function get_sch_list(){
	$.ajax({
		type:"post",
		url:"/ajax/admin_sch_txt_list.php",
		data: {
			it_id: '<?php echo $it_id; ?>'
		},
		success: function(data){
			$("#sch_txt_list").html(data);
		}
	});
}

$("#sch_add").click(function(){
	if($("#it_sch_txt").val() == ""){
		alert('등록할 검색어를 입력해 주세요.');
		return false;
	}
	$.ajax({
		type:"post",
		url:"/ajax/admin_func.php",
		data: {
			func: 'add_keyword',
			it_id: '<?php echo $it_id; ?>',
			it_keyword: $("#it_sch_txt").val()
		},
		success: function(data){
			if(data == 'same'){
				alert('이미 등록된 검색어 입니다.');
			}else{
				$("#it_sch_txt").val("");
				get_sch_list();
			}
		}
	});
});

$("#sch_add2").click(function(){
	if($("#it_sch_txt").val() == ""){
		alert('등록할 검색어를 입력해 주세요.');
		return false;
	}

	if($(".empty_sch").size()){
		$("#sch_txt_list").html("");
	}

	$("#sch_txt_list").append("<li class='sch_txt'>#"+$("#it_sch_txt").val()+" <span data-id='"+$("#it_sch_txt").val()+"'><i class='fa fa-close'></i></span></li>");

	var keyword = "";
	$(".sch_txt").each(function(){
		keyword += $(this).children('span').attr("data-id")+"||";
	});
	$("#it_keyword").val(keyword);
	$("#it_sch_txt").val("");
});

$(document).on("click", "#sch_txt_list > li > span", function(){
	$(this).parent('li').remove();

	var keyword = "";
	$(".sch_txt").each(function(){
		keyword += $(this).children('span').attr("data-id")+"||";
	});
	$("#it_keyword").val(keyword);

	if($(".sch_txt").size() == '0'){
		$("#sch_txt_list").html("<li class='empty_sch'>등록된 검색어가 없습니다.</li>");
	}

	/*
	$.ajax({
		type:"post",
		url:"/ajax/admin_func.php",
		data: {
			func: 'del_keyword',
			it_id: '<?php echo $it_id; ?>',
			it_keyword: $(this).attr("data-id")
		},
		success: function(data){
			get_sch_list();
		}
	});
	*/
});

$(window).load(function(){
	//get_sch_list();
	fnPrice();
});

/*
function categorychange(f)
{
    var idx = f.ca_id.value;

    if (f.w.value == "" && idx)
    {
        f.it_use.checked = ca_use[idx] ? true : false;
        f.it_stock_qty.value = ca_stock_qty[idx];
        f.it_sell_email.value = ca_sell_email[idx];
    }
}

categorychange(document.fitemform);
*/
</script>
<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
