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
        <a class="btn btn-danger mb-2" href="{{ route('interval.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
    </div>

    <form method="POST" action="{{ route('interval.update', $interval->key_id) }}">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                @csrf
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Waktu Interval perekaman (Menit):</strong>
                            <input type="number" name="interval" placeholder="Interval" class="form-control" value="{{ $interval->interval }}">
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
</script>

@endsection