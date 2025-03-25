<?php 
include "../koneksi.php";
// Ambil parameter production order dari request
$productionorder = isset($_POST['prod_order']) ? $_POST['prod_order'] : '';

// Query untuk mengambil data Line Reservation berdasarkan Production Order
$qgroupLine = "SELECT DISTINCT 
                    VIEWPRODUCTIONRESERVATION.GROUPLINE AS GROUPLINE,
                    VIEWPRODUCTIONRESERVATION.GROUPSTEPNUMBER
                FROM
                    VIEWPRODUCTIONRESERVATION
                LEFT JOIN ITXVIEWKK i ON
                    i.PRODUCTIONORDERCODE = VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE
                LEFT JOIN ITXVIEW_RESERVATION ir ON
                    ir.PRODRESERVATIONLINKGROUPCODE = VIEWPRODUCTIONRESERVATION.PRODRESERVATIONLINKGROUPCODE
                    AND ir.PRODUCTIONORDERCODE = VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE
                WHERE
                    VIEWPRODUCTIONRESERVATION.PRODUCTIONORDERCODE = '$productionorder'
                    AND VIEWPRODUCTIONRESERVATION.ITEMTYPEAFICODE IN ('RFD')
                ORDER BY
                    VIEWPRODUCTIONRESERVATION.GROUPSTEPNUMBER ASC,
                    VIEWPRODUCTIONRESERVATION.GROUPLINE ASC";

// Eksekusi query
$stmtgroupLine = db2_exec($conn1, $qgroupLine);

// Siapkan array untuk menyimpan hasil
$groupLines = [];

// Loop untuk mengambil data dan menyimpannya dalam array
while($groupLine = db2_fetch_assoc($stmtgroupLine)) {
    $groupLines[] = [
        'GROUPLINE' => $groupLine['GROUPLINE'],
        'GROUPSTEPNUMBER' => $groupLine['GROUPSTEPNUMBER']
    ];
}

// Kirimkan data sebagai JSON
echo json_encode(['success' => true, 'lines' => $groupLines]);
?>
