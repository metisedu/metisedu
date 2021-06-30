<?php
include_once('./_common.php');
define('_NOHEADER_',true);

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/orderinquiry.php');
    return;
}

define("_ORDERINQUIRY_", true);

$order_info = array();
$request_pwd = $od_pwd;
$od_pwd = get_encrypt_string($od_pwd);

// 회원인 경우
if ($is_member)
{
    $sql_common = " from {$g5['g5_shop_order_table']} where mb_id = '{$member['mb_id']}' ";
}
else if ($od_id && $od_pwd) // 비회원인 경우 주문서번호와 비밀번호가 넘어왔다면
{
    if( defined('G5_MYSQL_PASSWORD_LENGTH') && strlen($od_pwd) === G5_MYSQL_PASSWORD_LENGTH ) {
        $sql_common = " from {$g5['g5_shop_order_table']} where od_id = '$od_id' and od_pwd = '$od_pwd' ";
    } else {
        $sql_common = " from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";

        $order_info = get_shop_order_data($od_id);
        if (!check_password($request_pwd, $order_info['od_pwd'])) {
            run_event('password_is_wrong', 'shop', $order_info);
            alert('주문이 존재하지 않습니다.');
            exit;
        }

    }
}
else // 그렇지 않다면 로그인으로 가기
{
    goto_url(G5_BBS_URL.'/login.php?url='.urlencode(G5_SHOP_URL.'/orderinquiry.php'));
}

if($s_date && $e_date){
	$s_date = date("Y-m-d", strtotime($s_date));
	$e_date = date("Y-m-d", strtotime($e_date));
	$sql_common .= " AND od_status IN ('입금','완료') AND LEFT(od_time,10) >= '".date("Y-m-d", strtotime($s_date))."' AND LEFT(od_time,10) <= '".date("Y-m-d", strtotime($e_date))."' ";
}else{
	$s_date = date('Y-m-d', strtotime('-3 months', G5_SERVER_TIME));
	$e_date = date("Y-m-d");
}

$d1 = date_create($s_date);
$d2= date_create($e_date);
$interval= date_diff($d1, $d2);
$bt_mon = $interval->format('%m');

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

// 비회원 주문확인시 비회원의 모든 주문이 다 출력되는 오류 수정
// 조건에 맞는 주문서가 없다면
if ($total_count == 0)
{
    if (!$is_member) // 비회원일 경우는 이전 페이지로 이동
        alert('주문이 존재하지 않습니다.');
}

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

// 비회원 주문확인의 경우 바로 주문서 상세조회로 이동
if (!$is_member)
{
    if( defined('G5_MYSQL_PASSWORD_LENGTH') && strlen($od_pwd) === G5_MYSQL_PASSWORD_LENGTH ) {
        $sql = " select od_id, od_time, od_ip from {$g5['g5_shop_order_table']} where od_id = '$od_id' and od_pwd = '$od_pwd' ";
        $row = sql_fetch($sql);
    } else if( $order_info ){
        if (check_password($request_pwd, $order_info['od_pwd'])) {
            $row = $order_info;
        }
    }

    if ($row['od_id']) {
        $uid = md5($row['od_id'].$row['od_time'].$row['od_ip']);
        set_session('ss_orderview_uid', $uid);
        goto_url(G5_SHOP_URL.'/orderinquiryview.php?od_id='.$row['od_id'].'&amp;uid='.$uid);
    }
}

$g5['title'] = '결제내역';
include_once('./_head.php');
?>

<!-- 주문 내역 시작 { -->
<link rel="stylesheet" type="text/css" href="/css/datepicker3.css" />
<script type="text/javascript" src="/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/js/bootstrap-datepicker.kr.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<section id="sub_mypage">
	<div class="container">
		<div class="row">
			<?php
			include_once(G5_THEME_PATH.'/mypage.left.php');
			?>

			<?php
			$limit = " limit $from_record, $rows ";
			include "./orderinquiry.sub.php";
			?>

			<?php echo get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
		</div>
	</div>
<script>
$('.input_period').datepicker({
 autoclose: true,
 format: "yyyy.mm.dd",
 language: "kr"
});
</script>
</section>



<div id="sod_v">

</div>
<!-- } 주문 내역 끝 -->

<?php
include_once('./_tail.php');
?>
