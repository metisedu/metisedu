<?php
include_once('./_common.php');
$where = array();

$doc = strip_tags($doc);
$sort1 = in_array($sort1, array('od_id', 'od_cart_price', 'od_receipt_price', 'od_cancel_price', 'od_misu', 'od_cash', 'od_item_names')) ? $sort1 : '';
$sort2 = in_array($sort2, array('desc', 'asc')) ? $sort2 : 'desc';
$sel_field = get_search_string($sel_field);
if( !in_array($sel_field, array('od_id', 'mb_id', 'od_name', 'od_tel', 'od_hp', 'od_b_name', 'od_b_tel', 'od_b_hp', 'od_deposit_name', 'od_invoice', 'od_item_names')) ){   //검색할 필드 대상이 아니면 값을 제거
    $sel_field = '';
}
$od_status = get_search_string($od_status);
$search = get_search_string($search);
if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = '';
if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = '';

$od_misu = preg_replace('/[^0-9a-z]/i', '', $od_misu);
$od_cancel_price = preg_replace('/[^0-9a-z]/i', '', $od_cancel_price);
$od_refund_price = preg_replace('/[^0-9a-z]/i', '', $od_refund_price);
$od_receipt_point = preg_replace('/[^0-9a-z]/i', '', $od_receipt_point);
$od_coupon = preg_replace('/[^0-9a-z]/i', '', $od_coupon);

$sql_search = "";
if ($search != "") {
    if ($sel_field != "") {
        $where[] = " $sel_field like '%$search%' ";
    }

    if ($save_search != $search) {
        $page = 1;
    }
}

if ($od_status) {
    switch($od_status) {
        case '전체취소':
            $where[] = " od_status = '취소' ";
            break;
        case '부분취소':
            $where[] = " od_status IN('주문', '입금', '준비', '배송', '완료') and od_cancel_price > 0 ";
            break;
        default:
            $where[] = " od_status = '$od_status' ";
            break;
    }

    switch ($od_status) {
        case '주문' :
            $sort1 = "od_id";
            $sort2 = "desc";
            break;
        case '입금' :   // 결제완료
            $sort1 = "od_receipt_time";
            $sort2 = "desc";
            break;
        case '배송' :   // 배송중
            $sort1 = "od_invoice_time";
            $sort2 = "desc";
            break;
    }
}

if ($od_settle_case) {
    $where[] = " od_settle_case = '$od_settle_case' ";
}

if ($od_misu) {
    $where[] = " od_misu != 0 ";
}

if ($od_cancel_price) {
    $where[] = " od_cancel_price != 0 ";
}

if ($od_refund_price) {
    $where[] = " od_refund_price != 0 ";
}

if ($od_receipt_point) {
    $where[] = " od_receipt_point != 0 ";
}

if ($od_coupon) {
    $where[] = " ( od_cart_coupon > 0 or od_coupon > 0 or od_send_coupon > 0 ) ";
}

if ($od_escrow) {
    $where[] = " od_escrow = 1 ";
}

if ($fr_date && $to_date) {
    $where[] = " od_time between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
}

if ($where) {
    $sql_search = ' where '.implode(' and ', $where);
}

if ($sel_field == "")  $sel_field = "od_id";
if ($sort1 == "") $sort1 = "od_id";
if ($sort2 == "") $sort2 = "desc";

$sql_common = " from {$g5['g5_shop_order_table']} $sql_search ";

$sql = " select count(od_id) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql  = " select *,
            (od_cart_coupon + od_coupon + od_send_coupon) as couponprice
           $sql_common
           order by $sort1 $sort2";
$result = sql_query($sql);

while($row = sql_fetch_array($result)){
	$list[] = $row;
}




function conv_telno($t)
{
    // 숫자만 있고 0으로 시작하는 전화번호
    if (!preg_match("/[^0-9]/", $t) && preg_match("/^0/", $t))  {
        if (preg_match("/^01/", $t)) {
            $t = preg_replace("/([0-9]{3})(.*)([0-9]{4})/", "\\1-\\2-\\3", $t);
        } else if (preg_match("/^02/", $t)) {
            $t = preg_replace("/([0-9]{2})(.*)([0-9]{4})/", "\\1-\\2-\\3", $t);
        } else {
            $t = preg_replace("/([0-9]{3})(.*)([0-9]{4})/", "\\1-\\2-\\3", $t);
        }
    }

    return $t;
}



/*
echo count($list);
echo "<pre>";
print_r($list);
echo "</pre>";
exit;
*/
// 1.04.01
// MS엑셀 CSV 데이터로 다운로드 받음
if(!$csv){
	$csv == "csv";
}

