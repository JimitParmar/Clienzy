<?php
session_start();
include('includes/dbconnection.php');
if (strlen($_SESSION['clientmsuid']) == 0) {
    header('location:logout.php');
    exit();
}

$employeeId = $_SESSION['clientmsuid'];
$employeeName = $_SESSION['clientmsname'];

// Fetch the total number of clients
$sql = "SELECT tblclient.* FROM tblclient 
            INNER JOIN tblassignments ON tblclient.ID = tblassignments.ID 
            INNER JOIN tblemployee ON tblassignments.EmployeeID = tblemployee.EmployeeID 
            WHERE tblassignments.EmployeeID = :employeeId

            UNION

            SELECT * FROM tblclient WHERE ClientAddedBy = :employeeName";

    $query = $dbh->prepare($sql);
    $query->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
    $query->bindParam(':employeeName', $employeeName, PDO::PARAM_STR);
    $query->execute();
    $clients = $query->fetchAll(PDO::FETCH_OBJ);
    $totalClients = count($clients);
    $_SESSION['totalClients'] = $totalClients;

$sqlTotal = "SELECT COUNT(*) AS totalClients FROM (
    SELECT tblclient.* FROM tblclient 
    INNER JOIN tblassignments ON tblclient.ID = tblassignments.ID 
    INNER JOIN tblemployee ON tblassignments.EmployeeID = tblemployee.EmployeeID 
    WHERE tblassignments.EmployeeID = :employeeId

    UNION

    SELECT * FROM tblclient WHERE ClientAddedBy = :employeeName
) AS totalClients";

$queryTotal = $dbh->prepare($sqlTotal);
$queryTotal->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
$queryTotal->bindParam(':employeeName', $employeeName, PDO::PARAM_STR);
$queryTotal->execute();
$totalClients = $queryTotal->fetchColumn();

// Fetch the number of completed clients
$sqlCompleted = "SELECT COUNT(*) AS completedCount FROM (
    SELECT tblclient.* FROM tblclient 
    INNER JOIN tblassignments ON tblclient.ID = tblassignments.ID 
    INNER JOIN tblemployee ON tblassignments.EmployeeID = tblemployee.EmployeeID 
    WHERE tblassignments.EmployeeID = :employeeId AND tblclient.Status = 'Completed'

    UNION

    SELECT * FROM tblclient WHERE ClientAddedBy = :employeeName AND Status = 'Completed'
) AS completedClients";

$queryCompleted = $dbh->prepare($sqlCompleted);
$queryCompleted->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
$queryCompleted->bindParam(':employeeName', $employeeName, PDO::PARAM_STR);
$queryCompleted->execute();
$completedClients = $queryCompleted->fetchColumn();

// Fetch the number of clients with status 'In Process'
$sqlInProcess = "SELECT COUNT(*) AS inProcessCount FROM (
    SELECT tblclient.* FROM tblclient 
    INNER JOIN tblassignments ON tblclient.ID = tblassignments.ID 
    INNER JOIN tblemployee ON tblassignments.EmployeeID = tblemployee.EmployeeID 
    WHERE tblassignments.EmployeeID = :employeeId AND tblclient.Status = 'In Process'

    UNION

    SELECT * FROM tblclient WHERE ClientAddedBy = :employeeName AND Status = 'In Process'
) AS inProcessClients";

$queryInProcess = $dbh->prepare($sqlInProcess);
$queryInProcess->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
$queryInProcess->bindParam(':employeeName', $employeeName, PDO::PARAM_STR);
$queryInProcess->execute();
$inProcessClients = $queryInProcess->fetchColumn();

// Fetch the number of clients with status 'Not Started'
$sqlNotStarted = "SELECT COUNT(*) AS notStartedCount FROM tblclient 
    LEFT JOIN tblassignments ON tblclient.ID = tblassignments.ID 
    WHERE (tblassignments.EmployeeID = :employeeId OR tblclient.ClientAddedBy = :employeeName) 
    AND tblclient.Status = 'Not Started' 
    AND tblassignments.ID IS NULL";

$queryNotStarted = $dbh->prepare($sqlNotStarted);
$queryNotStarted->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
$queryNotStarted->bindParam(':employeeName', $employeeName, PDO::PARAM_STR);
$queryNotStarted->execute();
$notStartedClients = $queryNotStarted->fetch(PDO::FETCH_ASSOC)['notStartedCount'];

// Fetch the number of clients with payment status 'Paid'
$sqlPaid = "SELECT COUNT(*) AS paidCount FROM (
    SELECT tblclient.* FROM tblclient 
    INNER JOIN tblassignments ON tblclient.ID = tblassignments.ID 
    INNER JOIN tblemployee ON tblassignments.EmployeeID = tblemployee.EmployeeID 
    WHERE tblassignments.EmployeeID = :employeeId AND tblclient.PaymentStatus = 'Paid'

    UNION

    SELECT * FROM tblclient WHERE ClientAddedBy = :employeeName AND PaymentStatus = 'Paid'
) AS paidClients";

