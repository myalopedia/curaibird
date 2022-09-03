
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
            <!-- row -->
            <div class="container-fluid">
                <div class="form-head mb-4">
                    <h2 class="text-black font-w600 mb-0">Transaksi</h2>
                </div>
                <div class="col-12 m-0 px-0 py-3 clearfix">
                    <a href="<?php echo base_url(); ?>transaksi/tambah" class="btn btn-success btn-sm float-right">Tambah</a>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="example4" class="display min-w850">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Tanggal</th>
                                                        <th>Pembeli</th>
                                                        <th>Pembayaran</th>
                                                        <th>Harga</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($transaksi as $key => $a) { ?>
                                                    <tr>
                                                        <td><?php echo $key+1; ?></td>
                                                        <td><?php echo date('d-m-Y', strtotime($a['create_at'])); ?></td>
                                                        <td><?php echo $a['name']; ?></td>
                                                        <td><?php echo $a['bank_code']; ?></td>
                                                        <td><?php echo 'Rp.'.number_format($a['expected_amount']); ?></td>
                                                        <td>
                                                            <?php
                                                            switch ($a['status']) {
                                                                case 'PENDING':
                                                                    echo '<span class="badge light badge-warning">Pending</span>';
                                                                    break;
                                                                case 'INACTIVE':
                                                                    echo '<span class="badge light badge-danger">Tidak Aktif</span>';
                                                                    break;
                                                                case 'ACTIVE':
                                                                    echo '<span class="badge light badge-warning">Active</span>';
                                                                    break;                                                                    
                                                                case 'PAID':
                                                                    echo '<span class="badge light badge-success">Paid</span>';
                                                                    break;
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <a href="<?php echo base_url(); ?>transaksi/detail/<?php echo $a['id']; ?>" class="btn btn-primary btn-xs">Info</a>
                                                            <a href="<?php echo base_url(); ?>transaksi/delete_transaksi/<?php echo $a['id']; ?>" class="btn btn-danger btn-xs">Hapus</a>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
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
			
        </div>
        <!--**********************************
            Content body end
        ***********************************-->