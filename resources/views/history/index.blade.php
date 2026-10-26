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
                <div class="col-sm-12 col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Filter</h3>
                        </div>
                        <div class="card-body">
                            <div class="row" data-select2-id="53">
                                <div class="col-md-6" data-select2-id="43">
                                    <div class="form-group" data-select2-id="42">
                                        <select class="select2" style="width: 100%" id="unit" name="unit" data-placeholder="Choose one thing">
                                            @if (in_array(Session::get('auth')['key_id_unit_kerja'], array("0", "1")) )
                                            <option value='0'> All </option>
                                            @endif
                                            <?php echo $unit  ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6" data-select2-id="30">
                                    <div class="form-group">
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input reservationdate" data-target="#reservationdate" data-placeholder="Choose one thing">
                                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">

                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table id="tbl_list" class="table table-striped table-bordered" cellspacing="0" width="100%"></table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var grid;

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

    const initTable = function(id) {
        let pickedDate = $("#reservationdate").find("input").val();
        let _id = $("#unit").val();
        console.log(_id)

        if (grid != null) {
            $('#tbl_list').DataTable().clear().destroy();
            $('#tbl_list').empty();

        }
        var headers = {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf_token,
        };
        grid = $('#tbl_list').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: 'get_hist_datatable',
                // url: '{{ url()->current() }}',
                data: {
                    unitid: '0',
                    tgl: '',
                }
            },

            columns: [{
                    className: 'dt-control',
                    orderable: false,
                    data: null,
                    defaultContent: ''
                },
                {
                    title: 'Id',
                    data: 'key_id',
                    name: 'key_id',
                    visible: false,
                },
                {
                    title: 'Kd Unit Kerja',
                    data: 'kd_unit_kerja',
                    name: 'kd_unit_kerja'
                },
                {
                    title: 'Nama Unit Kerja',
                    data: 'nm_unit_kerja',
                    name: 'nm_unit_kerja'
                },

            ]
        });

        grid.on('click', 'td.dt-control', function(e) {
            let tr = e.target.closest('tr');
            let row = grid.row(tr);

            // if (row.child.isShown()) {
            //     // This row is already open - close it
            //     row.child.hide();
            // } else {
            //     // Open this row
            //     row.child(format(row.data())).show();
            // }
            if (document.getElementById(`tr-${row.data().key_id}`)) {
                tr.classList.remove('shown')
                $(`#tr-${row.data().key_id}`).remove()
            } else {
                expandSubGrid(tr, row.data())
                tr.classList.add('shown')
            }
        });
    }

    const expandSubGrid = async (tr, data) => {
        let subGridData = []
        let permision = "{{ auth()->user()->can('role-edit') }}";

        let pickedDate = $("#reservationdate").find("input").val();
        let datex = new Date(pickedDate);
        let _tgl = null;

        if (pickedDate.length > 0) {
            _tgl = datex.toLocaleDateString('en-GB'); //pickedDate,
        }

        tr.insertAdjacentHTML('afterend', `
        <tr id="tr-${data.key_id}">
            <td colspan='3' style="max-width: 0px !important;">
                <table style="width: 100%; height: 100%;" id='datatable-detail-${data.key_id}' class='table table-bordered table-striped datatable-details'>
                </table>
            </td>
        </tr> `);

        await $(`#datatable-detail-${data.key_id}`).dataTable({
            order: [],
            scrollX: true,
            processing: true,
            serverSide: true,
            paging: true,
            info: true,
            ajax: {
                url: 'getdtlhist',
                // dataSrc: "data",
                type: 'GET',
                data: {
                    id: data.key_id,
                    tgl: _tgl,
                }
            },
            columns: [{
                    title: 'Id',
                    data: 'key_id',
                    name: 'key_id',
                    visible: false,
                },
                {
                    title: 'No',
                    data: "no",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    title: 'File Name',
                    data: 'nm_file_sound',
                    name: 'nm_file_sound'
                },
                {
                    title: 'Create File',
                    data: 'created_file',
                    name: 'created_file',
                    render: function(data, type, row) {
                        if (type === "sort" || type === "type") {
                            return data;
                        }
                        return moment(data).format("DD-MM-YYYY HH:mm:ss");
                    }
                },
                {
                    title: 'Chanel File',
                    data: 'chanel',
                    name: 'chanel',
                },
                {
                    title: 'File Size',
                    data: 'ukuran',
                    name: 'ukuran',
                    render: function(data, type, row, meta) {
                        return row.ukuran + " Mbytes";
                    }
                },
                {
                    title: 'Start Recording',
                    className: 'text-center',
                    data: 'start_time',
                    name: 'start_time'
                },
                {
                    title: 'End Recording',
                    className: 'text-center',
                    data: 'end_time',
                    name: 'end_time'
                },
                {
                    title: 'Action',
                    data: null,
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        let btn = `<div><button class="btn btn-secondary btn-sm dl_btn" data-id="${data.key_id}" data-name="${data.nm_file_sound}" title="Download File" disabled><i class="fas fa-save"></i></button></div>`
                        if (permision) {
                            btn = `<div><button class="btn btn-secondary btn-sm dl_btn" data-id="${data.key_id}" data-name="${data.nm_file_sound}" title="Download File"><i class="fas fa-save"></i></button></div>`
                        }
                        return btn
                    }
                }
            ],
            initComplete: function(settings, json) {
                $(`table#datatable-detail-${data.key_id} > thead > tr`).css({
                    visibility: 'collapse'
                });
            },
            drawCallback: function(settings) {
                $(`table#datatable-detail-${data.key_id} > thead > tr`).css({
                    visibility: 'collapse'
                });
                setTimeout(() => {
                    $(`#datatable-detail-${data.key_id}`).DataTable().columns.adjust()
                    $(`table#datatable-detail-${data.key_id} > thead > tr`).css({
                        visibility: 'collapse'
                    });
                    // tableLokasiInstance.columns.adjust()
                }, 0);

            }
        })
    }

    function format(d) {
        // `d` is the original data object for the row
        let permision = "{{ auth()->user()->can('role-edit') }}";

        let pickedDate = $("#reservationdate").find("input").val();
        let datex = new Date(pickedDate);
        let _tgl = null;

        if (pickedDate.length > 0) {
            _tgl = datex.toLocaleDateString('en-GB'); //pickedDate,
        }
        let child_table = $(
            `<table id='datatable-detail-${d.key_id}' class='table table-bordered table-striped w-100 datatable-detail'></table>`
        );
        child_table.DataTable({
            searching: false,
            lengthChange: false,
            ordering: false,
            order: [],
            ajax: {
                url: 'getdtlhist',
                // dataSrc: "data",
                type: 'GET',
                data: {
                    id: d.key_id,
                    tgl: _tgl,
                }
            },
            columns: [{
                    title: 'Id',
                    data: 'key_id',
                    name: 'key_id',
                    visible: false,
                },
                {
                    title: 'No',
                    data: "no",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    title: 'File Name',
                    data: 'nm_file_sound',
                    name: 'nm_file_sound'
                },
                {
                    title: 'Create File',
                    data: 'created_file',
                    name: 'created_file',
                    render: function(data, type, row) {
                        return moment(data).format("DD-MM-YYYY HH:mm:ss");
                    }
                },
                {
                    title: 'Chanel File',
                    data: 'chanel',
                    name: 'chanel',
                },
                {
                    title: 'File Size',
                    data: 'ukuran',
                    name: 'ukuran',
                    render: function(data, type, row, meta) {
                        // console.log(data);
                        // if (row.ukuran = null) {
                        //     return " 0 Mbytes";
                        // } else {
                        //     return row.ukuran + " Mbytes";
                        // }
                        return row.ukuran + " Mbytes";
                    }
                },
                {
                    title: 'Start Recording',
                    className: 'text-center',
                    data: 'start_time',
                    name: 'start_time'
                },
                {
                    title: 'End Recording',
                    className: 'text-center',
                    data: 'end_time',
                    name: 'end_time'
                },
                {
                    data: null,
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        let btn = `<div><button class="btn btn-secondary btn-sm dl_btn" data-id="${data.key_id}" data-name="${data.nm_file_sound}" title="Download File" disabled><i class="fas fa-save"></i></button></div>`
                        if (permision) {
                            btn = `<div><button class="btn btn-secondary btn-sm dl_btn" data-id="${data.key_id}" data-name="${data.nm_file_sound}" title="Download File"><i class="fas fa-save"></i></button></div>`
                        }
                        return btn
                    }
                }
            ],
        });
        return child_table;
    }

    $(document).on("click", ".dl_btn", async function(e) {
        let _sound = $(this).attr("data-id");
        let _filename = $(this).attr("data-name");

        console.log(_filename);
        // let filename = document.getElementById("file");
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
        a.download = _filename; //$('#file').text(); //File name Here
        a.click();
    });

    $("#unit").on('change', async function(e) {
        console.log('Unit Change.....')
        let pickedDate = $("#reservationdate").find("input").val();
        let datex = new Date(pickedDate);
        let tgl = null;
        if (pickedDate.length > 0) {
            tgl = datex.toLocaleDateString('en-GB'); //pickedDate,
        }

        let _id = $("#unit").val();

        grid.ajax.url("refreshgrid?unitids=" + _id + "&tgls=" + tgl).load()
    });

    $("#reservationdate").on('change.datetimepicker', function(event) {
        let pickedDate = $("#reservationdate").find("input").val();
        let _id = $("#unit").val();
        let datex = new Date(pickedDate);
        let tgl = null;
        if (pickedDate.length > 0) {
            tgl = datex.toLocaleDateString('en-GB'); //pickedDate,
        }

        grid.ajax.url("refreshgrid?unitids=" + _id + "&tgls=" + tgl).load()
    })

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
    // Proses Datatable ---
    $(document).ready(function() {
        var dateNow = new Date();
        $('.select2').select2({
            placeholder: "Select a state",
            allowClear: true,
            theme: 'bootstrap4'
        });

        $('#reservationdate').datetimepicker({

            format: 'DD-MMMM-YYYY',
            // defaultDate: dateNow
        });
        initTable();
    });
</script>
@endsection