<?php
$sub_menu = '400200';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);
include_once(G5_LIB_PATH.'/iteminfo.lib.php');

auth_check($auth[$sub_menu], "w");

$html_title = "강의 ";

if ($w == "")
{
    $html_title .= "등록";

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

	$lt_id = "LEC".time();
}
else if ($w == "u")
{
    $html_title .= "수정";

    if ($is_admin != 'super')
    {
        $sql = " select lt_id from han_shop_lec a, {$g5['g5_shop_category_table']} b
                  where a.lt_id = '$lt_id'
                    and a.ca_id = b.ca_id
                    and b.ca_mb_id = '{$member['mb_id']}' ";
        $row = sql_fetch($sql);
        if (!$row['lt_id'])
            alert("\'{$member['mb_id']}\' 님께서 수정 할 권한이 없는 강의입니다.");
    }

    $it = get_shop_lec($lt_id);

    if(!$it)
        alert('강의정보가 존재하지 않습니다.');

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

$pg_anchor ='<ul class="nav nav-tabs bg-warning-a800" role="tab-list">
<li class="nav-item"><a href="#anc_sitfrm_cate" class="nav-link">강의분류</a></li>
<li class="nav-item"><a href="#anc_sitfrm_ini" class="nav-link">커리큐럼</a></li>
<!--<li class="nav-item"><a href="#anc_sitfrm_relation" class="nav-link">연관강좌</a></li>-->
</ul>
';

?>
<style>
.table th, .table td {
    vertical-align: middle;
}
input[type=file].frm_input {
	height:36px;
}

.suc_btn {border-radius: 5px;border: 0px;padding: 0px 8px;font-weight: bold;}
</style>
<form name="flecform" action="./lecformupdate.php" method="post" enctype="MULTIPART/FORM-DATA" autocomplete="off" onsubmit="return flecformcheck(this)">

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
		<div class="card-title">강의분류</div>
	</div>
	<div class="card-block">
		<div class="row">
			<div class="col-md-12">

				<section id="anc_sitfrm_cate">
					<?php echo $pg_anchor; ?>
					<div class="local_desc02 local_desc">
						<p>강의분류는 반드시 선택하셔야 합니다.</p>
					</div>

					<div class="tbl_frm01 tbl_wrap table-responsive">
						<table class="table">
						<caption>강의분류 입력</caption>
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
							<th scope="row"><label for="mb_id">강사명</label></th>
							<td>
								<?php echo mb_level(3, $it['mb_id']); ?>
							</td>
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
		<div class="card-title">커리큐럼</div>
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
							<th scope="row">강의코드</th>
							<td colspan="2">
								<?php if ($w == '') { // 추가 ?>
									<!-- 최근에 입력한 코드(자동 생성시)가 목록의 상단에 출력되게 하려면 아래의 코드로 대체하십시오. -->
									<!-- <input type=text class=required name=lt_id value="<?php echo 10000000000-time()?>" size=12 maxlength=10 required> <a href='javascript:;' onclick="codedupcheck(document.all.lt_id.value)"><img src='./img/btn_code.gif' border=0 align=absmiddle></a> -->
									<?php echo help("강의의 코드는 13자리 숫자로 자동생성합니다. <b>직접 강의코드를 입력할 수도 있습니다.</b>\n강의코드는 영문자, 숫자, - 만 입력 가능합니다."); ?>
									<input type="text" name="lt_id" value="<?php echo $lt_id; ?>" id="lt_id" required class="frm_input required" size="20" maxlength="20">
									<!-- <?php if ($default['de_code_dup_use']) { ?><button type="button" class="btn_frmline" onclick="codedupcheck(document.all.lt_id.value)">중복검사</a><?php } ?> -->
								<?php } else { ?>
									<input type="hidden" name="lt_id" value="<?php echo $it['lt_id']; ?>">
									<span class="frm_ca_id"><?php echo $it['lt_id']; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="it_name">강의명</label></th>
							<td colspan="2">
								<?php echo help("HTML 입력이 불가합니다."); ?>
								<input type="text" name="it_name" value="<?php echo get_text(cut_str($it['it_name'], 250, "")); ?>" id="it_name" required class="frm_input required" size="95">
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="it_price">판매가격</label></th>
							<td colspan="2">
								<input type="text" name="it_price" value="<?php echo $it['it_price']; ?>" id="it_price" class="frm_input" size="8"> 원
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="it_basic">평점</label></th>
							<td colspan="2">
								<?php echo help("1~5 사이의 소숫점 첫째자리 숫자만 입력"); ?>
								<input type="text" name="it_basic" value="<?php echo get_text(html_purifier($it['it_basic'])); ?>" id="it_basic" class="frm_input" size="95">
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="it_basic">교재 및 참고자료</label></th>
							<td colspan="2">
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
								$('<button type="button" id="it_limg<?php echo $i; ?>_view" class="btn_frmline sit_wimg_view">이미지<?php echo $i; ?> 확인</button>').appendTo('.sit_wimg_limg<?php echo $i; ?>');
								</script>
								<?php } ?>
							</td>
						</tr>

						<tr>
							<th scope="row">
								커리큐럼 등록</br>
								<button type="button" class="btn btn_02" id="chapter_add_btn">섹션추가</button>
							</th>
							<td colspan="2" id="chapter_lec_list">
							</td>
						</tr>
						<tr>
							<td colspan="3" style="text-align:right;">
								<!--<button type="button" class="btn_submit btn">임시저장</button>-->
							</td>
						</tr>

						<script>
							$("#chapter_add_btn").click(function(){
								/*
								if($('#cp_name').val() == ""){
									alert('섹션 제목을 입력해 주세요.');
									return false;
								}
								*/
								if($(".empty_sec").size() > 0)
									$("#chapter_lec_list").html("");

								$.ajax({
									type:"post",
									url:"./lecform_section_update.php",
									data:{
										lt_id: '<?php echo $lt_id; ?>',
										cp_name: $('#cp_name').val(),
										cp_order: $('#cp_order').val()
									},
									success:function(data){
										if(data == "01"){
											alert('등록실패!');
										}else{
											alert('등록되었습니다.');
											$("#chapter_lec_list").append(data);
											//get_chapter_lec_list();
										}
									}
								});
							});

							function get_chapter_lec_list(){
								$.ajax({
									type:"post",
									url:"./lecform_section.php",
									data:{
										lt_id: '<?php echo $lt_id; ?>'
									},
									success:function(data){
										$("#chapter_lec_list").html(data);
									}
								});
							}

							get_chapter_lec_list();
						</script>

						<tr>
							<th scope="row">커리큐럼 등록</th>
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

<div class="card card-inverse card-flat" style="display:none;">
	<div class="card-header">
		<div class="card-title">연관강좌</div>
	</div>
	<div class="card-block">
		<div class="row">
			<div class="col-md-12">
				<section id="anc_sitfrm_relation" class="srel">
					<?php echo $pg_anchor; ?>

					<div class="local_desc02 local_desc">
						<p>
							등록된 전체강의 목록에서 강의분류를 선택하면 해당 강의 리스트가 연이어 나타납니다.<br>
							강의리스트에서 관련 강의으로 추가하시면 선택된 관련강의 목록에 <strong>함께</strong> 추가됩니다.<br>
							예를 들어, A 강의에 B 강의을 관련강의으로 등록하면 B 강의에도 A 강의이 관련강의으로 자동 추가되며, <strong>확인 버튼을 누르셔야 정상 반영됩니다.</strong>
						</p>
					</div>

					<div class="compare_wrap">
						<section class="compare_left">
							<h3>등록된 전체강의 목록</h3>
							<label for="sch_relation" class="sound_only">강의분류</label>
							<span class="srel_pad">
								<select id="sch_relation">
									<option value=''>분류별 강의</option>
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
								<label for="sch_name" class="sound_only">강의명</label>
								<input type="text" name="sch_name" id="sch_name" class="frm_input" size="15">
								<button type="button" id="btn_search_item" class="btn_frmline">검색</button>
							</span>
							<div id="relation" class="srel_list">
								<p>강의의 분류를 선택하시거나 강의명을 입력하신 후 검색하여 주십시오.</p>
							</div>
							<script>
							$(function() {
								$("#btn_search_item").click(function() {
									var ca_id = $("#sch_relation").val();
									var it_name = $.trim($("#sch_name").val());
									var $relation = $("#relation");

									if(ca_id == "" && it_name == "") {
										$relation.html("<p>강의의 분류를 선택하시거나 강의명을 입력하신 후 검색하여 주십시오.</p>");
										return false;
									}

									$("#relation").load(
										"./lecformrelation.php",
										{ lt_id: "<?php echo $lt_id; ?>", ca_id: ca_id, it_name: it_name }
									);
								});

								$(document).on("click", "#relation .add_item", function() {
									// 이미 등록된 강의인지 체크
									var $li = $(this).closest("li");
									var lt_id = $li.find("input:hidden").val();
									var lt_id2;
									var dup = false;
									$("#reg_relation input[name='re_lt_id[]']").each(function() {
										lt_id2 = $(this).val();
										if(lt_id == lt_id2) {
											dup = true;
											return false;
										}
									});

									if(dup) {
										alert("이미 선택된 강의입니다.");
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
									if(!confirm("강의을 삭제하시겠습니까?"))
										return false;

									$(this).closest("li").remove();

									var count = $("#reg_relation li").size();
									if(count < 1)
										$("#reg_relation").html("<p>선택된 강의이 없습니다.</p>");
								});
							});
							</script>
						</section>

						<section class="compare_right">
							<h3>선택된 관련강의 목록</h3>
							<span class="srel_pad"></span>
							<div id="reg_relation" class="srel_sel">
								<?php
								$str = array();
								$sql = " select b.ca_id, b.lt_id, b.it_name, b.it_price
										   from {$g5['g5_shop_item_relation_table']} a
										   left join han_shop_lec b on (a.lt_id2=b.lt_id)
										  where a.lt_id = '$lt_id'
										  order by ir_no asc ";
								$result = sql_query($sql);
								for($g=0; $row=sql_fetch_array($result); $g++)
								{
									$it_name = get_it_image($row['lt_id'], 50, 50).' '.$row['it_name'];

									if($g==0)
										echo '<ul>';
								?>
									<li>
										<input type="hidden" name="re_lt_id[]" value="<?php echo $row['lt_id']; ?>">
										<div class="list_item"><?php echo $it_name; ?></div>
										<div class="list_item_btn"><button type="button" class="del_item btn_frmline">삭제</button></div>
									</li>
								<?php
									$str[] = $row['lt_id'];
								}
								$str = implode(",", $str);

								if($g > 0)
									echo '</ul>';
								else
									echo '<p>선택된 강의이 없습니다.</p>';
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
    <a href="./leclist.php?<?php echo $qstr; ?>" class="btn btn_02">목록</a>
    <input type="submit" value="확인" class="btn_submit btn" accesskey="s">
</div>
</form>


<script>
var f = document.flecform;

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
        alert('강의코드를 입력하십시오.');
        f.lt_id.focus();
        return;
    }

    var lt_id = id.replace(/[A-Za-z0-9\-_]/g, "");
    if(lt_id.length > 0) {
        alert("강의코드는 영문자, 숫자, -, _ 만 사용할 수 있습니다.");
        return false;
    }

    $.post(
        "./codedupcheck.php",
        { lt_id: id },
        function(data) {
            if(data.name) {
                alert("코드 '"+data.code+"' 는 '".data.name+"' (으)로 이미 등록되어 있으므로\n\n사용하실 수 없습니다.");
                return false;
            } else {
                alert("'"+data.code+"' 은(는) 등록된 코드가 없으므로 사용하실 수 있습니다.");
                document.flecform.codedup.value = '';
            }
        }, "json"
    );
}

