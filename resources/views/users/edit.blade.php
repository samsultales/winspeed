@extends ('admin.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-right">
            <a class="btn btn-primary btn-sm mb-2" href="{{ route('users.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>

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
<div class="card card-primary">
    <form method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        <input type="text" name="name" placeholder="Name" class="form-control" value="{{ $user->name }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Email:</strong>
                        <input type="email" name="email" placeholder="Email" class="form-control" value="{{ $user->email }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Password:</strong>
                        <input type="password" name="password" placeholder="Password" class="form-control">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Confirm Password:</strong>
                        <input type="password" name="confirm-password" placeholder="Confirm Password" class="form-control">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Unit Kerja:</strong>
                        <div class="select2-primary">
                            <!-- <select name="roles[]" class="select2" multiple="multiple" id='role' data-dropdown-css-class="select2-purple" style="width: 100%;"> -->
                            <select class="select2" style="width: 100%" id="unit" name="unit" data-placeholder="Choose one Unit Kerja">
                                @foreach ($units as $value => $label)
                                <option value="{{ $value }}" {{ isset($userUnit[$value]) ? 'selected' : ''}}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Role:</strong>
                        <div class="select2-primary">
                            <!-- <select name="roles[]" class="select2" multiple="multiple" id='role' data-dropdown-css-class="select2-purple" style="width: 100%;"> -->
                            <select name="roles" class="select2" id='role' data-dropdown-css-class="select2-purple" style="width: 100%;">
                                @foreach ($roles as $value => $label)
                                <option value="{{ $value }}" {{ isset($userRole[$value]) ? 'selected' : ''}}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-primary btn-sm mt-2 mb-3"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
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

        $('form').submit(function(e) {
            $(':disabled').each(function(e) {
                $(this).removeAttr('disabled');
            })
        });
    });
</script>

@endsection