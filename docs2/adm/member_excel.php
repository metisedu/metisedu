<?php
include_once("./_common.php");
include_once(G5_LIB_PATH.'/PHPExcel.php');

if (!$is_admin =="super")
  alert_close("최고 관리자 영역 입니다.");

function column_char($i) { return chr( 65 + $i ); }

$headers = array('번호', '아이디', '이름', '레벨', '가입일', 'Email', '연락처', '회사명', '부서명', '직급', '인지경로', '최근로그인', '마케팅 선택여부');
$widths  = array(18, 15, 15, 15, 15, 15, 15, 50, 20, 20, 20, 20, 20, 20, 20, 20, 100);
$header_bgcolor = 'FFABCDEF';
$last_char = column_char(count($headers) - 1);


$sql    = " select * from han_member where mb_leave_date = '' order by mb_datetime desc ";
$result = sql_query($sql);
for($i=1; $row=sql_fetch_array($result); $i++) {

   if ($row['mb_sex'] =="M") { $mb_sex ="남자"; } else if ($row['mb_sex'] =="F"){ $mb_sex ="여자"; }
   if ($row['mb_mailling'] =="1") { $mb_mailling ="받음"; } else if ($row['mb_mailling'] =="0"){ $mb_mailling ="안받음"; }
   if ($row['mb_open'] =="1") { $mb_open  ="공개"; } else if ($row['mb_open'] =="0"){ $mb_open ="비공개"; }

   if ($row['mb_marketing'] =="1") { $mb_marketing  ="동의"; } else if ($row['mb_marketing'] =="0"){ $mb_marketing ="비동의"; }
    $rows[] =
             array(
				$i,
				$row[mb_id],
				$row[mb_name],
				$row[mb_level],
				$row[mb_datetime],
				$row[mb_email],
				$row[mb_tel],
				$row[mb_company],
				$row[mb_partname],
				$row[mb_position],
				$row[mb_route],
				$row[mb_today_login],
				$mb_marketing
             );
}

$data = array_merge(array($headers), $rows);

$excel = new PHPExcel();
$excel->setActiveSheetIndex(0)->getStyle( "A1:${last_char}1" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
$excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
foreach($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( column_char($i) )->setWidth($w);
$excel->getActiveSheet()->fromArray($data,NULL,'A1');

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"members-".date("ymd", time()).".xls\"");
header("Cache-Control: max-age=0");

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
$writer->save('php://output');
?>