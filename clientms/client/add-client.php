<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['clientmsuid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])){
        $clientmsaid = $_SESSION['clientmsuid'];
        $cname = $_POST['cname'];
        $comname = $_POST['comname'];
        $deadline = $_POST['deadline'];
		$Tag = $_POST['Tag'];
		$financialyear = $_POST['financialyear'];
		$file = $_POST['file'];
		$budgethrs = $_POST['budgethrs'];
		$actualhrs = $_POST['actualhrs'];
		$expense = $_POST['expense'];
		$remark = $_POST['remark'];
        $notes = $_POST['notes'];

        $employeeID = $_SESSION['clientmsuid'];
        $sql = "SELECT EmployeeName FROM tblemployee WHERE EmployeeID = :employeeID";
        $query = $dbh->prepare($sql);
        $query->bindParam(':employeeID', $employeeID, PDO::PARAM_INT);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $employeeName = $row['EmployeeName'];

        $sql = "INSERT INTO tblclient (ContactName, CompanyName, deadline, Tag, Notes,ClientAddedBy,financialyear, file, budgethrs, actualhrs, expense, remark)
        VALUES (:cname, :comname,  :deadline,:Tag ,:notes,:employeeName,:financialyear, :file, :budgethrs, :actualhrs, :expense, :remark )";
        
        $query = $dbh->prepare($sql);
        $query->bindParam(':cname', $cname, PDO::PARAM_STR);
        $query->bindParam(':comname', $comname, PDO::PARAM_STR);
        $query->bindParam(':notes', $notes, PDO::PARAM_STR);
        $query->bindParam(':deadline', $deadline, PDO::PARAM_STR);
		$query->bindParam(':Tag', $Tag, PDO::PARAM_STR);
		$query->bindParam(':financialyear', $financialyear, PDO::PARAM_STR);
		$query->bindParam(':file', $file, PDO::PARAM_STR);
		$query->bindParam(':budgethrs', $budgethrs, PDO::PARAM_STR);
		$query->bindParam(':actualhrs', $actualhrs, PDO::PARAM_STR);
		$query->bindParam(':expense', $expense, PDO::PARAM_STR);
		$query->bindParam(':remark', $remark, PDO::PARAM_STR);
        
        $query->bindParam(':employeeName', $employeeName, PDO::PARAM_STR);
        $query->execute();

        $lastInsertedClientId = $dbh->lastInsertId();
        if ($lastInsertedClientId > 0) {
            echo '<script>alert("Client has been added.")</script>';
            echo "<script>window.location.href ='add-client.php'</script>";
        } else {
            echo '<script>alert("Something went wrong. Please try again.")</script>';
        }
    }
}
?>


<!DOCTYPE HTML>
<html>

<head>
    <title>Client Management System || Add Clients</title>

    <script type="application/x-javascript">
        addEventListener("load", function() {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>
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
            <div class="inner-content">                               <!-- header-starts -->
<?php include_once('includes/header.php');?>
                <!--//outer-wp-->
                <div class="outter-wp">
                    <!--/sub-heard-part-->
                    <div class="sub-heard-part">
                        <ol class="breadcrumb m-b-0">
                            <li><a href="dashboard.php">Home</a></li>
                            <li class="active">Add Clients</li>
                        </ol>
                    </div>
                    <!--/sub-heard-part-->
                    <!--/forms-->
                    <div class="forms-main">
                        <h2 class="inner-tittle1" style="margin-left: 32px;">Add Clients </h2>
                        <div class="graph-form">
                            <div class="form-body">
                                <form method="post">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Contact Name</label>
                                        <input type="text" name="cname" placeholder="Contact Name" value="" class="form-control" required='true'>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Company Name</label>
                                        <input type="text" name="comname" placeholder="Company Name" value="" class="form-control" required='true'>
                                    </div>
                                    <div class="form-group">
    <label for="exampleInputEmail1">Work Description</label>
    <input type="text" name="Tag" value="" placeholder="Work Description" class="form-control" required="true" onkeyup="convertToUppercase(this)">
	</div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Deadline</label>
                                        <input type="date" name="deadline" value="" placeholder="Deadline" class="form-control" >
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Financial Year</label>
                                        <input type="text" name="financialyear" value="" placeholder="Financial Year" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">File No.</label>
                                        <input type="text" name="file" value="" placeholder="File Number" class="form-control" required='true'>
                                    </div>
                                    <div class="form-group"> <label for="exampleInputEmail1">Budget Hours</label> <input type="text" name="budgethrs" value="" placeholder="Budget hours" class="form-control" required='true'> </div> 
                                    <div class="form-group"> <label for="exampleInputEmail1">Actual Hours</label> <input type="text" name="actualhrs" value="" placeholder="Actual hours" class="form-control" required='true'> </div> 
                                    <div class="form-group"> <label for="exampleInputEmail1">Expense</label> <input type="text" name="expense" value="" placeholder="Total Expense" class="form-control" required='true'> </div> 
                                    <div class="form-group"> <label for="exampleInputEmail1">Remark</label> <textarea type="text" name="remark" placeholder="Remark" value="" class="form-control"  rows="3" cols="3"></textarea> </div>
                                    
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Notes</label>
                                        <textarea type="text" name="notes" placeholder="Notes" value="" class="form-control" required='true' rows="4" cols="3"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-default" name="submit" id="submit">Save</button>
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
            if (toggle) {
                $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
                $("#menu span").css({
                    "position": "absolute"
                });
            } else {
                $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
                setTimeout(function() {
                    $("#menu span").css({
                        "position": "relative"
                    });
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
<?php ?>
