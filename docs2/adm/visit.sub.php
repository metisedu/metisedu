<?php
if (!defined('_GNUBOARD_')) exit;

include_once(G5_LIB_PATH.'/visit.lib.php');
include_once('./admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

if (empty($fr_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = G5_TIME_YMD;
if (empty($to_date) || ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = G5_TIME_YMD;

$qstr = "fr_date=".$fr_date."&amp;to_date=".$to_date;
$query_string = $qstr ? '?'.$qstr : '';


?>
<div class="card card-inverse card-flat">

	<div class="card-block">
		<div class="row">
			<div class="col-md-12">


<form name="fvisit" id="fvisit" class="local_sch03 local_sch" method="get">
<div class="sch_last">
    <strong>기간별검색</strong>
    <input type="text" name="fr_date" value="<?php echo $fr_date ?>" id="fr_date" class="frm_input" size="11" maxlength="10">
    <label for="fr_date" class="sound_only">시작일</label>
    ~
    <input type="text" name="to_date" value="<?php echo $to_date ?>" id="to_date" class="frm_input" size="11" maxlength="10">
    <label for="to_date" class="sound_only">종료일</label>
    <input type="submit" value="검색" class="btn_submit">
</div>
</form>
<?php
/*
<ul class="nav nav-tabs bg-warning-a800">
    <li class="nav-item"><a href="./visit_list.php<?php echo $query_string ?>" class="nav-link<?php echo $_SERVER['PHP_SELF'] == '/adm/visit_list.php'?' active':''?>">접속자</a></li>
    <li class="nav-item"><a href="./visit_domain.php<?php echo $query_string ?>" class="nav-link<?php echo $_SERVER['PHP_SELF'] == '/adm/visit_domain.php'?' active':''?>">도메인</a></li>
    <li class="nav-item"><a href="./visit_browser.php<?php echo $query_string ?>" class="nav-link<?php echo $_SERVER['PHP_SELF'] == '/adm/visit_browser.php'?' active':''?>">브라우저</a></li>
    <li class="nav-item"><a href="./visit_os.php<?php echo $query_string ?>" class="nav-link<?php echo $_SERVER['PHP_SELF'] == '/adm/visit_os.php'?' active':''?>">운영체제</a></li>
    <?php if(version_compare(phpversion(), '5.3.0', '>=') && defined('G5_BROWSCAP_USE') && G5_BROWSCAP_USE) { ?>
    <li class="nav-item"><a href="./visit_device.php<?php echo $query_string ?>" class="nav-link<?php echo $_SERVER['PHP_SELF'] == '/adm/visit_device.php'?' active':''?>">접속기기</a></li>
    <?php } ?>
    <li class="nav-item"><a href="./visit_hour.php<?php echo $query_string ?>" class="nav-link<?php echo $_SERVER['PHP_SELF'] == '/adm/visit_hour.php'?' active':''?>">시간</a></li>
    <li class="nav-item"><a href="./visit_week.php<?php echo $query_string ?>" class="nav-link<?php echo $_SERVER['PHP_SELF'] == '/adm/visit_week.php'?' active':''?>">요일</a></li>
    <li class="nav-item"><a href="./visit_date.php<?php echo $query_string ?>" class="nav-link<?php echo $_SERVER['PHP_SELF'] == '/adm/visit_date.php'?' active':''?>">일</a></li>
    <li class="nav-item"><a href="./visit_month.php<?php echo $query_string ?>" class="nav-link<?php echo $_SERVER['PHP_SELF'] == '/adm/visit_month.php'?' active':''?>">월</a></li>
    <li class="nav-item"><a href="./visit_year.php<?php echo $query_string ?>" class="nav-link<?php echo $_SERVER['PHP_SELF'] == '/adm/visit_year.php'?' active':''?>">년</a></li>
</ul>
*/
?>
<script>
$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});

function fvisit_submit(act)
{
    var f = document.fvisit;
    f.action = act;
    f.submit();
}
</script>
