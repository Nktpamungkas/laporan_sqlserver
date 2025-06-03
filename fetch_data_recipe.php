<?php
require_once "koneksi.php";

$recipe_code    = $_POST['recipe_code'];
$suffix         = $_POST['suffix'];

$query = "SELECT * FROM RECIPE WHERE SUBCODE01 = '$recipe_code' AND SUFFIXCODE = '$suffix'";
$exec = db2_exec($conn1, $query);
$dataMain = db2_fetch_assoc($exec);

if ($dataMain) {
    $numberid = $dataMain['NUMBERID'];
    $queryRecipeComponent   = "SELECT 
                                TRIM(r.GROUPNUMBER) AS GROUPNUMBER,
                                TRIM(r.GROUPTYPECODE) AS GROUPTYPECODE,
                                TRIM(r.SEQUENCE) AS SEQUENCE,
                                TRIM(r.SUBSEQUENCE) AS SUBSEQUENCE,
                                TRIM(r.ITEMTYPEAFICODE) AS ITEMTYPEAFICODE,
                                TRIM(r.SUBCODE01) AS SUBCODE01,
                                TRIM(r.SUBCODE02) AS SUBCODE02,
                                TRIM(r.SUBCODE03) AS SUBCODE03,
                                TRIM(r.SUFFIXCODE) AS SUFFIXCODE,
                                TRIM(r.COMMENTLINE) AS COMMENTLINE,
                                COALESCE(TRIM(p.LONGDESCRIPTION), TRIM(r2.SHORTDESCRIPTION)) AS LONGDESCRIPTION,
                                TRIM(r.CONSUMPTIONTYPE) AS CONSUMPTIONTYPE,
                                TRIM(r.COMPONENTUOMCODE) AS COMPONENTUOMCODE,
                                TRIM(r.CONSUMPTION) AS CONSUMPTION
                            FROM 
                                RECIPECOMPONENT r 
                            LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = r.ITEMTYPEAFICODE 
                                AND p.SUBCODE01 = r.SUBCODE01 
                                AND p.SUBCODE02 = r.SUBCODE02 
                                AND p.SUBCODE03 = r.SUBCODE03
                            LEFT JOIN RECIPE r2 ON r2.ITEMTYPECODE = r.ITEMTYPEAFICODE 
                                AND r2.SUBCODE01 = r.SUBCODE01 
                                AND r2.SUFFIXCODE = r.SUFFIXCODE 
                            WHERE r.RECIPENUMBERID = '$numberid'";
    $resultRecipeComponent  = db2_exec($conn1, $queryRecipeComponent);
    $recipes = [];

    while ($recipeComponent = db2_fetch_assoc($resultRecipeComponent)) {
        $recipes[] = $recipeComponent;

        if($recipeComponent['ITEMTYPEAFICODE'] == 'RFF') {
            $queryRecipeComponentRFF   = "SELECT 
                                                TRIM(r2.GROUPNUMBER) AS GROUPNUMBER,
                                                TRIM(r2.GROUPTYPECODE) AS GROUPTYPECODE,
                                                TRIM(r2.SEQUENCE) AS SEQUENCE,
                                                TRIM(r2.SUBSEQUENCE) AS SUBSEQUENCE,
                                                TRIM(r2.ITEMTYPEAFICODE) AS ITEMTYPEAFICODE,
                                                TRIM(r2.SUBCODE01) AS SUBCODE01,
                                                TRIM(r2.SUBCODE02) AS SUBCODE02,
                                                TRIM(r2.SUBCODE03) AS SUBCODE03,
                                                TRIM(r2.SUFFIXCODE) AS SUFFIXCODE,
                                                TRIM(r2.COMMENTLINE) AS COMMENTLINE,
                                                TRIM(p.LONGDESCRIPTION) AS LONGDESCRIPTION,
                                                TRIM(r2.CONSUMPTIONTYPE) AS CONSUMPTIONTYPE,
                                                TRIM(r2.COMPONENTUOMCODE) AS COMPONENTUOMCODE,
                                                TRIM(r2.CONSUMPTION) AS CONSUMPTION
                                            FROM 
                                                RECIPECOMPONENT r2 
                                            LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = r2.ITEMTYPEAFICODE 
                                                AND p.SUBCODE01 = r2.SUBCODE01 
                                                AND p.SUBCODE02 = r2.SUBCODE02 
                                                AND p.SUBCODE03 = r2.SUBCODE03
                                            WHERE 
                                                r2.RECIPEITEMTYPECODE = 'RFF' 
                                                AND r2.RECIPESUBCODE01 = '$recipeComponent[SUBCODE01]' 
                                                AND r2.RECIPESUFFIXCODE = '$recipeComponent[SUFFIXCODE]'";
            $resultRecipeComponentRFF  = db2_exec($conn1, $queryRecipeComponentRFF);
            while ($rffComponent = db2_fetch_assoc($resultRecipeComponentRFF)) {
                $recipes[] = $rffComponent; // Tambahkan hasil RFF ke $recipes
            }
        }

    }
}

