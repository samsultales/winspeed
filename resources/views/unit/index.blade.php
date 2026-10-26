@extends ('admin.app')

@section('content')

@session('success')
<div class="alert alert-success" role="alert">
    {{ $value }}
</div>
@endsession

<div class="card">
    <div class="card-header bg-primary">
        @can('role-create')
        <a class="btn btn-success btn-sm mb-2" href="{{ route('units.create') }}"><i class="fa fa-plus"></i>&nbsp; Add Unit Kerja</a>
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
                    <table id="example1" class="table table-bordered table-striped">
                        <thead class="bg-secondary">
                            <tr>
                                <th width="100px">No</th>
                                <th>Name</th>
                                <th width="280px">Action</th>
                            </tr>
                        </thead>

                        @foreach ($units as $key => $unit)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $unit->nm_unit_kerja }}</td>
                            <td>
                                <!-- <button class="btn btn-warning btn-sm" onclick="showModal()"><i class="fas fa-list"></i>&nbsp; Menu</button> -->
                                @can('role-edit')
                                <!-- <button class="btn btn-sm btn-success btn-menu-role" title="Ubah Unit Kerja" data-id="{{$unit->key_id}}"><i class="fa fa-cog"></i></button> -->
                                <a class="btn btn-primary btn-sm" href="{{ route('units.edit',$unit->key_id) }}"><i class="fas fa-pen"></i>&nbsp; Edit</a>
                                @endcan

                                @can('role-delete')
                                <form method="POST" action="{{ route('units.destroy', $unit->key_id) }}" style="display:inline" data-flag="0">
                                    @csrf
                                    @method('DELETE')
                                    <button id="btn-submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>&nbsp; Delete</button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </table>

                    
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

    // function submitForm(e) {
    //     e.preventDefault();
    //     swal.fire({
    //             title: "Are you sure?",
    //             text: "You won't be able to revert this!",
    //             icon: "warning",
    //             showCancelButton: true,
    //             confirmButtonColor: "#3085d6",
    //             cancelButtonColor: "#d33",
    //             confirmButtonText: "Yes, delete it!"
    //         }).then((result) => {
    //             if (result.isConfirmed) {
    //                 document.myForm.submit();
    //             } else if (result.dismiss == 'cancel') {
    //                 console.log('cancel');
    //                 return false;
    //             }
    //         });
    // }

    $('#btn-submit').on('click', function(e) {
        e.preventDefault();
        var form = $(this).parents('form');
        console.log(form)
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
                form.submit();
            } else if (result.dismiss == 'cancel') {
                console.log('cancel');
                return false;
            }
        });
    });

    $(document).ready(function() {
        //console.log("Ready!");
        $('#example1').DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    });
    });
</script>
@endsection