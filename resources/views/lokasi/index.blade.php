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
        <a class="btn btn-success btn-sm mb-2" href="{{ route('lokasi.create') }}"><i class="fa fa-plus"></i>&nbsp; New Lokasi File</a>
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
                                <th>Lokasi File </th>
                                <th>File size </th>
                                <th>Total Storage </th>
                                <th>Usage </th>
                                <th width="280px">Status</th>
                            </tr>
                        </thead>

                        @foreach ($lokasi as $key => $item)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $item->lokasi_file }}</td>
                            <td>{{ $files[$key]['file_sizes'] }} MB</td>
                            <td>{{ $files[$key]['total_sizes'] }} MB</td>
                            <td>
                                <div class="progress mb-3">
                                    <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="{{ $files[$key]['file_sizes_ori'] }}" aria-valuemin="0" aria-valuemax="{{ $files[$key]['total_sizes_ori'] }}" style="width: {{ $files[$key]['prosen'] }}">
                                        <span class="sr-only">{{ $files[$key]['prosen'] }} Complete</span>
                                    </div>
                                </div>
                            </td>
                            <td class="{{ $item->flag_aktif == '1' ? 'bg-success': 'bg-danger'}}"><span>{{ $item->flag_aktif == '1' ? 'AKTIF': 'NON AKTIF'}}</span></td>
                            <!-- <td> 
                                @can('role-edit') 
                                <a class="btn btn-primary btn-sm" href="{{ route('interval.edit',$item->key_id) }}"><i class="fas fa-pen"></i>&nbsp; Edit</a>
                                @endcan
                            </td> -->
                        </tr>
                        @endforeach
                    </table>

                    {!! $lokasi->links('pagination::bootstrap-5') !!}
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