<?php
	// Start the session.
	session_start();
	if(!isset($_SESSION['user'])) header('location: login.php');

	// Get SESION user from data.
	$user = $_SESSION['user'];

	// Get graph data - purchase order by status
	include('database/po_status_pie_graph.php');

	// Get graph data - supplier product count
	include('database/supplier_product_bar_graph.php');


	// Get line graph data - delivery history per day
	include('database/delivery_history.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Dashboard - Inventory Management System</title>
	<link rel="stylesheet" type="text/css" href="css/login.css">
	<script src="https://use.fontawesome.com/0c7a3095b5.js"></script>
</head>
<body>
	<div id="dashboardMainContainer">
		<?php include('partials/app-sidebar.php') ?>
		<div class="dasboard_content_container" id="dasboard_content_container">
			<?php include('partials/app-topnav.php') ?>
			<?php if(in_array('dashboard_view', $user['permissions'])) { ?>
			<div class="dashboard_content">
				<div class="dashboard_content_main">
					<div class="col50">
						<figure class="highcharts-figure">
						    <div id="container"></div>
						    <p class="highcharts-description">
						        Here is the breakdown of the purchase orders by status.
						    </p>
						</figure>						
					</div>			
					<div class="col50">
						<figure class="highcharts-figure">
						    <div id="containerBarChart"></div>
						    <p class="highcharts-description">
						        Here is the breakdown of the purchase orders by status.
						    </p>
						</figure>						
					</div>			
				</div>
					<div id="deliveryHistory"></div>
			</div>
			<?php } else { ?>
				<div id="errorMessage"> Access denied.</div>
			<?php } ?>
		</div>
	</div>

<script src="js/script.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script>
	var graphData = <?= json_encode($results) ?>;

	Highcharts.chart('container', {
	    chart: {
	        plotBackgroundColor: null,
	        plotBorderWidth: null,
	        plotShadow: false,
	        type: 'pie'
	    },
	    title: {
	        text: 'Purchase Orders By Status',
	        align: 'left'
	    },
	    tooltip: {
          	pointFormatter: function(){
		        var point = this,
		            series = point.series;

		        return `<b>${point.name}</b>: ${point.y}`
		    }
	    },
	    plotOptions: {
	        pie: {
	            allowPointSelect: true,
	            cursor: 'pointer',
	            dataLabels: {
	                enabled: true,
	                format: '<b>{point.name}</b>: {point.y}'
	            }
	        }
	    },
	    series: [{
	        name: 'Status',
	        colorByPoint: true,
	        data: graphData
	    }],
	    exporting: {
        chartOptions: { // specific options for the exported image
            plotOptions: {
                series: {
                    dataLabels: {
                        enabled: true
                    }
                }
            }
        },
        fallbackToExportServer: false
    	}
	});




	var barGraphData = <?= json_encode($bar_chart_data) ?>;
	var barGraphCategories = <?= json_encode($categories) ?>;

	Highcharts.chart('containerBarChart', {
	    chart: {
	        type: 'column'
	    },
	    title: {
	        text: 'Product Count Assigned To Supplier'
	    },
	    xAxis: {
	        categories: barGraphCategories,
	        crosshair: true
	    },
	    yAxis: {
	        min: 0,
	        title: {
	            text: 'Product Count'
	        }
	    },
	    tooltip: {
          	pointFormatter: function(){
		        var point = this,
		            series = point.series;

		        return `<b>${point.category}</b>: ${point.y}`
		    }
	    },
	    plotOptions: {
	        column: {
	            pointPadding: 0.2,
	            borderWidth: 0
	        }
	    },
	    series: [{
	        name: 'Suppliers',
	        data: barGraphData
	    }],
	    exporting: {
        chartOptions: { // specific options for the exported image
            plotOptions: {
                series: {
                    dataLabels: {
                        enabled: true
                    }
                }
            }
        },
        fallbackToExportServer: false
    	}
	});

	var lineCategories = <?= json_encode($line_categories) ?>;
	var lineData = <?= json_encode($line_data) ?>;

	Highcharts.chart('deliveryHistory', {
	  chart: {
	  	type: 'spline'
	  },
	  title: {
	    text: 'Delivery History Per Day',
	    align: 'left'
	  },

	  yAxis: {
	    title: {
	      text: 'Product Delivered'
	    }
	  },

	  xAxis: {
	  	categories: lineCategories
	  },

	  legend: {
	    layout: 'vertical',
	    align: 'right',
	    verticalAlign: 'middle'
	  },

	  plotOptions: {
	    series: {
	      label: {
	        connectorAllowed: false
	      },
	    }
	  },

	  series: [{
	    name: 'Product Delivered',
	    data: lineData
	  }],

	  responsive: {
	    rules: [{
	      condition: {
	        maxWidth: 500
	      },
	      chartOptions: {
	        legend: {
	          layout: 'horizontal',
	          align: 'center',
	          verticalAlign: 'bottom'
	        }
	      }
	    }]
	  },
	  exporting: {
        chartOptions: { // specific options for the exported image
            plotOptions: {
                series: {
                    dataLabels: {
                        enabled: true
                    }
                }
            }
        },
        fallbackToExportServer: false
    	}
	});

</script>

</body>
</html>