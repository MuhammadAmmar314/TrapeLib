@extends('layouts.admin')
@section('header', 'Transaction')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
    <div id="controller">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row my-2">
                            <div class="col-md-6 d-flex align-items-center">
                                <a href="#" @click="addData()" class="btn btn-sm btn-primary pull-right">Create New Transaction</a>
                            </div>

                            <div class="col-md-3 d-flex align-items-center">
                                <select class="form-control" name="status">
                                    <option value="0">Belum dikembalikan</option>
                                    <option value="1">Sudah dikembalikan</option>
                                </select>
                            </div>

                            <div class="col-md-3 d-flex align-items-center">
                                    <input type="date" class="form-control" name="date_start">
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body table-responsive p-0" style="height: 800px;">
                        <table id="example1" class="table table-head-fixed text-nowrap table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class='text-center'>Date Start</th>
                                    <th class='text-center'>Date End</th>
                                    <th class='text-center'>Name</th>
                                    <th class='text-center'>Duration(Days)</th>
                                    <th class='text-center'>Total Book</th>
                                    <th class='text-center'>Total Payment</th>
                                    <th class='text-center'>Status</th>
                                    <th class='text-center'>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    
    <script type="text/javascript">
        var actionUrl = '{{ url('transactions') }}';
        var apiUrl = '{{ url('/api/transactions') }}';

        // var columns = [
        //     {data: 'date_start', class: 'text-center', orderable: false},
        //     {data: 'date_end', class: 'text-center', orderable: false},
        //     {data: 'name', class: 'text-center', orderable: true},
        //     {data: 'duration', class: 'text-center', orderable: true},
        //     {data: 'total_book', class: 'text-center', orderable: true},
        //     {data: 'total_payment', class: 'text-center', orderable: true},
        //     {data: 'status', class: 'text-center', orderable: true},
            // {render: function(index, row, data, meta){
            //     return `
            //         <a href="#" class="btn btn-warning btn-sm" onclick="controller.editData(event, ${meta.row})">
            //             Edit
            //         </a>
            //         <a class="btn btn-danger btn-sm" onclick="controller.deleteData(event, ${data.id})">
            //             Delete
            //         </a>`;
            // }, orderable: false, width: '200px', class:'text-center'},
        // ];
    </script>
    <script src="{{ asset('js/data.js') }}"></script>
    <script type="text/javascript">
        $('select[name=status]').on('change' , function() {
            status = $('select[name=status]').val();
            if (status == '0') {
                controller.table.ajax.url(apiUrl).load();
            } else {
                controller.table.ajax.url(apiUrl+'?stats='+status).load();
            }
        });
    </script>
    <script>
        $('input[name=date_start]').on('change' , function() {
            date = $('input[name=date_start]').val();
            
            controller.table.ajax.url(apiUrl+'?date_start='+date).load();
        });
    </script>
@endsection