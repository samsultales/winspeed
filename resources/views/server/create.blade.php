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
        <a class="btn btn-danger mb-2" href="{{ route('server.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
    </div>

    <form method="POST" action="{{ route('server.store') }}" class="swa-confirm" data-flag="0" id="some-form">
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                @csrf
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Unit Kerja:</strong>
                            <select name="key_id_unit_kerja" class="select2" id='key_id_unit_kerja' data-dropdown-css-class="select2-purple" style="width: 100%;">
                                <option value="0">Pilih Unit Kerja</option>
                                @foreach ($units as $value => $label)
                                <option value="{{ $value }}">
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>IP Server :</strong>
                            <input type="text"  name="ip_server"  class="form-control" data-inputmask="'alias': 'ip'" data-mask="" inputmode="decimal">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Nama Server:</strong>
                            <input type="text" name="nm_server" placeholder="Nama Server" class="form-control">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>User Local:</strong>
                            <input type="text" name="user_local" placeholder="User" class="form-control">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Password Local</strong>
                            <input type="text" name="password_local" placeholder="Password" class="form-control">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Email Lokal:</strong>
                            <input type="email" name="email_local" placeholder="Email Lokal" class="form-control">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Seq Server:</strong>
                            <input type="number" name="seq_server" placeholder="Sequence" class="form-control" value=1>
                        </div>
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
    let timerInterval;
    const $myForm = $('#some-form').on('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: "Saving Data",
            html: "Please wait",
            timer: 2000,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
                const timer = Swal.getPopup().querySelector("b");
                timerInterval = setInterval(() => {
                    timer.textContent = `${Swal.getTimerLeft()}`;
                }, 100);
            },
            willClose: () => {
                clearInterval(timerInterval);
            }
        }).then((result) => {
            // setTimeout(function() {
            $myForm[0].submit()
            // }, 2000); // submit the DOM form
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
                console.log("I was closed by the timer");
            }
        });
    });

    $(document).ready(function() {
        //console.log("Ready!");

        $('[data-mask]').inputmask()

        $('.select2').select2({
            placeholder: "Please Choose One",
            theme: 'bootstrap4'
        });
        $('.multi-select2').select2({
            placeholder: "Select a role",
        });
    });
</script>

@endsection