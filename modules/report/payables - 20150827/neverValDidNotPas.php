<?php
session_start();
include "../../../adodb/adodb.inc.php";
include("../../../includes/db.inc.php");
include("../../../includes/common.php");
include("neverValDidNotPasObj.php");

$neverValDidNotPasObj = new neverValDidNotPasObj();

//$userName = $_SESSION['userName'];
//$passWord = $_SESSION['passWord'];
//$userAcess = $neverValDidNotPasObj->login($userName,$passWord);
//$userNotExist = $neverValDidNotPasObj->getRecCount($neverValDidNotPasObj->loginNotExist($userName,$passWord));

//Restriction
//if($userName == '' && $passWord == ''){
//header('Location: index.php');
//}

switch($_GET['action']){
	
	case 'Print':
		
		echo "window.open('neverValDidNotPas_xls.php?{$_SERVER['QUERY_STRING']}');";	
		//$neverValDidNotPasObj->dailyPaidProcessCsv($GET['txtDateFrom'],$GET['txtDateTo'],$GET['cmbOrgId']);
			
	exit();
	
	break;
		
}

$arrOrgId = array('0'=>"All",'85'=>"PPCI",'87'=>"JR",'133'=>"PUREGOLD SUBIC");
?>

<html>
	<head>
    	<!-- jQuery, Bootstrap -->
    	<link rel="stylesheet" href="../../../includes/bootstrap/css/bootstrap.css"/>
        <link rel="stylesheet" href="../../../includes/bootstrap/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="../../../includes/bootstrap/css/bootstrap-responsive.css"/>
        <link rel="stylesheet" href="../../../includes/bootstrap/css/bootstrap-responsive.min.css"/>
        <!-- jQuery, Bootstrap -->
        
		<link type="text/css" href="../../../includes/jquery/css/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
		<link type="text/css" href="../../../includes/jquery/development-bundle/demos/demos.css" rel="stylesheet" />
		
        <script src="../../../includes/jquery/js/jquery-1.6.2.min.js"></script>
        <script src="../../../includes/jquery/js/jquery-ui-1.8.16.custom.min.js"></script>
        <script src="../../../includes/bootbox/bootbox.js"></script>
        
        <script src="../../../includes/toastmessage/src/main/javascript/jquery.toastmessage.js"></script>
        <link rel="stylesheet" type="text/css" href="../../../includes/toastmessage/src/main/resources/css/jquery.toastmessage.css" />
        
        <script src="../../../includes/modal/modal.js"></script>
        <link rel="stylesheet" href="../../../includes/modal/modal.css">
        
        
        <link href="../../../includes/showLoading/css/showLoading.css" rel="stylesheet" media="screen" /> 
        <!--<script type="text/javascript" src="../../../includes/showLoading/js/jquery-1.3.2.min.js"></script>-->
        <script type="text/javascript" src="../../../includes/showLoading/js/jquery.showLoading.js"></script>
        
        <script type="text/javascript">
		
		function printCsv(){
		
			var dateStart = $('#txtDateFrom').val();
			var orgId = $('#cmbOrgId').val();
			
			if (valDateStart(dateStart,'txtDateFrom')){
				printDailyPaid(dateStart,orgId);
			}	
		}
		
		function printDailyPaid(dateStart,orgId) {
		
				$.ajax({
					url: 'neverValDidNotPas.php',
					type: "GET",
					data: $("#formInq").serialize()+'&action=Print',
					success: function(Data){
						eval(Data);
						$().toastmessage('showToast', {
							text: 'Exported to Excel!',
							sticky: false,
							position: 'middle-center',
							type: 'success',
							closeText: '',
							close: function () 
							{
							console.log("toast is closed ...");
							}
						});
					}				
				});	
		}
		
		$(function(){
			$('#txtDateFrom, #txtDateTo').datepicker({
				dateFormat : 'yy-mm-dd'
			});
		});
		
		function valDateStart(valStart,id1) {
			if (valStart !='') {
				$('#'+id1).removeClass('ui-state-error');
				return true;
			}else {
				$('#'+id1).addClass('ui-state-error');
				$().toastmessage('showToast', {
					text: 'Please Select Creation Date!',
					sticky: true,
					position: 'middle-center',
					type: 'error',
					closeText: '',
					close: function () 
					{
					console.log("toast is closed ...");
					}
				});
				return false;
			}
		}
		
		function valDateStartEnd(valStart,valEnd,id1,id2) {
			var parseStart = Date.parse(valStart);
			var parseEnd = Date.parse(valEnd);
			if (valStart !='' && valEnd !='') {
				if(parseStart > parseEnd) {
					$('#'+id1).addClass('ui-state-error');
					$('#'+id2).addClass('ui-state-error');
					$().toastmessage('showToast', {
						text: 'Date TO Must Be Greater than Date FROM!',
						sticky: true,
						position: 'middle-center',
						type: 'error',
						closeText: '',
						close: function () 
						{
						console.log("toast is closed ...");
						}
					});
					return false;
				} else {
					$('#'+id1).removeClass('ui-state-error');
					$('#'+id2).removeClass('ui-state-error');	
					return true;
				}
			}else {
				$('#'+id1).addClass('ui-state-error');
				$('#'+id2).addClass('ui-state-error');
				$().toastmessage('showToast', {
					text: 'Please Select Date Range!',
					sticky: true,
					position: 'middle-center',
					type: 'error',
					closeText: '',
					close: function () 
					{
					console.log("toast is closed ...");
					}
				});
				return false;
			}
		}
		
		function numericFilter(txb) {
		   txb.value = txb.value.replace(/[^0-9]/ig, "");
		}
		
		function makeBlank(){
		$('#tags').val('');
		$('#tagsid').val('');
		}
		
		$(function() {
			$( "#tags" ).autocomplete({
				source: function(request, response) {
					$.getJSON('neverValDidNotPas.php?action=GETAUTO', {
						term: request.term
					}, response);
				},
				
				select: function(event, ui) {
					var tagId = ui.item.id;
					
					$('#tags').val(tagId);
					$('#tagsid').val(tagId);
				}
			})
		});
		
		</script>
        
        <style type="text/css">
		.selectBox {
			width:205px;
		}
		
		.selectBox2 {
			width:495px;
		}
		
		<!--
		input,
		textarea,
		select {
			padding: 3px;
			font: 900 1em Verdana, Sans-serif;
			font-size:11px;
			color: #333;
			background:#eee;
			border: 1px solid #ccc;
			margin:0 0 0px 0;
			width:700px%;
		}
		input:focus,
		textarea:focus,
		select:focus {
			background: #fff;
			border: 1px solid #999;
		}
		
		input,
		textarea {
		  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		  -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		  -webkit-transition: border linear 0.2s, box-shadow linear 0.2s;
		  -moz-transition: border linear 0.2s, box-shadow linear 0.2s;
		  -ms-transition: border linear 0.2s, box-shadow linear 0.2s;
		  -o-transition: border linear 0.2s, box-shadow linear 0.2s;
		  transition: border linear 0.2s, box-shadow linear 0.2s;
		}
		input:focus,
		textarea:focus {
		  border-color: rgba(82, 168, 236, 0.8);
		  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(82, 168, 236, 0.6);
		  -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(82, 168, 236, 0.6);
		  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(82, 168, 236, 0.6);
		  outline: 0;
		  outline: thin dotted \9;
		  /* IE6-9 */
		}
		.dvContainer{
			margin-left:10px;
		}
		#input-userName{
			margin-left:50px;
		}
		
		
		a {
		  color: blue;
		  cursor:pointer;
			  text-decoration: underline;
		}

			div.instructions_container {
		   float: left;
			   width: 100%;

			}

		div#activity_pane {
			   float:left;
			   width: 100%;
			   height: 100%;
			   border: 1px solid #CCCCCC;
			   background-color:#FFF;
		   padding-top: 0px;
		   text-align: center;
			   
			}

			div.example_links 
			 .link_category {
			   margin-bottom: 15px;
			}

		.loading-indicator-bars {
			background-image: url('images/loading-bars.gif');
			width: 150px;
		}
		-->	
		</style>   
    </head>
    	<body>
			<div id="activity_pane">
			<br />
            <form name="formInq" id="formInq">
                <div class="dvContainer">
                	<table border=0 align="center">
						<th colspan="6">
                        	<h4 align="center"><font style="font-family:Lucida Handwriting"> Never Validated Did Not Pass </font></h4>
                        </th>
						<tr>
							<td colspan = "6"> </td>
						</tr>
						<tr>
							<!-- <td><font style="font-size:13px"> Trx Date From </font></td> -->
							<td><font style="font-size:13px"> Creation Date up to present </font></td>
							<td><font style="font-size:13px"> : </font></td>
							<td><input type="text" name="txtDateFrom" id="txtDateFrom" readonly="readonly" class="textBox" style="height:30px;"/></td>
						</tr>
						<tr>
							<td><font style="font-size:13px"> Company </font></td>
							<td><font style="font-size:13px"> : </font></td>
							<td><? $neverValDidNotPasObj->DropDownMenu($arrOrgId,'cmbOrgId','','class="selectBox ui-widget-content ui-corner-all"'); ?></td>
						</tr>
						<tr>
							<td colspan = "6"> </td>
						</tr>
						<tr>
							<td colspan = "6"> </td>
						</tr>
                        <tr>
                        	<td colspan="6" align="center">
                            	<input type="button" name="submit" class="btn btn-success" onClick="printCsv();" value="Export to Excel">
                            </td>
                        </tr>
                     </table>
                </div>
            </form>	
            
            <!--<img src="../../../includes/images/Spinner.gif">-->
  
			</div>
            <div style="clear:both;"></div>
			
        </body>
</html>