@extends ('admin.app')

@section('content')

<div class="container-fluid">
    <div class="card card-default" data-select2-id="31">

        <div class="card-body gradient-buttons">
            <div class="row" data-select2-id="53" style="width: 100%;">

                <!-- <div class="col-md-2">
                    <span class="btn btn-grad text-white btnoffline" style="border-radius: 16px;">Playback Mode &nbsp; <i class="fas fa-podcast"></i></span> -->
                <!-- <input class="btn btn-grad text-white btnoffline" id="upload" type="file" accept="audio/*" style="border-radius: 16px;"> -->
                <!-- </div>
                <div class="col-md-2" style="margin-left: -30px;">
                    <button class="btn btn-grad-red text-white btnlive" id="live">Play live stream &nbsp; <i class="fab fa-creative-commons-sampling-plus"></i></button>
                </div> -->
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Parameter Playlist</h3>
                        </div>
                        <div class="card-body">
                            <!-- <input type="checkbox" name="my-checkbox" checked data-bootstrap-switch> -->
                            <!-- <input type="checkbox" name="my-checkbox" id="my-checkbox" checked data-bootstrap-switch data-off-color="danger" data-on-color="success" data-on-text="Playback"
                                data-off-text="Live Streaming" disabled>
                        </div> -->
                            <div class="row" data-select2-id="53">
                                <div class="col-md-3" data-select2-id="43">
                                    <div class="form-group" data-select2-id="42">
                                        <select class="select2" style="width: 100%" id="unit" name="unit" data-placeholder="Choose one thing">
                                            <?php echo $unit  ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4" data-select2-id="30">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <!-- <input type="text" class="form-control float-right" id="reservation"> -->
                                        <input type="text" class="form-control float-right" id="reservationtime">
                                    </div>
                                </div>

                                <!-- <div class="col-md-3" data-select2-id="30">
                                    <div class="form-group">
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input reservationdate" data-target="#reservationdate" data-placeholder="Choose one thing">
                                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="col-md-5" data-select2-id="30">
                                    <div class="form-group" data-select2-id="29">
                                        <select class="select2" style="width:100%;" id="sound" name="sound" data-placeholder="Choose one thing">
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="col-md-1" data-select2-id="30"> <button class="btn bg-success btnplay" id="btnplay">
                                    <i class="fas fa-play"></i> Play
                                </button></div>
                            <div class="col-md-1" data-select2-id="30" style="margin-left: -25px;">
                                <button class="btn bg-danger btnstop" id="btnstop">
                                    <i class="fas fa-stop"></i> Stop
                                </button>
                            </div> -->
                            </div>
                        </div>
                    </div>
                </div>


            </div>



            <div class="row" data-select2-id="53">
                <div class="col-md-12">
                    <!-- analyzer container -->
                    <div id="container"></div>
                    <!-- audio element -->

                </div>
            </div>

            <div class="row" style="width: 100%; margin:10px">

                <div class="col-md-10" align="left">
                    <audio id="audio" controls crossorigin style="width:100%" controls controlsList="nodownload"></audio>
                    </a>
                </div>
                @can('role-edit')
                <div class="col-md-2" align="right">

                    <button class="btn btn-pill btn-success btnDownload" id="btnDownload" style="border-radius: 16px;"><i class="fas fa-cloud-download-alt"></i> &nbsp; Download &nbsp; </button>
                </div>
                @endcan

                <div class="info" hidden><a href="#"></a> <span id="version"></span></div>
            </div>

            <!-- subtitle (SRT dari Whisper) -->
            <div class="row" style="width: 100%; margin:10px" id="subtitleWrap" hidden>
                <div class="col-md-12">
                    <div id="subtitle" class="subtitle-line"></div>
                    <details class="mt-2">
                        <summary>Transkrip lengkap</summary>
                        <div id="subtitleList" class="subtitle-list"></div>
                    </details>
                </div>
            </div>

            <div class="row" data-select2-id="53" id="tag">
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td colspan="3">TRACK Detail Information :</td>
                            </tr>
                            <tr>
                                <td style="width:250px;">Server Unit Kerja</td>
                                <td style="width:2px;">:</td>
                                <td id="ip"></td>
                            </tr>
                            <tr>
                                <td>Chanel</td>
                                <td>:</td>
                                <td id="chanel"></td>
                            </tr>
                            <tr>
                                <td>File Name</td>
                                <td>:</td>
                                <td id="file"></td>
                            </tr>
                            <tr>
                                <td>Durasi</td>
                                <td>:</td>
                                <td id="date"></td>
                            </tr>
                            <tr>
                                <td>Sound Card Name</td>
                                <td>:</td>
                                <td id="soundcard"></td>
                            </tr>
                            <tr>
                                <td>File Size</td>
                                <td>:</td>
                                <td id="size"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://unpkg.com/audiomotion-analyzer/dist"></script>
