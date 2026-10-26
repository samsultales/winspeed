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
        <a class="btn btn-danger mb-2" href="{{ route('indikator.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
    </div>

    <form method="POST" action="{{ route('indikator.update', $indikator->key_id) }}">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                @csrf
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Interval Perekaman:</strong>
                            <input type="number" name="bts_rusak" placeholder="Unit Kerja" class="form-control" value="{{ $indikator->bts_rusak }}">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="form-group">
                            <strong>Satuan :</strong>
                            <select name="satuan" class="select2" id='satuan' data-dropdown-css-class="select2-purple" style="width: 100%;">
                                <option value="hours">Jam</option>
                                <option value="days">Hari</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <button type="submit" class="btn btn-success mb-3"><i class="fa-solid fa-floppy-disk" data-flag="0"></i> Submit</button>
                </div>
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

    let satuan = "{{ $indikator->satuan }}";
    $('#timezone').val(satuan).trigger("change")
</script>

@endsection