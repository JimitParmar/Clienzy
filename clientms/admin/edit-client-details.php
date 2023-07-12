<?php
session_start();
include('includes/dbconnection.php');
if (strlen($_SESSION['clientmsaid']==0)) {
  header('location:logout.php');
  } else{
    if(isset($_POST['submit']))
  {
$eid=$_GET['addid'];
$clientmsaid=$_SESSION['clientmsaid'];
 $cname=$_POST['cname'];
 $comname=$_POST['comname'];
 $deadline=$_POST['deadline'];
 $Tag=$_POST['Tag'];
 $financialyear = $_POST['financialyear'];
 $file = $_POST['file'];
 $budgethrs = $_POST['budgethrs'];
 $actualhrs = $_POST['actualhrs'];
 $expense = $_POST['expense'];
 $remark = $_POST['remark'];
 $notes=$_POST['notes'];

$sql="SELECT ID from tblclient";
$sql="UPDATE tblclient SET ContactName=:cname, CompanyName=:comname, deadline=:deadline, Tag=:Tag, Notes=:notes, financialyear=:financialyear, file=:file, budgethrs=:budgethrs, actualhrs=:actualhrs, expense=:expense, remark=:remark WHERE ID=:eid";
$query=$dbh->prepare($sql);
$query->bindParam(':cname',$cname,PDO::PARAM_STR);
$query->bindParam(':comname',$comname,PDO::PARAM_STR);
$query->bindParam(':financialyear',$financialyear,PDO::PARAM_STR);
$query->bindParam(':deadline',$deadline,PDO::PARAM_STR);
$query->bindParam(':Tag',$Tag,PDO::PARAM_STR);
$query->bindParam(':file', $file, PDO::PARAM_STR);
$query->bindParam(':budgethrs', $budgethrs, PDO::PARAM_STR);
$query->bindParam(':actualhrs', $actualhrs, PDO::PARAM_STR);
$query->bindParam(':expense', $expense, PDO::PARAM_STR);
$query->bindParam(':remark', $remark, PDO::PARAM_STR);
$query->bindParam(':notes',$notes,PDO::PARAM_STR);
$query->bindParam(':eid',$eid,PDO::PARAM_STR);
$query->execute();
echo '<script>alert("Client detail has been updated")</script>';
echo "<script type='text/javascript'> document.location ='manage-client.php'; </script>";
  }
  ?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Client Management Sysytem|| Update Clients</title>

	<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
	<!-- Custom CSS -->
	<link href="css/style.css" rel='stylesheet' type='text/css' />
	<!-- Graph CSS -->
	<link href="css/font-awesome.css" rel="stylesheet"> 
	<!-- jQuery -->
	<link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'>
	<!-- lined-icons -->
	<link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
	<!-- //lined-icons -->
	<script src="js/jquery-1.10.2.min.js"></script>
	<!--clock init-->
	<script src="js/css3clock.js"></script>
	<!--Easy Pie Chart-->
	<!--skycons-icons-->
	<script src="js/skycons.js"></script>
	<!--//skycons-icons-->
	<script>
	function convertToUppercase(input) {
        input.value = input.value.toUpperCase();
    }
	</script>
</head> 
<body>
<div class="page-container">
<!--/content-inner-->
<div class="left-content">
<div class="inner-content">
	
<?php //include_once('includes/header.php');?>
				<!--//outer-wp-->
<div class="outter-wp">
					<!--/sub-heard-part-->
<div class="sub-heard-part">
<ol class="breadcrumb m-b-0">
<li><a href="dashboard.php">Home</a></li>
<li class="active">Update Clients</li>
</ol>
</div>	
					<!--/sub-heard-part-->	
					<!--/forms-->
<div class="forms-main">
<h2 class="inner-tittle">Update Clients </h2>
<div class="graph-form">
<div class="form-body">
<form method="post"> 
	<?php
$eid=$_GET['addid'];
$sql="SELECT * from tblclient where ID=:eid";
$query = $dbh -> prepare($sql);
$query->bindParam(':eid',$eid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{               ?>								

		
		
	</select> </div>
	<div class="form-group"> <label for="exampleInputEmail1">Contact Name</label> <input type="text" name="cname" value="<?php  echo $row->ContactName;?>" class="form-control" required='true'> </div>
	<div class="form-group"> <label for="exampleInputEmail1">Company Name</label> <input type="text" name="comname" value="<?php  echo $row->CompanyName;?>" class="form-control" required='true'> </div>
	<div class="form-group"> <label for="exampleInputEmail1">Deadline</label> <input type="date" name="deadline" value="<?php  echo $row->deadline;?>" class="form-control" required='true'> </div> 
	<div>
	<label for="exampleInputEmail1">Work Description</label>
    <input type="text" name="Tag" value="<?php  echo $row->Tag;?>" placeholder="Work Description" class="form-control" required="true" onkeyup="convertToUppercase(this)">
	</div>
	<div class="form-group"> <label for="exampleInputEmail1">Financial Year</label> <input type="text" name="financialyear" value="<?php  echo $row->financialyear;?>" placeholder="Financial Year" class="form-control" required='true'> </div> 
	<div class="form-group"> <label for="exampleInputEmail1">File No.</label> <input type="text" name="file" value="<?php  echo $row->file;?>" placeholder="File Number" class="form-control" required='true'> </div> 
	<div class="form-group"> <label for="exampleInputEmail1">Budget Hours</label> <input type="text" name="budgethrs" value="<?php  echo $row->budgethrs;?>" placeholder="Budget hours" class="form-control" required='true'> </div> 
	<div class="form-group"> <label for="exampleInputEmail1">Actual Hours</label> <input type="text" name="actualhrs" value="<?php  echo $row->actualhrs;?>" placeholder="Actual hours" class="form-control" required='true'> </div> 
	<div class="form-group"> <label for="exampleInputEmail1">Expense</label> <input type="text" name="expense" value="<?php  echo $row->expense;?>" placeholder="Total Expense" class="form-control" required='true'> </div> 
	<div class="form-group"> <label for="exampleInputEmail1">Remark</label> <textarea type="text" name="remark" placeholder="Remark" value="<?php  echo $row->remark;?>" class="form-control"  rows="3" cols="3"></textarea> </div>
	<div class="form-group"> <label for="exampleInputEmail1">Notes</label> <textarea type="text" name="notes" class="form-control" rows="4" cols="3"><?php  echo $row->Notes;?></textarea> </div>
	
	
	<div class="form-group"> <label for="exampleInputPassword1">Creation Date</label> <input type="text" name="" value="<?php  echo $row->CreationDate;?>" required='true' class="form-control" readonly='true'> </div>
	<?php $cnt=$cnt+1;}} ?>
	 <button type="submit" class="btn btn-default" name="submit" id="submit">Update</button><button type="submit" class="btn btn-default" name="submit" id="submit"><a href="assign-employees.php?addid=<?php echo $row->ID; ?>">Assign </a></button></form> 
</div>
</div>
</div> 
</div>

</div>
<?php include_once('includes/sidebar.php');?>
<div class="clearfix"></div>		
</div>
<script>
		var toggle = true;

		$(".sidebar-icon").click(function() {                
			if (toggle)
			{
				$(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
				$("#menu span").css({"position":"absolute"});
			}
			else
			{
				$(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
				setTimeout(function() {
					$("#menu span").css({"position":"relative"});
				}, 400);
			}

			toggle = !toggle;
		});
	</script>
	<!--js -->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>

	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php }  ?>