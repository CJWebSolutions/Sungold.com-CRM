<?php
// 2012-02-07 removed financial status
ob_start();
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";
include_once "htmlinclude/head.php";

$act = $_REQUEST["act"];
$limitList = $_REQUEST["limitList"];
$project_inf_id = $_REQUEST["project_inf_id"];


if($action_type=="delete") {
		$sql = "DELETE FROM project_information WHERE project_inf_id=".$project_inf_id;
		//echo $sql;
		pQuery($sql,"delete");
		echo "<script>location.href='project_info_list.php';</script>";
	}


?>
<script type="text/javascript">
function goSort() {
	var f = document.projectForm;
	f.act.value="sort";
	f.submit();
}

function searchNow() {
	var f = document.searchform;
	var sday = f.sday.value;
	var smonth = f.smonth.value;
	var syear = f.syear.value;

	var eday = f.eday.value;
	var emonth = f.emonth.value;
	var eyear = f.eyear.value;

	var start_date = syear + "-" + smonth + "-" + sday;
	var end_date = eyear + "-" + emonth + "-" + eday;

	if(start_date > end_date) {
		alert("Can't search!");
		return;
	}

	f.action="<?=$_SERVER['PHP_SELF']?>";
	f.submit();
}

$(function() {
	$("input:button, button").button();
	$(".list_table thead").addClass('ui-widget-header');
	$(".list_table tbody").addClass('ui-widget-content');
	$('.ui-widget-content').css({'background-image' :'none','background-color':'none'});
});
</script>
<BODY leftmargin=0 topmargin=0>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<!-- BODY TOP ------------------------------------------------------------------------------------------->
<tr>
<td style="padding-left:0px"><? include_once "top.php"; ?></td>
</tr>
<!-- BODY TOP END --------------------------------------------------------------------------------------->

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<br>
	<td valign="top" width="100" height="100%">
	<!-- LEFT -------------------------------------------------------------------------------------------------->
	<? //include_once "left.php"; ?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<!-- LEFT END ---------------------------------------------------------------------------------------------->
	</td>
	<!-- LEFT BG------------------------------------------------------------------------------------------------>
	<td width="3">
	</td>
	<!-- LEFT BG END-------------------------------------------------------------------------------------------->
	<td valign="top">
	<!-- BODY -------------------------------------------------------------------------------------------------->
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<!-- BODY CENTER ----------------------------------------------------------------------------------------->
		<tr>
			<td width="100%">			
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style="padding-left:15px" valign="bottom" height="24" colspan="2">					
				</td>
			</tr>	
			<tr>
				<td style="padding-left:15px" valign="top">


				<!-- CONTENTS -------------------------------------------------------------------------------------------->
				<table border="0" cellpadding="0" cellspacing="0" width="1300">
				<tr>
					<td valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="1300" class="font11_bold">
						<tr><td height="8"></td></tr>
						<tr><td width="252"><img src="images/icon_circle03.gif">&nbsp;<span style="height:21px">Project Information List</span></td></tr>
						<tr><td height="3"></td></tr>
						<tr><td background="images/bg_check.gif" height="3"></td></tr>
						<tr><td height="2"></td></tr>
					</table>
					</td>
				</tr>
			
				<tr>
					<td valign="top">
						<?php
							//Search Value
							$s_Cond = "";
							
							$project_id = $_REQUEST["project_id"];
							$builder_id = $_REQUEST["builder_id"];
							$sday = $_REQUEST["sday"];
							$smonth = $_REQUEST["smonth"];
							$syear = $_REQUEST["syear"];
							
							$eday = $_REQUEST["eday"];
							$emonth = $_REQUEST["emonth"];
							$eyear = $_REQUEST["eyear"];
							
							if($sday == "") {
								
								$sdate = date("d-m-Y" ,time() - (3600 * 24 * 365));
								
								$sday = substr($sdate, 0,2);
								$smonth = substr($sdate, 3,2);
								$syear = substr($sdate, 6,4);
								//echo $sday . "<br>";
								//echo $smonth . "<br>";
								//echo $syear . "<br>";
								
								$eday = substr($now_date, 0,2);
								$emonth = substr($now_date, 3,2);
								$eyear = substr($now_date, 6,4);
								$ehour = substr($now_time, 0,2);
								$emin = substr($now_time, 3,2);
							}	
								
								
							
							$start_date = $syear."-".$smonth."-".$sday;							
							$end_date = $eyear."-".$emonth."-".$eday;

							//$start_date = $sday."/".$smonth."/".$syear;
							//$end_date = $eday."/".$emonth."/".$eyear;
							
							$end_date = $end_date . " 23:59:59";
							
							$s_Cond .= " AND ( job_start_date >= '$start_date' AND job_start_date <= '$end_date') ";	
							 
							$srch_param = "srch_pbase=$srch_pbase&sday=$sday&smonth=$smonth&syear=$syear&eday=$eday&emonth=$emonth&eyear=$eyear";


							if($srch_project_status != "") {
								$s_Cond .= " AND project_status='" .$srch_project_status."' ";
								$srch_param .= "&srch_project_status=$srch_project_status";
							}
							
							$srch_opt = $_REQUEST["srch_opt"];
							$srch_opt_value = $_REQUEST["srch_opt_value"];
							
							if($srch_opt != "" && $srch_opt_value != "") {
								$s_Cond .= " AND $srch_opt like '%". $srch_opt_value ."%' ";
								$srch_param .= "&srch_opt=$srch_opt";
								$srch_param .= "&srch_opt_value=$srch_opt_value";
							}
							
							if($limitList) {
								$srch_param .="&limitList=$limitList";
								//$srch_param = urlencode($srch_param);
							}
							
							if($builder_id != "") {
								$s_Cond .= " AND builder_id ='". $builder_id ."'";
								$srch_param .= "&builder_id=$builder_id";
							}
							
							if($project_id != "") {
								$s_Cond .= " AND project_id ='". $project_id ."'";
								$srch_param .= "&project_id=$project_id";
							}
														
							
						?>
						<form name="searchform">
						<table border="0" cellpadding="0" cellspacing="0" height="40" valign="bottom" width="1300">							
							<tr class="ui-widget-header">
								<td width="200" height="30"  style="padding-left:5px">	Job Started
								</td>
								<td style="padding-left:5px">
						
								<select name="sday">
								<? for($i=1; $i < 32; $i++) { 
									if($i < 10) {
										$k = "0".$i;
									} else {$k=$i;}
									?>
								<option value="<?=$k?>" <?if($sday==$i) echo "selected";?>><?=$k?></option>
								<? } ?>

								</select>&nbsp;
								<select name="smonth">
								<? for($i=1; $i < 13; $i++) { 
									if($i < 10) {
										$k = "0".$i;
									} else {$k=$i;}
									?>
								<option value="<?=$k?>" <?if($smonth==$i) echo "selected";?>><?=$k?></option>
								<? } ?>
								</select>&nbsp;
								<? yearOption("syear",$syear); ?>
								~
								<select name="eday">
								<? for($i=1; $i < 32; $i++) { 
									if($i < 10) {
										$k = "0".$i;
									} else {$k=$i;}
									?>
								<option value="<?=$k?>" <?if($eday==$i) echo "selected";?>><?=$k?></option>
								<? } ?>

								</select>&nbsp;
								<select name="emonth">
								<?php
								 for($i=1; $i < 13; $i++) { 
									if($i < 10) {
										$k = "0".$i;
									} else {$k=$i;}
									?>
								<option value="<?=$k?>" <?if($emonth==$i) echo "selected";?>><?=$k?></option>
								<? } ?>
								</select>&nbsp;
								<? yearOption("eyear",$eyear); ?>
								</td>
							</tr>
							
							<tr class="ui-widget-header">
								<td height="30" style="padding-left:5px; width:200px;">	Project Name
								
								</td>
								<td style="padding-left:5px">
								<? getOption("project", $project_id); ?>
								</td>
							</tr>
							
							
							<tr class="ui-widget-header">
								<td height="30" style="padding-left:5px; width:200px;">	Builder Name
								
								</td>
								<td style="padding-left:5px">
								<? getOption("builder", $builder_id); ?>
								</td>
							</tr>
							
							
						<!--	<tr class="ui-widget-header">
								<td height="30" style="padding-left:5px; width:200px;">
								<select name="srch_opt" id="srch_opt">
									<option value='project_name' <?php if($srch_opt == 'project_name' ) echo " selected "; ?> >Project Name</option>
									<option value='builder_name' <?php if($srch_opt == 'builder_name' ) echo " selected "; ?>>Builder Name</option>
									
								</select>
								</td>
								<td style="padding-left:5px">
								<input type="text" name='srch_opt_value' id='srch_opt_value' value='<?php echo "$srch_opt_value";?>'> 
								</td>
							</tr>-->
							
							
							
							
							<tr><td colspan="2" background="images/bg_check02.gif" height="3"></td></tr>
								
							<tr>
							<td colspan="2" align="right" height="30">
							<span style="float:left;">Items per page:<select name="limitList" id="limitList" onchange="searchNow();">
						<option value="300" <?php if($limitList == 300) echo "selected";?> >300</option>
						<option value="600" <?php if($limitList == 600) echo "selected";?> >600</option>
						<option value="900" <?php if($limitList == 900) echo "selected";?> >900</option>
						<option value="999999" <?php if($limitList == 999999) echo "selected";?> >All</option>
						</select>
						
						<input type="hidden" name="list_view" id="list_view" value="<?php echo $list_view;?>" />
						</span>	
						<input type="button" value="New Entry" onclick="location.href='project_information_regist.php?'" />
								<input type="button" Value="Search" onclick="searchNow()"></td></tr>
						</table>
						</form>
						<br>
						<form name="projectForm" method="POST" action="<?=$_SERVER["PHP_SELF"]?>">
						<input type="hidden" name="act" value="<?=$act?>">
						<table border="1" width="1300" cellpadding="0" cellspacing="1" class="list_table">
						<thead>
						<tr align="center" >
							<th width="30">No.</th>
							<th width="200">Project Name</th>
							<th width="200">Builder Name</th>
							<th width="130" class="">Tile Detail</th>
							<th width="130" class="">Glue Detail</th>
							<th width="130">Grout Detail</th>
							<th width="130">Silicon Detail</th>
							<th width="100">Site Manager</th>
							<th width="100">Contact No.</th>
							<th width="85">Job Start<br>Date</th>
							<th width="85">Job Finish<br>Date</th>
							<th width="85">Modify</th>
						</thead>
						<tbody>
						<?php
							// 페이지 계산 ///////////////////////////////
							$page = $_REQUEST["page"];
							if(!$page)
								$page = 1;
						
							$limitPage = 10;
							
							if (!$_REQUEST["limitList"]) {
								$limitList = 300;
							} else
								$limitList = $_REQUEST["limitList"];
							
							$total = getRowCount2("SELECT COUNT(*) FROM project_information WHERE 1=1 ". $s_Cond);
							//echo ceil(1.2);
							$totalPage = ceil($total/$limitList);
							$block = ceil($page/$limitPage);
							$start = ($page-1)*$limitList;

							$startPage = ($block-1)*$limitPage + 1;
							$endPage = $startPage + $limitPage - 1;
							if ($endPage > $totalPage ) $endPage = $totalPage; 
							// 페이지 계산 끝/////////////////////////////////////////////////

							## 쿼리, 담을 배열 선언
							$list_Records = array();
							
							
							$Query  = "SELECT * ";
							$Query .= " FROM project_information WHERE 1=1 " . $s_Cond . "ORDER BY project_inf_id DESC LIMIT $start, $limitList";

							$id_cnn = mysql_query($Query) or exit(mysql_error());
							while($id_rst = mysql_fetch_assoc($id_cnn)) {
								$list_Records = array_merge($list_Records, array($id_rst));
								//print_r($list_Records);
								//echo "<p>";
							}
							//echo count($list_Records);
							if(count($list_Records)) {
							for($i=0; $i<count($list_Records); $i++) {

								/*
								$f_status = 0;

								$Query = "SELECT sum(orders_inventory * IF(material_id <> '0' and material_id <> '',material_price,IF(orders_tax = 'N', material_price,material_price/1.1)) ) FROM orders WHERE (orders_number = ' ' or (orders_number  <> '' and material_id = 0) ) and project_inf_id = '".$list_Records[$i]["project_inf_id"]."'";
								$result =  mysql_query($Query) or exit(mysql_error());
								
								while($rows = mysql_fetch_row($result)) {
									if ($rows[0] != NULL) 					
										$f_status = $rows[0];
								}
								*/
								$bgcolor = "";
								
								if($i%2 == 0){
									$even_odd = ' class="even" ';
								} else
									$even_odd = ' class="odd" ';
						?>
						<tr align="center" <?php echo $even_odd;?> onmouseover="javascript:this.style.color='#536499';this.style.backgroundColor='#dcebfe';"  onmouseout="javascript:this.style.color='#000000';this.style.backgroundColor='';">
							<input type="hidden" value="<?=$list_Records[$i]["project_inf_id"]?>" name="project_inf_id[]">
							<td class="center"><?=($i+1)?>&nbsp;</td>
							<td class="center"><?php echo getName("project",$list_Records[$i]["project_id"]); ?>&nbsp;</td>
							<td class="center"><b><?php echo getName("builder",$list_Records[$i]["builder_id"]); ?></td>
				
							<td class="left"><strong>Floor:</strong>&nbsp;<?=($list_Records[$i]["glue_detail_floor"])?>&nbsp;<br>
							<strong>Wall:</strong>&nbsp;&nbsp;
							<?=($list_Records[$i]["tile_detail_wall"])?>
							</td>
							
							<td class="left"><strong>Floor:</strong>&nbsp;<?=($list_Records[$i]["tile_detail_floor"])?>&nbsp;<br>
							<strong>Wall:</strong>&nbsp;&nbsp;
							<?=($list_Records[$i]["glue_detail_wall"])?>
							</td>
							
							<td class="left"><strong>Floor:</strong>&nbsp;<?=($list_Records[$i]["grout_detail_floor"])?>&nbsp;<br>
							<strong>Wall:</strong>&nbsp;&nbsp;
							<?=($list_Records[$i]["grout_detail_wall"])?>
							</td>	
							
							<td class="left"><strong>Floor:</strong>&nbsp;<?=($list_Records[$i]["silicon_detail_floor"])?>&nbsp;<br>
							<strong>Wall:</strong>&nbsp;&nbsp;
							<?=($list_Records[$i]["silicon_detail_wall"])?>
							</td>	
							
							<td class="center"><?=($list_Records[$i]["sm_name"])?>&nbsp;</td>
							<td class="center"><?=($list_Records[$i]["sm_contact_no"])?>&nbsp;</td>
							
							<td class="center"><?=getAUDate($list_Records[$i]["job_start_date"])?></td>
							<td class="center"><?=getAUDate($list_Records[$i]["job_finish_date"])?></td>
							
							
							
							<td ><a href="project_information_regist.php?project_inf_id= <?=$list_Records[$i]["project_inf_id"] ?>&action_type=modify">[EDIT]</a></td>
						
						</tr>
						<?php
							}
							} else {
								echo "<tr><td colspan=12 height=40 align=center>Nothing to display</td></tr>";
							}
						?>
						</tbody>
						</table>
						<br>
						<table border="0" cellpadding="0" cellspacing="0" width="1000">
						<tr><td></td><td align="right"><input type="button" value="New Entry" onclick="location.href='project_information_regist.php?'"></td></tr>
						</table>
						</form>
						<br>
						
					</td>
				</tr>
				<tr><td align="center"><?php include_once "paging.php"?></td></tr>
				</table>
				<!-- CONTENTS END -------------------------------------------------------------------------------------------->
			</td>
			<td>
			</td>
		  </tr>
		  <tr><td colspan="2" height="20"></td></tr>
		 </table>
		 </td>
	    </tr>
	    <!-- BODY CENTER END------------------------------------------------------------------------------------->
	   </table>
	<!-- BODY END -------------------------------------------------------------------------------------------->
	</td>
</tr>
<tr>
	<td colspan="3">
	<!-- BOTTOM -------------------------------------------------------------------------------------------->
	<? include_once "bottom.php"; ?>
	<!-- BOTTOM END -------------------------------------------------------------------------------------------->
	</td>
</tr>
</table>
</BODY>
</HTML>
<?php ob_flush(); ?>