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
    <form method="POST" action="{{ route('users.store') }}" onsubmit="this.querySelectorAll('input').forEach(i => i.disabled = false)">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        <input type="text" name="name" placeholder="Name" class="form-control" autocomplete="off">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Email:</strong>
                        <input type="email" name="email" placeholder="Email" class="form-control" autocomplete="off">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Username:</strong>
                        <input type="username" name="username" placeholder="Username " class="form-control" autocomplete="off">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Password:</strong>
                        <input type="password" name="password" placeholder="Password" class="form-control" autocomplete="off">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Confirm Password:</strong>
                        <input type="password" name="confirm-password" placeholder="Confirm Password" class="form-control" autocomplete="off">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Role:</strong>
                        <div class="select2-purple">
                            <!-- <select name="roles[]" class="multi-select2" multiple="multiple" id='role' data-dropdown-css-class="select2-purple" style="width: 100%;"> -->
                            <select name="roles" class="select2" id='role' data-dropdown-css-class="select2-purple" style="width: 100%;">

                                @foreach ($roles as $value => $label)
                                <option value="{{ $value }}">
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Unit Kerja:</strong>
                        <div name="id_unit_kerja" class="form-group" data-select2-id="29">
                            <select class="select2" style="width: 100%" id="unit" name="unit" data-placeholder="Choose one Unit Kerja">
                                <option value="0">Pilih Unit Kerja</option>
                                @foreach ($units as $value => $label)
                                <option value="{{ $value }}">
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
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
        $('.multi-select2').select2({
            placeholder: "Select a role",
        });

        $("#unit").val(0).trigger('change');
        $("#unit").attr('disabled', true);
        $("#unit").prop("readonly", true);

        $("#role").on('change', async function(e) {
            let _role = $("#role").val();
            console.log(_role);
            if (['Administrator', 'Kantor Pusat'].includes(_role)) {
                // $("#unit").attr('disabled', true);
                $("#unit").prop("readonly", true);
                $("#unit").prop("disabled", true);
                toastr.info('Unit Kerja tidak Wajib Diisi');
                $("#unit").val(0).trigger('change');
            } else {
                $("#unit").prop("readonly", false);
                $("#unit").prop("disabled", false);
                toastr.error('Unit Kerja Wajib Diisi');
                $("#unit").val(0).trigger('change');
            }
        });

        $('form').submit(function(e) {
            $(':disabled').each(function(e) {
                $(this).removeAttr('disabled');
            })
        });

        function validateForm() {
            let _role = $("#role").val();
            let x = document.forms["myForm"]["unit"].value;
            if (['Administrator', 'Kantor Pusat'].includes(_role)) {
                if (x == "") {
                    alert("Name must be filled out");
                    return false;
                }
            }
            console.log('Saving...s')
            $("#unit").prop("disabled", false);
            return false;

        }

    });
</script>

@endsection

<style>

</style>