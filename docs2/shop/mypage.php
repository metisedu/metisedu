<?php
include_once('./_common.php');
define('_NOHEADER_',true);

if (!$is_member)
    goto_url(G5_BBS_URL."/login.php?url=".urlencode(G5_SHOP_URL."/mypage.php"));

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/mypage.php');
    return;
}

// 테마에 mypage.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
    $theme_mypage_file = G5_THEME_SHOP_PATH.'/mypage.php';
    if(is_file($theme_mypage_file)) {
        include_once($theme_mypage_file);
        return;
        unset($theme_mypage_file);
    }
}

$g5['title'] = '마이페이지';
include_once('./_head.php');

// 내 클래스
$t_day = date("Y-m-d");

$sQuery = " SELECT count(*) as cnt
			FROM g5_shop_cart
			WHERE mb_id = '".$member['mb_id']."'
			AND   ct_status IN ('입금','배송','완료')
			AND   ct_start_date <= '".$t_day."'
			AND   ct_end_date >= '".$t_day."'
			";
$row = sql_fetch($sQuery);
$cl_count = $row['cnt'];

// 응원 클래스
$sql = " select count(*) as cnt from {$g5['g5_shop_wish_table']} a, g5_shop_item b where (a.it_id = b.it_id) and b.it_use = 1 and a.mb_id = '{$member['mb_id']}' ";
$row = sql_fetch($sql);
$cheer_count = $row['cnt'];

// 구매내역
$sql = " select count(*) as cnt from {$g5['g5_shop_order_table']} where mb_id = '{$member['mb_id']}' ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];
?>
<style>

</style>

<!-- 마이페이지 시작 { -->
<section id="sub_mypage">
	<div class="container">
		<div class="row">
			<?php
			include_once(G5_THEME_PATH.'/mypage.left.php');
			?>
			<div class="col-md-9 col-sm-12 col-xs-12 my_con">
				<div class="mypage-class">
					<div class="mypage-class-menu">
						<ul>
							<li class="my_tab_btn" data-id="class"><a href="/shop/mypage.php#class">수강중인 강의</a></li>
							<li><a href="/shop/cart.php">장바구니</a></li>
							<li class="my_tab_btn" data-id="done"><a href="/shop/mypage.php#done">수강 완료 강의</a></li>
						</ul>
					</div>
					<!-- 수강중인 강의 -->
					<div class="mypage-class-list responsive" id="smb_my_class"></div>
					<!-- 수강중인 강의 end -->
				</div>
			</div>
		</div>
	</div>
</section>

<script>
var page_type = '<?php echo $type; ?>';
var page = '<?php echo $page; ?>';

function get_mypage_list(tab){
	$(".my_tab_btn a").removeClass("active");
	$("[data-id='"+tab+"']").find("a").addClass("active")

	var link_page = "/ajax/mypage_"+ tab +".php";

	$.ajax({
		type:"post",
		url: link_page,
		data: {
			page: page
		},
		success: function(data){
			$("#smb_my_class").html(data);
		}
	});
}

function member_leave()
{
    return confirm('정말 회원에서 탈퇴 하시겠습니까?')
}

$(function() {
    $(".win_coupon").click(function() {
        var new_win = window.open($(this).attr("href"), "win_coupon", "left=100,top=100,width=700, height=600, scrollbars=1");
        new_win.focus();
        return false;
    });

	$(".my_tab_btn").click(function(){
		location.hash = $(this).attr('data-id');
		get_mypage_list($(this).attr('data-id'));
	});

	if(location.hash){
		var my_hash = location.hash.replace('#', '');
		get_mypage_list(my_hash);
	}else if(page_type){
		location.hash = page_type;
		get_mypage_list(page_type);
	}else{
		get_mypage_list('class');
	}

	$(".win_coupon").click(function() {
        var new_win = window.open($(this).attr("href"), "win_coupon", "left=100,top=100,width=700, height=600, scrollbars=1");
        new_win.focus();
        return false;
    });
});

function member_leave()
{
    return confirm('정말 회원에서 탈퇴 하시겠습니까?')
}

function out_cd_check(fld, out_cd)
{
    if (out_cd == 'no'){
        alert("옵션이 있는 상품입니다.\n\n상품을 클릭하여 상품페이지에서 옵션을 선택한 후 주문하십시오.");
        fld.checked = false;
        return;
    }

    if (out_cd == 'tel_inq'){
        alert("이 상품은 전화로 문의해 주십시오.\n\n장바구니에 담아 구입하실 수 없습니다.");
        fld.checked = false;
        return;
    }
}

function fwishlist_check(f, act)
{
    var k = 0;
    var length = f.elements.length;

    for(i=0; i<length; i++) {
        if (f.elements[i].checked) {
            k++;
        }
    }

    if(k == 0)
    {
        alert("상품을 하나 이상 체크 하십시오");
        return false;
    }

    if (act == "direct_buy")
    {
        f.sw_direct.value = 1;
    }
    else
    {
        f.sw_direct.value = 0;
    }

    return true;
}
</script>
<!-- } 마이페이지 끝 -->

<?php
include_once("./_tail.php");
?>