<!--**********************************
            Content body end
        ***********************************-->
        <?php
        $transaksi_date_array = [];
        foreach ($transaksi_date as $value) {
            array_push($transaksi_date_array, date('M', strtotime($value)));
        }
        ?>
        <script>
            <?php
                $transaksi_date_array_js_array = json_encode($transaksi_date_array);
                $transaksi_success_perbulan_js_array = json_encode($transaksi_success_perbulan);
                $transaksi_fail_perbulan_js_array = json_encode($transaksi_fail_perbulan);
                echo "var transaksi_date = ". $transaksi_date_array_js_array . ";\n";
                echo "var transaksi_success_perbulan = ". $transaksi_success_perbulan_js_array . ";\n";
                echo "var transaksi_fail_perbulan = ". $transaksi_fail_perbulan_js_array . ";\n";
            ?>
            let draw = Chart.controllers.line.__super__.draw; //draw shadow
            var screenWidth = $(window).width();
            
                var options = {
                    series: [
                        {
                            name: 'Success',
                            data: transaksi_success_perbulan,
                            //radius: 12,	
                        }, 
                        {
                            name: 'Failed',
                            data: transaksi_fail_perbulan
                        }, 
                        
                    ],
                    chart: {
                        type: 'bar',
                        height: 370,
                        
                        toolbar: {
                            show: false,
                        },
                        
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '57%',
                            endingShape: "rounded",
                        },
                    },
                    states: {
                        hover: {
                            filter: 'none',
                        }
                    },
                    colors:['#009944', '#cf000f'],
                    dataLabels: {
                        enabled: false,
                    },
                    markers: {
                        shape: "circle",
                    },
                
                
                    legend: {
                        show: false,
                        fontSize: '12px',
                        labels: {
                            colors: '#000000',
                        },
                        markers: {
                            width: 18,
                            height: 18,
                            strokeWidth: 0,
                            strokeColor: '#fff',
                            fillColors: undefined,
                            radius: 12,	
                        }
                    },
                    stroke: {
                        show: true,
                        width: 4,
                        colors: ['transparent']
                    },
                    grid: {
                        borderColor: '#eee',
                    },
                    xaxis: {
                        categories: transaksi_date,
                        labels: {
                            style: {
                                colors: '#787878',
                                fontSize: '13px',
                                fontFamily: 'poppins',
                                fontWeight: 100,
                                cssClass: 'apexcharts-xaxis-label',
                            },
                        },
                        crosshairs: {
                            show: false,
                        }
                    },
                    yaxis: {
                        labels: {
                            offsetX:-16,
                            style: {
                                colors: '#787878',
                                fontSize: '13px',
                                fontFamily: 'poppins',
                                fontWeight: 100,
                                cssClass: 'apexcharts-xaxis-label',
                            },
                        },
                    },
                    fill: {
                        opacity: 1,
                        colors:['#009944', '#cf000f'],
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return "Rp " + val.toLocaleString('en-US')
                            }
                        }
                    },
                };

                var chartBar4 = new ApexCharts(document.querySelector("#chartBarNow"), options);
                chartBar4.render();
        </script>