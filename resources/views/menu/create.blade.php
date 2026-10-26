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
    <div class="card-header">
        <a class="btn btn-primary mb-2" href="{{ route('roles.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
    </div>

    <form method="POST" action="{{ route('roles.store') }}" class="swa-confirm" data-flag="0" id="some-form">
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                @csrf
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Name:</strong>
                            <input type="text" name="name" placeholder="Name" class="form-control">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Permission:</strong>
                            <br />
                            @foreach($permission as $value)
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input custom-control-input-danger" type="checkbox" id="permission[{{$value->id}}]" name="permission[{{$value->id}}]" value="{{$value->id}}">
                                <label for="permission[{{$value->id}}]" class="custom-control-label">{{ $value->name }}</label>
                            </div>
                            <!-- <label><input type="checkbox" name="permission[{{$value->id}}]" value="{{$value->id}}" class="name" id="customCheckbox4">
                                {{ $value->name }}</label>
                            <br /> -->
                            @endforeach
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

    // setTimeout(function() {
    //     $myForm[0].submit()
    // }, 2000); // submit the DOM form
    // $(".swa-confirm").on("click", function(e) {

    // });

    $(document).ready(function() {
        //console.log("Ready!");
    });
</script>

@endsection