<?php
	require_once("includes/start.php");
	require_once("includes/config.php");
	require_once("includes/classes/VDatabase.php");
	require_once("includes/vutils.php");
	
	$errormessage = "";
	$successmessage = "";
	$db = new VDatabase(true);
	
	if(isset($_POST['submit']))
	{
		
					
				$curlurl = WEBSERVICE_URL."document.php";
				
				$fileName = $_FILES["documentName"]["name"];
  				$fileSize = $_FILES["documentName"]["size"]/1024;
  				$fileType = $_FILES["documentName"]["type"];
  				$fileTmpName = $_FILES["documentName"]["tmp_name"]; 
  				
			    $fileInfo = new CurlFile($fileTmpName, $fileType, $fileName);
			    
			    if($fileName != '')
			    {
			        $headers = array("Content-Type:multipart/form-data"); // cURL headers for file uploading
			        
			        $postData = array(
					'contractType' => $_POST['contractType'],
					'documentName' => $fileInfo,
					'party'        => $_POST['party'],
					'uploaddate'   => $_POST['uploaddate']

			   		);
			        
			       $curl = curl_init($curlurl);

					curl_setopt($curl, CURLOPT_HEADER, 0);
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($curl, CURLOPT_POST, 1);
					curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
					                   
					$response = curl_exec($curl);
					/*curl_close($curl);*/
					//print_r($response);	
					$result = json_decode($response,true);
				    
				    if(is_array($result))
				    {
				    	$successmessage = $result['Message'];
					}
					else
					{
						$errormessage = $result;
					}
					
			        curl_close($curl);
			    }
			    else
			    {
					$errormessage = "Please select a copy of your File to upload";
				}
		
			
		}
		
		
	
	
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
	<title>File Upload</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="/resources/demos/style.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<link rel="stylesheet" type="text/css" href="css/vendor.bundle.css">
	<link id="style-css" rel="stylesheet" type="text/css" href="css/stylef269.css?ver=1.0.1">
</head>
<body class="site-body style-v1">
	<!-- Container-->
	<div class="section section-contents section-pad" style="margin-top: -60px;">
		<div class="container">
			<div class="content row">
				<div class="row">
					<div class="col-md-12">
						<form class="well form-horizontal" action="" method="post" enctype="multipart/form-data" id="">
							<fieldset>
							<!-- Form Name -->
								<legend><center><h3 style="color: #206444"><b>Upload Contract</b></h3></center></legend><br>
								<!-- Message -->
								<?php
									if($errormessage != "")
									{
										echo '<div class="alert alert-danger text-center role="alert""><i class="glyphicon glyphicon-thumbs-down"></i>'.$errormessage.'</div>';
									}
								?>
								<?php
									if($successmessage != "")
									{
										echo '<div class="alert alert-success text-center role="alert""><i class="glyphicon glyphicon-thumbs-up"></i>'.$successmessage.'</div>';
									}
								?>
								<div class="form-group">
								  <label class="col-md-4 control-label">Contract Type</label>  
									<div class="col-md-4 inputGroupContainer">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-file"></i></span>
								  <input type="text" name="contractType" placeholder="Contract Type" class="form-control" >
									</div>
								  </div>
								</div>
								<div class="form-group">
								  <label class="col-md-4 control-label">Contract File</label>  
									<div class="col-md-4 inputGroupContainer">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-file"></i></span>
								  <input type="file" name="documentName" placeholder="Upload Document" class="form-control" value="">
									</div>
								  </div>
								</div>
								<div class="form-group">
								  <label class="col-md-4 control-label">Party</label>  
									<div class="col-md-4 inputGroupContainer">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-file"></i></span>
								  <input type="text" name="party" placeholder="Party" class="form-control" value="">
									</div>
								  </div>
								</div>
								<div class="form-group">
								  <label class="col-md-4 control-label">Date</label>  
									<div class="col-md-4 inputGroupContainer">
									<div class="input-group" id="datetimepicker1">
									<span class="input-group-addon"><i class="fa fa-file"></i></span>
										<input type="text" name="uploaddate" placeholder="Date" class="form-control" value="" id="datetimepicker">
									</div>
								  </div>
								</div>
								<!-- Button -->
								<div class="form-group">
								  <label class="col-md-4 control-label"></label>
								  <div class="col-md-4"><br>
									&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<button type="submit" name="submit" class="btn btn-warning">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspSubmit &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</button>
								  </div>
								</div>

							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
    <!-- End Container-->
	 <!-- Bootstrap Date-Picker Plugin -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
	
	<!--Date picker script -->
	
	<script>
	    $(document).ready(function(){
	      var date_input=$('input[name="uploaddate"]'); //our date input has the name "date"
	      var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
	      var options={
	        format: 'mm/dd/yyyy',
	        container: container,
	        endDate: '0+d',
	        todayHighlight: true,
	        autoclose: true,
	      };
	      date_input.datepicker(options);
	    })
	</script>
</body>
</html>