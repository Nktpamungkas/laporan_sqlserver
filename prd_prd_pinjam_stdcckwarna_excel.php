<?php
header("content-type:application/vnd-ms-excel");
header("content-disposition:attachment;filename=Data Resep.xls");
header('Cache-Control: max-age=0');
?>
<table>
    <thead>
        <th width="4%">No Barcode</th>
        <th width="4%">No Warna</th>
        <th width="10%">Warna</th>
        <th width="3%">Kode</th>
        <th width="22%">Note</th>
        <th width="7%">Customer</th>
        <th width="20%">Status Pinjam</th>
    </thead>
    <tbody>
        <?php
        require_once "koneksi.php";

        // Menentukan query berdasarkan parameter arsip
        if (isset($_GET['arsip']) && $_GET['arsip'] == 0) {
            $q_bukupinjam = sqlsrv_query($con_nowprd, "SELECT * FROM nowprd.buku_pinjam WHERE status_file IS NULL");
        } else {
            $q_bukupinjam = sqlsrv_query($con_nowprd, "SELECT * FROM nowprd.buku_pinjam WHERE status_file = 'Arsip'");
        }

        // Cek jika query berhasil
        if ($q_bukupinjam === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        // Loop melalui hasil query
        while ($row_bukupinjam = sqlsrv_fetch_array($q_bukupinjam)) {
            ?>
            <tr>
                <td><?= sprintf("%'.06d", $row_bukupinjam['id']); ?></td>
                <td><?= $row_bukupinjam['no_warna']; ?></td>
                <td>
                    <?php
                    $q_warna = db2_exec($conn1, "SELECT * FROM USERGENERICGROUP WHERE CODE = '" . $row_bukupinjam['no_warna'] . "'");
                    $row_warna = db2_fetch_assoc($q_warna);
                    echo $row_warna['LONGDESCRIPTION'] ?? ''; // Cek apakah LONGDESCRIPTION ada
                    ?>
                </td>
                <td>
                    <a style="border-bottom:1px dashed green;" data-pk="<?= $row_bukupinjam['id'] ?>"
                        data-value="<?= $row_bukupinjam['kode'] ?>" class="kode_edit" href="javascript:void(0)">
                        <?= $row_bukupinjam['kode']; ?>
                    </a>
                </td>
                <td>
                    <a style="border-bottom:1px dashed green;" data-pk="<?= $row_bukupinjam['id'] ?>"
                        data-value="<?= $row_bukupinjam['note'] ?>" class="note_edit" href="javascript:void(0)">
                        <?= $row_bukupinjam['note']; ?>
                    </a>
                </td>
                <td><?= $row_bukupinjam['customer']; ?></td>
                <td>
                    <?php
                    $cari_nama_in = mysqli_query($con_hrd, "SELECT * FROM tbl_makar WHERE no_scan = '$row_bukupinjam[absen_in]'");
                    $cari_nama_out = mysqli_query($con_hrd, "SELECT * FROM tbl_makar WHERE no_scan = '$row_bukupinjam[absen_out]'");
                    $nama_in    = mysqli_fetch_assoc($cari_nama_in);
                    $nama_out   = mysqli_fetch_assoc($cari_nama_out);
                    if (!empty($row_bukupinjam['tgl_in'])) {
                        // Pastikan $row_bukupinjam['tgl_in'] adalah objek DateTime
                        if ($row_bukupinjam['tgl_in'] instanceof DateTime) {
                            $tgl_in = $row_bukupinjam['tgl_in'];
                        } else {
                            $tgl_in = new DateTime($row_bukupinjam['tgl_in']); // Jika bukan, buat objek baru
                        }
                        echo "Dipinjam : " . ($nama_in['nama'] ?? 'Tidak Diketahui') . ", " . $tgl_in->format('Y-m-d H:i:s') . "<br>";
                    }
                    if (!empty($row_bukupinjam['tgl_out'])) {
                        // Pastikan $row_bukupinjam['tgl_out'] adalah objek DateTime
                        if ($row_bukupinjam['tgl_out'] instanceof DateTime) {
                            $tgl_out = $row_bukupinjam['tgl_out'];
                        } else {
                            $tgl_out = new DateTime($row_bukupinjam['tgl_out']); // Jika bukan, buat objek baru
                        }
                        echo "Dikembalikan : " . ($nama_out['nama'] ?? 'Tidak Diketahui') . ", " . $tgl_out->format('Y-m-d H:i:s');
                    }
                    ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>