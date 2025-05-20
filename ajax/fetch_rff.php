<?php
require_once "../koneksi.php";

$subcode01 = $_POST['recipecode'] ?? '';
$suffixcode = $_POST['suffixcode'] ?? '';
$query = "SELECT * FROM RECIPE WHERE SUBCODE01 = '$subcode01' AND SUFFIXCODE = '$suffixcode'";
$exec = db2_exec($conn1, $query);
$dataMain = db2_fetch_assoc($exec);

    $numberid = $dataMain['NUMBERID'];
    $queryRecipeComponent   = "SELECT 
                                TRIM(r.GROUPNUMBER) AS GROUPNUMBER,
                                TRIM(r.GROUPTYPECODE) AS GROUPTYPECODE,
                                TRIM(r.SEQUENCE) AS SEQUENCE,
                                TRIM(r.SUBSEQUENCE) AS SUBSEQUENCE,
                                CASE
                                  WHEN r.GROUPTYPECODE = '201' THEN TRIM(r.SUBCODE01)
                                  WHEN r.GROUPTYPECODE = '100' THEN ''
                                  WHEN r.GROUPTYPECODE = '010' THEN TRIM(r.SUBCODE01) || '-' || TRIM(r.SUBCODE02)|| '-' || TRIM(r.SUBCODE03)
                                  WHEN r.GROUPTYPECODE = '001' THEN TRIM(r.SUBCODE01) || '-' || TRIM(r.SUBCODE02)|| '-' || TRIM(r.SUBCODE03)
                                END AS ITEMCODE,
                                TRIM(r.ITEMTYPEAFICODE) AS ITEMTYPEAFICODE,
                                TRIM(r.SUBCODE01) AS SUBCODE01,
                                TRIM(r.SUBCODE02) AS SUBCODE02,
                                TRIM(r.SUBCODE03) AS SUBCODE03,
                                TRIM(r.SUFFIXCODE) AS SUFFIXCODE,
                                TRIM(r.SUFFIXCODE) AS RECIPESUFFIXCODE,
                                TRIM(r.COMMENTLINE) AS COMMENTLINE,
                                COALESCE(TRIM(p.LONGDESCRIPTION), TRIM(r2.SHORTDESCRIPTION)) AS LONGDESCRIPTION,
                                 CASE 
                                    WHEN r.CONSUMPTIONTYPE = '2' THEN 'Percentage'
                                    WHEN r.CONSUMPTIONTYPE = '1' THEN 'Quantity'
                                    ELSE ''
                                  END AS CONSUMPTIONTYPE,
                                TRIM(r.COMPONENTUOMCODE) AS COMPONENTUOMCODE,
                                CASE
                                  WHEN TRIM(r.CONSUMPTION) ='.00000' THEN NULL
                                  ELSE TRIM(r.CONSUMPTION)
                                END AS CONSUMPTION
                            FROM 
                                RECIPECOMPONENT r 
                            LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = r.ITEMTYPEAFICODE 
                                AND p.SUBCODE01 = r.SUBCODE01 
                                AND p.SUBCODE02 = r.SUBCODE02 
                                AND p.SUBCODE03 = r.SUBCODE03
                            LEFT JOIN RECIPE r2 ON r2.ITEMTYPECODE = r.ITEMTYPEAFICODE 
                                AND r2.SUBCODE01 = r.SUBCODE01 
                                AND r2.SUFFIXCODE = r.SUFFIXCODE 
                            WHERE r.RECIPENUMBERID = '$dataMain[NUMBERID]'";
    $query  = db2_exec($conn1, $queryRecipeComponent);

if (!$query) {
    echo json_encode(['success' => false, 'message' => 'Query gagal dijalankan']);
    exit;
}

$data = [];
while ($row = db2_fetch_assoc($query)) {
    $data[] = $row;
}

echo json_encode(['success' => true, 
    'recipecode_before' => $dataMain['SUBCODE01'],
    'suffix_before'     => $dataMain['SUFFIXCODE'],
    'lr_before'         => $dataMain['LIQUORRATIO'],
    'longdescription'   => $dataMain['LONGDESCRIPTION'],
    'shortdescription'  => $dataMain['SHORTDESCRIPTION'],
    'searchdescription' => $dataMain['SEARCHDESCRIPTION'],
    'data' => $data]);
