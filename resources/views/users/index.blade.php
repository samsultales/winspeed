@extends ('admin.app')

@section('content')

@session('success')
<div class="alert alert-success" role="alert">
    {{ $value }}
</div>
@endsession

<div class="card">
    <div class="card-header">
        <a class="btn btn-success mb-2" href="{{ route('users.create') }}"><i class="fa fa-plus"></i> Create New User</a>
    </div>

    <div class="card-body">
        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="dt-buttons btn-group flex-wrap">

                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div id="example1_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="example1"></label></div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-bordered">
                        <tr>
                            <th>No</th>
                            <th>UserName</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Unit Kerja</th>
                            <th width="280px">Action</th>
                        </tr>
                        @foreach ($data as $key => $user)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if(!empty($user->getRoleNames()))
                                @foreach($user->getRoleNames() as $v)
                                <label class="badge bg-success">{{ $v }}</label>
                                @endforeach
                                @endif
                            </td>
                            <td><label class="badge bg-info">{{ $user->nm_unit_kerja }}</label> </td>
                            <td>
                                @if(!empty($user->getRoleNames()))
                                @foreach($user->getRoleNames() as $v)
                                <a class="btn btn-info btn-sm" href="{{ route('users.show',$user->id) }}"><i class="fas fa-key"></i>&nbsp;Ubah</a>
                                <a class="btn btn-primary btn-sm <?php if (in_array($v, ['Administrator', 'Installer'])) { ?> disabled <?php   } ?>" href="{{ route('users.edit',$user->id) }}"><i class="fas fa-pen"></i> Edit</a>
                                <!-- <a class="btn btn-danger" onclick="return ConfirmDelete(event);" href="{{route('users.destroy', $user->id)}}"><i class="fa fa-trash"></i></a> -->
                                <!-- <form method="POST" id="formDelete" action="<?php if (in_array($v, ['Administrator', 'Installer'])) { ?>#<?php   } else {  ?> {{route('users.destroy', $user->id)}} <?php } ?>" style="display:inline"> -->
                                <form method="POST" id="formDelete" class="swa-confirm" action="{{route('users.destroy', $user->id)}}" style="display:inline" data-flag="0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" id="btn-submit" class="btn btn-danger btn-sm" <?php if (in_array($v, ['Administrator', 'Installer'])) { ?> disabled <?php   } ?>><i class="fas fa-trash"></i> Delete</button>
                                </form>
                                @endforeach
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </table>

                    {!! $data->links('pagination::bootstrap-5') !!}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('script')


<script>
    $(".swa-confirm").on("click", function(e) {
        $flag = $(this).attr('data-flag');

        if ($flag == 0) {
            e.preventDefault(); //to prevent submitting

            swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // if confirmed change the data-flag to 1 (as true), to submit
                    $('.swa-confirm').attr('data-flag', '1');
                    $('.swa-confirm').submit();
                } else if (result.dismiss == 'cancel') {
                    console.log('cancel');
                    return false;
                }
            });
        }

        return true;
    });

    @if(Session::has('notification')) //this line works as expected
    var type = "{{ Session::get('notification')['alert_type'], 'info' }}";
    switch (type) {
        case 'info':
            toastr.info("{{ Session::get('notification')['message'] }}");
            break;

        case 'warning':
            toastr.warning("{{ Session::get('notification')['message'] }}");
            break;

        case 'success':
            toastr.success("{{ Session::get('notification')['message'] }}");
            break;

        case 'error':
            toastr.error("{{ Session::get('notification')['message'] }}");
            break;
    }
    @endif
</script>

<style>

</style>
@endsection