<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['clientmsaid']) == 0) {
    header('location:logout.php');
} else {
    $adminid=$_SESSION['clientmsaid'];

    // Check if the form is submitted and the required fields are set
    if (isset($_POST['client_id']) && isset($_POST['status'])) {
        $clientId = $_POST['client_id'];
        $status = $_POST['status'];

        // Update the status of the client in the database
        $sql = "UPDATE tblclient SET Status=:status WHERE ID=:clientId";
        $query = $dbh->prepare($sql);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':clientId', $clientId, PDO::PARAM_INT);

        if ($query->execute()) {
            // Status updated successfully
            echo json_encode(['success' => true, 'message' => 'Client status updated successfully']);
            exit();
        } else {
            // Error occurred while updating status
            echo json_encode(['success' => false, 'message' => 'Failed to update client status']);
            exit();
        }
    } else {
        // Invalid request
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        exit();
    }
}
?>
