<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('includes/dbconnection.php');

if (strlen($_SESSION['clientmsuid']) == 0) {
    header('location:logout.php');
    exit();
} else {
    $employeeId = $_SESSION['clientmsuid'];
    $employeeName = $_SESSION['clientmsname'];

    // Fetch the assigned clients for the employee and clients added by the employee
    $sql = "    SELECT tblclient.* FROM tblclient 
    INNER JOIN tblassignments ON tblclient.ID = tblassignments.ID 
    INNER JOIN tblemployee ON tblassignments.EmployeeID = tblemployee.EmployeeID 
    WHERE tblassignments.EmployeeID = :employeeId AND tblclient.PaymentStatus = 'Overdue'

    UNION

    SELECT * FROM tblclient WHERE ClientAddedBy = :employeeName AND PaymentStatus = 'Overdue'";

    $query = $dbh->prepare($sql);
    $query->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
    $query->bindParam(':employeeName', $employeeName, PDO::PARAM_STR);
    $query->execute();
    $clients = $query->fetchAll(PDO::FETCH_OBJ);
    $totalClients = count($clients);
    $_SESSION['totalClients'] = $totalClients;


}

// Update client status if form is submitted
if (isset($_POST['update_status'])) {
    $clientId = $_POST['client_id'];
    $status = $_POST['status'];

    // Update the client status in the database
    $updateSql = "UPDATE tblclient SET Status=:status WHERE ID=:clientId";
    $updateQuery = $dbh->prepare($updateSql);
    $updateQuery->bindParam(':status', $status, PDO::PARAM_STR);
    $updateQuery->bindParam(':clientId', $clientId, PDO::PARAM_INT);
    $updateQuery->execute();

    // Return the updated status as JSON response
    echo json_encode(['status' => 'success', 'message' => 'Client status updated successfully']);
    exit();
}

// Check if a search query is submitted
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];

    // Fetch clients based on the search query
    $sql = "SELECT * FROM tblclient WHERE ContactName LIKE :search";
    $query = $dbh->prepare($sql);
    $query->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    // Check if any clients are found
    if ($query->rowCount() > 0) {
        // Clients found, display the search results
        $clients = $results;
    } else {
        // No clients found for the search query
        $clients = [];
    }
}

