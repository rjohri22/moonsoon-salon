@extends('admin.layout')
@section('content')
<!-- adminx-content-aside -->
<div class="adminx-content">
  <!-- <div class="adminx-aside">
        </div> -->
  <div class="adminx-main-content">
    <div class="container-fluid">
      <!-- BreadCrumb -->
      <div class="pb-3">
        <h1>Dashboard</h1>
      </div>

      <div class="row">


        <div class="col-md-6 col-lg-3 d-flex">
          <div class="card border-0 bg-primary text-white text-center mb-grid w-100">
            <div class="d-flex flex-row align-items-center h-100">
              <div class="card-icon d-flex align-items-center h-100 justify-content-center">
                <i class="fa fa-line-chart fa-2x" aria-hidden="true"></i>
              </div>
              <div class="card-body">
                <div class="card-info-title">Total Brands</div>
                <h3 class="card-title mb-0">
                  {{$brand}}
                </h3>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-3 d-flex">
          <div class="card border-0 bg-success text-white text-center mb-grid w-100">
            <div class="d-flex flex-row align-items-center h-100">
              <div class="card-icon d-flex align-items-center h-100 justify-content-center">
                <i class="fa fa-line-chart fa-2x" aria-hidden="true"></i>
              </div>
              <div class="card-body">
                <div class="card-info-title">Total Service</div>
                <h3 class="card-title mb-0">
                  {{$service}}
                </h3>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-3 d-flex">
          <div class="card border-0 bg-success text-white text-center mb-grid w-100">
            <div class="d-flex flex-row align-items-center h-100">
              <div class="card-icon d-flex align-items-center h-100 justify-content-center">
              <i class="fa fa-line-chart fa-2x" aria-hidden="true"></i>
              </div>
              <div class="card-body">
                <div class="card-info-title">Total Items</div>
                <h3 class="card-title mb-0">
                  {{$item}}
                </h3>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-3 d-flex">
          <div class="card border-0 bg-primary text-white text-center mb-grid w-100">
            <div class="d-flex flex-row align-items-center h-100">
              <div class="card-icon d-flex align-items-center h-100 justify-content-center">
                <!-- <i data-feather="credit-card"></i> -->
                <i class="fa fa-inr fa-2x" aria-hidden="true"></i>
              </div>
              <div class="card-body">
                <div class="card-info-title">Today Received Payment</div>
                <h3 class="card-title mb-0">
                  <!-- ₹ 11,654 -->
                  ₹ {{number_format($payment,2)}}
                </h3>
              </div>
            </div>
          </div>
        </div>
        {{--
            <div class="col-md-6 col-lg-3 d-flex">
              <div class="card mb-grid w-100">
                <div class="card-body d-flex flex-column">
                  <div class="d-flex justify-content-between mb-3">
                    <h5 class="card-title mb-0">
                        Today Recived payment
                    </h5>

                    <div class="card-title-sub">
                      ₹7589.43
                    </div>
                  </div>

                  <div class="progress mt-auto">
                    <div class="progress-bar" role="progressbar" style="width: 75%;" aria-valuenow="75"
                      aria-valuemin="0" aria-valuemax="100">3/4</div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-lg-3 d-flex">
              <div class="card mb-grid w-100">
                <div class="card-body d-flex flex-column">
                  <div class="d-flex justify-content-between mb-3">
                    <h5 class="card-title mb-0">
                      Open Tasks
                    </h5>

                    <div class="card-title-sub">
                      18/30
                    </div>
                  </div>

                  <div class="progress mt-auto">
                    <div class="progress-bar" role="progressbar" style="width: 60%;" aria-valuenow="75"
                      aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>
              </div>
            </div> --}}
        {{--
            <div class="col-md-6 col-lg-3 d-flex">
              <div class="card border-0 bg-primary text-white text-center mb-grid w-100">
                <div class="d-flex flex-row align-items-center h-100">
                  <div class="card-icon d-flex align-items-center h-100 justify-content-center">
                    <i data-feather="shopping-cart"></i>
                  </div>
                  <div class="card-body">
                    <div class="card-info-title">Today Booking</div>
                    <h3 class="card-title mb-0">
                      768
                    </h3>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-lg-3 d-flex">
              <div class="card border-0 bg-success text-white text-center mb-grid w-100">
                <div class="d-flex flex-row align-items-center h-100">
                  <div class="card-icon d-flex align-items-center h-100 justify-content-center">
                    <i data-feather="users"></i>
                  </div>
                  <div class="card-body">
                    <div class="card-info-title">Today Passenger</div>
                    <h3 class="card-title mb-0">
                      1,258
                    </h3>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-8">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <div class="card-header-title">Featured</div>

                  <nav class="card-header-actions">
                    <a class="card-header-action" data-toggle="collapse" href="#card1" aria-expanded="false"
                      aria-controls="card1">
                      <i data-feather="minus-circle"></i>
                    </a>

                    <div class="dropdown">
                      <a class="card-header-action" href="#" role="button" id="card1Settings" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i data-feather="settings"></i>
                      </a>

                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="card1Settings">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <a class="dropdown-item" href="#">Something else here</a>
                      </div>
                    </div>

                    <a href="#" class="card-header-action">
                      <i data-feather="x-circle"></i>
                    </a>
                  </nav>
                </div>
                <div class="card-body collapse show" id="card1">
                  <h4 class="card-title">Special title treatment</h4>
                  <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                  <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="card">
                <div class="card-header">
                  Featured
                </div>
                <div class="card-body">
                  <h4 class="card-title">Special title treatment</h4>
                  <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                  <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
              </div>
            </div>
          </div>--}}
      </div>
      <div class="row">
        <div class="col-md-12">
          <canvas id="myChart" width="400" height="400"></canvas>
        </div>
      </div>
    </div>
  </div>
  @endsection
  @section('js')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
  <script>
  // const ctx = document.getElementById('myChart');
    var orderDataSet = JSON.parse("{{json_encode($toReturn['order_dataset'])}}");
    console.log("orderDataSet",orderDataSet);
    var ctx = document.getElementById("myChart");
    var myLineChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
          label: "Earing",
          lineTension: 0.3,
          backgroundColor: "rgba(78, 115, 223, 0.05)",
          borderColor: "rgba(78, 115, 223, 1)",
          pointRadius: 3,
          pointBackgroundColor: "rgba(78, 115, 223, 1)",
          pointBorderColor: "rgba(78, 115, 223, 1)",
          pointHoverRadius: 3,
          pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
          pointHoverBorderColor: "rgba(78, 115, 223, 1)",
          pointHitRadius: 10,
          pointBorderWidth: 2,
          // data: [0,0,635,0,0,0,0,0,0,0,0,0],
          data: orderDataSet,
          // data: [0, 10000, 5000, 15000, 10000, 20000, 15000, 25000, 20000, 30000, 25000, 40000],
        }],
      },
      options: {
        maintainAspectRatio: false,
        layout: {
          padding: {
            left: 10,
            right: 25,
            top: 25,
            bottom: 0
          }
        },
        scales: {
          xAxes: [{
            time: {
              unit: 'date'
            },
            gridLines: {
              display: false,
              drawBorder: false
            },
            ticks: {
              maxTicksLimit: 7
            }
          }],
          yAxes: [{
            ticks: {
              maxTicksLimit: 5,
              padding: 10,
              // Include a dollar sign in the ticks
              callback: function(value, index, values) {
                return  number_format(value);
              }
            },
            gridLines: {
              color: "rgb(234, 236, 244)",
              zeroLineColor: "rgb(234, 236, 244)",
              drawBorder: false,
              borderDash: [2],
              zeroLineBorderDash: [2]
            }
          }],
        },
        legend: {
          display: false
        },
        tooltips: {
          backgroundColor: "rgb(255,255,255)",
          bodyFontColor: "#858796",
          titleMarginBottom: 10,
          titleFontColor: '#6e707e',
          titleFontSize: 14,
          borderColor: '#dddfeb',
          borderWidth: 1,
          xPadding: 15,
          yPadding: 15,
          displayColors: false,
          intersect: false,
          mode: 'index',
          caretPadding: 10,
          callbacks: {
            label: function(tooltipItem, chart) {
              var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
              return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
            }
          }
        }
      }
    });
    
  </script>
  @endsection
