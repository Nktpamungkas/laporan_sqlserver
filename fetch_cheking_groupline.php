<?php
require_once "koneksi.php";

if (isset($_POST['production_number'])) {
    $productionNumber = $_POST['production_number'];

    try {
        // Prepare the SQL query
        $sql_query = "SELECT
                 DISTINCT VIEWPRODUCTIONRESERVATION.GROUPLINE AS GROUPLINE
                FROM
                    VIEWPRODUCTIONRESERVATION VIEWPRODUCTIONRESERVATION
                LEFT JOIN ITXVIEWKK i ON i.PRODUCTIONORDERCODE = VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE
                LEFT JOIN ITXVIEW_RESERVATION ir ON ir.PRODRESERVATIONLINKGROUPCODE = VIEWPRODUCTIONRESERVATION.PRODRESERVATIONLINKGROUPCODE 
                                                AND ir.PRODUCTIONORDERCODE = VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE 
                WHERE
                    VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE = '$productionNumber'
                    AND VIEWPRODUCTIONRESERVATION.ITEMTYPEAFICODE = 'RFD'";

        // Execute the query
        $result = db2_exec($conn1, $sql_query);
        $groupLineArray = [];

        while ($groupLine = db2_fetch_assoc($result)) {
            $groupLineArray[] = $groupLine['GROUPLINE'];
        }

        // Check if there are results and respond accordingly
        if ($result) {
            echo json_encode([
                'success' => true,
                'groupLine' => $groupLineArray
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No records found']);
        }
    } catch (PDOException $e) {
        // Handle any errors
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false]);
}
