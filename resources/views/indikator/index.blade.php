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
        <!-- <a class="btn btn-success btn-sm mb-2" href="{{ route('units.create') }}"><i class="fa fa-plus"></i>&nbsp; Add Unit Kerja</a> -->
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
                                <th>Waktu Indikator Kerusakan</th>
                                <th>Satuan</th>
                                <th width="280px">Action</th>
                            </tr>
                        </thead>

                        @foreach ($indikator as $key => $item)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $item->bts_rusak }}</td>
                            <td>{{ $item->satuan }}</td>
                            <td> 
                                @can('role-edit') 
                                <a class="btn btn-primary btn-sm" href="{{ route('indikator.edit',$item->key_id) }}"><i class="fas fa-pen"></i>&nbsp; Edit</a>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </table>

                    {!! $indikator->links('pagination::bootstrap-5') !!}
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

    function submitForm() {
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

    $(document).ready(function() {
        //console.log("Ready!");
    });
</script>
@endsection