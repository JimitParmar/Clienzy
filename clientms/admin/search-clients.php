<?php
include('includes/dbconnection.php');

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];

    // Fetch clients based on the search query
    $sql = "SELECT * FROM tblclient WHERE ContactName LIKE :search";
    $query = $dbh->prepare($sql);
    $query->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    // Iterate through the search results and generate table rows
    foreach ($results as $row) {
        echo '<tr>';
        echo '<td>' . $row['ContactName'] . '</td>'; // Modify based on your table structure
        echo '<td>' . $row['CompanyName'] . '</td>'; // Modify based on your table structure
        // Add more table cells as needed
        echo '</tr>';
    }
} else {
    // If no search query is provided, fetch all clients
    $sql = "SELECT * FROM tblclient";
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    // Iterate through all clients and generate table rows
    foreach ($results as $row) {
        echo '<tr>';
        echo '<td>' . $row['ContactName'] . '</td>'; // Modify based on your table structure
        echo '<td>' . $row['CompanyName'] . '</td>'; // Modify based on your table structure
        // Add more table cells as needed
        echo '</tr>';
    }
}
?>
