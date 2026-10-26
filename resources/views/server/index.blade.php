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
                                <div class="col-md-12" data-select2-id="43">
                                    <div class="form-group" data-select2-id="42">
                                        <select class="select2" style="width: 100%" id="unit" name="unit" data-placeholder="Choose one thing">
                                            @if (in_array(Session::get('auth')['key_id_unit_kerja'], array("0", "1")) )
                                            <option value='0'> All </option>
                                            @endif
                                            <?php echo $unit  ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- <div class="col-md-6" data-select2-id="30">
                                    <div class="form-group">
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input reservationdate" data-target="#reservationdate" data-placeholder="Choose one thing">
                                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <!-- <div id="example1_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="example1"></label></div> -->
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>

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
            // ajax: '{{ url()->current() }}',
            ajax: {
                url: 'get_unit_datatable',
                // url: 'getlogdtlhist',
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
                    data: 'key_id_unit_kerja',
                    name: 'key_id_unit_kerja',
                    visible: false,

                },
                {
                    title: 'Nama Unit Kerja',
                    data: 'nm_unit_kerja',
                    name: 'nm_unit_kerja'
                },
                {
                    title: 'Nama Server',
                    data: 'nm_server',
                    name: 'nm_server'
                },
                {
                    title: 'Seq Server',
                    data: 'seq_server',
                    name: 'seq_server'
                },
                {
                    title: 'IP Server',
                    data: 'ip_server',
                    name: 'ip_server'
                },
                {
                    title: 'Username',
                    data: 'user_local',
                    name: 'user_local'
                },

                {
                    title: 'Password',
                    data: 'pwd',
                    name: 'pwd',
                    // render: function(data, type, row) {
                    //     return '********'
                    //     // return maskify(data)
                    // }
                },
                {
                    title: 'Password',
                    data: 'password_local',
                    name: 'password_local',
                    type: "password",
                    visible: false,
                    // render: function(data, type, row) {
                    //     return '********'
                    //     // return maskify(data)
                    // }
                },
                {
                    title: 'Show',
                    data: null,
                    render: function(data, type, row) {
                        if (row.password_local) {
                            return `<button class="btn btn-success shows">Show</button>`;
                        }
                        return '';
                    }
                },
            ]
        });

        grid.on('draw', function() {
            console.log('table has been re-drawn')
            grid.column(2).data().each(function(value, index) {
                // grid.cell(index, 7 ).data('*******');
            });
        });

        grid.on('click', 'button', function(e) {
            var idx = grid.row(e.target.closest('tr')).index();
            let data = grid.row(e.target.closest('tr')).data();

            let vl = grid.cell(idx, 7).data();
            if (vl == '*******') {
                grid.cell(idx, 7).data(data.password_local);
            } else {
                grid.cell(idx, 7).data('*******');
            }

            // alert(data.password_local);
        });

        grid.on('click', 'td.dt-control', function(e) {
            let tr = e.target.closest('tr');
            let row = grid.row(tr);

            // if (row.child.isShown()) {
            //     // This row is already open - close it
            //     row.child.hide();
            // } else {
            //     // Open this row
            //     // row.child(format(row.data())).show();
            //     expandSubGrid(tr, row.data())
            // }
            if (document.getElementById(`tr-${row.data().key_id_unit_kerja}`)) {
                tr.classList.remove('shown')
                $(`#tr-${row.data().key_id_unit_kerja}`).remove()
            } else {
                expandSubGrid(tr, row.data())
                tr.classList.add('shown')
            }

        });
    }

    const maskify = (info) => {
        if (info !== null) {
            return info.replace(/[a-zA-Z0-9]/g, '*');
            // return info.repeat('*', 4);
        } else {
            return info;
        }
    }

    const expandSubGrid = async (tr, data) => {
        let subGridData = []
        let pickedDate = $("#reservationdate").find("input").val();
        let datex = new Date(pickedDate);
        let _tgl = null;

        tr.insertAdjacentHTML('afterend', `
        <tr id="tr-${data.key_id_unit_kerja}">
            <td colspan='8' style="max-width: 0px !important;">
                <table style="width: 100%; height: 100%;" id='datatable-detail-${data.key_id_unit_kerja}' class='table table-bordered table-striped datatable-details'>
                </table>
            </td>
        </tr> `);

        await $(`#datatable-detail-${data.key_id_unit_kerja}`).dataTable({
            order: [],
            scrollX: true,
            processing: true,
            serverSide: true,
            paging: true,
            info: true,
            // data: subGridData,
            ajax: {
                url: 'getdetailunit',
                // dataSrc: "data",
                // type: 'GET',
                data: {
                    id: data.key_id_unit_kerja,
                    tgl: _tgl,
                }
            },
            columns: [{
                    data: "id",
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    title: 'Channel',
                    className: 'text-center',
                    data: 'chanel',
                    name: 'chanel'
                },
                {
                    title: 'Tanggal Cek Terakhir',
                    className: 'text-center',
                    data: 'last_check',
                    name: 'last_check',
                    render: function(data, type, row) {
                        if (type === "sort" || type === "type") {
                            return data;
                        }
                        return moment(data).format("DD-MM-YYYY HH:mm:ss");
                    }
                },
                {
                    title: 'Tanggal Record Terakhir',
                    className: 'text-center',
                    data: 'last_recording',
                    name: 'last_recording',
                    render: function(data, type, row) {
                        if (type === "sort" || type === "type") {
                            return data;
                        }
                        return moment(data).format("DD-MM-YYYY HH:mm");
                    }
                },
                {
                    title: 'Nama File Terakhir',
                    data: 'last_file_sound',
                    name: 'last_file_sound',
                },
                {
                    title: 'Status',
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row) {
                        if (row.status === 0) {

                            return 'Not OK'
                        } else {

                            return 'OK'
                        }

                    }
                },

            ],
            createdRow: function(row, data, dataIndex) {
               
                if (data.status == 0) {
                    $(row).addClass('bg-danger');
                    // grid.cell(dataIndex, 5).addClass('bg-danger');
                }
            },
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
        // `d` is the original data object for the row
        let child_table = $(
            `<table id='datatable-detail-${d.key_id}' class="w-100"></table>`
        );
        child_table.DataTable({
            processing: true,
            serverSide: true,
            paging: true,
            info: true,
            PaginationType: "full_numbers",
            dom: "fltip",
            ajax: {
                url: 'getlogdtlhist',
                // dataSrc: "data",
                // type: 'GET',
                data: {
                    id: d.key_id,
                    tgl: _tgl,
                }
            },

            columns: [{
                    "data": "id",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },

                {
                    title: 'Keterangan',
                    data: 'pesan',
                    name: 'pesan'
                },
                {
                    title: 'Tanggal Pesan',
                    data: 'date_pesan',
                    name: 'date_pesan',
                    render: function(data, type, row) {
                        if (type === "sort" || type === "type") {
                            return data;
                        }
                        return moment(data).format("DD-MM-YYYY HH:mm:ss");
                    }
                },
                {
                    title: 'Tanggal Integrasi',
                    className: 'text-center',
                    data: 'date_intg',
                    name: 'date_intg',
                    render: function(data, type, row) {
                        if (type === "sort" || type === "type") {
                            return data;
                        }
                        return moment(data).format("DD-MM-YYYY HH:mm");
                    }
                },

            ],
        });

        return child_table;
    }

    $(document).on("click", ".dl_btn", async function(e) {
        let _id = $(this).attr("data-id");

        param = {
            id: _id,
            tgl: null,
        };

        $.ajax({
            url: "{{route('download')}}",
            type: 'GET',
            data: param,
            success: function(res) {
                if (res.status == 'error') {
                    toastr.error('File Not Found!');
                } else {
                    window.location = route('download', {
                        id: _id
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire(errorThrown, textStatus, "error");
            },
        });
    });



    $("#unit").on('change', async function(e) {
        // let pickedDate = $("#reservationdate").find("input").val();
        // let datex = new Date(pickedDate);
        let tgl = null;
        // if (pickedDate.length > 0) {
        //     tgl = datex.toLocaleDateString('en-GB'); //pickedDate,
        // }

        let _id = $("#unit").val();
        grid.ajax.url("refreshgridUnit?unitids=" + _id + "&tgls=" + tgl).load()
    });

    $("#reservationdate").on('change.datetimepicker', function(event) {
        // let pickedDate = $("#reservationdate").find("input").val();
        let _id = $("#unit").val();
        // let datex = new Date(pickedDate);
        let tgl = null;
        // if (pickedDate.length > 0) {
        //     tgl = datex.toLocaleDateString('en-GB'); //pickedDate,
        // }

        grid.ajax.url("refreshgridUnit?unitids=" + _id + "&tgls=" + tgl).load()
    })

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