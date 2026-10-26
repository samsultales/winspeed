@extends('admin.app')

<style>
    .fontawesome-select {
        font-family: 'FontAwesome', 'Helvetica';
    }
</style>


@section('content')
<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-2">Pengaturan Menu</h4>
                <hr>
                <div id="menutree"></div>
            </div>
        </div>
    </div>

    <div class="col-6" id="divtool">
        <div class="mb-3">
            <button type="button" class="btn btn-primary w-md" id="btnCreate">Tambah Menu</button>
            <button type="button" class="btn btn-warning w-md" id="btnEdit">Edit Menu</button>
            <button type="button" class="btn btn-danger w-md" id="btnDelete">Hapus Menu</button>
        </div>
        <form id="form" class="d-none" action="menu/store" method="POST">
            <input type="hidden" name="id" id="id" />
            <input type="hidden" name="parent_id" id="parent_id" />
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="control-label" for="parent_name">Menu Induk</label>
                        <input type="text" id="parent_name" name="parent_name" class="form-control" readonly />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="control-label" for="name">Nama Menu</label>
                        <input type="text" id="name" name="name" class="form-control" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="control-label" for="route">URL Menu</label>
                        <input type="text" id="route" name="route" class="form-control" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="control-label" for="name">Icon</label>
                        <!-- <input type="text" id="icon" name="icon" class="form-control" /> -->
                        <div class="select2-wrapper">
                            <select id="icon" name="icon" class="input icons_select2" style="width: 100%;">
                                @foreach ($icon as $value => $label)
                                <option value="{{ $value }}" data-icon="{{ $value }}">
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="control-label" for="sequence">No. Urut</label>
                        <input type="number" min="1" value="1" id="sequence" name="sequence" class="form-control" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active">
                            <label class="form-check-label" for="is_active">Aktif</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_default" id="is_default">
                            <label class="form-check-label" for="is_default">Default</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <button class="btnTool btn btn-primary w-md" id="btnSave" type="button">Simpan Menu</button>
                <button class="btnTool btn btn-outline-secondary w-md" id="btnCancel" type="button">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection


