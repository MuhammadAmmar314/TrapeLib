@extends('layouts.admin')
@section('header', 'Transaction')

@section('css')
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')
    <div id="controller">
        <div class="row justify-content-center">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Create New Transaction</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="addForm" action="{{ url('transactions') }}" method="post" class="form-horizontal" >
                        @csrf
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Member</label>
                                <div class="col-sm-10" style="margin: auto">
                                    <select id="selectmbr" name="member_id" class="form-control"></select>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Date</label>
                                <div class="col-sm-4">
                                    <input type="date" class="form-control" name="date_start">
                                </div>
                                <div class="col-sm-1">
                                    
                                </div>
                                <div class="col-sm-1">
                                    -
                                </div>
                                <div class="col-sm-4 d-flex align-items-center">
                                    <input type="date" class="form-control" name="date_end">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Book</label>
                                <div class="col-sm-10">
                                    <select id="selectbk" name="book_id[]" class="form-select form-control" style="width: 100%;" aria-label="Default select example"></select>
                                </div>
                            </div>

                            <div class="form-group row" style="display: none">
                                <label class="col-sm-2 col-form-label">Status</label>
                                <div class="form-check col-sm-10">
                                    <input class="form-check-input" type="radio" name="status" value="1" checked>
                                    <label class="form-check-label">Belum dikembalikan</label>
                                </div>
                                <div class="form-check offset-sm-2 col-sm-10">
                                    <input class="form-check-input" type="radio" name="status" value="0">
                                    <label class="form-check-label">Sudah dikembalikan</label>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button id="saveBtn" type="submit" class="btn btn-primary offset-sm-9 col-sm-3">Save</button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Select2 -->
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- <script src="{{ asset('js/data.js') }}"></script> -->
    <script>
    //SELECT OPTION OF MEMBER
    $(document).ready(function() {
        $('#selectmbr').select2({
            placeholder: "Select Member",
            ajax: {
                url: "{{ route('select.mbr') }}",
                processResults: function({
                    data
                }){
                    return {
                        results: $.map(data, function(item){
                            return {
                                id: item.id,
                                text: item.name
                            }
                        })
                    }
                }
            }
        });
        //MULTIPLE SELECT OF BOOK
        $('#selectbk').attr("multiple" , "multiple"); //SET ATTRIBUTE OF SELECT WITH THIS ID AS MULTIPLE
        $('#selectbk').select2({
            placeholder: "Select book",
            allowClear: true,
            ajax : {
                url: "{{ route('select.bk') }}",
                processResults: function({
                    data
                }) {
                    return {
                        results: $.map(data, function(item){
                            console.log(item);
                            return {
                                id: item.id,
                                text: item.title
                            }
                        })
                    }
                }
            }
        });
    });
    </script>
@endsection