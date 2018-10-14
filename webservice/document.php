<?php

	require_once("../includes/start.php");
	require_once("../includes/config.php");
	require_once("../includes/classes/VDatabase.php");
	require_once("../includes/vutils.php");
	require_once("../includes/validations.php");
	error_reporting(E_ALL ^ E_WARNING);
	
	
	$errormessage = "";
	$array_result = array();
	$db = new VDatabase(true);
	
	$contractType = (isset($_POST['contractType']) ?  ($_POST['contractType']) : "");
	$documentName = (isset($_POST['documentName']) ?  ($_POST['documentName']) : "");
	$party = (isset($_POST['party']) ?  ($_POST['party']) : "");
	$uploaddate = (isset($_POST['uploaddate']) ?  ($_POST['uploaddate']) : "");
		
		
		
		if($errormessage == "")
		{
			  if(isset($_FILES["documentName"]))
			  {

				  $fileName = $_FILES["documentName"]["name"];
				  $fileSize = $_FILES["documentName"]["size"]/1024;
				  $fileType = $_FILES["documentName"]["type"];
				  $fileTmpName = $_FILES["documentName"]["tmp_name"];  
				 
				  $allowedExts = array("doc", "docx");
				  $extensions = (explode(".", $fileName));
			 
					 if(isset($extensions[(count($extensions)-1)]))
					 {
					 	 $extension = $extensions[(count($extensions)-1)];
					 }
					 else
					 {
					 	 $extension = "";
					 }

					  if(in_array($extension, $allowedExts))
					  {
					    if($fileSize <= 200)
					    {

					      //New file name
					      //$random=rand(1111,9999);
					      $timestamp = time();
					      $newFileName = $timestamp. "_" .$fileName;

					      //File upload path
					      $uploadPath="../uploads/".$newFileName;

						      //function for upload file
						      if(move_uploaded_file($fileTmpName,$uploadPath))
						      {
						        /*echo "Successful"; 
						        echo "File Name :".$newFileName; 
						        echo "File Size :".$fileSize." kb"; 
						        echo "File Type :".$fileType; */
									
									$insert_query = sprintf("insert into contract_files (Contract_Type,File_Name,Party,Date) VAlUES ('%s','%s','%s','%s')",$contractType, $newFileName, $party, $uploaddate);

									$row = $db->insertRow($insert_query);
									
									//$password = decrypt($password);
									
									if($insert_query)  
								    {  
								        //echo json_encode("Sucessfully Registered..");
								        $array_result=array(
					                	'Success' => 'TRUE',
					                	'Message' => 'Your Document has been Updated Sucessfully..'
					                	);
					                	echo json_encode($array_result, JSON_UNESCAPED_SLASHES); 
								    }
						    	}
						    }
						    else
						    {
						      echo json_encode("Maximum upload file size limit is 200 kb");
						    }
			  			}
					  else
					  {
					    echo json_encode("You can only upload a Word doc file.");
					  }  
				}
			}	
			else
			{
				echo json_encode($errormessage);
				/*$array_result['Success'] = $errormessage;
				echo json_encode($array_result, JSON_UNESCAPED_SLASHES);*/
			}
	
	
	$db->closeConnection();
		
	
?>