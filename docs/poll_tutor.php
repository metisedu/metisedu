<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

//if($is_admin) goto_url(G5_ADMIN_URL);

include_once(G5_PATH.'/_head.php');


$qstr = "";


$sql = " SELECT count(*) AS cnt FROM han_write_tutor WHERE wr_3 = 'Y'";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 5;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함



$sql = "SELECT * FROM han_write_tutor WHERE wr_3 = 'Y' ORDER BY wr_id DESC LIMIT {$from_record}, {$rows}"; 
$res = sql_query($sql);
for($i=0; $row = sql_fetch_array($res); $i++){
	$tutor_data[$i] = $row;
	$tutor_data[$i]["per_good"] = @floor($row["wr_good"] / ( $row["wr_good"] + $row["wr_nogood"] ) * 100); // 소수올림
	$tutor_data[$i]["per_nogood"] = 100 - $tutor_data[$i]["per_good"];

	$tutor_data[$i]["good_href"] = G5_BBS_URL.'/good.php?bo_table=tutor&amp;wr_id='.$row["wr_id"].'&amp;good=good';
    $tutor_data[$i]["nogood_href"] = G5_BBS_URL.'/good.php?bo_table=tutor&amp;wr_id='.$row["wr_id"].'&amp;good=nogood';
}

?>

<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/xeicon@2.3.3/xeicon.min.css">

<section id="sub_poll">
	<div class="inner">
		<ul class="tutor">
			<?php
			for($i=0; $i<count($tutor_data); $i++){
				$thumb = get_list_thumbnail("tutor", $tutor_data[$i]['wr_id'], "360", "202", true, true);
				if($thumb['src']) {
				$img_content = '<img src="'.$thumb['src'].'" alt="'.$thumb['alt'].'" >';
				} else {
				$img_content = '<span class="no_image">no image</span>';
				}
				
			?>
			<li>
				<div class="thumb">
					<a href="<?php echo G5_BBS_URL;?>/board.php?bo_table=tutor&wr_id=<?php echo $tutor_data[$i]["wr_id"];?>"><?php echo $img_content;?></a>
				</div>
				<div class="info">
					<h4><?php echo $tutor_data[$i]["wr_subject"];?></h4>
					<p class="service"><?php echo cut_str($tutor_data[$i]["wr_content"],80,"...");?></p>
					<p class="career"><?php echo cut_str($tutor_data[$i]["wr_1"],80,"...");?></p>
					<table class="table">
						<tr class="tr_<?php echo $tutor_data[$i]["wr_id"];?>" data-tr_num="tr_<?php echo $tutor_data[$i]["wr_id"];?>">
							<td><button type="button" class="btn btn-blue" id="good_button" data-href="<?php echo $tutor_data[$i]["good_href"].'&amp;'.$qstr ?>"><i class="xi-thumbs-up"></i> <!-- 좋아요 -->like</button></td>
							<td>
								<div class="progress d-inline-block">
									
									<div class="progress-bar bg-blue good_bar bo_v_act_gng" role="progressbar" aria-valuenow="<?php echo $tutor_data[$i]["per_good"];?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $tutor_data[$i]["per_good"];?>%">
										<span class="good_per"><?php echo $tutor_data[$i]["per_good"];?></span>%
										<b id="bo_v_act_good"></b>
									</div>

									<div class="progress-bar bg-pink nogood_bar bo_v_act_gng" role="progressbar" aria-valuenow="<?php echo $tutor_data[$i]["per_nogood"];?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $tutor_data[$i]["per_nogood"];?>%">
										<span class="nogood_per"><?php echo $tutor_data[$i]["per_nogood"];?></span>%
										<b id="bo_v_act_nogood"></b>
									</div>

								</div>
							</td>
							<td><button type="button" class="btn btn-pink" id="nogood_button" data-href="<?php echo $tutor_data[$i]["nogood_href"].'&amp;'.$qstr ?>"><i class="xi-thumbs-down"></i> <!-- 싫어요 -->dislike</button></td>
						</tr>
					</table>
					<small class="deadline">투표마감일 <time><?php echo $tutor_data[$i]["wr_2"];?></time></small>
				</div>
				<div class="btn_more">
					<a href="<?php echo G5_BBS_URL;?>/board.php?bo_table=tutor&wr_id=<?php echo $tutor_data[$i]["wr_id"];?>" class="btn-orange"><!-- 상세보기 -->More view <i>&#8640;</i></a>
				</div>
			</li>
			<?php 
			}
			?>
			
		</ul>
	</div>
	<?php
	$qstr .= "&amp;page=";
	$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr");
	echo $pagelist;
	?>
