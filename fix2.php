<?php
require_once "koneksi.php";

// Query SQL yang akan dijalankan
$query = "SELECT
                SUBSTRING(ID_NO, 1, 8) AS PRODUCTIONORDER
            FROM
                TICKET.dbo.TICKET_DETAIL
            WHERE
                PRODUCT_CODE = 'E-1-002'
                AND LEN(ID_NO) > 9
                AND (ACTUAL_WT IS NULL OR ACTUAL_WT = '0')
            ORDER BY 
                ID_NO DESC";

// Menjalankan query
$result = sqlsrv_query($conn_sql2, $query);
if ($result === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>
<table border='1'>
    <tr>
        <th>PRODUCTIONORDER</th>
    </tr>
    <?php while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) { ?>
        <?php
            $productionOrder = $row['PRODUCTIONORDER'];

            // Query SQL kedua dengan parameterized query
            $query2 = "SELECT
                            rs_reservationline,
                            CONCAT(SUBSTRING(CONVERT(VARCHAR, RS_CREATIONDATETIME, 120), 1, 4),
                                   SUBSTRING(CONVERT(VARCHAR, RS_CREATIONDATETIME, 120), 6, 2),
                                   SUBSTRING(CONVERT(VARCHAR, RS_CREATIONDATETIME, 120), 9, 2)) AS YearPart,
                            SUBSTRING(CONVERT(VARCHAR, RS_CREATIONDATETIME, 120), 12, 8) AS timePart,
                            rs_productionordercode,
                        CASE
                                WHEN ( rs_subcode02 = '' AND rs_subcode03 = '' ) 
                                OR ( rs_subcode02 IS NULL AND rs_subcode03 IS NULL ) THEN
                                    rs_subcode01 ELSE CONCAT ( rs_subcode01, '-', rs_subcode02, '-', rs_subcode03 ) 
                                    END AS CODE,
                                RS_USERPRIMARYQUANTITY AS ACUAN_QTY,
                                CAST(RS_USEDUSERPRIMARYQUANTITY AS INT) AS AKTUAL_QTY 
                        FROM
                            tab_rs 
                        WHERE
                            rs_productionordercode LIKE ?
                            AND NOT ( rs_subcode02 = '' OR rs_subcode03 = '' )
                            AND NOT RS_USEDUSERPRIMARYQUANTITY = 0
                            AND CONCAT(RS_SUBCODE01, '-', RS_SUBCODE02, '-', RS_SUBCODE03) = 'E-1-002'";

            $params2 = array("%$productionOrder%");
            $result2 = sqlsrv_query($conn_cams, $query2, $params2);
            if ($result2 === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            $row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);
            if ($row2 !== false) {
                $aktualQty = $row2['AKTUAL_QTY'];
                $yearPart = $row2['YearPart'];
                $timePart = $row2['timePart'];
                $rsProductionOrderCode = $row2['rs_productionordercode'];

                // Query SQL ketiga dengan parameterized query
                $query3 = "UPDATE
                                TICKET.dbo.TICKET_DETAIL
                            SET ACTUAL_WT = ?,
                                COMP_DATE = ?,
                                COMP_TIME = ?	
                            WHERE
                                PRODUCT_CODE = 'E-1-002'
                                AND LEN(ID_NO) > 9
                                AND ACTUAL_WT IS NULL
                                AND SUBSTRING(ID_NO, 1, 8) = ?";

                $params3 = array($aktualQty, $yearPart, $timePart, $rsProductionOrderCode);
                $result3 = sqlsrv_query($conn_sql2, $query3, $params3);
                if ($result3 === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
            }
        ?>
        <tr>
            <td><?php echo $row['PRODUCTIONORDER']; ?></td>
        </tr>
    <?php } ?>
</table>

<?php
// Menutup koneksi
sqlsrv_free_stmt($result);
sqlsrv_close($conn_sql2);
sqlsrv_close($conn_cams);
?>