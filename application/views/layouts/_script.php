 <!-- jquery latest version -->
 <script src="<?= base_url(); ?>assets/js/vendor/jquery-2.2.4.min.js"></script>
 <!-- bootstrap 4 js -->
 <script src="<?= base_url(); ?>assets/js/popper.min.js"></script>
 <script src="<?= base_url(); ?>assets/js/bootstrap.min.js"></script>
 <script src="<?= base_url(); ?>assets/js/owl.carousel.min.js"></script>
 <script src="<?= base_url(); ?>assets/js/metisMenu.min.js"></script>
 <script src="<?= base_url(); ?>assets/js/jquery.slimscroll.min.js"></script>
 <script src="<?= base_url(); ?>assets/js/jquery.slicknav.min.js"></script>

 <!-- Start datatable js -->
 <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
 <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
 <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
 <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

 <script src="<?= base_url(); ?>assets/css/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
 <script src="<?= base_url(); ?>assets/js/vanillatoasts.js"></script>
 <!-- start chart js -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
 <!-- start highcharts js -->
 <script src="https://code.highcharts.com/highcharts.js"></script>
 <!-- start zingchart js -->
 <script src="https://cdn.zingchart.com/zingchart.min.js"></script>
 <!-- sweetalert js -->
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
 <!-- select2 js -->
 <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
 <!-- cropie js -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.js" integrity="sha512-vUJTqeDCu0MKkOhuI83/MEX5HSNPW+Lw46BA775bAWIp1Zwgz3qggia/t2EnSGB9GoS2Ln6npDmbJTdNhHy1Yw==" crossorigin="anonymous"></script>
 <script>
     zingchart.MODULESDIR = "https://cdn.zingchart.com/modules/";
     ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9", "ee6b7db5b51705a13dc2339db3edaf6d"];
 </script>
 <!-- all line chart activation -->
 <script src="<?= base_url(); ?>assets/js/line-chart.js"></script>
 <!-- all pie chart -->
 <script src="<?= base_url(); ?>assets/js/pie-chart.js"></script>
 <!-- others plugins -->
 <script src="<?= base_url(); ?>assets/js/plugins.js"></script>
 <script src="<?= base_url(); ?>assets/js/scripts.js"></script>
 <script src="<?= base_url(); ?>assets/js/app.js"></script>


 <script src="https://js.pusher.com/6.0/pusher.min.js"></script>
 <?php $this->load->view('layouts/myapp'); ?>

 <script>
     getSalesChart();


     

     function getSalesChart() {
         if ($('#coin_sales1').length) {
             var ctx = document.getElementById("coin_sales1").getContext('2d');
             var chart = new Chart(ctx, {
                 // The type of chart we want to create
                 type: 'line',
                 // The data for our dataset
                 data: {
                     labels: [
                         <?php
                            foreach (getMonth() as $key => $value) {
                                echo "'" . $value . "',";
                            }

                            ?>
                     ],
                     datasets: [{
                         label: "Sales",
                         backgroundColor: "rgba(117, 19, 246, 0.1)",
                         borderColor: '#0b76b6',
                         data: [
                             <?php
                                foreach (getMonth() as $key => $value) {
                                    echo "'" . array_sum(array_column($sales_report[$key], 'total')) . "',";
                                }

                                ?>
                         ],
                     }]
                 },
                 // Configuration options go here
                 options: {
                     legend: {
                         display: false
                     },
                     animation: {
                         easing: "easeInOutBack"
                     },
                     scales: {
                         yAxes: [{
                             display: !1,
                             ticks: {
                                 fontColor: "rgba(0,0,0,0.5)",
                                 fontStyle: "bold",
                                 beginAtZero: !0,
                                 maxTicksLimit: 5,
                                 padding: 0
                             },
                             gridLines: {
                                 drawTicks: !1,
                                 display: !1
                             }
                         }],
                         xAxes: [{
                             display: !1,
                             gridLines: {
                                 zeroLineColor: "transparent"
                             },
                             ticks: {
                                 padding: 0,
                                 fontColor: "rgba(0,0,0,0.5)",
                                 fontStyle: "bold"
                             }
                         }]
                     },
                     tooltips: {
                         callbacks: {
                             label: function(tooltipItem, data) {
                                 var label = data.labels[tooltipItem.index];
                                 var val = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                                 return label + ': IDR ' + formatRupiah(val);
                             }
                         }
                     }
                 }
             });
         }
     }

     function getSalesRequestChart(tot_arr) {
         if ($('#coin_sales1').length) {
             var ctx = document.getElementById("coin_sales1").getContext('2d');
             var chart = new Chart(ctx, {
                 // The type of chart we want to create
                 type: 'line',
                 // The data for our dataset
                 data: {
                     labels: [
                         <?php
                            foreach (getMonth() as $key => $value) {
                                echo "'" . $value . "',";
                            }

                            ?>
                     ],
                     datasets: [{
                         label: "Sales",
                         backgroundColor: "rgba(117, 19, 246, 0.1)",
                         borderColor: '#0b76b6',
                         data: tot_arr,
                     }]
                 },
                 // Configuration options go here
                 options: {
                     legend: {
                         display: false
                     },
                     animation: {
                         easing: "easeInOutBack"
                     },
                     scales: {
                         yAxes: [{
                             display: !1,
                             ticks: {
                                 fontColor: "rgba(0,0,0,0.5)",
                                 fontStyle: "bold",
                                 beginAtZero: !0,
                                 maxTicksLimit: 5,
                                 padding: 0
                             },
                             gridLines: {
                                 drawTicks: !1,
                                 display: !1
                             }
                         }],
                         xAxes: [{
                             display: !1,
                             gridLines: {
                                 zeroLineColor: "transparent"
                             },
                             ticks: {
                                 padding: 0,
                                 fontColor: "rgba(0,0,0,0.5)",
                                 fontStyle: "bold"
                             }
                         }]
                     },
                     tooltips: {
                         callbacks: {
                             label: function(tooltipItem, data) {
                                 var label = data.labels[tooltipItem.index];
                                 var val = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                                 return label + ': IDR ' + formatRupiah(val);
                             }
                         }
                     }
                 }
             });
         }
     }


     getProductInChart();

     function getProductInChart() {
         if ($('#product_in_chart').length) {
             var ctx = document.getElementById("product_in_chart").getContext('2d');
             var chart = new Chart(ctx, {
                 // The type of chart we want to create
                 type: 'line',
                 // The data for our dataset
                 data: {
                     labels: [
                         <?php
                            foreach (getMonth() as $key => $value) {
                                echo "'" . $value . "',";
                            }

                            ?>
                     ],
                     datasets: [{
                         label: "Product In",
                         backgroundColor: "rgba(240, 180, 26, 0.1)",
                         borderColor: '#F0B41A',
                         data: [
                             <?php
                                foreach (getMonth() as $key => $value) {
                                    echo "'" . array_sum(array_column($product_in_report[$key], 'stock_in')) . "',";
                                }

                                ?>
                         ],
                     }]
                 },
                 // Configuration options go here
                 options: {
                     legend: {
                         display: false
                     },
                     animation: {
                         easing: "easeInOutBack"
                     },
                     scales: {
                         yAxes: [{
                             display: !1,
                             ticks: {
                                 fontColor: "rgba(0,0,0,0.5)",
                                 fontStyle: "bold",
                                 beginAtZero: !0,
                                 maxTicksLimit: 5,
                                 padding: 0
                             },
                             gridLines: {
                                 drawTicks: !1,
                                 display: !1
                             }
                         }],
                         xAxes: [{
                             display: !1,
                             gridLines: {
                                 zeroLineColor: "transparent"
                             },
                             ticks: {
                                 padding: 0,
                                 fontColor: "rgba(0,0,0,0.5)",
                                 fontStyle: "bold"
                             }
                         }]
                     }
                 }
             });
         }
     }

     getItemSales();

     function getItemSales() {
         if ($('#items_sales_chart').length) {
             var ctx = document.getElementById("items_sales_chart").getContext('2d');
             var chart = new Chart(ctx, {
                 // The type of chart we want to create
                 type: 'line',
                 // The data for our dataset
                 data: {
                     labels: [
                         <?php
                            foreach (getMonth() as $key => $value) {
                                echo "'" . $value . "',";
                            }

                            ?>
                     ],
                     datasets: [{
                         label: "Items Sales",
                         backgroundColor: "rgba(247, 163, 58, 0.1)",
                         borderColor: '#fd9d24',
                         fill: true,
                         data: [
                             <?php
                                foreach (getMonth() as $key => $value) {
                                    echo "'" . array_sum(array_column($items_sales_report[$key], 'qty')) . "',";
                                }

                                ?>
                         ],
                     }]
                 },
                 // Configuration options go here
                 options: {
                     legend: {
                         display: false
                     },
                     animation: {
                         easing: "easeInOutBack"
                     },
                     scales: {
                         yAxes: [{
                             display: !1,
                             ticks: {
                                 fontColor: "rgba(0,0,0,0.5)",
                                 fontStyle: "bold",
                                 beginAtZero: !0,
                                 maxTicksLimit: 5,
                                 padding: 0
                             },
                             gridLines: {
                                 drawTicks: !1,
                                 display: !1
                             }
                         }],
                         xAxes: [{
                             display: !1,
                             gridLines: {
                                 zeroLineColor: "transparent"
                             },
                             ticks: {
                                 padding: 0,
                                 fontColor: "rgba(0,0,0,0.5)",
                                 fontStyle: "bold"
                             }
                         }]
                     }
                 }
             });
         }
     }


     const base_url2 = 'http://localhost/kasir_online/';
     $(function() {

         $("#yearDashboard").datepicker({
             format: "yyyy",
             viewMode: "years",
             minViewMode: "years",
             autoclose: true,

         });


         $(document).on('change', '#yearDashboard', function() {
             let year = $(this).val();
             $.ajax({
                 method: "POST",
                 url: base_url2 + 'admin/dashboard/sales_report/' + year,
                 beforeSend: function() {

                     $('#coin_sales1').hide();
                     $('.spaceSalesTotChart').hide();
                     $('.loadDash').show();
                 },
                 success: function(response) {
                     var data = JSON.parse(response);
                     //console.log(data[1].length != 0 ? data[1].length : '');

                     var arr = [];
                     var tot_arr = [];
                     for (var i = 1; i <= 12; i++) {
                         if (data[i].length != 0) {
                             for (var j = 0; j < data[i].length; j++) {

                                 arr.push(Number(data[i][j].total));
                             }


                             let sum = arr.reduce(function(a, b) {
                                 return a + b;
                             });

                             tot_arr.push(sum);
                         } else {
                             tot_arr.push(0);
                         }


                     }
                     $('#coin_sales1').fadeToggle(1000);
                     $('.spaceSalesTotChart').fadeToggle(1000);
                     $('.loadDash').hide();
                     getSalesRequestChart(tot_arr);

                     let tot_sales = tot_arr.reduce(function(a, b) {
                         return a + b;
                     });

                     $('.salesTotalChart').text('IDR ' + formatRupiah(tot_sales));
                 }

             });

         });
     });
 </script>