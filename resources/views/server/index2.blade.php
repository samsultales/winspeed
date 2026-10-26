@extends ('admin.app')

@section('content')

@session('success')
<div class="alert alert-success" role="alert">
    {{ $value }}
</div>
@endsession

<div class="card">
    <div class="card-header">
        @can('role-create')
        <!-- <a class="btn btn-success btn-sm mb-2" href="{{ route('server.create') }}"><i class="fa fa-plus"></i>&nbsp; Add Server Unit Kerja</a> -->
        @endcan
    </div>

    <div class="card-body">
        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="dt-buttons btn-group flex-wrap">

                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <!-- <div id="example1_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="example1"></label></div> -->
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-secondary">
                            <tr>
                                <th width="100px">No</th>
                                <th>Name Unit Kerja</th>
                                <th>Name Server</th>
                                <th>Seq Server</th>
                                <th>IP Server</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th width="15px">Action</th>
                            </tr>
                        </thead>

                        @foreach ($units as $key => $unit)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $unit->nm_unit_kerja }}</td>
                            <td>{{ $unit->nm_server }}</td>
                            <td>{{ $unit->seq_server }}</td>
                            <td>{{ $unit->ip_server }}</td>
                            <td>{{ $unit->user_local }}</td>
                            <td id="password{{$i}}" type="password"><?= str_repeat("*", strlen($unit->password_local)); ?> </td>
                            <td>
                                <input type="hidden" id="vpass{{$i}}" name="vpass{{$i}}" value="{{$unit->password_local}}">
                                @can('role-edit')
                                <button class="btn btn-success btn-sm btn-show{{$i}}" onclick="showPass('{{$i}}')" data-show="0"><i class="fas fa-eye"></i></button>
                                <!-- <button class="btn btn-sm btn-success btn-menu-role" title="Ubah Unit Kerja" data-id="{{$unit->key_id}}"><i class="fa fa-cog"></i></button> -->
                                <!-- <a class="btn btn-primary btn-sm" href="{{ route('server.edit',$unit->key_id) }}"><i class="fas fa-pen"></i>&nbsp; Edit</a> -->
                                @endcan

                                @can('role-delete')
                                <form method="POST" id="deleteform" class="swa-confirm" action="{{ route('server.destroy', $unit->key_id) }}" style="display:inline" data-flag="0">
                                    @csrf
                                    @method('DELETE')
                                    <!-- <button onclick="javascript: deleteForm()" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>&nbsp; Delete</button> -->
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </table>

                    {!! $units->links('pagination::bootstrap-5') !!}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('script')
<script>
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

    function deleteForm() {
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
                document.myForm.submit();
            } else if (result.dismiss == 'cancel') {
                console.log('cancel');
                return false;
            }
        });
    }

    function showPass(id) {
        // let id = $('.btn-show'+id).attr("data-id");
        let show = $('.btn-show'+id).attr("data-show");
        if (show == '0') {
            let vpass = $('#vpass'+id).val();
            $('.btn-show'+id).attr("data-show", "1");
            $('#password'+id).html(vpass);
            $('.btn-show'+id).html('<i class="fas fa-eye-slash"></i>'); 
        } else {
            let vpass = $('#vpass'+id).val();
            $('.btn-show'+id).attr("data-show", "0");
            $('#password'+id).html("*".repeat(vpass.length));
            $('.btn-show'+id).html('<i class="fas fa-eye"></i>'); 
        }
    }

    const eventHandler = () => {
       
    }


    $(document).ready(function() {
        //console.log("Ready!");
        eventHandler();
    });
</script>
@endsection