if ($dataMain) {
    echo json_encode([
        'success' => true,
        'numberid'          => $dataMain['NUMBERID'],
        'recipecode_before' => $dataMain['SUBCODE01'],
        'suffix_before'     => $dataMain['SUFFIXCODE'],
        'lr_before'         => $dataMain['LIQUORRATIO'],
        'longdescription'   => $dataMain['LONGDESCRIPTION'],
        'shortdescription'  => $dataMain['SHORTDESCRIPTION'],
        'searchdescription' => $dataMain['SEARCHDESCRIPTION'],
        'recipecomponent'   => $recipes,
    ]);
} else {
    echo json_encode(['success' => false]);
}

// SELECT
// 	*
// FROM
// 	RECIPE r
// WHERE 
// 	SUBCODE01 = '22078/240583D/D' AND SUFFIXCODE = '25012127D'
	
// SELECT
// 	TRIM(r.GROUPNUMBER) AS GROUPNUMBER,
// 	TRIM(r.GROUPTYPECODE) AS GROUPTYPECODE,
// 	TRIM(r.SEQUENCE) AS SEQUENCE,
// 	TRIM(r.SUBSEQUENCE) AS SUBSEQUENCE,
// 	TRIM(r.ITEMTYPEAFICODE) AS ITEMTYPEAFICODE,
// 	TRIM(r.SUBCODE01) AS SUBCODE01,
// 	TRIM(r.SUBCODE02) AS SUBCODE02,
// 	TRIM(r.SUBCODE03) AS SUBCODE03,
// 	TRIM(r.SUFFIXCODE) AS SUFFIXCODE,
// 	TRIM(r.COMMENTLINE) AS COMMENTLINE,
// 	COALESCE(TRIM(p.LONGDESCRIPTION), TRIM(r2.SHORTDESCRIPTION)) AS LONGDESCRIPTION,
// 	TRIM(r.CONSUMPTIONTYPE) AS CONSUMPTIONTYPE,
// 	TRIM(r.COMPONENTUOMCODE) AS COMPONENTUOMCODE,
// 	TRIM(r.CONSUMPTION) AS CONSUMPTION
// FROM
// 	RECIPECOMPONENT r
// LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = r.ITEMTYPEAFICODE 
// 					AND p.SUBCODE01 = r.SUBCODE01 
// 					AND p.SUBCODE02 = r.SUBCODE02 
// 					AND p.SUBCODE03 = r.SUBCODE03
// LEFT JOIN RECIPE r2 ON r2.ITEMTYPECODE = r.ITEMTYPEAFICODE 
// 					AND r2.SUBCODE01 = r.SUBCODE01 
// 					AND r2.SUFFIXCODE = r.SUFFIXCODE 
// WHERE 
// 	r.RECIPENUMBERID = '176917'
	
// SELECT
// 	*
// FROM
// 	RECIPECOMPONENT r2
// LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = r2.ITEMTYPEAFICODE 
// 					AND p.SUBCODE01 = r2.SUBCODE01 
// 					AND p.SUBCODE02 = r2.SUBCODE02 
// 					AND p.SUBCODE03 = r2.SUBCODE03
// WHERE
// 	r2.RECIPEITEMTYPECODE = 'RFF'
// 	AND r2.RECIPESUBCODE01 = 'SC3301'
// 	AND r2.RECIPESUFFIXCODE = '001'

?>

