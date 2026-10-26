@extends('admin.app')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <div class="pull-right">
            <a class="btn btn-warning mb-2" href="{{ route('roles.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>


    <form method="POST" action="{{ route('roles.update', $role->id) }}">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="exampleInputEmail1">Roles Name:</label>
                <input type="text" name="name" placeholder="Name" class="form-control" value="{{ $role->name }}" readonly>
            </div>
            <div class="form-group">
                <strong>Permission:</strong>
                <br />
                @foreach($permission as $value)
                <label><input type="checkbox" name="permission[{{$value->id}}]" value="{{$value->id}}" class="name" {{ in_array($value->id, $rolePermissions) ? 'checked' : ''}}>
                    {{ $value->name }}</label>
                <br />
                @endforeach
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
    </form>
</div>

@endsection