@section('script')
<script>
    function iformat(icon) { 
        var originalOption = icon.element; 
        return $('<span><i class="' + $(originalOption).data('icon') + '"></i> &nbsp; ' + icon.text + '</span>');
    }

    $('.icons_select2').select2({
        width: "100%",
        templateSelection: iformat,
        templateResult: iformat,
        allowHtml: true,
        theme: 'bootstrap4'
    });


    var selectedNode;

    var tree = null;

    var initMenu = function() {
        $(":ui-fancytree").fancytree("destroy");
        $("#menutree").fancytree({
            icon: false,
            selectMode: 3,
            source: {
                url: 'menudata?root=1',
                cache: false
            },
            activate: function(event, data) {
                selectedNode = data.tree.getActiveNode();
            }
        });
        $(".fancytree-container").addClass("fancytree-connectors");
        tree = $.ui.fancytree.getTree("#menutree");
        selectedNode = null;
        $('#btnCancel').click();
    }

    var cancelHandler = function() {
        $('#btnCancel').click(function() {
            $('#form').addClass("d-none");
            $('#btnCreate').removeClass("d-none");
            $('#btnEdit').removeClass("d-none");
            $('#btnDelete').removeClass("d-none");
        });
    }

    var createMenuHandler = function() {
        $('#btnCreate').click(function() {

            //console.log(selectedNode);

            if (selectedNode == null) {
                toastr.error('Pilih Node terlebih dulu');
                return false;
            }

            $('#form').find("input[type=text], textarea, input[type=hidden]").val("");
            $('#parent_id').val(selectedNode.key == parseInt(selectedNode.key) ? selectedNode.key : null);
            $('#parent_name').val(selectedNode.key == parseInt(selectedNode.key) ? selectedNode.title : 'Root');
            $('#sequence').val(1);
            $('#is_active').attr('checked', 'checked');
            $('#is_default').attr('checked', 'checked');
            $('#form').removeClass("d-none");
            $('#btnEdit').addClass("d-none");
            $('#btnDelete').addClass("d-none");
        });
    }

    var editMenuHandler = function() {
        $('#btnEdit').click(function() {
            if (selectedNode == null) {
                toastr.error('Pilih Node terlebih dulu');
                return false;
            }

            if (selectedNode.key != parseInt(selectedNode.key) || selectedNode.key == '0') {
                toastr.error('Node Root tidak dapat diedit');
                return false;
            }

            //console.log(selectedNode.icon);

            $('#form').find("input[type=text], textarea, input[type=hidden]").val("");
            $('#parent_id').val(selectedNode.parent.key == parseInt(selectedNode.parent.key) ? selectedNode.parent.key : '0');
            $('#parent_name').val(selectedNode.parent.title);
            $('#id').val(selectedNode.key);
            $('#name').val(selectedNode.title);
            $('#route').val(selectedNode.data.route);
            $('#icon').val(selectedNode.icon).change();
            $('#sequence').val(selectedNode.data.sequence);
            // $('#is_active').val(selectedNode.data.is_active);
            // $('#is_default').val(selectedNode.data.is_default);

            if (selectedNode.data.is_active == 1) {
                $('#is_active').attr("checked", "");
            } else {
                $('#is_active').removeAttr("checked");
            }

            if (selectedNode.data.is_default == 1) {
                $('#is_default').attr("checked", "");
            } else {
                $('#is_default').removeAttr("checked");
            }

            $('#form').removeClass("d-none");
            $('#btnCreate').addClass("d-none");
            $('#btnDelete').addClass("d-none");
        });

    }

    var deleteMenuHandler = function() {
        $('#btnDelete').click(function() {
            if (selectedNode == null) {
                toastr.error('Pilih Node terlebih dulu');
                return false;
            }

            if (selectedNode.key != parseInt(selectedNode.key) || selectedNode.key == '0') {
                toastr.error('Node Root tidak dapat dihapus');
                return false;
            }

            Swal.fire({
                text: 'Anda yakin akan menghapus menu ini?',
                icon: 'warning',
                showCancelButton: true
            }).then((result) => {

                if (result.isConfirmed) {
                    var header = {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf_token,
                    };

                    var xhr = $.ajax({
                            url: '/menu/' + selectedNode.key,
                            method: 'DELETE',
                            dataType: 'json',
                            headers: header
                        })
                        .done(function(response) {
                            message = (response.message) ? response.message : '';
                            if (response.success) {
                                toastr.options.onHidden = initMenu;
                                toastr.success(message);
                            } else {
                                message = (message !== '') ? message : 'Ada kesalahan';
                                swal.fire('', message, 'error');
                            }
                        })
                        .fail(function(jqXHR, textStatus, errorThrown) {
                            failCallback(jqXHR, textStatus, errorThrown);
                        });
                }
            });
        });

    }

    var formSave = function() {
        $('#btnSave').click(function(e) {
            e.preventDefault();

            var parent_id = $('#parent_id').val();
            var param = $('#form').serialize();

            var header = {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf_token,
            };

            var xhr = $.ajax({
                    url: 'menu',
                    method: 'POST',
                    dataType: 'json',
                    headers: header,
                    data: param
                })
                .done(function(response) {
                    message = (response.message) ? response.message : '';
                    if (response.success) {
                        toastr.options.onHidden = initMenu;
                        toastr.success(message);
                    } else {
                        message = (message !== '') ? message : 'Ada kesalahan';
                        swal.fire('', message, 'error');
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    failCallback(jqXHR, textStatus, errorThrown);
                });
        });
    }


    $(document).ready(function() {

        initMenu();
        createMenuHandler();
        editMenuHandler();
        cancelHandler();
        deleteMenuHandler();
        formSave();
    });
</script>
@endsection