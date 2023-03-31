@extends('layouts.admin')
@section('header', 'Home')

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h1>{{ $numbers_of_book }}</h1>
                <p>Numbers of Book</p>
            </div>
            <div class="icon">
                <i class="fa fa-book"></i>
            </div>
            <a href="{{ url('books') }}" class="small-box-footer">More Info<i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h1>{{ $numbers_of_member }}</h1>
                <p>Numbers of Member</p>
            </div>
            <div class="icon">
                <i class="fa fa-book"></i>
            </div>
            <a href="{{ url('members') }}" class="small-box-footer">More Info<i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h1>{{ $numbers_of_publisher }}</h1>
                <p>Numbers of Publisher</p>
            </div>
            <div class="icon">
                <i class="fa fa-book"></i>
            </div>
            <a href="{{ url('publishers') }}" class="small-box-footer">More Info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h1>{{ $numbers_of_transaction }}</h1>
                <p>Transaction Data</p>
            </div>
            <div class="icon">
                <i class="fa fa-book"></i>
            </div>
            <a href="{{ url('transactions') }}" class="small-box-footer">More Info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card card-danger">
            <div class="card-header">
                <h3 class="card-title">Grafik Penerbit</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tools" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tools" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="donutChart" style="min-height:250px; height:250px; max-height:250px; max-width:100%"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Grafik Peminjaman</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tools" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tools" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="barChart" style="min-height:250px; height:250px; max-height:250px; max-width:100%"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Grafik Harga Buku</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tools" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tools" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="barChart2" style="min-height:250px; height:250px; max-height:250px; max-width:100%"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<!-- ChartJS -->
<script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
<script type="text/javascript">

    var label_donut = '{!! json_encode($label_donut) !!}';
    var data_donut = '{!! json_encode($data_donut )!!}';
    var data_bar = '{!! json_encode($data_bar )!!}';
    var label_bar2 = '{!! json_encode($book_id )!!}';
    var data_bar2 = '{!! json_encode($data_bar2 )!!}';

    $(function () {
        /* ChartJS
        * -------
        * Here we will create a few charts using ChartJS
        */

        //-------------
        //- DONUT CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
        var donutData        = {
            labels: JSON.parse(label_donut),
            datasets: [
                {
                data: JSON.parse(data_donut),
                backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de' , '#8dfd45' , '#532f17' , '#9045fd' , '#a8602e' ,'#ec45fd'],
                }
            ]
        }

        var donutOptions     = {
            maintainAspectRatio : false,
            responsive : true,
        }

        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        new Chart(donutChartCanvas, {
            type: 'doughnut',
            data: donutData,
            options: donutOptions,
        })

        //-------------
        //- BAR CHART 1 -
        //-------------

        var areaChartData = {
            labels: ['January' , 'February' , 'March' , 'April' , 'May' , 'June' , 'July' , 'August' , 'September' , 'October' , 'November' , 'December'],
            datasets: JSON.parse(data_bar)
        }

        var barChartCanvas = $('#barChart').get(0).getContext('2d')
        var barChartData = $.extend(true, {}, areaChartData)

        var barChartOptions = {
            responsive              : true,
            maintainAspectRatio     : false,
            datasetFill             : false
        }

        new Chart(barChartCanvas, {
            type: 'bar',
            data: barChartData,
            options: barChartOptions
        })

        /*
        * BAR CHART 2
        * ---------
        */

        var areaChartData = {
            labels: JSON.parse(label_bar2),
            datasets: JSON.parse(data_bar2)
        }

        var barChartCanvas = $('#barChart2').get(0).getContext('2d')
        var barChartData = $.extend(true, {}, areaChartData)

        var barChartOptions = {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                    }
                }]
            },
            responsive              : true,
            maintainAspectRatio     : false,
            datasetFill             : false
        }

        new Chart(barChartCanvas, {
            type: 'bar',
            data: barChartData,
            options: barChartOptions
        })
        // var areaChartData = {
        //     labels: JSON.parse(book_id),
        //     datasets: JSON.pase(book_price)
        // }

        // var barChartCanvas = $('#barChart2').get(0).getContext('2d')
        // var barChartData = $.extend(true, {}, areaChartData)

        // var barChartOptions = {
        //     responsive              : true,
        //     maintainAspectRatio     : false,
        //     datasetFill             : false
        // }

        // new Chart(barChartCanvas, {
        //     type: 'bar',
        //     data: barChartData,
        //     options: barChartOptions
        // })

        /* END BAR CHART */
    })
</script>


