<?php
session_start();
include('includes/dbconnection.php');
if (strlen($_SESSION['clientmsaid']==0)) {
  header('location:logout.php');
  }     
  $sql = "SELECT COUNT(ID) AS totalClients FROM tblclient";
  $query = $dbh->prepare($sql);
  $query->execute();
  $data = $query->fetch(PDO::FETCH_ASSOC);
  $totalClients = $data['totalClients'];

  // Fetch total work done
  $sql = "SELECT COUNT(ID) AS totalWorkDone FROM tblclient WHERE Status = 'Completed'";
  $query = $dbh->prepare($sql);
  $query->execute();
  $data = $query->fetch(PDO::FETCH_ASSOC);
  $totalWorkDone = $data['totalWorkDone'];

  // Fetch work in process
  $sql = "SELECT COUNT(ID) AS workInProcess FROM tblclient WHERE Status = 'In Process'";
  $query = $dbh->prepare($sql);
  $query->execute();
  $data = $query->fetch(PDO::FETCH_ASSOC);
  $workInProcess = $data['workInProcess'];

  // Fetch work not done
  $sql = "SELECT COUNT(ID) AS workNotDone FROM tblclient WHERE Status = 'Not Started'";
  $query = $dbh->prepare($sql);
  $query->execute();
  $data = $query->fetch(PDO::FETCH_ASSOC);
  $workNotDone = $data['workNotDone'];

  // Fetch payment done
  $sql = "SELECT COUNT(ID) AS paymentDone FROM tblclient WHERE PaymentStatus = 'Paid'";
  $query = $dbh->prepare($sql);
  $query->execute();
  $data = $query->fetch(PDO::FETCH_ASSOC);
  $paymentDone = $data['paymentDone'];

  // Fetch payment overdue
  $sql = "SELECT COUNT(ID) AS paymentOverdue FROM tblclient WHERE PaymentStatus = 'Overdue'";
  $query = $dbh->prepare($sql);
  $query->execute();
  $data = $query->fetch(PDO::FETCH_ASSOC);
  $paymentOverdue = $data['paymentOverdue'];

  // Fetch payment pending
  $sql = "SELECT COUNT(ID) AS paymentPending FROM tblclient WHERE PaymentStatus = 'Pending'";
  $query = $dbh->prepare($sql);
  $query->execute();
  $data = $query->fetch(PDO::FETCH_ASSOC);
  $paymentPending = $data['paymentPending'];

  // Fetch overdue clients
  $today = date('Y-m-d');
  $sql = "SELECT COUNT(ID) AS overdueClients FROM tblclient WHERE deadline < :today";
  $query = $dbh->prepare($sql);
  $query->bindParam(':today', $today, PDO::PARAM_STR);
  $query->execute();
  $data = $query->fetch(PDO::FETCH_ASSOC);
  $overdueClients = $data['overdueClients'];


  ?>
<!DOCTYPE HTML>
<html>
<head>
<title>Client Management System||Dashboard</title>

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
<script src="js/amcharts.js"></script>	
<script src="js/serial.js"></script>	
<script src="js/light.js"></script>	
<script src="js/radar.js"></script>	
<link href="css/barChart.css" rel='stylesheet' type='text/css' />
<link href="css/fabochart.css" rel='stylesheet' type='text/css' />
<!--clock init-->
<script src="js/css3clock.js"></script>
<!--Easy Pie Chart-->
<!--skycons-icons-->
<script src="js/skycons.js"></script>

<script src="js/jquery.easydropdown.js"></script>

<!--//skycons-icons-->
</head> 
<body>
<div class="page-container">
	<!--/content-inner-->
	<div class="left-content">
		<div class="inner-content">			
			<div class="outter-wp">
				<!--custom-widgets-->
				<div class="custom-widgets">
					<div class="row-one">
						<div class="col-md-4 widget">
							<a href="manage-client.php">
							<div class="stats-left ">
								<h5>Total</h5>
								<h4> Clients</h4>
							</div>
							<div class="stats-right">
								<label><?php echo htmlentities($totalClients);?></label>
							</div>
							<div class="clearfix"> </div>
							</a>	
						</div>
						<div class="col-md-3 col-sm-6 widget">
							<a href='work-done-clients.php'>
								<div class="stats-left">
									<h5>Work</h5>
									<h4>Done</h4>
								</div>
								<div class="stats-right">
									<label><?php echo htmlentities($totalWorkDone); ?></label>
								</div>
								<div class="clearfix"></div>
							</a>
                        </div>
                        <div class="col-md-3 col-sm-6 widget">
							<a href="in-progress-client.php">
                            <div class="stats-left">
                                <h5>Work</h5>
                                <h4>In Process</h4>
                            </div>
                            <div class="stats-right">
                                <label><?php echo htmlentities($workInProcess); ?></label>
                            </div>
                            <div class="clearfix"></div>
							</a>
                        </div>
                        <div class="col-md-3 col-sm-6 widget">
                            <a href="not-started-client.php">
								<div class="stats-left">
                                <h5>Work</h5>
                                <h4>Not Done</h4>
                            </div>
                            <div class="stats-right">
                                <label><?php echo htmlentities($workNotDone); ?></label>
                            </div>
                            <div class="clearfix"></div>
							</a>
                        </div>
                        <div class="col-md-3 col-sm-6 widget">
							<a href = "payment-done.php">
                            <div class="stats-left">
                                <h5>Payment</h5>
                                <h4>Done</h4>
                            </div>
                            <div class="stats-right">
                                <label style="color:#53d769;"><?php echo htmlentities($paymentDone); ?></label>
                            </div>
                            <div class="clearfix"></div>
							</a>
						</div>
                        <div class="col-md-3 col-sm-6 widget">
							<a href ="payment-overdue.php">
                            <div class="stats-left">
                                <h5>Payment</h5>
                                <h4>Overdue	</h4>
                            </div>
                            <div class="stats-right">
                                <label style="color:#fc3d39;"><?php echo htmlentities($paymentOverdue); ?></label>
                            </div>
                            <div class="clearfix"></div>
							</a>
                        </div>
                        <div class="col-md-3 col-sm-6 widget">
							<a href = "payment-pending.php">
                            <div class="stats-left">
                                <h5>Payment</h5>
                                <h4>Pending</h4>
                            </div>
                            <div class="stats-right">
                                <label style="color:#a9a9a9;"><?php echo htmlentities($paymentPending); ?></label>
                            </div>
                            <div class="clearfix"></div>
							</a>
                        </div>
                        <div class="col-md-3 col-sm-6 widget">
							<a href = "deadline-passed.php">
                            <div class="stats-left">
                                <h5>Passed</h5>
                                <h4>Deadline</h4>
                            </div>
                            <div class="stats-right"style="background-color:#fc3d39;">
                                <label ><?php echo htmlentities($overdueClients); ?></label>
                            </div>
                            <div class="clearfix"></div>
							</a>
                        </div>


<!--//content-inner-->

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
<link rel="stylesheet" href="css/vroom.css">
<script type="text/javascript" src="js/vroom.js"></script>
<script type="text/javascript" src="js/TweenLite.min.js"></script>
<script type="text/javascript" src="js/CSSPlugin.min.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>