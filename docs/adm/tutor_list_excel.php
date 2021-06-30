<?php
$sub_menu = '300150';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");






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

// 1.04.01
// MS엑셀 CSV 데이터로 다운로드 받음
if ($csv == 'csv')
{
    $fr_date = date_conv($fr_date);
    $to_date = date_conv($to_date);

	$sql_common = " from han_write_tutor ";
	$sql_search = " where (1) ";
	if ($stx) {
		$sql_search .= " and ( ";
		switch ($sfl) {
			case 'mb_id' : 
			case 'mb_name' : 
				$sql_search .= " ({$sfl} LIKE '%{$stx}%') ";
				break;
			case 'partner_account' :
			case 'client_account' :
				$sql_search .= " ({$sfl} = '{$stx}') ";
				break;
		  
			case 'memo' :
				echo "DB 작업 필요."; //LHH 2020-07-03 금
				//$sql_search .= " ({$sfl} like '%{$stx}%') ";
				break;

			default :
				$sql_search .= " ({$sfl} like '{$stx}%') ";
				break;
		}
		$sql_search .= " ) ";
	}


	if ($fr_date && $to_date) {
		$sql_search .= "AND reward_date between '{$fr_date} 00:00:00' and '{$to_date} 23:59:59'";
	}

	$sql = " select *
				{$sql_common}
				{$sql_search}
				order by reward_date DESC ";
	$result = sql_query($sql);
    $cnt = @sql_num_rows($result);
    if (!$cnt)
        alert("출력할 내역이 없습니다.");

    //header('Content-Type: text/x-csv');
    header("Content-charset=utf-8");
    header('Content-Type: doesn/matter');
    header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Content-Disposition: attachment; filename="han_write_tutor-' . date("ymd", time()) . '.csv"');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
			
	echo iconv('utf-8', 'euc-kr', "정산일,파트너명,파트너ID,파트너코드,고객코드,거래량LOTS,거래금액USD,거래횟수,거래유형\n");

	for ($i=0; $row=sql_fetch_array($result); $i++)
    {	
		$member_sql = "select * from g5_member where mb_1 = '{$row['partner_account']}'";
		$member_row = sql_fetch($member_sql);

		echo '"'.$row['reward_date']. '"'.',';
		echo '"'.iconv('utf-8', 'euc-kr', $member_row['mb_name']). '"'.',';
		echo '="'.$member_row['mb_id']. '"'.',';
		echo '"'.$row['partner_account']. '"'.',';
		echo '"'.$row['client_account']. '"'.',';
		echo '"'.$row['volume_lots']. '"'.',';
        echo '"'.$row['volume_usd']. '"'.',';
		echo '"'.$row['orders_count']. '"'.',';
		echo '"'.$row['client_account_type']. '"'.',';
		

        echo "\n";
    }
    if ($i == 0)
        echo '자료가 없습니다.'.PHP_EOL;

    exit;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// MS엑셀 XLS 데이터로 다운로드 받음
if ($csv == 'xls')
{
    $fr_date = date_conv($fr_date);
    $to_date = date_conv($to_date);

	$sql_common = " from han_write_tutor ";
	$sql_search = " where (1) ";
	if ($stx) {
		$sql_search .= " and ( ";
		switch ($sfl) {
			case 'mb_id' : 
			case 'mb_name' : 
				$sql_search .= " ({$sfl} LIKE '%{$stx}%') ";
				break;
			case 'partner_account' :
			case 'client_account' :
				$sql_search .= " ({$sfl} = '{$stx}') ";
				break;
		  
			case 'memo' :
				echo "DB 작업 필요."; //LHH 2020-07-03 금
				//$sql_search .= " ({$sfl} like '%{$stx}%') ";
				break;

			default :
				$sql_search .= " ({$sfl} like '{$stx}%') ";
				break;
		}
		$sql_search .= " ) ";
	}


	if ($fr_date && $to_date) {
		$sql_search .= "AND reward_date between '{$fr_date} 00:00:00' and '{$to_date} 23:59:59'";
	}

	$sql = " select *
				{$sql_common}
				{$sql_search}
				order by reward_date DESC ";
	$result = sql_query($sql);
    $cnt = @sql_num_rows($result);
    if (!$cnt)
        alert("출력할 내역이 없습니다.");

    /*================================================================================
    php_writeexcel http://www.bettina-attack.de/jonny/view.php/projects/php_writeexcel/
    =================================================================================*/

    include_once(G5_LIB_PATH.'/Excel/php_writeexcel/class.writeexcel_workbook.inc.php');
    include_once(G5_LIB_PATH.'/Excel/php_writeexcel/class.writeexcel_worksheet.inc.php');

    $fname = tempnam(G5_DATA_PATH, "tmp-han_write_tutor.xls");
    $workbook = new writeexcel_workbook($fname);
    $worksheet = $workbook->addworksheet();
	
	// 아이디 0 안잘리게 LHH 2020-07-27 월
	$telformat = $workbook->addformat(array(num_format => '\0#'));

    // Put Excel data
    $data = array('정산일', '파트너명', '파트너ID', '파트너코드', '고객코드', '거래량LOTS', '거래금액USD', '리워드USD', '거래횟수', '거래유형');
    $data = array_map('iconv_euckr', $data);

		


    $col = 0;
    foreach($data as $cell) {
        $worksheet->write(0, $col++, $cell);
    }

    $save_it_id = '';
    for($i=1; $row=sql_fetch_array($result); $i++)
    {

		$member_sql = "select * from g5_member where mb_1 = '{$row['partner_account']}'";
		$member_row = sql_fetch($member_sql);

       

        $worksheet->write($i, 0, ' '.$row['reward_date']);
        $worksheet->write($i, 1, iconv('utf-8', 'euc-kr', $member_row['mb_name']));
        $worksheet->write($i, 2, $member_row['mb_id'], $telformat);
        $worksheet->write($i, 3, $row['partner_account']);
        $worksheet->write($i, 4, $row['client_account']);
        $worksheet->write($i, 5, $row['volume_lots']);
        $worksheet->write($i, 6, $row['volume_usd']);
        $worksheet->write($i, 7, $row['reward_usd']);
        $worksheet->write($i, 8, $row['orders_count']);
        $worksheet->write($i, 9, $row['client_account_type']);
    }

    $workbook->close();

    header("Content-Type: application/x-msexcel; name=\"han_write_tutor-".date("ymd", time()).".xls\"");
    header("Content-Disposition: inline; filename=\"han_write_tutor-".date("ymd", time()).".xls\"");
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