$queryPaid = $dbh->prepare($sqlPaid);
$queryPaid->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
$queryPaid->bindParam(':employeeName', $employeeName, PDO::PARAM_STR);
$queryPaid->execute();
$paidClients = $queryPaid->fetchColumn();

// Fetch the number of clients with payment status 'Overdue'
$sqlOverdue = "SELECT COUNT(*) AS overdueCount FROM (
    SELECT tblclient.* FROM tblclient 
    INNER JOIN tblassignments ON tblclient.ID = tblassignments.ID 
    INNER JOIN tblemployee ON tblassignments.EmployeeID = tblemployee.EmployeeID 
    WHERE tblassignments.EmployeeID = :employeeId AND tblclient.PaymentStatus = 'Overdue'

    UNION

    SELECT * FROM tblclient WHERE ClientAddedBy = :employeeName AND PaymentStatus = 'Overdue'
) AS overdueClients";

$queryOverdue = $dbh->prepare($sqlOverdue);
$queryOverdue->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
$queryOverdue->bindParam(':employeeName', $employeeName, PDO::PARAM_STR);
$queryOverdue->execute();
$overdueClients = $queryOverdue->fetchColumn();

// Fetch the number of clients with payment status 'Pending'
$sqlPending = "SELECT COUNT(*) AS pendingCount FROM (
    SELECT tblclient.* FROM tblclient 
    INNER JOIN tblassignments ON tblclient.ID = tblassignments.ID 
    INNER JOIN tblemployee ON tblassignments.EmployeeID = tblemployee.EmployeeID 
    WHERE tblassignments.EmployeeID = :employeeId AND tblclient.PaymentStatus = 'Pending'

    UNION

    SELECT * FROM tblclient WHERE ClientAddedBy = :employeeName AND PaymentStatus = 'Pending'
) AS pendingClients";

$queryPending = $dbh->prepare($sqlPending);
$queryPending->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
$queryPending->bindParam(':employeeName', $employeeName, PDO::PARAM_STR);
$queryPending->execute();
$pendingClients = $queryPending->fetchColumn();

//Fetch the number of clients with Deadline passed:
$today = date('Y-m-d');
$sqlPassed = "SELECT COUNT(*) AS passedCount FROM (
    SELECT tblclient.* FROM tblclient 
    INNER JOIN tblassignments ON tblclient.ID = tblassignments.ID 
    INNER JOIN tblemployee ON tblassignments.EmployeeID = tblemployee.EmployeeID 
    WHERE tblassignments.EmployeeID = :employeeId AND tblclient.deadline < :today

    UNION

    SELECT * FROM tblclient WHERE ClientAddedBy = :employeeName AND deadline < :today
) AS passedClients";

$queryPassed = $dbh->prepare($sqlPassed);
$queryPassed->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
$queryPassed->bindParam(':employeeName', $employeeName, PDO::PARAM_STR);
$queryPassed->bindParam(':today', $today, PDO::PARAM_STR);
$queryPassed->execute();
$passedClients = $queryPassed->fetchColumn();
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--//skycons-icons-->
</head> 
<body>		
<div class="page-container">
    <!--/content-inner-->
    <div class="left-content">
        <div class="inner-content">
        <?php include_once('includes/header.php');?>
            <div class="outter-wp">
                <!--custom-widgets-->
                <div class="custom-widgets">
                    <div class="row-one">
                        <div class="col-md-3 col-sm-6 widget">
							<a href='manage-clients.php'>
								<div class="stats-left">
									<h5>Total</h5>
									<h4>Clients</h4>
								</div>
								<div class="stats-right">
									<label><?php echo htmlentities($_SESSION['totalClients']); ?></label>
								</div>
								<div class="clearfix"></div>
							</a>
                        </div>
                        <div class="col-md-3 col-sm-6 widget">
							<a href='work-done-clients.php'>
								<div class="stats-left">
									<h5>Work</h5>
									<h4>Done</h4>
								</div>
								<div class="stats-right">
									<label><?php echo htmlentities($completedClients); ?></label>
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
                                <label><?php echo htmlentities($inProcessClients); ?></label>
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
                                <label><?php echo htmlentities($notStartedClients); ?></label>
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
                            <div class="stats-right"style="background-color:#53d769;">
                                <label><?php echo htmlentities($paidClients); ?></label>
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
                            <div class="stats-right"style="background-color:#fc3d39;">
                                <label><?php echo htmlentities($overdueClients); ?></label>
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
                            <div class="stats-right" style="background-color:#aaaaaa;">
                                <label><?php echo htmlentities($pendingClients); ?></label>
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
                            <div class="stats-right" style="background-color:#fc3d39;">
                                <label><?php echo htmlentities($passedClients); ?></label>
                            </div>
                            <div class="clearfix"></div>
							</a>
                        </div>
						<?php include_once('includes/sidebar.php');?>
<div class="clearfix"></div>	
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--//content-inner-->

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