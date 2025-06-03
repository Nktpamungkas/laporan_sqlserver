<?php
require_once "../koneksi.php";

// Ambil parameter
$grouptype = $_GET['GROUPTYPECODE'] ?? '';
$search = $_GET['q'] ?? '';

// Normalisasi grouptype ke format 3-digit
if ($grouptype == 1) {
    $grouptype = '001';
} elseif ($grouptype == 10) {
    $grouptype = '010';
} elseif ($grouptype == 100) {
    $grouptype = '100';
} else {
    $grouptype = '201';
}

// Escaping pencarian (basic sanitasi, sesuaikan dengan DB2 Anda)
$search = strtoupper($search); // optional, ignore case
$search = str_replace("'", "''", $search); // escape kutip

// Siapkan kondisi pencarian khusus per grup
$whereLike = "";
switch ($grouptype) {
    case '201':
        $whereLike = "AND TRIM(r2.SUBCODE01) || '-' || TRIM(r2.SUFFIXCODE) LIKE '%$search%'";
        break;
    case '100':
        $whereLike = "AND TRIM(r2.COMMENTLINE) LIKE '%$search%'";
        break;
    case '001':
        $whereLike = "AND TRIM(r2.SUBCODE01) || '-' || TRIM(r2.SUBCODE02) || '-' || TRIM(r2.SUBCODE03) LIKE '%$search%'";
        break;
    case '010':
        $whereLike = "AND TRIM(r2.SUBCODE01) || '-' || TRIM(r2.SUBCODE02) || '-' || TRIM(r2.SUBCODE03) LIKE '%$search%'";
        break;
    default:
        $whereLike = "AND 1=0"; // fallback kosong
        break;
}

// Query utama
$query = "SELECT DISTINCT
    TRIM(r2.GROUPTYPECODE) AS GROUPTYPECODE,
    CASE
        WHEN r2.GROUPTYPECODE = '201' THEN TRIM(r2.SUBCODE01) || '-'||TRIM(r2.SUFFIXCODE)
        WHEN r2.GROUPTYPECODE = '100' THEN TRIM(r2.COMMENTLINE)
        WHEN r2.GROUPTYPECODE IN ('010','001') THEN TRIM(r2.SUBCODE01) || '-' || TRIM(r2.SUBCODE02) || '-' || TRIM(r2.SUBCODE03)
        ELSE ''
    END AS ITEMCODE,
    CASE
        WHEN r2.GROUPTYPECODE = '201' THEN TRIM(r2.SUBCODE01)
        WHEN r2.GROUPTYPECODE = '100' THEN TRIM(r2.COMMENTLINE)
        WHEN r2.GROUPTYPECODE IN ('010','001') THEN TRIM(r2.SUBCODE01) || '-' || TRIM(r2.SUBCODE02) || '-' || TRIM(r2.SUBCODE03)
        ELSE ''
    END AS ITEMCODE2,
    TRIM(r2.ITEMTYPEAFICODE) AS ITEMTYPEAFICODE,
    TRIM(r2.SUBCODE01) AS SUBCODE01,
    TRIM(r2.SUBCODE02) AS SUBCODE02,
    TRIM(r2.SUBCODE03) AS SUBCODE03,
    TRIM(r2.SUFFIXCODE) AS SUFFIXCODE,
    TRIM(r2.COMMENTLINE) AS COMMENTLINE,
    COALESCE(TRIM(p.LONGDESCRIPTION), TRIM(r.SHORTDESCRIPTION)) AS LONGDESCRIPTION
    -- CASE 
    --     WHEN r2.CONSUMPTIONTYPE = '2' THEN 'Percentage'
    --     WHEN r2.CONSUMPTIONTYPE = '1' THEN 'Quantity'
    --     ELSE ''
    -- END AS CONSUMPTIONTYPE,
    -- TRIM(r2.COMPONENTUOMCODE) AS COMPONENTUOMCODE,
    -- CASE
    --     WHEN TRIM(r2.CONSUMPTION) ='.00000' THEN NULL
    --     ELSE TRIM(r2.CONSUMPTION)
    -- END AS CONSUMPTION
FROM RECIPECOMPONENT r2
LEFT JOIN PRODUCT p ON
    p.ITEMTYPECODE = r2.ITEMTYPEAFICODE
    AND p.SUBCODE01 = r2.SUBCODE01
    AND p.SUBCODE02 = r2.SUBCODE02
    AND p.SUBCODE03 = r2.SUBCODE03
LEFT JOIN RECIPE r ON
    r.ITEMTYPECODE = r2.ITEMTYPEAFICODE
    AND r.SUBCODE01 = r2.SUBCODE01
    AND r.SUFFIXCODE = r2.SUFFIXCODE
WHERE r2.GROUPTYPECODE = '$grouptype'
$whereLike
FETCH FIRST 15 ROWS ONLY
";

// Eksekusi query
$result = db2_exec($conn1, $query);

$data = [];
while ($row = db2_fetch_assoc($result)) {
    if($grouptype == '100' OR $grouptype == '001'){
        $row['COMPONENTUOMCODE'] = 'g';
    }
    $data[] = [
        'id' => $row['ITEMCODE2'], // untuk value select2
        'text' => $row['ITEMCODE'], // untuk tampilan select2
        'suffixcode' => $row['SUFFIXCODE'],
        'subcode01' => $row['SUBCODE01'],
        'subcode02' => $row['SUBCODE02'],
        'subcode03' => $row['SUBCODE03'],
        'longdescription' => $row['LONGDESCRIPTION'],
        'commentline' => $row['COMMENTLINE'],
        'consumptiontype' => $row['CONSUMPTIONTYPE'],
        'consumption' => $row['CONSUMPTION'],
        'uom' => $row['COMPONENTUOMCODE']
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
?>