function flecformcheck(f)
{
    if (!f.ca_id.value) {
        alert("기본분류를 선택하십시오.");
        f.ca_id.focus();
        return false;
    }

    if (f.w.value == "") {
        var error = "";
        $.ajax({
            url: "./ajax.lt_id.php",
            type: "POST",
            data: {
                "lt_id": f.lt_id.value
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

    // 관련강의처리
    var item = new Array();
    var re_item = lt_id = "";

    $("#reg_relation input[name='re_lt_id[]']").each(function() {
        lt_id = $(this).val();
        if(lt_id == "")
            return true;

        item.push(lt_id);
    });

    if(item.length > 0)
        re_item = item.join();

    $("input[name=it_list]").val(re_item);

    // 이벤트처리
    var evnt = new Array();
    var ev = ev_id = "";

    $("#reg_event_list input[name='ev_id[]']").each(function() {
        ev_id = $(this).val();
        if(ev_id == "")
            return true;

        evnt.push(ev_id);
    });

    if(evnt.length > 0)
        ev = evnt.join();

    $("input[name=ev_list]").val(ev);

    <?php echo get_editor_js('it_explan'); ?>
    <?php echo get_editor_js('it_mobile_explan'); ?>
    <?php echo get_editor_js('it_head_html'); ?>
    <?php echo get_editor_js('it_tail_html'); ?>
    <?php echo get_editor_js('it_mobile_head_html'); ?>
    <?php echo get_editor_js('it_mobile_tail_html'); ?>

    return true;
}
</script>
<script>
	$(document).on("click", ".spl_move_apply", function() {
		var i = $("button.spl_move_apply").index( $(this) );
		var $el = $(".mv_option_addfrm:eq("+i+") tr:last");
		var fld = "<tr>\n";
		fld += "	<td class=\"td_chk\">\n";
		fld += "		<input type=\"hidden\" name=\"mv_cp_id[]\" value=\"{cp_id}\">\n";
		fld += "		<input type=\"hidden\" name=\"mv_file_name[]\" value=\"\">\n";
		fld += "		<input type=\"checkbox\" name=\"mv_chk[]\" value=\"1\">\n";
		fld += "	</td>\n";
		fld += "	<td class=\"spl-subject-cell\" style=\"text-align:center;\">\n";
		fld += "		<input type=\"text\" name=\"mv_name[]\" value=\"\" class=\"frm_input\" size=\"50\" /><br>\n";
		fld += "	</td>\n";
		fld += "	<td class=\"spl-subject-cell\" style=\"text-align:center;\"><input type=\"text\" name=\"mv_url[]\" value=\"\" class=\"frm_input\" size=\"40\" /></td>\n";
		fld += "	<td class=\"spl-subject-cell\" style=\"text-align:center;\"><input type=\"text\" name=\"mv_preview[]\" value=\"\" class=\"frm_input\" size=\"40\" /></td>\n";
		fld += "	<td class=\"spl-subject-cell\" style=\"text-align:center;\"><input type=\"text\" name=\"mv_order[]\" value=\"\" class=\"frm_input\" size=\"7\" /></td>\n";
		//fld += "	<td class=\"spl-cell\" style=\"text-align:center;\">\n";
		//fld += "		<input type=\"radio\" name=\"mv_preview[]\" value=\"\" class=\"frm_input\" size=\"50\" /> 무료노출<br>\n";
		//fld += "		<input type=\"radio\" name=\"mv_preview[]\" value=\"\" class=\"frm_input\" size=\"50\" /> 비노출\n";
		//fld += "	</td>\n";
		fld += "	<td class=\"td_numsmall\" style=\"text-align:center;\">\n";
		fld += "		<input type=\"file\" name=\"mv_file[]\" value=\"\" id=\"mv_file_0\" class=\"frm_input\" style=\"border:0px;\" size=\"9\">\n";
		fld += "	</td>\n";
		fld += "		<td class=\"td_mng\">\n";
		fld += "			<select name=\"mv_use[]\">\n";
		fld += "				<option value=\"1\" >사용함</option>\n";
		fld += "				<option value=\"0\" >사용안함</option>\n";
		fld += "			</select>\n";
		fld += "		</td>\n";
		fld += "	<td class=\"td_mng\" style=\"display:none;\">\n";
		fld += "		<button type=\"button\">완료</button>\n";
		fld += "		<button type=\"button\">수정</button>\n";
		fld += "	</td>\n";
		fld += "</tr>\n";

		fld = fld.replace('{cp_id}', $(this).attr('data-id'));

		$el.after(fld);
	});

	// 모두선택
	$(document).on("click", "input[name=mv_chk_all]", function() {
		if($(this).is(":checked")) {
			$("input[name='mv_chk[]']").attr("checked", true);
		} else {
			$("input[name='mv_chk[]']").attr("checked", false);
		}
	});

	// 선택삭제
	$(document).on("click", ".sel_move_delete", function() {
		var $el = $("input[name='mv_chk[]']:checked");
		if($el.size() < 1) {
			alert("삭제하려는 영상을 하나 이상 선택해 주십시오.");
			return false;
		}

		$el.closest("tr").remove();
	});

	// 챕터 삭제
	$(document).on("click",".suc_btn",function(){
		if(confirm('정말 삭제 하시겠습니까?')){
			$(this).parent().parent().parent().remove();
		}
	});
</script>
<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
