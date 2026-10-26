<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Voice Recorder | Log in</title>
    <link rel="stylesheet" href="{{ asset('assets/AdminLTE-3.2.0/dist/css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-5">
                    <!-- <h2 class="heading-section">Login #05</h2> -->
                </div>
            </div>
            <div class="row justify-content-center" autocomplete="off">
                <div class="col-md-7 col-lg-5">
                    <div class="wrap" style="margin-top:-25%;">
                        <div class="img" style="background-image: url(assets/AdminLTE-3.2.0/dist/img/bg-1.jpg);"></div>
                        <div class="login-wrap p-4 p-md-5">
                            <div class="d-flex">
                                <div class="w-100">
                                    <h4 class="mb-4">APLIKASI VOICE RECORDER</h4>
                                </div>
                                <!-- <div class="w-100"> -->
                                <!-- <p class="social-media d-flex justify-content-end"> -->
                                <!-- <a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-facebook"></span></a> -->
                                <!-- <a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-twitter"></span></a> -->
                                <!-- </p> -->
                                <!-- </div> -->
                            </div>
                            <div class="form-group mt-3" >
                                <input id="username" type="text" class="form-control" required>
                                <label class="form-control-placeholder" for="username">Username or Email</label>
                            </div>
                            <div class="clear-both" style="margin-bottom:35px;"></div>
                            <div class="form-group">
                                <input id="password" type="password" class="form-control" required>
                                <label class="form-control-placeholder" for="password">Password</label>
                                <!-- <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span> -->
                            </div>

                            <div class="form-group">
                                <button class="form-control btn btn-primary rounded submit px-3 btn-login">Sign In</button>
                            </div>
                            <div class="form-group d-md-flex">
                                <div class="w-50 text-left">
                                    <label class="checkbox-wrap checkbox-primary mb-0">Remember Me
                                        <input type="checkbox" checked>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <!-- <div class="w-50 text-md-right">
                                        <a href="#">Forgot Password</a>
                                    </div> -->
                            </div>
                            <!-- <p class="text-center">Not a member? <a data-toggle="tab" href="#signup">Sign Up</a></p> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

<!-- jQuery -->
<script src="{{ asset('assets/AdminLTE-3.2.0/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/AdminLTE-3.2.0/dist/js/adminlte.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.all.min.js"></script>

<!-- include BlockUI -->
<script src="{{ asset('assets/jquery.blockUI.js') }}"></script>

<style>
    label.focused {
        top: -14px;
        font-size: 12px;
        color: #FF3B3F;
    }
</style>

<script>
    $(document).ready(function() {
        if ($('input:-webkit-autofill')) {
            $('#username').val(' ').val('');
            $('#password').val(' ').val('');
            $('#username').focus();  
        }

        $(".btn-login").click(function() {
            $(".btn-login").prop('disabled', true);
            // var email = $("#email").val();
            var email = $("#username").val();
            var password = $("#password").val();
            var token = $("meta[name='csrf-token']").attr("content");

            if (email.length == "") {
                $(".btn-login").prop('disabled', false);
                Swal.fire({
                    type: 'warning',
                    title: 'Oops...',
                    text: 'Alamat Email Wajib Diisi !'
                });

            } else if (password.length == "") {
                $(".btn-login").prop('disabled', false);
                Swal.fire({
                    type: 'warning',
                    title: 'Oops...',
                    text: 'Password Wajib Diisi !'
                });

            } else {
                $.blockUI({
                    css: {
                        border: 'none',
                        padding: '15px',
                        backgroundColor: '#000',
                        '-webkit-border-radius': '10px',
                        '-moz-border-radius': '10px',
                        opacity: .5,
                        color: '#fff',
                        fontSize: '16px'
                    },
                    message: 'Tunggu, Proses Login...'
                });
                $.ajax({

                    url: "/signin",
                    type: "POST",
                    dataType: "JSON",
                    cache: false,
                    data: {
                        "username": email,
                        "password": password,
                        "_token": token
                    },

                    success: function(response) {

                        if (response.success) {

                            Swal.fire({
                                    type: 'success',
                                    title: 'Login Berhasil!',
                                    text: 'Anda akan di arahkan dalam 3 Detik',
                                    timer: 3000,
                                    showCancelButton: false,
                                    showConfirmButton: false
                                })
                                .then(function() {
                                    $.unblockUI();
                                    window.location.href = "{{ route('dashboard.index') }}";
                                    $(".btn-login").prop('disabled', false);
                                });

                        } else {
                            $.unblockUI();
                            Swal.fire({
                                type: 'error',
                                title: 'Login Gagal!',
                                text: 'silahkan coba lagi!'
                            });

                        }
                    },

                    error: function(response) {
                        $.unblockUI();
                        $(".btn-login").prop('disabled', false);
                        Swal.fire({
                            type: 'error',
                            title: 'Opps!',
                            text: response.responseJSON['message']
                        });


                    }

                });

            }

        });

    });
</script>

</html>