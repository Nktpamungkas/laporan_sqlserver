<head>
    <link rel="stylesheet" type="text/css" href="files\assets\icon\themify-icons\themify-icons.css">
    <link rel="stylesheet" type="text/css" href="files\assets\icon\icofont\css\icofont.css">
    <link rel="stylesheet" type="text/css" href="files\assets\icon\feather\css\feather.css">
</head>

<body>
    <div class="">
        <div class="ball-scale">
            <div class='contain'>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
            </div>
        </div>
    </div>
    <?php
        $current_page = basename($_SERVER['PHP_SELF']);
        $active_pages_ppc = ['ppc_filter.php', 
                            'ppc_filter_order_perminggu.php', 
                            'ppc_filter_persediaan_kain_jadi.php', 
                            'ppc_filter_poselesai.php', 
                            'ppc_filter_steps.php', 
                            'ppc_filter_qty_knt.php', 
                            'ppc_bagi_kain.php', 
                            'ppc_pengiriman.php',
                            'ppc_scan_kkoke.php',
                            'ppc_status_greige.php',
                            'ppc_summary_lot_panjang.php'];
        $style_active_ppc = in_array($current_page, $active_pages_ppc) ? 'style="background-color: #d4e9fa;"' : '';
        
        $active_pages_fin = ['fin_filter_cetaklabel.php', 
                            'fin_filter_bon_reservation.php'];
        $style_active_fin = in_array($current_page, $active_pages_fin) ? 'style="background-color: #d4e9fa;"' : '';
        
        $active_pages_brs = ['brs_filter_cetaklabel.php'];
        $style_active_brs = in_array($current_page, $active_pages_brs) ? 'style="background-color: #d4e9fa;"' : '';

        $active_pages_lab = ['dye_pemakaian_obat.php',
                            'dye_Summary_pemakaian_obat.php',
                            'gas_filter_MasterSuhu.php'];
        $style_active_lab = in_array($current_page, $active_pages_lab) ? 'style="background-color: #d4e9fa;"' : '';

        $active_pages_tas = ['tas_filter_cetaklabel.php'];
        $style_active_tas = in_array($current_page, $active_pages_tas) ? 'style="background-color: #d4e9fa;"' : '';
        
        $active_pages_dye = ['dye_filter_LA.php', 
                            'dye_filter_bon_reservation.php', 
                            'dye_kk_bysuffix.php', 
                            'dye_greigewhiteness.php', 
                            'dye_greigewhiteness.php', 
                            'dye_kk_bysuffix.php',
                            'dye_lab_rcode.php',
                            'dye_search_detail_recipe.php',
                            'dye_create_topping.php',
                            'dye_approve_recipe.php'];
        $style_active_dye = in_array($current_page, $active_pages_dye) ? 'style="background-color: #d4e9fa;"' : '';
        
        $active_pages_mkt = ['mkt_sales_report.php', 
                            'mkt_update_invoicenow.php', 
                            'mkt_sales_register_buyer.php', 
                            'mkt_terima_order.php', 
                            'mkt_leadtime.php'];
        $style_active_mkt = in_array($current_page, $active_pages_mkt) ? 'style="background-color: #d4e9fa;"' : '';
        
        $active_pages_prd = ['prd_detail_demand_step.php', 
                            'prd_bukuresep.php', 
                            'prd_analisaKK.php', 
                            'prd_carigerobak.php', 
                            'prd_outstandingkk.php', 
                            'prd_pinjam_stdcckwarna.php',
                            'prd_pinjam_stdcckwarna_ld.php',
                            'prd_pinjam_stdcckwarna_te.php',
                            'Login_prd_pinjam_stdcckwarna_dl.php',
                            'prd_openticketMTC.php',
                            'prd_histori_kk.php',
                            'prd_tracking_report_tas.php',
                            'prd_update.php',
                            'prd_elements_cut.php',
                            'prd_laporan_mesin3.php',
                            'PRD_TimbangGerobak.php'];
        $style_active_prd = in_array($current_page, $active_pages_prd) ? 'style="background-color: #d4e9fa;"' : '';
        
        $active_pages_afs = ['aftersales_memopenting_order.php'];
        $style_active_afs = in_array($current_page, $active_pages_afs) ? 'style="background-color: #d4e9fa;"' : '';
        
        $active_pages_spec = ['spectro_upload.php'];
        $style_active_spec = in_array($current_page, $active_pages_spec) ? 'style="background-color: #d4e9fa;"' : '';

        $active_pages_otx = ['orgatex_dyelot_view.php', 
                            'orgatex_export.php', 
                            'batch_report_orgatex.php', 
                            'stop_machine_efficency.php'];
        $style_active_otx = in_array($current_page, $active_pages_otx) ? 'style="background-color: #d4e9fa;"' : '';
    ?>
    <div id="pcoded" class="pcoded">
        <div class="pcoded-container">
            <div class="pcoded-main-container bg-green-soft">
                <nav class="pcoded-navbar">
                    <div class="pcoded-inner-navbar">
                        <ul class="pcoded-item pcoded-left-item">
                            <li class="pcoded-hasmenu">
                                <a href="javascript:void(0)" <?= $style_active_ppc; ?>>
                                    <span class="pcoded-micon"><i class="ti-layout-media-right-alt"></i></span>
                                    <span class="pcoded-mtext">PPC</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                                <ul class="pcoded-submenu">
                                    <li <?= ($current_page == 'ppc_filter.php') ? $style_active_ppc : ''; ?>>
                                        <a href="ppc_filter.php" data-i18n="nav.animations.main">
                                            Memo Penting
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'ppc_filter_order_perminggu.php') ? $style_active_ppc : ''; ?>>
                                        <a href="ppc_filter_order_perminggu.php" data-i18n="nav.animations.main">
                                            Laporan Terima Order
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'ppc_filter_persediaan_kain_jadi.php') ? $style_active_ppc : ''; ?>>
                                        <a href="ppc_filter_persediaan_kain_jadi.php" data-i18n="nav.animations.main">
                                            Lap. Persediaan Kain Jadi
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'ppc_filter_poselesai.php') ? $style_active_ppc : ''; ?>>
                                        <a href="ppc_filter_poselesai.php" data-i18n="nav.animations.main">
                                            Laporan PO Selesai
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'ppc_filter_steps.php') ? $style_active_ppc : ''; ?>>
                                        <a href="ppc_filter_steps.php" data-i18n="nav.animations.main">
                                            Posisi KK
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'ppc_filter_qty_knt.php') ? $style_active_ppc : ''; ?>>
                                        <a href="ppc_filter_qty_knt.php" data-i18n="nav.animations.main">
                                            Laporan QTY Rajut KNT
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'ppc_pengiriman.php') ? $style_active_ppc : ''; ?>>
                                        <a href="ppc_pengiriman.php" data-i18n="nav.animations.main">
                                            Laporan Pengiriman PPC
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'ppc_bagi_kain.php') ? $style_active_ppc : ''; ?>>
                                        <a href="ppc_bagi_kain.php" data-i18n="nav.animations.main">
                                            Laporan Bagi Kain
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'ppc_scan_kkoke.php') ? $style_active_ppc : ''; ?>>
                                        <a href="ppc_scan_kkoke.php" data-i18n="nav.animations.main">
                                            Scan KK OKE
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'ppc_status_greige.php') ? $style_active_ppc : ''; ?>>
                                        <a href="ppc_status_greige.php" data-i18n="nav.animations.main">
                                            Status Greige
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'ppc_summary_lot_panjang.php') ? $style_active_ppc : ''; ?>>
                                        <a href="ppc_summary_lot_panjang.php" data-i18n="nav.animations.main">
                                            Laporan Summary Lot Panjang 
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="pcoded-hasmenu">
                                <a href="javascript:void(0)" <?= $style_active_fin; ?>>
                                    <span class="pcoded-micon"><i class="ti-infinite"></i></span>
                                    <span class="pcoded-mtext">FIN</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                                <ul class="pcoded-submenu">
                                    <li <?= ($current_page == 'fin_filter_cetaklabel.php') ? $style_active_fin : ''; ?>>
                                        <a href="fin_filter_cetaklabel.php" data-i18n="nav.animations.main">
                                            Cetak Label
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'fin_filter_bon_reservation.php') ? $style_active_fin : ''; ?>>
                                        <a href="fin_filter_bon_reservation.php" data-i18n="nav.animations.main">
                                            Lap. Bon Resep Reservation FIN
                                        </a>
                                    </li>
                                    <!-- <li class=" ">
                                        <a href="fin_bpp.php" data-i18n="nav.animations.main">
                                            Laporan Pembelian
                                        </a>
                                    </li> -->
                                </ul>
                            </li>
                            <li class="pcoded-hasmenu">
                                <a href="javascript:void(0)" <?= $style_active_brs; ?>>
                                    <span class="pcoded-micon"><i class="ti-infinite"></i></span>
                                    <span class="pcoded-mtext">BRS</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                                <ul class="pcoded-submenu">
                                    <li <?= ($current_page == 'brs_filter_cetaklabel.php') ? $style_active_brs : ''; ?>>
                                        <a href="brs_filter_cetaklabel.php" data-i18n="nav.animations.main">
                                            Cetak Label
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="pcoded-hasmenu">
                                <a href="javascript:void(0)" <?= $style_active_lab; ?>>
                                    <span class="pcoded-micon"><i class="icofont icofont-laboratory"></i></span>
                                    <span class="pcoded-mtext">LAB</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                                <ul class="pcoded-submenu">
                                    <li <?= ($current_page == 'dye_pemakaian_obat.php') ? $style_active_lab : ''; ?>>
                                        <a href="dye_pemakaian_obat.php" data-i18n="nav.animations.main">
                                            Laporan pemakaian obat
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'dye_Summary_pemakaian_obat.php') ? $style_active_lab : ''; ?>>
                                        <a href="dye_Summary_pemakaian_obat.php" data-i18n="nav.animations.main">
                                            Laporan bulanan pemakaian obat
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'lab_filter_MasterSuhu.php') ? $style_active_lab : ''; ?>>
                                        <a href="lab_filter_MasterSuhu.php" data-i18n="nav.animations.main">
                                            Master Suhu
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="pcoded-hasmenu">
                                <a href="javascript:void(0)" <?= $style_active_tas; ?>>
                                    <span class="pcoded-micon"><i class="ti-infinite"></i></span>
                                    <span class="pcoded-mtext">TAS</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                                <ul class="pcoded-submenu">
                                    <li <?= ($current_page == 'tas_filter_cetaklabel.php') ? $style_active_lab : ''; ?>>
                                        <a href="tas_filter_cetaklabel.php" data-i18n="nav.animations.main">
                                            Cetak Label
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="pcoded-hasmenu">
                                <a href="javascript:void(0)" <?= $style_active_dye; ?>>
                                    <span class="pcoded-micon"><i class="ti-paint-bucket"></i></span>
                                    <span class="pcoded-mtext">DYE</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                                <ul class="pcoded-submenu">
                                    <!-- <li <?= ($current_page == 'dye_pemakaian_obat.php') ? $style_active_dye : ''; ?>>
                                        <a href="dye_pemakaian_obat.php" data-i18n="nav.animations.main">
                                            Laporan pemakaian obat
                                        </a>
                                    </li> -->
                                    <li <?= ($current_page == 'dye_filter_LA.php') ? $style_active_dye : ''; ?>>
                                        <a href="dye_filter_LA.php" data-i18n="nav.animations.main">
                                            Laporan Hasil Aktual (LA)
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'dye_filter_bon_reservation.php') ? $style_active_dye : ''; ?>>
                                        <a href="dye_filter_bon_reservation.php" data-i18n="nav.animations.main">
                                            Lap. Bon Resep Reservation
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'dye_kk_bysuffix.php') ? $style_active_dye : ''; ?>>
                                        <a href="dye_kk_bysuffix.php" data-i18n="nav.animations.main">
                                            Search Recipe Code & Suffix
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'dye_greigewhiteness.php') ? $style_active_dye : ''; ?>>
                                        <a href="dye_greigewhiteness.php" data-i18n="nav.animations.main">
                                            Greige & Whiteness
                                        </a>
                                    </li>
                                    <!-- <li <?= ($current_page == 'dye_kk_bysuffix.php') ? $style_active_dye : ''; ?>>
                                        <a href="dye_kk_bysuffix.php" data-i18n="nav.animations.main">
                                            Kartu Kerja By Suffix
                                        </a>
                                    </li> -->
                                    <li <?= ($current_page == 'dye_lab_rcode.php') ? $style_active_dye : ''; ?>>
                                        <a href="dye_lab_rcode.php" data-i18n="nav.animations.main">
                                            Cek RCode Dyeing dan Laborat
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'dye_search_detail_recipe.php') ? $style_active_dye : ''; ?>>
                                        <a href="dye_search_detail_recipe.php" data-i18n="nav.animations.main">
                                            Bon Resep Dyeing
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'dye_create_topping.php') ? $style_active_dye : ''; ?>>
                                        <a href="dye_create_topping.php" data-i18n="nav.animations.main">
                                            Topping Bon Resep
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'dye_approve_recipe.php') ? $style_active_dye : ''; ?>>
                                        <a href="dye_approve_recipe.php" data-i18n="nav.animations.main">
                                            Approved Bon Resep Dye
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- <li class="pcoded-hasmenu">
                                <a href="javascript:void(0)">
                                    <span class="pcoded-micon"><i class="icofont icofont-basket"></i></span>
                                    <span class="pcoded-mtext">PCS</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                                <ul class="pcoded-submenu">
                                    <li class=" ">
                                        <a href="pcs_filter.php" data-i18n="nav.animations.main">
                                            History Pembelian Barang
                                        </a>
                                    </li>
                                </ul>
                            </li> -->
                            <li class="pcoded-hasmenu">
                                <a href="javascript:void(0)" <?= $style_active_mkt; ?>>
                                    <span class="pcoded-micon"><i class="icofont icofont-chart-histogram"></i></span>
                                    <span class="pcoded-mtext">MKT</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                                <ul class="pcoded-submenu">
                                    <li <?= ($current_page == 'mkt_sales_report.php') ? $style_active_mkt : ''; ?>>
                                        <a href="mkt_sales_report.php" data-i18n="nav.animations.main">
                                            Sales Report
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'mkt_update_invoicenow.php') ? $style_active_mkt : ''; ?>>
                                        <a href="mkt_update_invoicenow.php" data-i18n="nav.animations.main">
                                            Update Invoice NOW
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'mkt_sales_register_buyer.php') ? $style_active_mkt : ''; ?>>
                                        <a href="mkt_sales_register_buyer.php" data-i18n="nav.animations.main">
                                            Sales Register Buyer
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'mkt_terima_order.php') ? $style_active_mkt : ''; ?>>
                                        <a href="mkt_terima_order.php" data-i18n="nav.animations.main">
                                            Laporan Terima Order
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'mkt_leadtime.php') ? $style_active_mkt : ''; ?>>
                                        <a href="mkt_leadtime.php" data-i18n="nav.animations.main">
                                            Laporan Internal Lead Team
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="pcoded-hasmenu">
                                <a href="javascript:void(0)" <?= $style_active_prd; ?>>
                                    <span class="pcoded-micon"><i class="icofont icofont-industries-alt-3"></i></span>
                                    <span class="pcoded-mtext">PRD</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                                <ul class="pcoded-submenu">
                                    <li <?= ($current_page == 'prd_bukuresep.php') ? $style_active_prd : ''; ?>>
                                        <a href="prd_bukuresep.php" data-i18n="nav.animations.main">
                                            Buku Resep Digital
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'prd_detail_demand_step.php') ? $style_active_prd : ''; ?>>
                                        <a href="prd_detail_demand_step.php" data-i18n="nav.animations.main">
                                            Detail Demand Step
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'prd_analisaKK.php') ? $style_active_prd : ''; ?>>
                                        <a href="prd_analisaKK.php" data-i18n="nav.animations.main">
                                            Analisa Kartu Kerja
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'prd_carigerobak.php') ? $style_active_prd : ''; ?>>
                                        <a href="prd_carigerobak.php" data-i18n="nav.animations.main">
                                            Cari Gerobak
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'prd_outstandingkk.php') ? $style_active_prd : ''; ?>>
                                        <a href="prd_outstandingkk.php" data-i18n="nav.animations.main">
                                            Outstanding Kartu Kerja
                                        </a>
                                    </li>
                                    <li class="pcoded-hasmenu">
                                        <a href="javascript:void(0)">
                                            <span class="pcoded-micon"><i class="icofont icofont-clipboard"></i></span>
                                            <span class="pcoded-mtext">Pinjam Std Cck</span>
                                            <span class="pcoded-mcaret"></span>
                                        </a>
                                        <ul class="pcoded-submenu">
                                            <li <?= ($current_page == 'prd_pinjam_stdcckwarna.php') ? $style_active_prd : ''; ?>>
                                                <a href="prd_pinjam_stdcckwarna.php" data-i18n="nav.animations.main">
                                                    Pinjam Std Cck Warna RC
                                                </a>
                                            </li>
                                            <li <?= ($current_page == 'prd_pinjam_stdcckwarna_ld.php') ? $style_active_prd : ''; ?>>
                                                <a href="prd_pinjam_stdcckwarna_ld.php" data-i18n="nav.animations.main">
                                                    Pinjam Std Cck Warna LD
                                                </a>
                                            </li>
                                            <li <?= ($current_page == 'prd_pinjam_stdcckwarna_te.php') ? $style_active_prd : ''; ?>>
                                                <a href="prd_pinjam_stdcckwarna_te.php" data-i18n="nav.animations.main">
                                                    Pinjam Std Cck Warna TE
                                                </a>
                                            </li>
                                            <li <?= ($current_page == 'Login_prd_pinjam_stdcckwarna_dl.php') ? $style_active_prd : ''; ?>>
                                                <a href="Login_prd_pinjam_stdcckwarna_dl.php" data-i18n="nav.animations.main">
                                                    Pinjam Std Cck Warna DL
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li <?= ($current_page == 'prd_openticketMTC.php') ? $style_active_prd : ''; ?>>
                                        <a href="prd_openticketMTC.php" data-i18n="nav.animations.main">
                                            View Openticket MTC
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'prd_histori_kk.php') ? $style_active_prd : ''; ?>>
                                        <a href="prd_histori_kk.php" data-i18n="nav.animations.main">
                                            Riwayat Salin No. Demand
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'prd_tracking_report_tas.php') ? $style_active_prd : ''; ?>>
                                        <a href="prd_tracking_report_tas.php" data-i18n="nav.animations.main">
                                            Report Tracking TAS
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'prd_update.php') ? $style_active_prd : ''; ?>>
                                        <a href="prd_update.php" data-i18n="nav.animations.main">
                                            Update Production Order Quantity
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'prd_elements_cut.php') ? $style_active_prd : ''; ?>>
                                        <a href="prd_elements_cut.php" data-i18n="nav.animations.main">
                                            History Cut Elements
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'prd_laporan_mesin3.php') ? $style_active_prd : ''; ?>>
                                        <a href="prd_laporan_mesin3.php" data-i18n="nav.animations.main">
                                            Macro Plan
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'PRD_TimbangGerobak.php') ? $style_active_prd : ''; ?>>
                                        <a href="PRD_TimbangGerobak.php" data-i18n="nav.animations.main">
                                            Konsistensi Timbang Gerobak
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- <li class="pcoded-hasmenu">
                                <a href="javascript:void(0)">
                                    <span class="pcoded-micon"><i class="icofont icofont-picture"></i></span>
                                    <span class="pcoded-mtext">PRT</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                                <ul class="pcoded-submenu">
                                    <li class=" ">
                                        <a href="prt_pemakaian_obat.php" data-i18n="nav.animations.main">
                                            Laporan pemakaian obat
                                        </a>
                                    </li>
                                </ul>
                            </li> -->
                            <li class="pcoded-hasmenu">
                                <a href="javascript:void(0)" <?= $style_active_afs; ?>>
                                    <span class="pcoded-micon"><i class="icofont icofont-support"></i></span>
                                    <span class="pcoded-mtext">AFTER-SALES SERV.</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                                <ul class="pcoded-submenu">
                                    <li <?= ($current_page == 'aftersales_memopenting_order.php') ? $style_active_afs : ''; ?>>
                                        <a href="aftersales_memopenting_order.php" data-i18n="nav.animations.main">
                                            Memo Penting Order Replacement dan Retur
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="pcoded-hasmenu">
                                <a href="javascript:void(0)" <?= $style_active_spec; ?>>
                                    <span class="pcoded-micon"><i class="icofont icofont-upload-alt"></i></span>
                                    <span class="pcoded-mtext">SPECTRO</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                                <ul class="pcoded-submenu">
                                    <li <?= ($current_page == 'spectro_upload.php') ? $style_active_spec : ''; ?>>
                                        <a href="spectro_upload.php" data-i18n="nav.animations.main">
                                            Import Data Spectro
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="pcoded-hasmenu">
                                <a href="javascript:void(0)" <?= $style_active_otx; ?>>
                                    <span class="pcoded-micon"><i class="icofont icofont-upload-alt"></i></span>
                                    <span class="pcoded-mtext">ORGATEX</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                                <ul class="pcoded-submenu">
                                    <li <?= ($current_page == 'orgatex_dyelot_view.php') ? $style_active_otx : ''; ?>>
                                        <a href="orgatex_dyelot_view.php" data-i18n="nav.animations.main">
                                            List batch Export
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'orgatex_export.php') ? $style_active_otx : ''; ?>>
                                        <a href="orgatex_export.php" data-i18n="nav.animations.main">
                                            Export to Orgatex
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'batch_report_orgatex.php') ? $style_active_otx : ''; ?>>
                                        <a href="batch_report_orgatex.php" data-i18n="nav.animations.main">
                                            Report Batch 
                                        </a>
                                    </li>
                                    <li <?= ($current_page == 'stop_machine_efficency.php') ? $style_active_otx : ''; ?>>
                                        <a href="stop_machine_efficency.php" data-i18n="nav.animations.main">
                                            Stop Machine Efficency
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="pcoded-hasmenu">
                                <a href="javascript:void(0)">
                                    <span class="pcoded-micon"><i class="icofont icofont-info"></i></span>
                                    <span class="pcoded-mtext">Info</span>
                                </a>
                                <ul class="pcoded-submenu">
                                    <li>
                                        <a href="index.php" target="_blank" data-i18n="nav.animations.main">
                                            Dashboard Log Loading
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div id="sidebar" class="users p-chat-user showChat">
                    <div class="had-container">
                        <div class="card card_main p-fixed users-main">
                            <div class="user-box">
                                <div class="chat-inner-header">
                                    <div class="back_chatBox">
                                        <div class="right-icon-control">
                                            <input type="text" class="form-control  search-text"
                                                placeholder="Search Friend" id="search-friends">
                                            <div class="form-icon">
                                                <i class="icofont icofont-search"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="main-friend-list">
                                    <div class="media userlist-box" data-id="1" data-status="online"
                                        data-username="Josephin Doe" data-toggle="tooltip" data-placement="left"
                                        title="Josephin Doe">
                                        <a class="media-left" href="#!">
                                            <img class="media-object img-radius img-radius"
                                                src="files\assets\images\avatar-3.jpg" alt="Generic placeholder image ">
                                            <div class="live-status bg-success"></div>
                                        </a>
                                        <div class="media-body">
                                            <div class="f-13 chat-header">Josephin Doe</div>
                                        </div>
                                    </div>
                                    <div class="media userlist-box" data-id="2" data-status="online"
                                        data-username="Lary Doe" data-toggle="tooltip" data-placement="left"
                                        title="Lary Doe">
                                        <a class="media-left" href="#!">
                                            <img class="media-object img-radius" src="files\assets\images\avatar-2.jpg"
                                                alt="Generic placeholder image">
                                            <div class="live-status bg-success"></div>
                                        </a>
                                        <div class="media-body">
                                            <div class="f-13 chat-header">Lary Doe</div>
                                        </div>
                                    </div>
                                    <div class="media userlist-box" data-id="3" data-status="online"
                                        data-username="Alice" data-toggle="tooltip" data-placement="left" title="Alice">
                                        <a class="media-left" href="#!">
                                            <img class="media-object img-radius" src="files\assets\images\avatar-4.jpg"
                                                alt="Generic placeholder image">
                                            <div class="live-status bg-success"></div>
                                        </a>
                                        <div class="media-body">
                                            <div class="f-13 chat-header">Alice</div>
                                        </div>
                                    </div>
                                    <div class="media userlist-box" data-id="4" data-status="online"
                                        data-username="Alia" data-toggle="tooltip" data-placement="left" title="Alia">
                                        <a class="media-left" href="#!">
                                            <img class="media-object img-radius" src="files\assets\images\avatar-3.jpg"
                                                alt="Generic placeholder image">
                                            <div class="live-status bg-success"></div>
                                        </a>
                                        <div class="media-body">
                                            <div class="f-13 chat-header">Alia</div>
                                        </div>
                                    </div>
                                    <div class="media userlist-box" data-id="5" data-status="online"
                                        data-username="Suzen" data-toggle="tooltip" data-placement="left" title="Suzen">
                                        <a class="media-left" href="#!">
                                            <img class="media-object img-radius" src="files\assets\images\avatar-2.jpg"
                                                alt="Generic placeholder image">
                                            <div class="live-status bg-success"></div>
                                        </a>
                                        <div class="media-body">
                                            <div class="f-13 chat-header">Suzen</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>