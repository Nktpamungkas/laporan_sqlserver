<?php
// Koneksi ke database
require_once "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipe_code_new     = $_POST['recipe_code_new'];
    $suffix_new          = $_POST['suffix_new'];
    $long_new            = $_POST['long_new'];
    $short_new           = $_POST['short_new'];
    $search_new          = $_POST['search_new'];
    $lr_new              = $_POST['lr_new'];
    $tgl                 = date('Y-m-d H:i:s');

    $insertPrefix           = "INSERT INTO recipeprefix(recipe_code, suffix) VALUES ('$recipe_code_new', '$suffix_new')";
    $insertPrefixResult     = mysqli_query($con_db_lab, $insertPrefix);
    $IMPORTAUTOCOUNTER      = mysqli_insert_id($con_db_lab);

    $queryDataMain  = "INSERT INTO RECIPEBEAN (
                                        COMPANYCODE,
                                        IMPORTAUTOCOUNTER,
                                        DIVISIONCODE,
                                        ALLOWEDDIVISIONS,
                                        NUMBERID,
                                        RECIPETEMPLATECODE,
                                        ITEMTYPECODE,
                                        RECIPETYPE,
                                        SUBCODE01,
                                        SUBCODE02,
                                        SUBCODE03,
                                        SUBCODE04,
                                        SUBCODE05,
                                        SUBCODE06,
                                        SUBCODE07,
                                        SUBCODE08,
                                        SUBCODE09,
                                        SUBCODE10,
                                        SUFFIXCODE,
                                        GENERICRECIPE,
                                        LONGDESCRIPTION,
                                        SHORTDESCRIPTION,
                                        SEARCHDESCRIPTION,
                                        REFSUBCODE01,
                                        REFSUBCODE02,
                                        REFSUBCODE03,
                                        REFSUBCODE04,
                                        REFSUBCODE05,
                                        REFSUBCODE06,
                                        REFSUBCODE07,
                                        REFSUBCODE08,
                                        REFSUBCODE09,
                                        REFSUBCODE10,
                                        REFRECIPESUFFIXCODE,
                                        REFRECIPENUMBERID,
                                        GENERICREFERENCE,
                                        VALIDFROMDATE,
                                        VALIDTODATE,
                                        LIMITINPOBYNUMBEROFUSES,
                                        MAXNUMBEROFUSES,
                                        NUMBEROFUSES,
                                        SOLUTIONPASTEUMCODE,
                                        SOLUTIONPASTEWEIGHT,
                                        RECIPEINCIDENCE,
                                        SOLUTIONPASTEUMWEIGHTUMCODE,
                                        PRODUCTIONUMCODE,
                                        PRODUCTIONUMWEIGHT,
                                        PRODUCTIONUMWEIGHTUMCODE,
                                        BATCHSTANDARDSIZE,
                                        AVERAGELENGTH,
                                        BATCHAVERAGEUMCODE,
                                        DILUITIONPERCENTAGE,
                                        PICKUPPERCENTAGE,
                                        DRYRESIDUALPERCENTAGE,
                                        DRYRESIDUALQUANTITY,
                                        GLOBALWASTEPERCENTAGE,
                                        BATHVOLUME,
                                        RESIDUALBATHVOLUME,
                                        VOLUMEUMCODE,
                                        COMPOSITIONCODE,
                                        LIQUORRATIO,
                                        MIXVOLUME,
                                        PRODUCTIONRESERVATIONGROUPCODE,
                                        COSTGROUPCODE,
                                        USESUBRECIPEHEADERVALUES,
                                        BINDERFLUIDSRATIO,
                                        BINDERMINPERCENTAGE,
                                        BINDERITEMTYPECODE,
                                        BSUBCODE01,
                                        BSUBCODE02,
                                        BSUBCODE03,
                                        BSUBCODE04,
                                        BSUBCODE05,
                                        BSUBCODE06,
                                        BSUBCODE07,
                                        BSUBCODE08,
                                        BSUBCODE09,
                                        BSUBCODE10,
                                        FILLERITEMTYPECODE,
                                        FSUBCODE01,
                                        FSUBCODE02,
                                        FSUBCODE03,
                                        FSUBCODE04,
                                        FSUBCODE05,
                                        FSUBCODE06,
                                        FSUBCODE07,
                                        FSUBCODE08,
                                        FSUBCODE09,
                                        FSUBCODE10,
                                        BINDERGROUPNUMBER,
                                        BINDERGROUPTYPECODE,
                                        FILLERGROUPNUMBER,
                                        FILLERGROUPTYPECODE,
                                        TRANSLATEDLONGDESCRIPTION,
                                        TRANSLATEDLANGUAGECODE,
                                        TRANSLATEDSHORTDESCRIPTION,
                                        STATUS,
                                        APPROVALDATE,
                                        APPROVALUSER,
                                        CREATEHEADER,
                                        WSOPERATION,
                                        IMPOPERATIONUSER,
                                        IMPORTSTATUS,
                                        IMPCREATIONDATETIME,
                                        IMPCREATIONUSER,
                                        IMPLASTUPDATEDATETIME,
                                        IMPLASTUPDATEUSER,
                                        IMPORTDATETIME,
                                        RETRYNR,
                                        NEXTRETRY,
                                        IMPORTID,
                                        RELATEDDEPENDENTID,
                                        FATHERID,
                                        OWNEDCOMPONENT,
                                        RECIPEITEMTYPECODE,
                                        RECIPESUBCODE01,
                                        RECIPESUBCODE02,
                                        RECIPESUBCODE03,
                                        RECIPESUBCODE04,
                                        RECIPESUBCODE05,
                                        RECIPESUBCODE06,
                                        RECIPESUBCODE07,
                                        RECIPESUBCODE08,
                                        RECIPESUBCODE09,
                                        RECIPESUBCODE10,
                                        RECIPESUFFIXCODE,
                                        GROUPNUMBER,
                                        GROUPTYPECODE,
                                        LINETYPE,
                                        SEQUENCE,
                                        ALTERNATIVE,
                                        SUBSEQUENCE,
                                        COMPONENTINCIDENCE,
                                        REFRECIPEGROUPNUMBER,
                                        REFRECIPESEQUENCE,
                                        REFRECIPEALTERNATIVE,
                                        REFRECIPESUBSEQUENCE,
                                        REFRECIPESTATUS,
                                        ITEMTYPEAFICODE,
                                        COMMENTLINE,
                                        CONSUMPTIONTYPE,
                                        ASSEMBLYUOMCODE,
                                        COMPONENTUOMCODE,
                                        COMPONENTUOMTYPE,
                                        CONSUMPTION,
                                        COMPOSITIONCOMPONENTCODE,
                                        CONSFORMIXLABEL,
                                        CONSPERBATCHLABEL,
                                        CONSPERLABEL,
                                        WATERMANAGEMENT,
                                        BINDERFILLERCOMPONENT,
                                        PRODUCED,
                                        PRICELISTCODE,
                                        COSTINGPLANTCODE,
                                        INITIALENGINEERINGCHANGE,
                                        FINALENGINEERINGCHANGE,
                                        INITIALDATE,
                                        FINALDATE,
                                        UNITARYBATCHSTANDARDSIZE,
                                        ALLOWDELETEBINDERFILLER,
                                        TOTALCOSTTEXT
    ) VALUES (
            '100', -- COMPANYCODE
            '$IMPORTAUTOCOUNTER', --IMPORTAUTOCOUNTER
            '', -- DIVISIONCODE
            NULL, -- ALLOWEDDIVISIONS
            '$IMPORTAUTOCOUNTER', --NUMBERID
            'FD', -- RECIPETEMPLATECODE
            'RFD', -- ITEMTYPECODE
            '2', -- RECIPETYPE
            '$recipe_code_new', --SUBCODE01
            '', -- SUBCODE02
            '', -- SUBCODE03
            '', -- SUBCODE04
            '', -- SUBCODE05
            '', -- SUBCODE06
            '', -- SUBCODE07
            '', -- SUBCODE08
            '', -- SUBCODE09
            '', -- SUBCODE10
            '$suffix_new', --SUFFIXCODE
            '0', -- GENERICRECIPE
            '$long_new', --LONGDESCRIPTION
            '$short_new', --SHORTDESCRIPTION
            '$search_new', --SEARCHDESCRIPTION
            '', -- REFSUBCODE01
            '', -- REFSUBCODE02
            '', -- REFSUBCODE03
            '', -- REFSUBCODE04
            '', -- REFSUBCODE05
            '', -- REFSUBCODE06
            '', -- REFSUBCODE07
            '', -- REFSUBCODE08
            '', -- REFSUBCODE09
            '', -- REFSUBCODE10
            '', -- REFRECIPESUFFIXCODE
            '0', -- REFRECIPENUMBERID
            '', -- GENERICREFERENCE
            '1970-01-01', -- VALIDFROMDATE
            '2100-12-31', -- VALIDTODATE
            '0', -- LIMITINPOBYNUMBEROFUSES
            '0', -- MAXNUMBEROFUSES
            '0', -- NUMBEROFUSES
            'l', -- SOLUTIONPASTEUMCODE
            '1', -- SOLUTIONPASTEWEIGHT
            '100', -- RECIPEINCIDENCE
            'kg', -- SOLUTIONPASTEUMWEIGHTUMCODE
            'kg', -- PRODUCTIONUMCODE
            '1', -- PRODUCTIONUMWEIGHT
            'kg', -- PRODUCTIONUMWEIGHTUMCODE
            '1000', -- BATCHSTANDARDSIZE
            '0', -- AVERAGELENGTH
            'kg', -- BATCHAVERAGEUMCODE
            '0', -- DILUITIONPERCENTAGE
            '0', -- PICKUPPERCENTAGE
            '0', -- DRYRESIDUALPERCENTAGE
            '0', -- DRYRESIDUALQUANTITY
            '0', -- GLOBALWASTEPERCENTAGE
            '1000', -- BATHVOLUME
            '0', -- RESIDUALBATHVOLUME
            'l', -- VOLUMEUMCODE
            '', -- COMPOSITIONCODE
            '$lr_new', --LIQUORRATIO
            '0', -- MIXVOLUME
            '001', -- PRODUCTIONRESERVATIONGROUPCODE
            '', -- COSTGROUPCODE
            '0', -- USESUBRECIPEHEADERVALUES
            '0', -- BINDERFLUIDSRATIO
            '0', -- BINDERMINPERCENTAGE
            '0', -- BINDERITEMTYPECODE
            '', -- BSUBCODE01
            '', -- BSUBCODE02
            '', -- BSUBCODE03
            '', -- BSUBCODE04
            '', -- BSUBCODE05
            '', -- BSUBCODE06
            '', -- BSUBCODE07
            '', -- BSUBCODE08
            '', -- BSUBCODE09
            '', -- BSUBCODE10
            '', -- FILLERITEMTYPECODE
            '', -- FSUBCODE01
            '', -- FSUBCODE02
            '', -- FSUBCODE03
            '', -- FSUBCODE04
            '', -- FSUBCODE05
            '', -- FSUBCODE06
            '', -- FSUBCODE07
            '', -- FSUBCODE08
            '', -- FSUBCODE09
            '', -- FSUBCODE10
            '0', -- BINDERGROUPNUMBER
            '', -- BINDERGROUPTYPECODE
            '0', -- FILLERGROUPNUMBER
            '0', -- FILLERGROUPTYPECODE
            NULL, -- TRANSLATEDLONGDESCRIPTION
            NULL, -- TRANSLATEDLANGUAGECODE
            NULL, -- TRANSLATEDSHORTDESCRIPTION
            '2', -- STATUS
            NULL, -- APPROVALDATE
            '', -- APPROVALUSER
            '1', -- CREATEHEADER
            '5', -- WSOPERATION
            'Testing.nilo', --IMPOPERATIONUSER
            '0', -- IMPORTSTATUS
            NULL, -- IMPCREATIONDATETIME
            NULL, -- IMPCREATIONUSER
            NULL, -- IMPLASTUPDATEDATETIME
            NULL, -- IMPLASTUPDATEUSER
            '$tgl', --IMPORTDATETIME,
            '0', -- RETRYNR
            '0', -- NEXTRETRY
            '0', -- IMPORTID
            '$IMPORTAUTOCOUNTER', --RELATEDDEPENDENTID
            NULL, -- FATHERID
            NULL, -- OWNEDCOMPONENT
            NULL, -- RECIPEITEMTYPECODE
            NULL, -- RECIPESUBCODE01
            NULL, -- RECIPESUBCODE02
            NULL, -- RECIPESUBCODE03
            NULL, -- RECIPESUBCODE04
            NULL, -- RECIPESUBCODE05
            NULL, -- RECIPESUBCODE06
            NULL, -- RECIPESUBCODE07
            NULL, -- RECIPESUBCODE08
            NULL, -- RECIPESUBCODE09
            NULL, -- RECIPESUBCODE10
            NULL, -- RECIPESUFFIXCODE
            NULL, -- GROUPNUMBER
            NULL, -- GROUPTYPECODE
            NULL, -- LINETYPE
            NULL, -- SEQUENCE
            NULL, -- ALTERNATIVE
            NULL, -- SUBSEQUENCE
            NULL, -- COMPONENTINCIDENCE
            NULL, -- REFRECIPEGROUPNUMBER
            NULL, -- REFRECIPESEQUENCE
            NULL, -- REFRECIPEALTERNATIVE
            NULL, -- REFRECIPESUBSEQUENCE
            NULL, -- REFRECIPESTATUS
            NULL, -- ITEMTYPEAFICODE
            NULL, -- COMMENTLINE
            NULL, -- CONSUMPTIONTYPE
            NULL, -- ASSEMBLYUOMCODE
            NULL, -- COMPONENTUOMCODE
            NULL, -- COMPONENTUOMTYPE
            NULL, -- CONSUMPTION
            NULL, -- COMPOSITIONCOMPONENTCODE
            NULL, -- CONSFORMIXLABEL
            NULL, -- CONSPERBATCHLABEL
            NULL, -- CONSPERLABEL
            NULL, -- WATERMANAGEMENT
            NULL, -- BINDERFILLERCOMPONENT
            NULL, -- PRODUCED
            NULL, -- PRICELISTCODE
            NULL, -- COSTINGPLANTCODE
            NULL, -- INITIALENGINEERINGCHANGE
            NULL, -- FINALENGINEERINGCHANGE
            NULL, -- INITIALDATE
            NULL, -- FINALDATE
            NULL, -- UNITARYBATCHSTANDARDSIZE
            NULL, -- ALLOWDELETEBINDERFILLER
            NULL -- TOTALCOSTTEXT
        )";
    $insert_recipeBean  = db2_exec($conn1, $queryDataMain);

    if ($insert_recipeBean) {
    // Eksekusi query tambahan dari JavaScript (jika ada)
    if (!empty($_POST['query_sql'])) {
    $queries = explode(";", $_POST['query_sql']);

        foreach ($queries as $q) {
            $q = trim($q);
            if ($q !== '') {
                // Ganti placeholder dengan nilai IMPORTAUTOCOUNTER
                $q = str_replace("'Anjay29181'", "'$IMPORTAUTOCOUNTER'", $q);

                $result = db2_exec($conn1, $q);
                if (!$result) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Gagal mengeksekusi salah satu query: ' . db2_stmt_errormsg()
                    ]);
                    exit;
                }
            }
        }
    }


    echo json_encode(['success' => true, 'message' => 'Recipe successfully exported to NOW.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to export recipe to NOW.']);
}

}

?>