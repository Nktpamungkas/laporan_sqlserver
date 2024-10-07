<?php
require_once "koneksi.php"; // Make sure this file establishes the PDO connection

try {
    // Prepare the SQL query
    $sql_query = $pdo_orgatex->prepare("SELECT * FROM dbo.Dyelots ORDER BY AutoKey DESC");

    // Execute the query
    $sql_query->execute();

    // Fetch all results
    $dyelots = $sql_query->fetchAll(PDO::FETCH_ASSOC);

    // Check if there are results and respond accordingly
    if ($dyelots) {
        echo json_encode([
            'success' => true,
            'dyelots' => $dyelots
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No records found']);
    }
} catch (PDOException $e) {
    // Handle any errors
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
