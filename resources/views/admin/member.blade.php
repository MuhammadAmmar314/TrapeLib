@extends('layouts.admin')
@section('header', 'Member')

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
                            <div class="col-md-9 d-flex align-items-center">
                                <a href="#" @click="addData()" class="btn btn-sm btn-primary pull-right">Create New Member</a>
                            </div>

                            <div class="col-md-3 d-flex align-items-center">
                                <select class="form-control" name="gender">
                                    <option value="0">Semua Jenis Kelamin</option>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                </select>
                        </div>
                    </div>
                    
                    <div class="card-body table-responsive p-0" style="height: 800px;">
                        <table id="example1" class="table table-head-fixed text-nowrap table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class='text-center'>No.</th>
                                    <th class='text-center'>Name</th>
                                    <th class='text-center'>Gender</th>
                                    <th class='text-center'>Phone Number</th>
                                    <th class='text-center'>Address</th>
                                    <th class='text-center'>Email</th>
                                    <th class='text-center'>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" :action="actionUrl" autocomplete="off" @submit="submitForm($event, data.id)">
                        <div class="modal-header">
                            <h4 class="modal-title">Member</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @csrf

                            <input type="hidden" name="_method" value="PUT" v-if="editStatus">

                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" name="name" :value="data.name" required="" placeholder="Name">
                            </div>
                            <div class="form-group">
                                <label>Gender</label>
                                <input type="text" class="form-control" name="gender" :value="data.gender" required="" placeholder="M/F">
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" class="form-control" name="phone_number" :value="data.phone_number" required="" placeholder="Phone Number">
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" class="form-control" name="address" :value="data.address" required="" placeholder="Address">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control" name="email" :value="data.email" required="" placeholder="Email">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
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
        var actionUrl = '{{ url('members') }}';
        var apiUrl = '{{ url('/api/members') }}';

        var columns = [
            {data: 'DT_RowIndex', class: 'text-center', orderable: false},
            {data: 'name', class: 'text-center', orderable: false},
            {data: 'gender', class: 'text-center', orderable: true},
            {data: 'phone_number', class: 'text-center', orderable: true},
            {data: 'address', class: 'text-center', orderable: true},
            {data: 'email', class: 'text-center', orderable: true},
            {render: function(index, row, data, meta){
                return `
                    <a href="#" class="btn btn-warning btn-sm" onclick="controller.editData(event, ${meta.row})">
                        Edit
                    </a>
                    <a class="btn btn-danger btn-sm" onclick="controller.deleteData(event, ${data.id})">
                        Delete
                    </a>`;
            }, orderable: false, width: '200px', class:'text-center'},
        ];
    </script>
    <script src="{{ asset('js/data.js') }}"></script>
    <script type="text/javascript">
        $('select[name=gender]').on('change' , function() {
            gender = $('select[name=gender]').val();

            if (gender == 0) {
                controller.table.ajax.url(apiUrl).load();
            } else {
                controller.table.ajax.url(apiUrl+'?gender='+gender).load();
            }
        });
    </script>
@endsection