if ($csv == 'csv')
{
    
    $cnt = count($list);
    if (!$cnt)
        alert("출력할 내역이 없습니다.");

    //header('Content-Type: text/x-csv');

	header("Content-charset=utf-8");
    header('Content-Type: doesn/matter');
    header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Content-Disposition: attachment; filename="order_list-' . date("ymd", time()) . '.csv"');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');

	echo iconv('utf-8', 'euc-kr', "주문번호,주문상태,상품명,주문자,회원ID,주문합계 선불배송비 포함,입금합계,거래유형,미수금\n");

	for ($i=0; $i<count($list); $i++)
    {	
		
		echo '="'.$list[$i]['od_id']. '"'.',';
		echo '"'.iconv('utf-8', 'euc-kr', $list[$i]['od_status']). '"'.',';
		echo '"'.iconv('utf-8', 'euc-kr', str_replace("|",",",$list[$i]['od_item_names'])). '"'.',';
		echo '"'.iconv('utf-8', 'euc-kr', $list[$i]['od_name']). '"'.',';		
		echo '="'.$list[$i]['mb_id']. '"'.',';
		echo '="'.($list[$i]['od_cart_price'] + $list[$i]['od_send_cost'] + $list[$i]['od_send_cost2']). '"'.',';
		echo '="'.($list[$i]['od_receipt_price']). '"'.',';
		echo '"'.iconv('utf-8', 'euc-kr', $list[$i]['od_settle_case']). '"'.',';
		echo '"'.$list[$i]['od_misu']. '"'.',';		

        echo "\n";
    }
    if ($i == 0)
        echo '자료가 없습니다.'.PHP_EOL;

    exit;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// MS엑셀 XLS 데이터로 다운로드 받음
if ($csv == 'xls')
{
    $cnt = count($list);
    if (!$cnt)
        alert("출력할 내역이 없습니다.");

    /*================================================================================
    php_writeexcel http://www.bettina-attack.de/jonny/view.php/projects/php_writeexcel/
    =================================================================================*/

    include_once(G5_LIB_PATH.'/Excel/php_writeexcel/class.writeexcel_workbook.inc.php');
    include_once(G5_LIB_PATH.'/Excel/php_writeexcel/class.writeexcel_worksheet.inc.php');

    $fname = tempnam(G5_DATA_PATH, "tmp-order_list.xls");
    $workbook = new writeexcel_workbook($fname);
    $worksheet = $workbook->addworksheet();
	
	// 아이디 0 안잘리게 LHH 2020-07-27 월
	$telformat = $workbook->addformat(array(num_format => '\0#'));

    // Put Excel data
    $data = array("주문번호", "주문상태", "상품명", "주문자","회원ID", "주문합계 선불배송비 포함", "입금합계", "거래유형", "미수금");
    $data = array_map('iconv_euckr', $data);
	

	/*********************************************************************************
	echo iconv('utf-8', 'euc-kr', "주문번호,주문상태,주문자,회원ID,주문합계 선불배송비 포함,거래유형,미수금\n");

	for ($i=0; $i<count($list); $i++)
    {	

		echo '="'.$list[$i]['od_id']. '"'.',';
		echo '"'.iconv('utf-8', 'euc-kr', $list[$i]['od_status']). '"'.',';
		echo '"'.iconv('utf-8', 'euc-kr', $list[$i]['od_name']). '"'.',';
		echo '="'.$list[$i]['mb_id']. '"'.',';
		echo '="'.($list[$i]['od_cart_price'] + $list[$i]['od_send_cost'] + $list[$i]['od_send_cost2']). '"'.',';
		echo '"'.iconv('utf-8', 'euc-kr', $list[$i]['od_settle_case']). '"'.',';
		echo '"'.$list[$i]['od_misu']. '"'.',';		

        echo "\n";
    }
	************************************************************************************/

    $col = 0;
    foreach($data as $cell) {
        $worksheet->write(0, $col++, $cell);
    }
	
    $save_it_id = '';
    for ($i=0; $i<count($list); $i++)
    {


        $worksheet->write(($i+1), 0, ' '.$list[$i]['od_id']);
        $worksheet->write(($i+1), 1, iconv('utf-8', 'euc-kr', $list[$i]['od_status']));
        $worksheet->write(($i+1), 2, iconv('utf-8', 'euc-kr', str_replace("|",",",$list[$i]['od_item_names'])));
		$worksheet->write(($i+1), 3, iconv('utf-8', 'euc-kr', $list[$i]['od_name']));        
        $worksheet->write(($i+1), 4, $list[$i]['mb_id'], $telformat);
        $worksheet->write(($i+1), 5, ($list[$i]['od_cart_price'] + $list[$i]['od_send_cost'] + $list[$i]['od_send_cost2']));
        $worksheet->write(($i+1), 6, ($list[$i]['od_receipt_price']));
        $worksheet->write(($i+1), 7, iconv('utf-8', 'euc-kr', $list[$i]['od_settle_case']));
        $worksheet->write(($i+1), 8, $list[$i]['od_misu']);
        
    }

    $workbook->close();

    header("Content-Type: application/x-msexcel; name=\"order_list-".date("ymd", time()).".xls\"");
    header("Content-Disposition: inline; filename=\"order_list-".date("ymd", time()).".xls\"");
    $fh=fopen($fname, "rb");
    fpassthru($fh);
    unlink($fname);

    exit;
}

function get_order($od_id)
{
    global $g5;

    $sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
    return sql_fetch($sql);
}


?>