?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Manage Clients</title>
    <link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link href="css/font-awesome.css" rel="stylesheet"/>
    <link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
    <script src="js/jquery-1.10.2.min.js"></script>
    <script>
    var $j = jQuery.noConflict();
    // Use $j instead of the $ symbol for jQuery code
    </script>
    <script type="application/x-javascript">
        addEventListener("load", function() {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>
    <script>
        function updateStatus(ID, Status) {
            $j.ajax({
                url: 'update-client-status.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    client_id: ID, // Change 'ID' to 'client_id'
                    status: Status
                },
                success: function(response) {
                    if (response.success) { // Change 'Status' to 'success'
                        alert(response.message);
                    } else {
                        alert('Failed to update client status.');
                    }
                },
                error: function() {
                    alert('An error occurred while updating client status.');
                }
            });
        }
    </script>
    <script>
        function updatePaymentStatus(ID, PaymentStatus) {
    console.log('Function called'); // Check if the function is called
    console.log('ID:', ID); // Verify the value of ID
    console.log('PaymentStatus:', PaymentStatus); // Verify the value of PaymentStatus

    $j.ajax({
        url: 'update-clients-payment-status.php',
        type: 'POST',
        dataType: 'json',
        data: {
            client_id: ID,
            payment_status: PaymentStatus
        },
        success: function(response) {
            console.log('AJAX request successful:', response);
            if (response.success) {
                alert(response.message);
            } else {
                alert('Failed to update payment status.');
            }
        },
        error: function(xhr, status, error) {
            console.log('AJAX request failed:');
            console.log(xhr.responseText);
            console.log(status);
            console.log(error);
            alert('An error occurred while updating payment status.');
        }
    });
}

        </script>
</head>
<body>
    <div class="page-container">
            <!--/content-inner-->
            <div class="left-content">
                <div class="inner-content">
                    <!-- header-starts -->
                    <!-- <?php include_once('includes/header.php'); ?> -->
                    <!-- //header-ends -->
                    <!--outter-wp-->
                    <div class="outter-wp">
                        <!--sub-heard-part-->
                        <div class="sub-heard-part">
                            <ol class="breadcrumb m-b-0">
                                <li><a href="dashboard.php">Dashboard</a></li>
                                <li class="active">Payment Overdue</li>
                            </ol>
                        </div>
                        <!--//sub-heard-part-->
                        <div class="graph-visual tables-main">

                            <h3 class="inner-tittle two">Payment Overdue</h3>
                            <div class="graph">
                            <div class="search-bar-container">
                                <div class="search-bar">
                                    <form method="GET" action="">
                                        <input type="text" name="search" placeholder="Search clients...">
                                        <button id = "sbutton" type="submit"><i class = "lnr lnr-magnifier"></i></button>
                                    </form>
                                </div>
                            </div>
                                <div class="tables">
                                    <table class="table" border="1" id ="client-table">
                                        <thead>
                                        <tr>
                                                <th style="border: 1px solid black;">Job</th>
                                                <th style="border: 1px solid black;">Client</th>
                                                <th style="border: 1px solid black;">Company</th>
                                                <th style="border: 1px solid black;">Financial Year</th>
                                                <th style="border: 1px solid black;">File</th>
                                                <th style="border: 1px solid black;">Task</th>
                                                <th style="border: 1px solid black;">Deadline</th>
                                                <th style="border: 1px solid black;">Status</th>
                                                <th style="border: 1px solid black;">Payment Status</th>
                                            </tr>
                                        </thead>
                                        <tbody style="border= 1px solid black;">
                                            <?php
                                            $cnt = 1;
                                            if ($query->rowCount() > 0) {
                                                foreach ($clients as $client) {
                                                    ?>
                                                    <tr class="active">
                                                        <th scope="row"><?php echo htmlentities($client->ID); ?></th>
                                                        <td onclick="window.location.href='edit-client-details.php?viewid=<?php echo $client->ID; ?>'"><?php echo htmlentities($client->ContactName); ?></td>
                                                        <td onclick="window.location.href='edit-client-details.php?viewid=<?php echo $client->ID; ?>'"><?php echo htmlentities($client->CompanyName); ?></td>
                                                        <td onclick="window.location.href='edit-client-details.php?viewid=<?php echo $client->ID; ?>'"><?php echo htmlentities($client->financialyear); ?></td>
                                                        <td onclick="window.location.href='edit-client-details.php?viewid=<?php echo $client->ID; ?>'"><?php echo htmlentities($client->file); ?></td>
                                                        <td onclick="window.location.href='edit-client-details.php?viewid=<?php echo $client->ID; ?>'"><?php echo htmlentities($client->Tag); ?></td>
                                                        <td onclick="window.location.href='edit-client-details.php?viewid=<?php echo $client->ID; ?>'"><?php echo htmlentities($client->deadline); ?></td>
                                                        
                                                        <td>
                                                            <select name="status" onchange="updateStatus(<?php echo $client->ID; ?>, this.value)">
                                                                <option value="Not Started" <?php if ($client->Status == 'Not Started') echo 'selected'; ?>>Not Started</option>
                                                                <option value="In Process" <?php if ($client->Status == 'In Process') echo 'selected'; ?>>In Process</option>
                                                                <option value="Completed" <?php if ($client->Status == 'Completed') echo 'selected'; ?>>Completed</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="payment_status" onchange="updatePaymentStatus(<?php echo $client->ID; ?>, this.value)">
                                                            <option value="Pending" <?php if ($client->PaymentStatus == 'Pending') echo 'selected'; ?>>Pending</option>
                                                            <option value="Paid" <?php if ($client->PaymentStatus == 'Paid') echo 'selected'; ?>>Paid</option>
                                                            <option value="Overdue" <?php if ($client->PaymentStatus == 'Overdue') echo 'selected'; ?>>Overdue</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $cnt++;
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="6">No clients assigned.</td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <!--//graph-visual-->
                    </div>
                    <!--//outer-wp-->
                </div>
            </div>
            <!--//content-inner-->
            <!--/sidebar-menu-->
            <?php include_once('includes/sidebar.php');?>
            <div class="clearfix"></div>	
            <script>	
            var toggle = true;

$j(".sidebar-icon").click(function() {                
	if (toggle)
	{
		$j(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
		$j("#menu span").css({"position":"absolute"});
	}
	else
	{
		$j(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
		setTimeout(function() {
			$j("#menu span").css({"position":"relative"});
		}, 400);
	}

	toggle = !toggle;
});
</script>
        </div>


        <!--js -->
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>
        <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
