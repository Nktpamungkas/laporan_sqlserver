<?php
require_once "../koneksi.php";

$subcode01 = $_POST['subcode01'] ?? '';
$suffixcode = $_POST['suffixcode'] ?? '';

if (!$subcode01) {
    echo json_encode(['success' => false, 'message' => 'Parameter subcode01 dibutuhkan']);
    exit;
}

$query = "SELECT 
            TRIM(r2.GROUPNUMBER) AS GROUPNUMBER,
            TRIM(r2.GROUPTYPECODE) AS GROUPTYPECODE,
            TRIM(r2.SEQUENCE) AS SEQUENCE,
            TRIM(r2.SUBSEQUENCE) AS SUBSEQUENCE,
            TRIM(r2.ITEMTYPEAFICODE) AS ITEMTYPEAFICODE,
            CASE
              WHEN r2.GROUPTYPECODE = '201' THEN TRIM(r2.SUBCODE01) || '-' || TRIM(r2.SUFFIXCODE)
              WHEN r2.GROUPTYPECODE = '100' THEN ''
              WHEN r2.GROUPTYPECODE = '010' THEN TRIM(r2.SUBCODE01) || '-' || TRIM(r2.SUBCODE02)|| '-' || TRIM(r2.SUBCODE03)
              WHEN r2.GROUPTYPECODE = '001' THEN TRIM(r2.SUBCODE01) || '-' || TRIM(r2.SUBCODE02)|| '-' || TRIM(r2.SUBCODE03)
            END AS ITEMCODE,
            TRIM(r2.SUBCODE01) AS SUBCODE01,
            TRIM(r2.SUBCODE02) AS SUBCODE02,
            TRIM(r2.SUBCODE03) AS SUBCODE03,
            TRIM(r2.SUFFIXCODE) AS SUFFIXCODE,
            TRIM(r2.COMMENTLINE) AS COMMENTLINE,
            COALESCE(TRIM(p.LONGDESCRIPTION), TRIM(r.SHORTDESCRIPTION)) AS LONGDESCRIPTION,
            CASE 
            	WHEN r2.CONSUMPTIONTYPE = '2' THEN 'Percentage'
            	WHEN r2.CONSUMPTIONTYPE = '1' THEN 'Quantity'
            	ELSE ''
            END AS CONSUMPTIONTYPE,
            -- TRIM(r2.CONSUMPTIONTYPE) AS CONSUMPTIONTYPE,
            TRIM(r2.COMPONENTUOMCODE) AS COMPONENTUOMCODE,
            TRIM(r2.RECIPESUFFIXCODE) AS RECIPESUFFIXCODE,
            CASE
              WHEN TRIM(r2.CONSUMPTION) ='.00000' THEN NULL
              ELSE TRIM(r2.CONSUMPTION)
            END AS CONSUMPTION
          FROM RECIPECOMPONENT r2
          LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = r2.ITEMTYPEAFICODE
            AND p.SUBCODE01 = r2.SUBCODE01
            AND p.SUBCODE02 = r2.SUBCODE02
            AND p.SUBCODE03 = r2.SUBCODE03
          LEFT JOIN RECIPE r ON
            r.ITEMTYPECODE = r2.ITEMTYPEAFICODE
            AND r.SUBCODE01 = r2.SUBCODE01
            AND r.SUFFIXCODE = r2.SUFFIXCODE
          WHERE r2.RECIPEITEMTYPECODE = 'RFF'
            AND r2.RECIPESUBCODE01 = '$subcode01'
            AND r2.RECIPESUFFIXCODE = '$suffixcode'";

$result = db2_exec($conn1, $query);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Query gagal dijalankan']);
    exit;
}

$data = [];
while ($row = db2_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode(['success' => true, 'data' => $data]);
