@extends ('admin.app')

@section('content')

@session('success')
<div class="alert alert-success" role="alert">
    {{ $value }}
</div>
@endsession

<div class="card">
    <div class="card-header">
        <a class="btn btn-primary mb-2" href="{{ route('dashboard.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
        <!-- <a class="btn btn-primary mb-2" href="{{ route('dashboard.index') }}"><i class="fa fa-arrow-left"></i> Mark All As Read</a> -->
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
                                <th>Url</th>
                                <th>Jenis</th>
                                <th>Pesan</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        @foreach ($notifs as $key => $unit)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $unit->interface }}</td>
                            <td>{{ $unit->judul }}</td>
                            <td>{{ $unit->pesan }}</td>
                            <td class="{{ $unit->flag == '1' ? 'bg-danger': 'bg-success'}}">{{ $unit->flag == '1' ? 'Unread': 'Read'}}</td>
                        </tr>
                        @endforeach
                    </table>

                    {!! $notifs->links('pagination::bootstrap-5') !!}
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
        let show = $('.btn-show' + id).attr("data-show");
        if (show == '0') {
            let vpass = $('#vpass' + id).val();
            $('.btn-show' + id).attr("data-show", "1");
            $('#password' + id).html(vpass);
            $('.btn-show' + id).html('<i class="fas fa-eye-slash"></i>');
        } else {
            let vpass = $('#vpass' + id).val();
            $('.btn-show' + id).attr("data-show", "0");
            $('#password' + id).html("*".repeat(vpass.length));
            $('.btn-show' + id).html('<i class="fas fa-eye"></i>');
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