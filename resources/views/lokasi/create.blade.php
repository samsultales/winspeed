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
        <a class="btn btn-danger mb-2" href="{{ route('lokasi.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
    </div>

    <form method="POST" action="{{ route('lokasi.store') }}" class="swa-confirm" data-flag="0" id="some-form">
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                @csrf
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Lokasi Sound File:</strong>
                            <input type="text" name="lokasi_file" placeholder="/folder/subfolders (optional)" class="form-control">
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

    function setRegionalListValue(param) {
        $.ajax({
            url: "{{route('getRegionalList')}}",
            type: "get",
            data: param,
            success: function(res) {

                $('#kd_regional').empty();
                $('#kd_regional').find('option').not(':first').remove();
                $('#kd_regional').append('<option value=""></option>');
                $.each(res, function(index, data) {
                    $('#kd_regional').append('<option value="' + data['kd_regional'] + '">' + data['regional'] + '</option>');
                });

            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire(errorThrown, textStatus, "error");
            },
        });
    }

    $(document).ready(function() {
        //console.log("Ready!");

        $('.select2').select2({
            placeholder: "Please Choose One",
            theme: 'bootstrap4'
        });
        $('.multi-select2').select2({
            placeholder: "Select a role",
        });

        $("#kd_wilayah").on('change', async function(e) {
            console.log('-----> oke');

            let _id = $("#kd_wilayah").val();
            param = {
                id: _id,
            };

            setRegionalListValue(param);

            // if (_sound.length == '0') {
            //     toastr.error('File Not Found!');
            // } else {
            //     audioEl.src = route('playSoundLokasi', {
            //         id: _sound
            //     });

            //     stopAudio(audioEl);
            //     // // audioEl.src = "https://c5.siar.us/proxy/ssfm/stream";
            //     $('#live').attr('disabled', 'disabled');
            //     $('#btnplay').prop('disabled', true);
            //     $('#btnstop').prop('disabled', false);
            //     // $('#btnstop').prop('disabled', false);
            //     audioEl.play();
            //     Toast.fire({
            //         icon: 'success',
            //         title: "Wait Playing Sound "
            //     });
            // }
        });
    });
</script>

@endsection