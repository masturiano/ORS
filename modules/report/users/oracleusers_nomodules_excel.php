<?php
//initialize session
session_start();
//end initialize session

//avoid error
error_reporting(1);
//end avoid error message
//include
ini_set("memory_limit","1g");
ini_set('include_path','C:\wamp\php\PEAR');
//include("Obj.php");
include "adodb/adodb.inc.php";
require_once('Spreadsheet/Excel/Writer.php');
//end include


$workbook = new Spreadsheet_Excel_Writer();
$workbook->send("List_of_Oracle_users.xls");

$worksheet = $workbook->addWorksheet('Oracle users');
$headerFormat = $workbook->addFormat(array('Size' => 11,
										  'fgColor' => 'white',
										  'Color' => 'black',
										  'bold'=> 1,
										  'Align' => 'left'));
$headerFormat->setFontFamily('Calibri'); 

$headerFormat1 = $workbook->addFormat(array('Size' => 11,
										  'fgColor' => 'white',
										  'Color' => 'black',
										  'bold'=> 1,
										  'border' => 2,
										  'Align' => 'left'));
$headerFormat1->setFontFamily('Calibri'); 


$worksheet->setColumn(0,0,25);
$worksheet->setColumn(2,0,25);
$worksheet->setColumn(2,1,35);
$worksheet->setColumn(2,2,15);
$worksheet->setColumn(2,3,15);
$worksheet->setColumn(2,4,35);
$worksheet->setColumn(2,5,35);


//$worksheet->write(0, 0, 'PPCI',$headerFormat);
$worksheet->write(0, 0, 'List Of Oracle Users',$headerFormat1);
$worksheet->write(2, 0, 'User ID',$headerFormat1);
$worksheet->write(2, 1, 'Description',$headerFormat1);
$worksheet->write(2, 2, 'User Name',$headerFormat1);
$worksheet->write(2, 3, 'Start Date',$headerFormat1);
$worksheet->write(2, 4, 'End Date',$headerFormat1);
$worksheet->write(2, 5, 'Last Logon Date',$headerFormat1);
$worksheet->write(2, 6, 'Email',$headerFormat1);
$worksheet->write(2, 7, 'Fax',$headerFormat1);


$db = NewADOConnection("oci8");
$host = "192.168.200.136"; // PROD
//$host = "192.168.200.135"; // NEW UAT
$port = "1521"; // PROD
//$port = "1532"; // NEW UAT
$sid = "PROD"; // PROD
//$sid = "NPROD"; // NEW UAT
$cstr = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$port))
			(CONNECT_DATA=(SID=$sid)))";

$orauserqry = "SELECT fnd_user.USER_ID, fnd_user.USER_NAME, fnd_user.START_DATE, fnd_user.END_DATE, fnd_user.DESCRIPTION,fnd_user.LAST_LOGON_DATE,fnd_user.EMAIL_ADDRESS,fnd_user.FAX
FROM fnd_user 
where fnd_user.user_id >= 1000
AND fnd_user.END_DATE IS NULL
GROUP BY fnd_user.USER_ID, fnd_user.USER_NAME, fnd_user.START_DATE, fnd_user.END_DATE, fnd_user.DESCRIPTION,fnd_user.LAST_LOGON_DATE,fnd_user.EMAIL_ADDRESS,fnd_user.FAX
order by fnd_user.user_id";

//Never validated (Did not Pass on Validation Process)
if($db->Connect($cstr, 'apps', 'apps')){			
		$rs = $db->Execute("{$orauserqry}");
		
		
}
else{
	//echo "$('#confirmDialogs').dialog('close');";
	//echo "$('#confirmDialogs').dialog('destroy');";
	echo "'Failed to connect to Oracle Server/Database!'";	
		
}

$db->Disconnect();

$ctr = 3;

$olduserid = "";

foreach($rs as $val)
{
	/*
	if($olduserid == $rs->fields['USER_ID'])
	{
		$worksheet->write($ctr, 0, '',$headerFormat);
		$worksheet->write($ctr, 1, '',$headerFormat);
		$worksheet->write($ctr, 5, '',$headerFormat);
	}
	else
	{*/
		$worksheet->write($ctr, 0, $rs->fields['USER_ID'],$headerFormat1);
		$worksheet->write($ctr, 1, $rs->fields['DESCRIPTION'],$headerFormat1);
		$worksheet->write($ctr, 2, $rs->fields['USER_NAME'],$headerFormat1);
	/*}*/
		$worksheet->write($ctr, 3, $rs->fields['START_DATE'],$headerFormat1);
		$worksheet->write($ctr, 4, $rs->fields['END_DATE'],$headerFormat1);
		$worksheet->write($ctr, 5, $rs->fields['LAST_LOGON_DATE'],$headerFormat1);
		$worksheet->write($ctr, 6, $rs->fields['EMAIL_ADDRESS'],$headerFormat1);
		$worksheet->write($ctr, 7, $rs->fields['FAX'],$headerFormat1);
	$olduserid = $rs->fields['USER_ID'];	
		$ctr++;
}
$workbook->close();

?>

		