
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
            <!-- row -->
            <div class="container-fluid">
                <div class="form-head mb-4">
                    <h2 class="text-black font-w600 mb-0">Tambah Transaksi</h2>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Tambah Transaksi</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="basic-form">
                                            <form method="POST" action="<?php echo base_url(); ?>transaksi/tambah_aksi">
                                                <div class="form-group">
                                                    <label>Pembeli <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="pembeli" list="listPembeli" required>
                                                    <datalist id="listPembeli">
                                                        <option value="Supri">
                                                        <option value="Ikan">
                                                    </datalist>
                                                </div>
                                                <div class="form-group">
                                                    <label>Email Pembeli</label>
                                                    <input type="email" class="form-control" name="email_pembeli">
                                                </div>
                                                <div class="col-12 m-0 p-0" id="list_barang_transaksi_tambah">
                                                    <div class="row list_barang pt-2 mb-2" style="border: 1px solid #c4c4c4; border-radius: 5px;">
                                                        <div class="form-group col-md">
                                                            <label>Nama Barang <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="nama_barang[]" list="namaBarang" onchange="calculate()">
                                                            <datalist id="namaBarang">
                                                                <option value="Sprei">
                                                                <option value="Kardus">
                                                            </datalist>
                                                        </div>
                                                        <div class="form-group col-md">
                                                            <label>Qty Barang <span class="text-danger">*</span></label>
                                                            <input type="number" name="qty_barang[]" class="form-control qty_barang_input" onchange="calculate()">
                                                        </div>
                                                        <div class="form-group col-md">
                                                            <label>Harga Barang <span class="text-danger">*</span></label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Rp.</span>
                                                                </div>
                                                                <input type="number" name="harga_barang[]" class="form-control harga_barang_input" onchange="calculate()">
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md">
                                                            <label>Total Harga</label>
                                                            <div class="input-group mb-3">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Rp.</span>
                                                                </div>
                                                                <input type="varchar" disabled class="form-control total_harga_barang_show">
                                                                <input type="hidden" name="total_harga_barang[]" disabled class="form-control total_harga_barang_input">
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-auto" id="container_button">
                                                            <button type="button" onclick="tambah_form_barang(this)" class="btn btn-success btn-xs mt-md-5"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group" id="input_metode_pembayaran">
                                                    <label>Metode Pembayaran <span class="text-danger">*</span></label>
                                                    <select id="single-select" name="metode_pembayaran" onchange="calculate()" required>
                                                        <option value="">-- Select Metode Pembayaran --</option>
                                                        <?php foreach ($daftar_metode as $a) { ?>
                                                        <option value="<?php echo $a->code; ?>"><?php echo $a->name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <input type="hidden" name="sub_total_transaksi_input" id="sub_total_transaksi_input" />
                                                <input type="hidden" name="biaya_admin_transaksi_input" id="biaya_admin_transaksi_input" />
                                                <input type="hidden" name="total_transaksi_input" id="total_transaksi_input" />
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-responsive-md">
                                                            <tr>
                                                                <td><strong>Sub Total</strong></td>
                                                                <td id="sub_total_transaksi"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Admin (dibayar customer)</strong></td>
                                                                <td id="biaya_admin_transaksi"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Total</strong></td>
                                                                <td id="total_transaksi"></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Proses</button>
                                            </form>
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