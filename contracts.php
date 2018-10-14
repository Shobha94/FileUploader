<?php
	require_once("includes/start.php");
	require_once("includes/config.php");
	require_once("includes/classes/VDatabase.php");
	require_once("includes/vutils.php");
	
	
	$wherecond = " WHERE status = 'Active' ";
	if(isset($_POST['search']))
	{
		$search = $_POST['searchType'];
		
		if($search != "")
		{
			$wherecond .= " AND Contract_Type = '$search'  || Party = '$search' || Date = '$search' ";
		}
		
	}
		$postData = array(
		
			'whereCondition' => $wherecond
			);	

	$curlurl = WEBSERVICE_URL."ContractsDetails.php";
	$curl = curl_init($curlurl);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
                   
    $response = curl_exec($curl);
	curl_close($curl);
	
    $result = json_decode($response,true);
    //print_r($response);
    
    if(is_array($result))
    {
		
	}
	else
	{
		$errormessage = $result;
	}
	
		
?>


<!DOCTYPE html>
<html lang="zxx">
<head>
	<title>Contracts</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
						
						<form class="well form-horizontal" action="" method="post" name="search-form" enctype="multipart/form-data" id="">
							<div class="form-group"> 
							  <div class="col-md-5 inputGroupContainer">
							  <div class="input-group">
							  <span class="input-group-addon"><i class="fa fa-search"></i></span>
							  <input type="text" placeholder="Search" class="form-control" name="searchType" value="">
								</div>
							  </div>
							</div>
							<div class="form-group">
							  <div class="col-md-5">
							&nbsp&nbsp<button type="submit" name="search" class="btn btn-md">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspSearch&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</button>
							  </div>
							</div>
						<fieldset>

				<!-- Form Name -->
				<legend><h3 style="color: #206444"><b>All Contracts</b></h3></legend><br>
				
				

				<!-- Text input-->
				<div class="table-responsive">          
						<table class="table">
						<thead>
							<tr>
								<th>S.No</th>
								 <!--<th>Application Id</th>-->
								 <th>Contract Type</th>
						         <th>Contract File Name</th>
						         <th>Party</th>
								 <th>Date</th>
							</tr>
						</thead>
						<tbody>
							 <?php
						                    	if(is_array($result))
						                    	{
						                    		$i = 0;
						                    		foreach($result as $row)
						                    		{
						                    			$i++;
						                    			
											?>
														<tr class="">
									                        <td><?php echo $i; ?></td>
									                        <td><?php echo $row["Contract_Type"]; ?></td>
									                        <td><?php echo $row['File_Name']; ?></td>
									                        <td><?php echo $row['Party']; ?></td>
															<td><?php echo $row['Date']; ?></td>
									                      
									                          
									                    </tr>
											<?php
													}
												}
												else
												{
													echo "<tr><td colspan='5'>".$result."</td></tr>";
												}
						                    ?>
						</tbody>
						</table>
					</div>
					</fieldset>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
    <!-- End Container-->
</body>
</html>