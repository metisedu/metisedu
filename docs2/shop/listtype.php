<?php
include_once('./_common.php');

// 상품 리스트에서 다른 필드로 정렬을 하려면 아래의 배열 코드에서 해당 필드를 추가하세요.
if( isset($sort) && ! in_array($sort, array('it_sum_qty', 'it_price', 'it_use_avg', 'it_use_cnt', 'it_update_time')) ){
    $sort='';
}

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/listtype.php');
    return;
}

$type = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\s]/", "", $_REQUEST['type']);
if ($type == 1)      $g5['title'] = '히트상품';
else if ($type == 2) $g5['title'] = '추천상품';
else if ($type == 3) $g5['title'] = '최신상품';
else if ($type == 4) $g5['title'] = '인기상품';
else if ($type == 5) $g5['title'] = '할인상품';
else
    alert('상품유형이 아닙니다.');

include_once('./_head.php');

// 한페이지에 출력하는 이미지수 = $list_mod * $list_row
$list_mod   = $default['de_listtype_list_mod'];   // 한줄에 이미지 몇개씩 출력?
$list_row   = $default['de_listtype_list_row'];   // 한 페이지에 몇라인씩 출력?

$img_width  = $default['de_listtype_img_width'];  // 출력이미지 폭
$img_height = $default['de_listtype_img_height']; // 출력이미지 높이
?>

<?php
// 상품 출력순서가 있다면
$order_by = ' it_order, it_id desc ';
if ($sort != '')
    $order_by = $sort.' '.$sortodr.' , it_order, it_id desc';
else
    $order_by = 'it_order, it_id desc';

if (!$skin || preg_match('#\.+[\\\/]#', $skin))
    $skin = $default['de_listtype_list_skin'];
else
    $skin = preg_replace('#\.+[\\\/]#', '', $skin);

define('G5_SHOP_CSS_URL', G5_SHOP_SKIN_URL);
?>
<script>
var itemlist_ca_id = "<?php echo $ca_id; ?>";
</script>
<script src="<?php echo G5_JS_URL; ?>/shop.list.js"></script>

<section id="sub_content">
	<div class="container">
		<div class="row">
			<aside class="col-lg-3 col-md-4 col-sm-12">
				<div class="box_style_1">
            	<h4><a href="#" class="box_cate_tit active">맞춤 설정</a></h4>
				<div class="box_category">
					<span class="box_subj">난이도</span>
					<ul class="submenu-col">
						<li><div class="page__toggle">
							<label class="toggle">
							  <input class="toggle__input filter_level" type="checkbox" name="it_level" value="1">
							  <span class="toggle__label">
								<span class="toggle__text"> 초보자도 쉽게 가능해요</span>
							  </span>
							</label>
							</div>
						</li>
						<li><div class="page__toggle">
							<label class="toggle">
							  <input class="toggle__input filter_level" type="checkbox" name="it_level" value="2">
							  <span class="toggle__label">
								<span class="toggle__text"> 다소 어려울 수 있어요</span>
							  </span>
							</label>
							</div>
						</li>
						<li><div class="page__toggle">
							<label class="toggle">
							  <input class="toggle__input filter_level" type="checkbox" name="it_level" value="3">
							  <span class="toggle__label">
								<span class="toggle__text"> 숙련자라면 OK</span>
							  </span>
							</label>
							</div>
						</li>
					</ul>

					<div class="hr15"></div>
					<span class="box_subj">할인여부</span>
					<ul class="submenu-col">
						<li><div class="page__toggle">
							<label class="toggle">
							  <input class="toggle__input filter_type" type="checkbox" name="dis" value="it_dc_rate">
							  <span class="toggle__label">
								<span class="toggle__text"> 패키지 할인중</span>
							  </span>
							</label>
							</div>
						</li>
						<li><div class="page__toggle">
							<label class="toggle">
							  <input class="toggle__input filter_type" type="checkbox" name="dis" value="it_type17">
							  <span class="toggle__label">
								<span class="toggle__text"> 특별 할인 중</span>
							  </span>
							</label>
							</div>
						</li>
						<li><div class="page__toggle">
							<label class="toggle">
							  <input class="toggle__input filter_type" type="checkbox" name="dis" value="it_coupon">
							  <span class="toggle__label">
								<span class="toggle__text"> 쿠폰 적용 가능!</span>
							  </span>
							</label>
							</div>
						</li>

					</ul>
				</div>
			</div>

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

			<div class="col-lg-9 col-md-8 col-sm-12 sub-list">
				<div class="row" id="_page">
				<?php
				// 리스트 유형별로 출력
				$list_file = G5_SHOP_SKIN_PATH.'/'.$skin;
				if (file_exists($list_file)) {
					// 총몇개 = 한줄에 몇개 * 몇줄
					$items = $list_mod * $list_row;
					// 페이지가 없으면 첫 페이지 (1 페이지)
					if ($page < 1) $page = 1;
					// 시작 레코드 구함
					$from_record = ($page - 1) * $items;

					$list = new item_list();
					$list->set_type($type);
					$list->set_list_skin($list_file);
					$list->set_list_mod($list_mod);
					$list->set_list_row($list_row);
					$list->set_img_size($img_width, $img_height);
					$list->set_is_page(true);
					$list->set_order_by($order_by);
					$list->set_from_record($from_record);
					$list->set_view('it_img', true);
					$list->set_view('it_id', false);
					$list->set_view('it_name', true);
					$list->set_view('it_cust_price', false);
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
					echo '<div align="center">'.$skin.' 파일을 찾을 수 없습니다.<br>관리자에게 알려주시면 감사하겠습니다.</div>';
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
			type: '<?php echo $type; ?>',
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

<?php
$qstr .= '&amp;type='.$type.'&amp;sort='.$sort;
echo get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page=");
?>

<?php
include_once('./_tail.php');
?>
