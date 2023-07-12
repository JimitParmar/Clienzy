<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['clientmsaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $eid = $_GET['addid'];
        $clientmsaid = $_SESSION['clientmsaid'];
        $cname = $_POST['cname'];

        // Get input dates
        $inputDate1 = $_POST['expiry1'];
        $inputDate2 = $_POST['expiry2'];
        $inputDate3 = $_POST['expiry3'];

        // Convert input dates to DateTime objects
        $dateTime1 = new DateTime($inputDate1);
        $dateTime2 = !empty($inputDate2) ? new DateTime($inputDate2) : null;
        $dateTime3 = !empty($inputDate3) ? new DateTime($inputDate3) : null;

        // Add 2 years to the input dates
        $expiryDate1 = $dateTime1->add(new DateInterval('P2Y'))->format('Y-m-d');
        $expiryDate2 = $dateTime2 ? $dateTime2->add(new DateInterval('P2Y'))->format('Y-m-d') : null;
        $expiryDate3 = $dateTime3 ? $dateTime3->add(new DateInterval('P2Y'))->format('Y-m-d') : null;

        $sql = "UPDATE tblclient SET ContactName = :cname, expiryDate1 = :expiryDate1, expiryDate2 = :expiryDate2, expiryDate3 = :expiryDate3 WHERE ID = :eid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':cname', $cname, PDO::PARAM_STR);
        $query->bindParam(':eid', $eid, PDO::PARAM_STR);
        $query->bindParam(':expiryDate1', $expiryDate1, PDO::PARAM_STR);
        $query->bindParam(':expiryDate2', $expiryDate2, PDO::PARAM_STR);
        $query->bindParam(':expiryDate3', $expiryDate3, PDO::PARAM_STR);
        $query->execute();

        echo '<script>alert("Client detail has been updated")</script>';
        echo "<script type='text/javascript'> document.location ='manage-dsc.php'; </script>";
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Client Management Sysytem || Update Clients</title>
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
                    <h2 class="inner-tittle">Update Clients</h2>
                    <div class="graph-form">
                        <div class="form-body">
                            <form method="post">
                                <?php
                                $eid = $_GET['addid'];
                                $sql = "SELECT * FROM tblclient WHERE ID = :eid";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                $cnt = 1;
                                if ($query->rowCount() > 0) {
                                    foreach ($results as $row) {
                                        ?>

                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Contact Name</label>
                                            <input type="text" name="cname" value="<?php echo $row->ContactName; ?>"
                                                   class="form-control" required='true'>
                                        </div>
                                        <div class="form-group">
                                            <label for="creation_date">Creation Date (IceGate):</label>
                                            <input type="date" class="form-control" id="expiry_date" value="<?php echo $row->expiryDate1; ?>"name="expiry1">
                                        </div>
                                        <div class="form-group">
                                            <label for="expiry_date">Creation Date (DGFT)</label>
                                            <input type="date" class="form-control" id="expiry_date" value="<?php echo $row->expiryDate2; ?>"name="expiry2">
                                        </div>
                                        <div class="form-group">
                                            <label for="expiry_date">Creation Date (Class3)</label>
                                            <input type="date" class="form-control" id="expiry_date" value="<?php echo $row->expiryDate3; ?>"name="expiry3">
                                        </div>

                                        <?php
                                        $cnt = $cnt + 1;
                                    }
                                }
                                ?>
                                <button type="submit" class="btn btn-default" name="submit" id="submit">Update</button>
                                <input type="button" class="btn btn-default" value="Back"
                                       onClick="history.back();return true;"
                                       style="border-radius:15px ;background-color:#282828;color:#fff;">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include_once('includes/sidebar.php'); ?>
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