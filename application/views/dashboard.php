
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
            <!-- row -->
            <div class="container-fluid">
                <div class="form-head mb-4">
                    <h2 class="text-black font-w600 mb-0">Dashboard</h2>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-xl-8 col-lg-6 col-md-7 col-sm-8">
                                <div class="card-bx stacked">
                                    <img src="<?php echo base_url(); ?>assets/images/card/card.png" alt="" class="mw-100">
                                    <div class="card-info text-white">
                                        <p class="mb-1">Total Transaksi</p>
                                        <h2 class="fs-36 text-white mb-sm-4 mb-3">Rp. <?php echo number_format($transaksi['total_transaksi']); ?></h2>
                                        <div class="d-flex align-items-center justify-content-between mb-sm-5 mb-3">
                                            <img src="images/dual-dot.png" alt="" class="dot-img">
                                            <h4 class="fs-20 text-white mb-0"><?php echo $transaksi_terakhir != null ? $transaksi_terakhir['reference'] : '-'; ?></h4>
                                        </div>
                                        <div class="d-flex">
                                            <div class="mr-5">
                                                <p class="fs-14 mb-1 op6">Terakhir Transaksi Sukses</p>
                                                <span><?php echo $transaksi_terakhir != null ? date('d/m/Y', strtotime($transaksi_terakhir['create_at'])) : '-'; ?></span>
                                            </div>
                                            <div>
                                                <p class="fs-14 mb-1 op6">Nama</p>
                                                <span><?php echo $transaksi_terakhir != null ? $transaksi_terakhir['customer_name'] : '-'; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-5 col-sm-4">
                                <!-- <div class="card bgl-primary card-body overflow-hidden p-0 d-flex rounded">
                                    <div class="p-0 text-center mt-3">
                                        <span class="text-black">Limit</span>
                                        <h3 class="text-black fs-20 mb-0 font-w600">$4,000</h3>
                                        <small>/$10,000</small>
                                    </div>
                                    <canvas id="lineChart" height="300" class="mt-auto line-chart-demo"></canvas>
                                </div>	 -->
                            </div>
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-header d-sm-flex d-block border-0 pb-0">
                                        <div class="pr-3 mr-auto mb-sm-0 mb-3">
                                            <h4 class="fs-20 text-black mb-1">Transaction</h4>
                                            <span class="fs-12"></span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="chartBarNow"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			
        </div>
        