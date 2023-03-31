@extends('layouts.admin')
@section('header', 'Parent (CRUD Example)')

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
                        <h3 class="card-title">Edit</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('parent.update', $parent->id) }}" method="post" class="form-horizontal" >
                        @csrf
                        {{ method_field('PUT') }}

                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Member</label>
                                <div class="col-sm-10" style="margin: auto">
                                    <select id="selectmbr1" name="member_id" class="form-control">
                                        <option>Choose</option>
                                        @foreach($members as $id => $name)
                                            <option value="{{ $id }}" {{ $id == $parent->member_id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Book</label>
                                <div class="col-sm-10">
                                    <select id="selectbk1" multiple="" name="book_id[]" class="form-select form-control" style="width: 100%;" aria-label="Default select example">
                                        @foreach($books as $id => $title)
                                            <option value="{{ $id }}">{{ $title }}</option>
                                        @endforeach
                                    </select>
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
    $(document).ready(function() {
        $('#selectmbr1').select2({
            placeholder: "Select Member",
        }); 
        $('#selectbk1').attr("multiple" , "multiple");   
        $('#selectbk1').select2({
            placeholder: "Select book",
            allowClear: true,
        });
        $('#selectbk1').val({!! $selected !!});
        $('#selectbk1').trigger('change'); 
    });
    </script>
@endsection