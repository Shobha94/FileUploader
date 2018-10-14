<?php

	require_once("../includes/start.php");
	require_once("../includes/config.php");
	require_once("../includes/classes/VDatabase.php");
	require_once("../includes/vutils.php");
	
	
	$array_result = array();
	$db = new VDatabase(true);
	
	$wherecond = "";
	
	if(isset($_REQUEST['whereCondition']))
	{	
		$wherecond = $_REQUEST['whereCondition'];
		
	}
	
	$select_query = " SELECT * FROM  contract_files $wherecond ";  
	$rows = $db->getRows($select_query);
	 
	$count=0;
	
	foreach($rows as $row)
	{
		$count++;
		$array_result[]=array(
		'File_Id' => $row['File_Id'],
		'Contract_Type' => $row['Contract_Type'], 
		'File_Name' => $row['File_Name'],
		'Party' => $row['Party'],
		'Date' => $row['Date']
		);
	}
	if($count)
	{
		echo json_encode($array_result, JSON_UNESCAPED_SLASHES);
	}
	else
	{
		
		echo json_encode("No  Found");
	}

  

?>