
        <div class="footer">
            <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a href="http://dexignzone.com/" target="_blank">DexignZone</a> 2021</p>
            </div>
        </div>
    </div>
	<script src="<?php echo base_url(); ?>assets/vendor/global/global.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/chart.js/Chart.bundle.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/owl-carousel/owl.carousel.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/peity/jquery.peity.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/apexchart/apexchart.js" type="text/javascript"></script>
    <!-- <script src="<?php echo base_url(); ?>assets/js/dashboard/dashboard-1.js" type="text/javascript"></script> -->
    <script src="<?php echo base_url(); ?>assets/vendor/datatables/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins-init/datatables.init.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/custom.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/deznav-init.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins-init/select2-init.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/toastr/js/toastr.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins-init/toastr-init.js" type="text/javascript"></script>

    
    <script>
        // Add new input with associated 'remove' link when 'add' button is clicked.
        function tambah_form_barang(a) {
            var parent = $(a).parent().parent().clone();
            var form = parent[0];
            form.getElementsByTagName("input").value = "";
            // a.preventDefault();
            $("#list_barang_transaksi_tambah").append(form);

            $(a).parent().html('<button type="button" onclick="remove_form_barang(this)" class="btn btn-danger btn-xs mt-5 remove-form-product"><i class="fa fa-minus" aria-hidden="true"></i></button>');

            calculate();
        };

        // Remove parent of 'remove' link when link is clicked.
        function remove_form_barang(a) {
            $(a).parent().parent()[0].remove();
        };
    </script>
    <script>

        function calculate() {
            var sum_harga = 0;
            $('#list_barang_transaksi_tambah .list_barang').each(function(){
                var qty_barang_input = this.getElementsByClassName("qty_barang_input")[0].value;
                var harga_barang_input = this.getElementsByClassName("harga_barang_input")[0].value;
                
                var qty_barang = qty_barang_input?qty_barang_input:0
                var harga_barang = harga_barang_input?harga_barang_input:0

                // console.log(harga_barang_input);
                var total_harga_barang_input = qty_barang_input*harga_barang_input;
                this.getElementsByClassName("total_harga_barang_show")[0].value = 'Rp.'+total_harga_barang_input.toLocaleString('en-US');
                this.getElementsByClassName("total_harga_barang_input")[0].value = total_harga_barang_input;

                sum_harga += qty_barang_input*harga_barang_input;
            });
            $("#sub_total_transaksi").html('Rp.'+sum_harga.toLocaleString('en-US'));
            $("#sub_total_transaksi_input").val(sum_harga);

            var metode_pembayaran_input = $("#input_metode_pembayaran #single-select").val();
            var metode_pembayaran = metode_pembayaran_input? metode_pembayaran_input : false;
            var total_fee_admin = 0;
            if(metode_pembayaran) {
                console.log(metode_pembayaran);
                var postForm = { //Fetch form data
                    'metode' : metode_pembayaran_input, //Store name fields value
                    'amount' : sum_harga
                };

                $.ajax({ //Process the form using $.ajax()
                    type      : 'POST', //Method type
                    url       : '<?php echo base_url(); ?>transaksi/calculate_fee', //Your form processing file URL
                    data      : postForm, //Forms name
                    dataType  : 'json',
                    success   : function(data) {
                        console.log(data);
                        if(data.success) {
                            $("#biaya_admin_transaksi").html('Rp.'+data.data[0].total_fee.customer.toLocaleString('en-US'));
                            total_fee_admin = data.data[0].total_fee.customer;
                            var total_transaksi_html = sum_harga+total_fee_admin;
                            $("#total_transaksi").html('Rp.'+total_transaksi_html.toLocaleString('en-US'));

                            $("#biaya_admin_transaksi_input").val(total_fee_admin);
                            $("#total_transaksi_input").val(total_transaksi_html);
                        }
                    }
                });     
            }
        }
    </script>
</body>
</html>