<script>
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    var unitId = "{{$unitId}}";
    var _startd;
    var _endd;
    // display module version
    document.getElementById('version').innerText = `${AudioMotionAnalyzer.version}`;

    $(document).ready(function() {
        // document.querySelectorAll('.wp-block-audio audio').forEach(audio => audio.setAttribute('controlsList', 'nodownload'));
        var dateNow = new Date();
        $('.select2').select2({
            placeholder: "Select a state",
            allowClear: true,
            theme: 'bootstrap4'
        });

        //Date and time picker
        //Date picker
        $('#reservationdate').datetimepicker({
            // $('#reservationdate').datepicker({
            // format: 'L'
            format: 'DD-MMMM-YYYY',
            defaultDate: dateNow
        });

        $('#reservationtime').daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            timePickerIncrement: 5,
            locale: {
                format: 'MM/DD/YYYY HH:mm'
            }
        })

        let element = document.getElementById("tag");
        let hidden = element.getAttribute("hidden");

        $('.btnplay').addClass('btn-disabled');
        $('#btnplay').prop('disabled', true);
        $('#btnstop').prop('disabled', true);
        $('#btnDownload').prop('disabled', true);
        // $('#btnstop').prop('disabled', true); 

        $("input[data-bootstrap-switch]").each(function() {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        })

        $('#my-checkbox').on('switchChange.bootstrapSwitch', function(event, state) {
            if (state) {
                toastr.success('Playback Mode');
                disabled(false);
                audioEl.src = "https://c5.siar.us/proxy/ssfm/stream";
                stopAudio(audioEl);
            } else {
                toastr.error('Live Streaming Mode');
                disabled(true);
                // audioEl.src = "{{ asset('assets/sound_001.mp3') }}";
                // $('#btnplay').prop('disabled', true);
                // $('#btnstop').prop('disabled', true);
                // $('#live').attr('disabled', 'disabled');

                audioEl.src = "https://c5.siar.us/proxy/ssfm/stream";
                audioEl.play();
            }

            // console.log(state);
        });

        //Date range picker
        $('#reservation').daterangepicker({
            locale: {
                format: 'DD-MMMM-YYYY'
            },
            defaultDate: dateNow
        })


        $('#reservation').on('apply.daterangepicker', function(ev, picker) {
            _startd = $('#reservation').data('daterangepicker').startDate;
            _endd = $('#reservation').data('daterangepicker').endDate;
            let pickedDate = $("#reservationdate").find("input").val();
            let datex = new Date(pickedDate);
            let _id = $("#unit").val();

            param = {
                id: _id,
                tgl: datex.toLocaleDateString('en-GB'), //pickedDate,
                startd: _startd.format('YYYYMMDD'),
                endd: _endd.format('YYYYMMDD'),
            };
            setSoundListValue(param);
        });

        $('#reservationtime').on('apply.daterangepicker', function(ev, picker) {
            _startd = $('#reservationtime').data('daterangepicker').startDate;
            _endd = $('#reservationtime').data('daterangepicker').endDate;
            let pickedDate = $("#reservationtime").find("input").val();
            let datex = new Date(pickedDate);
            let _id = $("#unit").val();

        

            param = {
                id: _id,
                tgl: datex.toLocaleDateString('en-GB'), //pickedDate,
                startd: _startd.format('YYYYMMDDHHmm'),
                endd: _endd.format('YYYYMMDDHHmm'),
            };
            setSoundListValue(param);
        });


        $("#unit").on('change', async function(e) {
            _startd = $('#reservationtime').data('daterangepicker').startDate;
            _endd = $('#reservationtime').data('daterangepicker').endDate;
            let pickedDate = $("#reservationdate").find("input").val();
            let datex = new Date(pickedDate);
            let _id = $("#unit").val();

            param = {
                id: _id,
                tgl: datex.toLocaleDateString('en-GB'), //pickedDate,
                startd: _startd.format('YYYYMMDDHHmm'),
                endd: _endd.format('YYYYMMDDHHmm'),
            };
            setSoundListValue(param);

        });

        $("#sound").on('change', async function(e) {
            // _startd = $('#reservation').data('daterangepicker').startDate;
            // _endd = $('#reservation').data('daterangepicker').endDate;
            _startd = $('#reservationtime').data('daterangepicker').startDate;
            _endd = $('#reservationtime').data('daterangepicker').endDate;

            let pickedDate = $("#reservationdate").find("input").val();
            let _id = $("#unit").val();
            let _sound = $("#sound").val();
            let datasound;


            if (_sound.length == '0') {
                toastr.error('File Not Found!');
            } else {
                // audioEl.src = route('playSoundLokasi', {
                //     id: _sound
                // });
                try {
                    datasound = await getSoundData(_sound);
                    // console.log('datasound: '+datasound);
                } catch (error) {
                    toastr.error(error);
                }
                audioEl.src = "data:audio/mp3;base64," + datasound;

                $.get("gettag", {
                    id: _sound
                }, function(data) {
                    // $(".result").html(data);
                    // console.log(data[0]);
                    // console.log(data[0].key_id_unit_kerja);
                    element.removeAttribute("hidden");
                    $('#ip').html(data[0].nm_unit_kerja + " - " + data[0].nm_server + " - " + data[0].ip_server);
                    $('#file').html(data[0].nm_file_sound);
                    $('#date').html(data[0].start_time + " s/d " + data[0].end_time + ' ' + data[0].timezone);
                    $('#chanel').html(data[0].chanel);
                    $('#soundcard').html(data[0].nm_sound_card);
                    $('#size').html(data[0].ukuran + " Mbytes");
                    loadSubtitle(data[0].transkrip_text);

                });

                // console.log(tag);

                stopAudio(audioEl);
                // // audioEl.src = "https://c5.siar.us/proxy/ssfm/stream";
                $('#live').attr('disabled', 'disabled');
                $('#btnplay').prop('disabled', true);
                $('#btnstop').prop('disabled', false);
                // $('#btnstop').prop('disabled', false);


                audioEl.play();
                Toast.fire({
                    icon: 'success',
                    title: "Wait Playing Sound "
                });
            }
        });

        // $("#reservationdate").on("change.datetimepicker", ({
        $("#reservationdate").on('change.datetimepicker', function(event) {
            let pickedDate = $("#reservationdate").find("input").val();
            let _id = $("#unit").val();
            let datex = new Date(pickedDate);
            // console.log(datex.toLocaleDateString('en-GB'));
            param = {
                id: _id,
                tgl: datex.toLocaleDateString('en-GB'), //pickedDate,
            };
            setSoundListValue(param);
        })

        // play stream
        $('.btnlive').click(function() {
            // audioEl.src = "{{ asset('assets/sound_001.mp3') }}";
            $('#btnplay').prop('disabled', true);
            $('#btnstop').prop('disabled', true);

            audioEl.src = "https://c5.siar.us/proxy/ssfm/stream";
            $('#live').attr('disabled', 'disabled');
            loadSubtitle(null);
            audioEl.play();
        });

        $('.btnoffline').click(function() {
            $('#btnplay').prop('disabled', false);
        });

        $('.btnplay').click(function() {
            //do something
            let pickedDate = $("#reservationdate").find("input").val();
            let _id = $("#unit").val();
            let _sound = $("#sound").val();

            if (_sound.length == '0') {
                toastr.error('File Not Found!');
            } else {
                audioEl.src = route('playSoundLokasi', {
                    id: _sound
                });
                // // audioEl.src = "https://c5.siar.us/proxy/ssfm/stream";
                $('#live').attr('disabled', 'disabled');
                $('#btnplay').prop('disabled', true);
                $('#btnstop').prop('disabled', false);
                // $('#btnstop').prop('disabled', false);
                audioEl.play();
                Toast.fire({
                    icon: 'success',
                    title: "Wait Playing Sound "
                });
            }

        });

        $('.btnstop').click(function() {
            //do something
            //do something
            let pickedDate = $("#reservationdate").find("input").val();
            let _id = $("#unit").val();
            let _sound = $("#sound").val();

            audioEl.src = route('playSoundLokasi', {
                id: _sound
            });
            // // audioEl.src = "https://c5.siar.us/proxy/ssfm/stream";
            $('#live').attr('disabled', null);
            $('#btnplay').prop('disabled', false);
            $('#btnstop').prop('disabled', true);
            stopAudio(audioEl);
            Toast.fire({
                icon: 'success',
                title: "Wait Stoping Sound "
            });
        });

        // $('.btnDownload').click(function() {
        //     Toast.fire({
        //         icon: 'success',
        //         title: "Wait Stoping Sound "
        //     });
        // });


        $('.btnDownload').click(async function() {
            console.log('downloading....')
            let _sound = $("#sound").val();
            let filename = document.getElementById("file");
            param = {
                id: _sound,
            };

            try {
                datasound = await getSoundData(_sound);
            } catch (error) {
                toastr.error(error);
            }

            var a = document.createElement("a"); //Create <a>
            a.href = "data:audio/mp3;base64," + datasound; //Image Base64 Goes here
            a.download = $('#file').text(); //File name Here
            a.click();

            // $.ajax({
            //     url: "download",
            //     type: 'GET',
            //     data: param,
            //     success: function(res) {
            //         console.log('---------------->' + res);
            //         // if (res.status == 'error') {
            //         //     toastr.error('File Not Found!');
            //         // } else {
            //         //     var a = document.createElement("a"); //Create <a>
            //         //     a.href = "data:audio/mp3;base64," + datasound; //Image Base64 Goes here
            //         //     a.download = "sound.mp3"; //File name Here
            //         //     a.click();
            //         // }
            //     },
            //     error: function(jqXHR, textStatus, errorThrown) {
            //         Swal.fire(errorThrown, textStatus, "error");
            //     },
            // });
        });

        $('#unit').val(unitId).trigger("change")
    });

    let timeElapsed = 0;

    function setSoundListValue(param) {
        $.ajax({
            url: "{{route('getSoundfile')}}",
            type: "get",
            data: param,
            success: function(res) {

                $('#sound').empty();
                $('#sound').find('option').not(':first').remove();
                $('#sound').append('<option value=""></option>');
                $.each(res, function(index, data) {
                    $('#sound').append('<option value="' + data['key_id'] + '">' + data['nm_file_sound'] + ' ( ' + data['start_time'] + '- ' + data['end_time'] + ' )</option>');
                });
                // console.log(res[0].key_id)
                if (res[0].key_id !== '0') {
                    $('#btnplay').prop('disabled', false);
                    $('#btnDownload').prop('disabled', false);
                    $('#btnstop').prop('disabled', true);
                } else {
                    $('#btnplay').prop('disabled', true);
                    $('#btnstop').prop('disabled', true);
                    $('#btnDownload').prop('disabled', true);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire(errorThrown, textStatus, "error");
            },
        });
    }

    function stopAudio(audio) {
        audio.pause();
        audio.currentTime = 0;
    }

    // callback function
    function rotateGradient(analyzer, info) {
        if (info.timestamp - timeElapsed > 100) {
            timeElapsed = info.timestamp;
            const colorStops = [],
                offset = timeElapsed / 10 % 360;

            for (let i = 360; i >= 0; i -= 60)
                colorStops.push(`hsl( ${ offset + i }, 100%, 50% )`);

            // re-register gradient
            analyzer.registerGradient('rainbow', {
                dir: 'h',
                colorStops
            });
        }
    }

    function disabled(state) {
        if (state) {
            console.log(1);
            // $('#reservationdate').prop('disabled', true);
            $("#reservationdate").find("input").prop('disabled', true);
            $("#sound").attr('disabled', true);
        } else {
            console.log(2);
            // $('#reservationdate').prop('disabled', false);
            $("#reservationdate").find("input").prop('disabled', false);
            $("#sound").removeAttr('disabled');
        }
    }

    // audio source
    const audioEl = document.getElementById('audio');

    // instantiate analyzer
    const audioMotion = new AudioMotionAnalyzer(
        document.getElementById('container'), {
            source: audioEl,
            height: window.innerHeight - 50,
            mode: 3,
            gradient: 'rainbow',
            barSpace: .1,
            onCanvasDraw: rotateGradient
        }
    );

    // subtitle SRT (trn_file_sound.transkrip_text)
    let subtitleCues = [];
    let activeCueIndex = -1;

    function srtTimeToSeconds(t) {
        const m = t.trim().match(/(\d+):(\d+):(\d+)[,.](\d+)/);
        return m ? (+m[1]) * 3600 + (+m[2]) * 60 + (+m[3]) + (+m[4]) / 1000 : 0;
    }

    function parseSrt(srt) {
        return srt.replace(/\r/g, '').trim().split(/\n\s*\n/).map(function(block) {
            const lines = block.split('\n');
            const timeIdx = lines.findIndex(l => l.includes('-->'));
            if (timeIdx < 0) return null;
            const [start, end] = lines[timeIdx].split('-->');
            return {
                start: srtTimeToSeconds(start),
                end: srtTimeToSeconds(end),
                text: lines.slice(timeIdx + 1).join(' ').trim()
            };
        }).filter(c => c && c.text);
    }

    function formatCueTime(sec) {
        const m = Math.floor(sec / 60);
        const s = Math.floor(sec % 60);
        return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
    }

    function loadSubtitle(srt) {
        subtitleCues = srt ? parseSrt(srt) : [];
        activeCueIndex = -1;
        $('#subtitle').text('');
        $('#subtitleList').empty();
        $('#subtitleWrap').prop('hidden', subtitleCues.length === 0);

        subtitleCues.forEach(function(cue, i) {
            const row = $('<div class="cue"></div>').attr('data-index', i);
            row.append($('<span class="cue-time"></span>').text(formatCueTime(cue.start)));
            row.append($('<span></span>').text(cue.text));
            row.on('click', function() {
                audioEl.currentTime = cue.start;
                audioEl.play();
            });
            $('#subtitleList').append(row);
        });
    }

    audioEl.addEventListener('timeupdate', function() {
        if (subtitleCues.length === 0) return;
        const t = audioEl.currentTime;
        const idx = subtitleCues.findIndex(c => t >= c.start && t < c.end);
        if (idx === activeCueIndex) return;

        activeCueIndex = idx;
        $('#subtitle').text(idx >= 0 ? subtitleCues[idx].text : '');
        $('#subtitleList .cue').removeClass('active');
        if (idx >= 0) {
            const row = $('#subtitleList .cue[data-index="' + idx + '"]').addClass('active')[0];
            row.parentNode.scrollTop = row.offsetTop - row.parentNode.offsetTop - 80;
        }
    });

    function getSoundData(_sound) {
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: "{{route('playSoundData')}}",
                type: 'GET',
                data: {
                    id: _sound
                },
                success: function(res) {
                    if (!res.success) {
                        // toastr.error('File Not Found!');
                        reject('File Not Found!');
                    } else {
                        // window.location = route('download', {
                        //     id: _id
                        // });
                        // console.log(res);
                        // datasound = res.data;
                        resolve(res.data);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Swal.fire(errorThrown, textStatus, "error");
                    reject(jqXHR);
                },
            });
        });
    }
