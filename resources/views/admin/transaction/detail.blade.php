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
            <div class="col-md-4">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Detail Transaction</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Member</label>
                            <div class="col-sm-9" style="margin: auto">
                                {{ $members->name }}
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Date Start</label>
                            <div class="col-sm-9" style="margin: auto">
                                {{ $transaction->date_start }}
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Book</label>
                            <div class="col-sm-9">
                                @foreach($listBook as $book)
                                    {{ $book->title }}<br>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group row" >
                            <label class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9" style="margin: auto">
                                {{ statusLabel($transaction->status) }}
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <a href="{{url('transactions')}}" class="btn btn-default col-sm-3 offset-sm-9">Cancel</a>
                    </div>
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