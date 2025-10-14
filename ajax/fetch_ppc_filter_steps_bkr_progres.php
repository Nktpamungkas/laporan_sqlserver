<?php
/**
 * Ambil detail data KK berdasarkan PRODUCTIONORDERCODE & PRODUCTIONDEMANDCODE.
 * @param resource $conn1 Koneksi DB2 aktif
 * @param string $prod_order
 * @param string $demand
 * @return array Hasil rows dari DB2
 */

require_once "koneksi.php";

function getProductionDetail($conn1, $prod_order, $demand)
{
    $sql = " SELECT 
            p.PRODUCTIONORDERCODE,
            p.STEPNUMBER AS STEPNUMBER,
            CASE
                WHEN TRIM(p.PRODRESERVATIONLINKGROUPCODE) IS NULL 
                     OR TRIM(p.PRODRESERVATIONLINKGROUPCODE) = '' 
                THEN TRIM(p.OPERATIONCODE)
                ELSE TRIM(p.PRODRESERVATIONLINKGROUPCODE)
            END AS OPERATIONCODE,
            TRIM(o.OPERATIONGROUPCODE) AS DEPT,
            o.LONGDESCRIPTION,
            CASE
                WHEN p.PROGRESSSTATUS = 0 THEN 'Entered'
                WHEN p.PROGRESSSTATUS = 1 THEN 'Planned'
                WHEN p.PROGRESSSTATUS = 2 THEN 'Progress'
                WHEN p.PROGRESSSTATUS = 3 THEN 'Closed'
            END AS STATUS_OPERATION,
            iptip.MULAI,
            CASE
                WHEN p.PROGRESSSTATUS = 3 
                THEN COALESCE(iptop.SELESAI, SUBSTRING(p.LASTUPDATEDATETIME, 1, 19) || '(Run Manual Closures)')
                ELSE iptop.SELESAI
            END AS SELESAI,
            p.PRODUCTIONDEMANDCODE,
            iptip.LONGDESCRIPTION AS OP1,
            iptop.LONGDESCRIPTION AS OP2,
            CASE 
                WHEN a.VALUEBOOLEAN = 1 THEN 'Tidak Perlu Gerobak'
                ELSE CASE 
                    WHEN LISTAGG(DISTINCT FLOOR(idqd.VALUEQUANTITY), ', ') = '1' THEN 'PLASTIK'
                    WHEN LISTAGG(DISTINCT FLOOR(idqd.VALUEQUANTITY), ', ') = '2' THEN 'TONG'
                    WHEN LISTAGG(DISTINCT FLOOR(idqd.VALUEQUANTITY), ', ') = '3' THEN 'DALAM MESIN'
                    ELSE LISTAGG(DISTINCT FLOOR(idqd.VALUEQUANTITY), ', ')
                END
            END AS GEROBAK,
            iptip.MACHINECODE
        FROM 
            PRODUCTIONDEMANDSTEP p
        LEFT JOIN OPERATION o 
            ON o.CODE = p.OPERATIONCODE
        LEFT JOIN ADSTORAGE a 
            ON a.UNIQUEID = o.ABSUNIQUEID 
           AND a.FIELDNAME = 'Gerobak'
        LEFT JOIN ITXVIEW_POSISIKK_TGL_IN_PRODORDER iptip 
            ON iptip.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE 
           AND iptip.DEMANDSTEPSTEPNUMBER = p.STEPNUMBER
        LEFT JOIN ITXVIEW_POSISIKK_TGL_OUT_PRODORDER iptop 
            ON iptop.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE 
           AND iptop.DEMANDSTEPSTEPNUMBER = p.STEPNUMBER
        LEFT JOIN ITXVIEW_DETAIL_QA_DATA idqd 
            ON idqd.PRODUCTIONDEMANDCODE = p.PRODUCTIONDEMANDCODE 
           AND idqd.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
           AND idqd.OPERATIONCODE = CASE
                WHEN TRIM(p.PRODRESERVATIONLINKGROUPCODE) IS NULL 
                     OR TRIM(p.PRODRESERVATIONLINKGROUPCODE) = '' 
                THEN TRIM(p.OPERATIONCODE)
                ELSE TRIM(p.PRODRESERVATIONLINKGROUPCODE)
            END
           AND (idqd.VALUEINT = p.STEPNUMBER OR idqd.VALUEINT = p.GROUPSTEPNUMBER)
           AND idqd.CHARACTERISTICCODE IN (
                'GRB1','GRB2','GRB3','GRB4','GRB5','GRB6','GRB7','GRB8',
                'GRB9','GRB10','GRB11','GRB12','GRB13','GRB14','GRB15','GRB16','AREA'
            )
           AND idqd.VALUEQUANTITY NOT IN (999,9999,99999,99,91)
        WHERE
            p.PRODUCTIONORDERCODE = '$prod_order'
            AND p.PRODUCTIONDEMANDCODE = '$demand'
            AND p.PROGRESSSTATUS = '2'
            AND p.OPERATIONCODE = 'BKR1'
        GROUP BY
            p.PRODUCTIONORDERCODE,
            p.STEPNUMBER,
            p.OPERATIONCODE,
            p.PRODRESERVATIONLINKGROUPCODE,
            o.OPERATIONGROUPCODE,
            o.LONGDESCRIPTION,
            p.PROGRESSSTATUS,
            iptip.MULAI,
            iptop.SELESAI,
            p.LASTUPDATEDATETIME,
            p.PRODUCTIONDEMANDCODE,
            iptip.LONGDESCRIPTION,
            iptop.LONGDESCRIPTION,
            a.VALUEBOOLEAN,
            iptip.MACHINECODE
        ORDER BY p.STEPNUMBER ASC
    ";

    $stmt = db2_exec($conn1, $sql);
    $rows = [];

    if ($stmt) {
        while ($row = db2_fetch_assoc($stmt)) {
            $rows[] = array_map('trim', $row);
        }
    }

    return $rows;
}
