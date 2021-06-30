<?php
include_once('./_common.php');

// 상품 리스트에서 다른 필드로 정렬을 하려면 아래의 배열 코드에서 해당 필드를 추가하세요.
if( isset($sort) && ! in_array($sort, array('it_sum_qty', 'it_price', 'it_use_avg', 'it_use_cnt', 'it_update_time')) ){
    $sort='';
}

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/list.php');
    return;
}

$sql = " select * from {$g5['g5_shop_category_table']} where ca_id = '$ca_id' and ca_use = '1' ";
$ca = sql_fetch($sql);

if (!$ca['ca_id'])
    alert('등록된 분류가 없습니다.');

// 테마미리보기 스킨 등의 변수 재설정
if(defined('_THEME_PREVIEW_') && _THEME_PREVIEW_ === true) {
    $ca['ca_skin']       = (isset($tconfig['ca_skin']) && $tconfig['ca_skin']) ? $tconfig['ca_skin'] : $ca['ca_skin'];
    $ca['ca_img_width']  = (isset($tconfig['ca_img_width']) && $tconfig['ca_img_width']) ? $tconfig['ca_img_width'] : $ca['ca_img_width'];
    $ca['ca_img_height'] = (isset($tconfig['ca_img_height']) && $tconfig['ca_img_height']) ? $tconfig['ca_img_height'] : $ca['ca_img_height'];
    $ca['ca_list_mod']   = (isset($tconfig['ca_list_mod']) && $tconfig['ca_list_mod']) ? $tconfig['ca_list_mod'] : $ca['ca_list_mod'];
    $ca['ca_list_row']   = (isset($tconfig['ca_list_row']) && $tconfig['ca_list_row']) ? $tconfig['ca_list_row'] : $ca['ca_list_row'];
}

// 본인인증, 성인인증체크
if(!$is_admin) {
    $msg = shop_member_cert_check($ca_id, 'list');
    if($msg)
        alert($msg, G5_SHOP_URL);
}

$g5['title'] = $ca['ca_name'].' 리스트';

if ($ca['ca_include_head'] && is_include_path_check($ca['ca_include_head']))
    @include_once($ca['ca_include_head']);
else
    include_once(G5_SHOP_PATH.'/_head.php');

// 스킨경로
$skin_dir = G5_SHOP_SKIN_PATH;

if($ca['ca_skin_dir']) {
    if(preg_match('#^theme/(.+)$#', $ca['ca_skin_dir'], $match))
        $skin_dir = G5_THEME_PATH.'/'.G5_SKIN_DIR.'/shop/'.$match[1];
    else
        $skin_dir = G5_PATH.'/'.G5_SKIN_DIR.'/shop/'.$ca['ca_skin_dir'];

    if(is_dir($skin_dir)) {
        $skin_file = $skin_dir.'/'.$ca['ca_skin'];

        if(!is_file($skin_file))
            $skin_dir = G5_SHOP_SKIN_PATH;
    } else {
        $skin_dir = G5_SHOP_SKIN_PATH;
    }
}

define('G5_SHOP_CSS_URL', str_replace(G5_PATH, G5_URL, $skin_dir));

if ($is_admin)
    echo '<div class="sct_admin"><a href="'.G5_ADMIN_URL.'/shop_admin/categoryform.php?w=u&amp;ca_id='.$ca_id.'" class="btn_admin btn"><span class="sound_only">분류 관리</span><i class="fa fa-cog fa-spin fa-fw"></i></a></div>';
?>

<script>
var itemlist_ca_id = "<?php echo $ca_id; ?>";
</script>
<script src="<?php echo G5_JS_URL; ?>/shop.list.js"></script>