</section>		

<?php
include_once(G5_PATH.'/_tail.php');
?>


<script>
var mb_address = '<?php echo $member["mb_address"]; ?>';
async function requestSumbit() {
    if (window.ethereum) {
        window.web3 = new Web3(window.ethereum);

        try {
            await window.ethereum.enable();
            var accounts = await window.web3.eth.getAccounts();

            if (accounts.length === 0) {
                alert("연동된 주소가 없습니다.");
            } else {
                console.log(accounts[0]);

                $.ajax({
                    url: "/shop/updateaddress.php",
                    async: true,
                    type: "POST",
                    data: {
                        address: accounts[0]
                    },
                    dataType: "json",
                    success: function (res) {
                        console.log(res);
                    }
                })

                alert("연동이 완료되었습니다.");
                window.location.reload();
            }
        } catch (e) {
        }
    } else {
        if (confirm("메타마스크를 찾을 수 없습니다. 메타마스크를 실행 또는 설치를 위해 이동하시겠습니까?")) {
            window.open("https://metamask.app.link/dapp/metis.tcpschool.com", "_blank");
        }
    }
}

$(function() {
  

    // 추천, 비추천
    $("#good_button, #nogood_button").click(function() {
        if (mb_address.length <= 10) {
            alert("리뷰를 작성하기 위해서는 메타마스크 연동이 필요합니다.");
            requestSumbit().then(console.log);
            return false;
        }

        var $tx;
        if(this.id == "good_button")
            $tx = $("#bo_v_act_good");
        else
            $tx = $("#bo_v_act_nogood");
		
		var tr_num = $(this).parents("tr").attr("data-tr_num");

        excute_good($(this).attr("data-href"), $(this), $tx, tr_num);
        return false;
    });

});

function excute_good(href, $el, $tx, tr_num)
{
	$.post(
        href,
        { js: "on" },
        function(data) {
			if(data.error) {
                alert(data.error);
                return false;
            }

            if(data.count) {
                $el.find("strong").text(number_format(String(data.count)));
				if($tx.attr("id").search("nogood") > -1) {
                    //$tx.text("이 글을 싫어요 하셨습니다.");
                    alert("이 글을 싫어요 하셨습니다.");
					console.log(data);
					console.log("."+tr_num + " .nogood_bar");
					//$tx.fadeIn(200).delay(2500).fadeOut(200);
					$("."+tr_num + " .nogood_bar").attr("aria-valuenow", data.count_per);
					$("."+tr_num + " .nogood_bar").css("width", data.count_per+"%");
					$("."+tr_num + " .nogood_per").text(data.count_per);
					$("."+tr_num + " .good_bar").attr("aria-valuenow", data.revers_count_per);
					$("."+tr_num + " .good_bar").css("width", data.revers_count_per+"%");
					$("."+tr_num + " .good_per").text(data.revers_count_per);
                } else {
                    //$tx.text("이 글을 좋아요 하셨습니다.");
                    alert("이 글을 좋아요 하셨습니다.");
                    //$tx.fadeIn(200).delay(2500).fadeOut(200);
					$("."+tr_num + " .good_bar").attr("aria-valuenow", data.count_per);
					$("."+tr_num + " .good_bar").css("width", data.count_per+"%");
					$("."+tr_num + " .good_per").text(data.count_per);
					$("."+tr_num + " .nogood_bar").attr("aria-valuenow", data.revers_count_per);
					$("."+tr_num + " .nogood_bar").css("width", data.revers_count_per+"%");
					$("."+tr_num + " .nogood_per").text(data.revers_count_per);
                }
            }
        }, "json"
    );
}
</script>