<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Mophy | Page Login</title>
    <meta name="description" content="Some description for the page"/>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>assets/images/favicon.png">
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
    
</head>

<body class="h-100 p-0 m-0">
    <div class="authincation m-0 p-0 h-100">
        <div class="container p-0 h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-12 py-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="brand-logo mb-3">
                                        <img class="logo-abbr mr-2" src="<?php echo base_url(); ?>assets/images/logo.png" alt="">
                                        <img class="logo-compact" src="<?php echo base_url(); ?>assets/images/logo-text.png" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table">
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="left"><strong>Subtotal</strong></td>
                                                    <td class="right clearfix">Rp. <span class="float-right"><?php echo number_format($transaksi['expected_amount']-4995); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="left"><strong>Admin</strong></td>
                                                    <td class="right clearfix">Rp. <span class="float-right">4.995</span></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="left"><strong>Total</strong></td>
                                                    <td class="right clearfix">Rp. <span class="float-right"><?php echo number_format($transaksi['expected_amount']); ?></span></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="col-12">
                                        <h6>Customer:</h6>
                                        <div> <strong><?php echo $transaksi['name']; ?></strong> </div>
                                    </div>
                                    <div class="mt-4 col-12">
                                        <div class="row">
                                            <div class="col-12"> 
                                                <span>Metode Pembayaran:  
                                                    <strong class="d-block"><?php echo $transaksi['bank_code']; ?></strong>
                                                    <strong><?php echo $transaksi['account_number']; ?></strong>
                                                </span>
                                                <br>
                                                <!-- <small class="text-muted">Expired <?php echo date('d-m-Y H:i:s', $transaksi['expired_time']);?></small> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Cara Pembayaran</h5>
                                    </div>
                                    <div class="modal-body m-0 p-0">
                                        <div class="container col-12 p-0">
                                            <div id="accordion-one" class="accordion accordion-primary">
                                                <?php foreach ($cara_pembayaran['content'] as $key => $value) { ?>
                                                <div class="accordion__item">
                                                    <div class="accordion__header rounded-lg collapsed" data-toggle="collapse" data-target="#default_collapse_<?php echo $key; ?>" aria-expanded="false">
                                                        <span class="accordion__header--text"><?php echo $value['judul']; ?></span>
                                                        <span class="accordion__header--indicator"></span>
                                                    </div>
                                                    <div id="default_collapse_<?php echo $key; ?>" class="accordion__body collapse" data-parent="#accordion-one" style="">
                                                        <div class="accordion__body--text p-0">
                                                            <ul class="list-group list-group-flush">
                                                                <?php foreach ($value['step'] as $key => $valuea) { ?>
                                                                <li class="list-group-item"><?php echo $valuea; ?></li>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php 
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo base_url(); ?>assets/vendor/global/global.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>assets/js/custom.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>assets/js/deznav-init.js" type="text/javascript"></script>
</body>

</html>