</script>

<style>
    audio {
        display: inline-block;
        height: 40px;
        width: calc(100% - 650px);
    }

    .content-wrapper {
        min-height: 1026.4px;
    }

    .subtitle-line {
        min-height: 56px;
        padding: 12px 16px;
        border-radius: 16px;
        background-color: rgba(0, 0, 0, .75);
        color: #fff;
        font-size: 1.25rem;
        text-align: center;
    }

    .subtitle-list {
        max-height: 240px;
        overflow-y: auto;
        margin-top: 8px;
    }

    .subtitle-list .cue {
        padding: 4px 8px;
        border-radius: 8px;
        cursor: pointer;
    }

    .subtitle-list .cue:hover {
        background-color: #f1f1f1;
    }

    .subtitle-list .cue.active {
        background-color: #ffe08a;
    }

    .subtitle-list .cue-time {
        color: #888;
        font-family: monospace;
        margin-right: 8px;
    }


    label input {
        display: none;
    }

    canvas {
        display: block;
        width: 100%;
        height: 300px;
        border-radius: 16px;
        background-color: #fff;
    }

    /* .info {
        display: inline-block;
        float: right;
        margin: 15px 20px 0;
    } */

    .btn-grad {
        background-image: linear-gradient(to right, #7b4397 0%, #dc2430 51%, #7b4397 100%);
        border-radius: 16px;
        height: 40px;
        display: block;
        align-items: center;
        margin: 10px;
    }

    .btn-grad:hover {
        background-position: right center;
        /* change the direction of the change here */
        color: #fff;
        text-decoration: none;
    }


    .btn-grad-red {
        background-image: linear-gradient(to right, #e52d27 0%, #b31217 51%, #e52d27 100%);
        border-radius: 16px;
        height: 40px;
        margin: 10px;
        display: block;
    }

    .btn-grad-red:hover {
        background-position: right center;
        /* change the direction of the change here */
        color: #fff;
        text-decoration: none;
    }
</style>
@endsection