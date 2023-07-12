<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('includes/dbconnection.php');

if (isset($_POST['ID'])) {
    $uniqueIdentifier = $_POST['ID'];

    try {
        // Retrieve the client ID based on the unique identifier
        $sql = "SELECT ID FROM tblclient WHERE ID = :uniqueIdentifier";
        $query = $dbh->prepare($sql);
        $query->bindParam(':uniqueIdentifier', $uniqueIdentifier, PDO::PARAM_INT);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $clientId = $row['ID'];

            $response = array(
                'success' => true,
                'client_id' => $clientId
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Failed to retrieve client ID.'
            );
        }
    } catch (PDOException $e) {
        $response = array(
            'success' => false,
            'message' => 'An error occurred while retrieving client ID: ' . $e->getMessage()
        );
    }
} else {
    $response = array(
        'success' => false,
        'message' => 'Invalid parameters.'
    );
}

echo json_encode($response);
?>
