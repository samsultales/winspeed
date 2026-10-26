@extends('admin.app')

@section('content')

@if (count($errors) > 0)
<div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">
    <div class="card-header bg-primary">
        <a class="btn btn-danger mb-2" href="{{ route('units.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
    </div>

    <form method="POST" action="{{ route('units.update', $units->key_id) }}">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                @csrf
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Kode :</strong>
                            <input type="text" name="kd_unit_kerja" placeholder="Kd Unit Kerja" class="form-control" value="{{ $units->kd_unit_kerja }}" readonly>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Name:</strong>
                            <input type="text" name="nm_unit_kerja" placeholder="Unit Kerja" class="form-control" value="{{ $units->nm_unit_kerja }}">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Wilayah:</strong>
                            <select name="kd_wilayah" class="select2" id='kd_wilayah' data-dropdown-css-class="select2-purple" style="width: 100%;">
                                <option value="0">Pilih Wilayah</option>
                                @foreach ($regional as $value => $label)
                                <option value="{{ $value }}" {{ isset($owilayah[$value]) ? 'selected' : ''}}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Regional:</strong>
                            <select name="kd_regional" class="select2" id='kd_regional' data-dropdown-css-class="select2-purple" style="width: 100%;">
                                <option value="0">Pilih Regional</option>
                                @foreach ($wilayah as $value => $label)
                                <option value="{{ $value }}" {{ isset($oregional[$value]) ? 'selected' : ''}}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <button type="submit" class="btn btn-success mb-3"><i class="fa-solid fa-floppy-disk" data-flag="0"></i> Submit</button>
            </div>
        </div>
    </form>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select a role",
            theme: 'bootstrap4'
        });
    });

    function setRegionalListValue(param) {
        $.ajax({
            url: "{{route('getRegionalList')}}",
            type: "get",
            data: param,
            success: function(res) {

                $('#kd_regional').empty();
                $('#kd_regional').find('option').not(':first').remove();
                $('#kd_regional').append('<option value=""></option>');
                $.each(res, function(index, data) {
                    $('#kd_regional').append('<option value="' + data['kd_regional'] + '">' + data['regional'] + '</option>');
                });

            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire(errorThrown, textStatus, "error");
            },
        });
    }

    $("#kd_wilayah").on('change', async function(e) {

        let _id = $("#kd_wilayah").val();
        param = {
            id: _id,
        };

        setRegionalListValue(param);
    });
</script>

@endsection