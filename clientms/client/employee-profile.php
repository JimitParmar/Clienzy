<?php
session_start();
include('includes/dbconnection.php');
if (strlen($_SESSION['clientmsuid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $uid = $_SESSION['clientmsuid'];
        $empname = $_POST['empname'];
        $phnumber = $_POST['phnumber'];

        $sql = "UPDATE tblemployee SET EmployeeName = :empname, EmployeePhnumber = :phnumber WHERE EmployeeID = :uid";
        $query = $dbh->prepare($sql);

        $query->bindParam(':empname', $empname, PDO::PARAM_STR);
        $query->bindParam(':phnumber', $phnumber, PDO::PARAM_STR);
        $query->bindParam(':uid', $uid, PDO::PARAM_STR);

        $query->execute();

        if ($query->rowCount() > 0) {
            echo '<script>alert("Your profile has been updated")</script>';
            echo "<script>window.location.href = 'employee-profile.php'</script>";
        } else {
            echo '<script>alert("Something went wrong. Please try again")</script>';
        }
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Client Management System|| Employee Profile</title>

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
</head> 
<body>
<div class="page-container">
<!--/content-inner-->
<div class="left-content">
<div class="inner-content">
<?php include_once('includes/header.php');?>
	
				<!--//outer-wp-->
<div class="outter-wp">
					<!--/sub-heard-part-->
<div class="sub-heard-part">
<ol class="breadcrumb m-b-0">
<li><a href="dashboard.php">Home</a></li>
<li class="active">Employee Profile</li>
</ol>
</div>	
					<!--/sub-heard-part-->	
					<!--/forms-->
<div class="forms-main">
<h2 class="inner-tittle">Employee Profile </h2>
<div class="graph-form">
<div class="form-body">
<form method="post"> 
									<?php
$uid=$_SESSION['clientmsuid'];
$sql="SELECT * from  tblemployee where EmployeeID=:uid";
$query = $dbh -> prepare($sql);
$query->bindParam(':uid',$uid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{               ?>
	<div class="form-group">
    <label for="empname">Employee Name</label>
    <input type="text" name="empname" value="<?php echo $row->EmployeeName; ?>" class="form-control" required="true">
	</div>
	<div class="form-group">
    <label for="phnumber">Cell Phone Number</label>
    <input type="text" name="phnumber" value="<?php echo $row->EmployeePhnumber; ?>" class="form-control" maxlength="10" pattern="[0-9]+">
	</div>
<?php $cnt=$cnt+1;}} ?> 
	 <button type="submit" class="btn btn-default" name="submit" id="submit">Update</button> </form> 
</div>
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
<?php  ?>