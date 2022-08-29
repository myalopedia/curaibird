
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
            
            <div class="container-fluid">
                <div class="page-titles">
                    <h4>Invoice</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Layout</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Blank</a></li>
                    </ol>
                </div>
                <div class="row">
                    <div class="col-lg-12">                    
                        <div class="card mt-3">
                            <div class="card-header"> Invoice <strong><?php echo date('d/m/Y', strtotime($transaksi['create_at'])); ?></strong> <span class="float-right">
                                <strong>Status:</strong> <?php echo $transaksi['status']; ?></span> 
                            </div>
                            <div class="card-body">
                                <div class="row mb-5">
                                    <div class="col-md-6 col-sm-6">
                                        <h6>Customer:</h6>
                                        <div> <strong><?php echo $transaksi['customer_name']; ?></strong> </div>
                                    </div>
                                    <div class="mt-4 col-md-6 col-sm-6">
                                        <div class="row">
                                            <div class="col-12"> 
                                                <div class="brand-logo mb-3">
                                                    <img class="logo-abbr mr-2" src="<?php echo base_url(); ?>assets/images/logo.png" alt="">
                                                    <img class="logo-compact" src="<?php echo base_url(); ?>assets/images/logo-text.png" alt="">
                                                </div>
                                                <span>Metode Pembayaran:  
                                                    <button type="button" class="badge light badge-success" data-toggle="modal" data-target="#modalGrid" data-toggle="tooltip" data-placement="top" title="Cara Pembayaran">i</button>
                                                    <strong class="d-block"><?php echo $transaksi['payment_name']; ?></strong>
                                                    <?php if($transaksi['pay_code']) { ?><strong><?php echo $transaksi['pay_code']; ?></strong><button type="button" class="badge light badge-info" onclick="copyPayCode()" id="toastr-info-top-right" data-toggle="tooltip" data-placement="top" title="Link untuk portal pembayaran"><i class="flaticon-381-link"></i></button> <?php } ?>
                                                </span>
                                                <br>
                                                <small class="text-muted">Expired <?php echo date('d-m-Y H:i:s', $transaksi['expired_time']);?></small><br />
                                                <small class="text-muted">Link Customer </strong><button type="button" class="badge light badge-info" onclick="copyLink()" id="toastr-info-top-right" data-toggle="tooltip" data-placement="top" title="Link untuk portal pembayaran"><i class="flaticon-381-link"></i></button></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="modalGrid">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Cara Pembayaran</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="container col-12">
                                                    <div id="accordion-one" class="accordion accordion-primary">
                                                        <?php foreach ($transaksi_tripay->instructions as $key => $value) { ?>
                                                        <div class="accordion__item">
                                                            <div class="accordion__header rounded-lg collapsed" data-toggle="collapse" data-target="#default_collapse_<?php echo $key; ?>" aria-expanded="false">
                                                                <span class="accordion__header--text"><?php echo $value->title; ?></span>
                                                                <span class="accordion__header--indicator"></span>
                                                            </div>
                                                            <div id="default_collapse_<?php echo $key; ?>" class="accordion__body collapse" data-parent="#accordion-one" style="">
                                                                <div class="accordion__body--text">
                                                                    <ul class="list-group list-group-flush">
                                                                        <?php foreach ($value->steps as $key => $valuea) { ?>
                                                                        <li class="list-group-item"><?php echo $valuea; ?></li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                    <?php if($transaksi['payment_method'] == 'QRIS')
                                                                    {
                                                                    ?>
                                                                    <img style="mx-auto" style="height: 200px" src="<?php echo $transaksi_tripay->qr_url; ?>"/>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php 
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger light" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive p-3">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th class="center">#</th>
                                                <th>Nama Barang</th>
                                                <th class="right">Harga</th>
                                                <th class="center">Qty</th>
                                                <th class="right">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sub_total = 0;
                                            foreach ($barang_transaksi as $key => $a) { 
                                            ?>
                                            <tr>
                                                <td class="center"><?php echo $key+1; ?></td>
                                                <td class="left strong"><?php echo $a['nama']; ?></td>
                                                <td class="left">Rp. <?php echo number_format($a['harga']); ?></td>
                                                <td class="center"><?php echo $a['qty']; ?></td>
                                                <td class="right">Rp <?php echo number_format($a['harga']*$a['qty']); ?></td>
                                            </tr>
                                            <?php
                                            $sub_total += $a['harga']*$a['qty']; 
                                            } 
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row p-3">
                                    <div class="col-lg-4 col-sm-5"> </div>
                                    <div class="col-lg-4 col-sm-5 ml-auto">
                                        <table class="table table-clear">
                                            <tbody>
                                                <tr>
                                                    <td class="left"><strong>Subtotal</strong></td>
                                                    <td class="right clearfix">Rp. <span class="float-right"><?php echo number_format($sub_total); ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td class="left"><strong>Admin</strong></td>
                                                    <td class="right clearfix">Rp. <span class="float-right"><?php echo number_format($transaksi['fee_customer']); ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td class="left"><strong>Total</strong></td>
                                                    <td class="right clearfix">Rp. <span class="float-right"><?php echo number_format($sub_total+$transaksi['fee_customer']); ?></span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->
        <script>
            <?php 
            $trasnaksi_id = $transaksi['id'];
            $paycode = $transaksi['pay_code'];
            ?>
        function copyLink() {
            /* Copy the text inside the text field */
            navigator.clipboard.writeText('<?php echo base_url(); ?>pembelian/pembelian/<?php echo $trasnaksi_id; ?>');

            /* Alert the copied text */
            alert("Copied the text: " + copyText.value);
        }
        function copyPayCode() {
            /* Copy the text inside the text field */
            navigator.clipboard.writeText('<?php echo $paycode; ?>');

            /* Alert the copied text */
            alert("Copied the text: " + copyText.value);
        }
        </script>
