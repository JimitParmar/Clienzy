<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['clientmsuid']) == 0) {
    header('location:logout.php');
} else {
    function getAssignedEmployeeName($clientId){
        global $dbh;

        $sql = "SELECT e.EmployeeName
                FROM tblassignments a
                JOIN tblemployee e ON a.EmployeeID = e.EmployeeID
                WHERE a.ID = :clientId";

        $query = $dbh->prepare($sql);
        $query->bindParam(':clientId', $clientId, PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return htmlentities($result['EmployeeName']);
        } else {
            // Fetch the client added by name if not assigned to any employee
            $addedBySql = "SELECT ClientAddedBy FROM tblclient WHERE ID = :clientId";
            $addedByQuery = $dbh->prepare($addedBySql);
            $addedByQuery->bindParam(':clientId', $clientId, PDO::PARAM_INT);
            $addedByQuery->execute();

            $addedByResult = $addedByQuery->fetch(PDO::FETCH_ASSOC);
            if ($addedByResult) {
                return htmlentities($addedByResult['ClientAddedBy']);
            } else {
                return "No employee assigned";
            }
        }
    }

    $employeeId = $_SESSION['clientmsuid'];
    $employeeName = $_SESSION['clientmsname'];

    $sql = "SELECT tblclient.*, 
        expiryDate1, 
        expiryDate2, 
        expiryDate3 
        FROM tblclient 
        INNER JOIN tblassignments ON tblclient.ID = tblassignments.ID 
        INNER JOIN tblemployee ON tblassignments.EmployeeID = tblemployee.EmployeeID 
        WHERE tblassignments.EmployeeID = :employeeId

        UNION

        SELECT tblclient.*, 
        expiryDate1, 
        expiryDate2, 
        expiryDate3 
        FROM tblclient 
        WHERE ClientAddedBy = :employeeName";



    $query = $dbh->prepare($sql);
    $query->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
    $query->bindParam(':employeeName', $employeeName, PDO::PARAM_STR);
    $query->execute();
    $clients = $query->fetchAll(PDO::FETCH_OBJ);

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $_GET['search'];

        // Fetch clients based on the search query
        $searchSql = "SELECT tblclient.* FROM tblclient 
                    INNER JOIN tblassignments ON tblclient.ID = tblassignments.ID 
                    INNER JOIN tblemployee ON tblassignments.EmployeeID = tblemployee.EmployeeID 
                    WHERE tblassignments.EmployeeID = :employeeId
                    AND (tblclient.ContactName LIKE :search OR tblclient.CompanyName LIKE :search OR tblclient.Tag LIKE :search)

                    UNION

                    SELECT * FROM tblclient WHERE ClientAddedBy = :employeeName
                    AND (ContactName LIKE :search OR CompanyName LIKE :search OR Tag LIKE :search)";

        $searchQuery = $dbh->prepare($searchSql);
        $searchQuery->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
        $searchQuery->bindParam(':employeeName', $employeeName, PDO::PARAM_STR);
        $searchQuery->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        $searchQuery->execute();
        $searchResults = $searchQuery->fetchAll(PDO::FETCH_OBJ);

        if ($searchQuery->rowCount() > 0) {
            // Clients found, display the search results
            $clients = $searchResults;
        } else {
            // No clients found for the search query
            $clients = [];
        }
    }
}
?>


<!DOCTYPE HTML>
<html>
<head>
    <title>Client Management System || Manage Client</title>
    <link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link href="css/font-awesome.css" rel="stylesheet"> 
    <link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
    <!-- <script src="js/jquery-1.10.2.min.js"></script> -->
    <script type="application/x-javascript"> 
        addEventListener("load", function() { 
            setTimeout(hideURLbar, 0); 
        }, false); 
            
        function hideURLbar(){ 
            window.scrollTo(0,1); 
        } 
    </script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- //js-->
    <script>
        // Update client status
        function updateStatus(ID, Status) {
            $.ajax({
                url: 'update-client-status.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    client_id: ID,
                    status: Status
                },
                success: function(response) {
                    if (response.success) {
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
    $.ajax({
        url: 'update-client-payment-status.php',
        type: 'POST',
        dataType: 'json',
        data: {
            client_id: ID,
            payment_status: PaymentStatus
        },
        success: function(response) {
            if (response.success) {
                alert(response.message);
            } else {
                alert('Failed to update payment status.');
            }
        },
        error: function() {
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
                <?php include_once('includes/header.php');?>
                <!-- //header-ends -->
                <!--outter-wp-->
                <div class="outter-wp">
                    <!--sub-heard-part-->
                    <div class="sub-heard-part">
                        <ol class="breadcrumb m-b-0">
                            <li><a href="dashboard.php">Home</a></li>
                            <li class="active">Manage DSC</li>
                        </ol>
                    </div>
                    <!--//sub-heard-part-->
                    <div class="graph-visual tables-main">
                        
                    
                        <h3 class="inner-tittle two">Manage DSC</h3>
                        <div class="graph">
                        <div class="search-bar-container">
                            <div class="search-bar">
                                <form method="GET" action="">
                                    <input type="text" name="search" placeholder="Search clients...">
                                    <button type="submit"><i class = "lnr lnr-magnifier"></i></button>
                                </form>
                            </div>
                        </div>
                            <div class="tables">
                                <table class="table" border="1" id="client-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th style="width: 10%;">Client </th>
                                            <th>IceGate</th>
                                            <th>DGFT</th>
                                            <th>Class3</th>
                                            <th>Setting</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cnt = 1;
                                        if (!empty($clients)) {
                                            foreach ($clients as $client) {
                                        ?>
                                                <tr class="active">
                                                    <th scope="row"><?php echo htmlentities($cnt); ?></th>
                                                    <td><?php echo htmlentities($client->ContactName); ?></td>
                                                    <td><?php echo htmlentities($client->expiryDate1); ?></td>
                                                    <td><?php echo htmlentities($client->expiryDate2); ?></td>
                                                    <td><?php echo htmlentities($client->expiryDate3); ?></td>
                                                    <td>
                                                        <a href="edit-dsc-details.php?viewid=<?php echo $client->ID; ?>">Edit</a>
                                                    </td>
                                                </tr>
                                        <?php
                                                $cnt++;
                                            }
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
    </div>
    <script>
        var toggle = true;

        $(".sidebar-icon").click(function() {                
            if (toggle) {
                $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
                $("#menu span").css({"position":"absolute"});
            } else {
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