<section id="sub_content">
	<div class="container">
		<div class="row">
			<aside class="col-lg-4 col-md-4 col-sm-12">
			<?php
			$cate_skin = $skin_dir.'/listcategory.skin.php';
			if(!is_file($cate_skin))
				$cate_skin = G5_SHOP_SKIN_PATH.'/listcategory.skin.php';
			include $cate_skin;

			/*
			for($j = 0;$j < 5;$j += 1) {
				$active = '';
				if($j == 0) $active = 'active';
			?>
			<div class="box_style_2 <?php echo $active?>">
			<h4><a href="#" class="box_cate_tit <?php echo $active?>">데이터 사이언스<?php echo $j?></a></h4>
			<div class="box_category <?php echo $active?>">
			<ul class="submenu-col">
				<li><a href="" class="active">머신러닝</a></li>
				<?php for($i = 0;$i < 5;$i+= 1) { ?>
				<li><a href="#" class="">파이썬파이썬</a></li>
				<?php } ?>
			</ul>
			</div>
			</div>
			<?php }
			*/
			?>

			</aside>

			<div class="col-lg-12 col-md-12 col-sm-12 sub-list">
				<div class="row" id="_page">
					<?php
					/*
					$nav_skin = $skin_dir.'/navigation.skin.php';
					if(!is_file($nav_skin))
						$nav_skin = G5_SHOP_SKIN_PATH.'/navigation.skin.php';
					include $nav_skin;
					*/
					// 상단 HTML
					//echo '<div id="sct_hhtml">'.conv_content($ca['ca_head_html'], 1).'</div>';

					// 상품 출력순서가 있다면
					if ($sort != "")
						$order_by = $sort.' '.$sortodr.' , it_order, it_id desc';
					else
						$order_by = 'it_order desc, it_id desc';

					$error = '<p class="sct_noitem">There is no registered product.</p>';

					// 리스트 스킨
					$skin_file = is_include_path_check($skin_dir.'/'.$ca['ca_skin']) ? $skin_dir.'/'.$ca['ca_skin'] : $skin_dir.'/list.10.skin.php';

					if (file_exists($skin_file)) {
						/*
						echo '<div id="sct_sortlst">';
						$sort_skin = $skin_dir.'/list.sort.skin.php';
						if(!is_file($sort_skin))
							$sort_skin = G5_SHOP_SKIN_PATH.'/list.sort.skin.php';
						include $sort_skin;

						// 상품 보기 타입 변경 버튼

						$sub_skin = $skin_dir.'/list.sub.skin.php';
						if(!is_file($sub_skin))
							$sub_skin = G5_SHOP_SKIN_PATH.'/list.sub.skin.php';
						include $sub_skin;
						echo '</div>';
						*/

						// 총몇개 = 한줄에 몇개 * 몇줄
						$ca['ca_list_mod'] = "4"; // 몇개
						$ca['ca_list_row'] = "4"; // 몇줄
						$items = $ca['ca_list_mod'] * $ca['ca_list_row'];
						// 페이지가 없으면 첫 페이지 (1 페이지)
						if ($page < 1) $page = 1;
						// 시작 레코드 구함
						$from_record = ($page - 1) * $items;

						$list = new item_list($skin_file, $ca['ca_list_mod'], $ca['ca_list_row'], $ca['ca_img_width'], $ca['ca_img_height']);
						$list->set_category($ca['ca_id'], 1);
						$list->set_category($ca['ca_id'], 2);
						$list->set_category($ca['ca_id'], 3);
						$list->set_is_page(true);
						$list->set_order_by($order_by);
						$list->set_from_record($from_record);
						$list->set_view('it_img', true);
						$list->set_view('it_id', false);
						$list->set_view('it_name', true);
						$list->set_view('it_basic', true);
						$list->set_view('it_cust_price', true);
						$list->set_view('it_price', true);
						$list->set_view('it_icon', true);
						$list->set_view('sns', true);
						echo $list->run();

						// where 된 전체 상품수
						$total_count = $list->total_count;
						// 전체 페이지 계산
						$total_page  = ceil($total_count / $items);
					}
					else
					{
						echo '<div class="sct_nofile">'.str_replace(G5_PATH.'/', '', $skin_file).' 파일을 찾을 수 없습니다.<br>관리자에게 알려주시면 감사하겠습니다.</div>';
					}
					?>

				</div>
				<?php
				if($total_page > 1){
				?>
				<div class="row text-center btn_more" data-total="<?php echo $total_page; ?>" data-page="1">
					<a href="javascript:void(0);" style="margin:30px auto">더 보기</a>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
<script>
$(".box_cate_tit").on("click",function(){

	if($(this).hasClass("active")) {
		$(this).removeClass("active");
		$(this).parent().parent().removeClass("active");
		$(this).parent().next(".box_category").slideUp();
	}else{
		$(this).addClass("active");
		$(this).parent().parent().addClass("active");
		$(this).parent().next(".box_category").slideDown();
	}
});

var n_page = "1";
var it_type = "";
var it_level = "";

function get_cate_list(){
	$.ajax({
		type:"post",
		url:"/ajax/shop_list_more.php",
		data: {
			ca_id: itemlist_ca_id,
			it_level: it_level,
			it_type: it_type
		},
		success: function(data){
			var con = data.split('||');

			$("#_page").html(con[1]);
			$(".btn_more").attr("data-page","1");
			$(".btn_more").attr("data-total", con[0]);
			if(con[0] <= 1){
				$(".btn_more").hide();
			}else{
				$(".btn_more").show();
			}
		}
	});
}

$(".btn_more").click(function(){
	n_page = $(this).attr("data-page");
	n_page++;

	$(".btn_more").attr("disabled", true);
	$.ajax({
		type:"post",
		url:"/ajax/shop_list_more.php",
		data: {
			page: n_page
		},
		success: function(data){
			var con = data.split('||');

			$("#_page").append(con[1]);
			if($(".btn_more").attr("data-total") >= n_page){
				$(".btn_more").hide();
			}else{
				$(".btn_more").attr("data-page", n_page);
				$(".btn_more").find('a').attr("disabled", false);
			}
		}
	});
});

$(".filter_level").click(function(){
	it_level = "";
	$(".filter_level:checked").each(function(){
		it_level += ","+$(this).val();
	});

	get_cate_list();
});

$(".filter_type").click(function(){
	it_type = "";
	$(".filter_type:checked").each(function(){
		it_type += ","+$(this).val();
	});

	get_cate_list();
});
</script>
</section>

<!-- 상품 목록 시작 { -->
<div id="sct" style="display:none;">
    <?php
    $qstr1 .= 'ca_id='.$ca_id;
    $qstr1 .='&amp;sort='.$sort.'&amp;sortodr='.$sortodr;
    echo get_paging($config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr1.'&amp;page=');
    ?>

    <?php
    // 하단 HTML
    echo '<div id="sct_thtml">'.conv_content($ca['ca_tail_html'], 1).'</div>';
?>
</div>
<!-- } 상품 목록 끝 -->

<?php
if ($ca['ca_include_tail'] && is_include_path_check($ca['ca_include_tail']))
    @include_once($ca['ca_include_tail']);
else
    include_once(G5_SHOP_PATH.'/_tail.php');

echo "\n<!-- {$ca['ca_skin']} -->\n";
?>
