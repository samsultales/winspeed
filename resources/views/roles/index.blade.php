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
        <a class="btn btn-success btn-sm mb-2" href="{{ route('roles.create') }}"><i class="fa fa-plus"></i> Create New Role</a>
        @endcan
    </div>

    <div class="card-body">
        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="dt-buttons btn-group flex-wrap">

                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div id="example1_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="example1"></label></div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-bordered">
                        <tr>
                            <th width="100px">No</th>
                            <th>Name</th>
                            <th width="280px">Action</th>
                        </tr>
                        @foreach ($roles as $key => $role)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                <!-- <button class="btn btn-warning btn-sm" onclick="showModal()"><i class="fas fa-list"></i>&nbsp; Menu</button> -->
                                @if ( $role->id != 1 )
                                    @can('role-edit')
                                    <button class="btn btn-sm btn-success btn-menu-role" title="Ubah Role Menu" data-id="{{$role->id}}"><i class="fa fa-list-ul"></i></button>
                                    @if ( $role->id != 2 )
                                    <a class="btn btn-primary btn-sm" href="{{ route('roles.edit',$role->id) }}"><i class="fas fa-pen"></i>&nbsp; Edit</a>
                                    @endif
                                    @endcan

                                    @if ( $role->id != 2 )
                                        @can('role-delete')
                                        <form method="POST" action="{{ route('roles.destroy', $role->id) }}" style="display:inline" data-flag="0">
                                            @csrf
                                            @method('DELETE')
                                            <button id="btn-submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>&nbsp; Delete</button>
                                        </form>
                                        @endcan
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </table>

                    {!! $roles->links('pagination::bootstrap-5') !!}
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="role-menu-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ubah Role Menu</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="role-menu-tree">
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="role-menu-submit-btn">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    let modalInstanceRoleMenu = null;

    
    @if(Session::has('notification')) //this line works as expected
    var type = "{{ Session::get('notification')['alert_type'], 'info' }}";
    console.log(type)

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



    async function getMenuRole(id) {
        try {
            const data = await $.get("getrolemenudata", {
                id
            })
            tree.visit(function(node) {
                node.setSelected(false);
            })

            let nodelength = data.length;
            for (let i = 0; i < nodelength; i++) {
                let key = data[i].menu_id;
                let node = tree.getNodeByKey(key);
                if (node !== null) {
                    if (!node.hasChildren()) {
                        node.setSelected();
                    }
                }
            }
        } catch (error) {
            console.error(error)
        }
    }

    const treeHandler = (id) => {
        $(":ui-fancytree").fancytree("destroy");
        $("#role-menu-tree").fancytree({
            icon: false,
            checkbox: true,
            selectMode: 3,
            source: {
                url: "getrolemenu?root=1",
                cache: false
            },
            init: function() {
                getMenuRole(id);
            },
            select: function(event, data) {
                selectedNode = new Array();
                $.map(data.tree.getSelectedNodes(), function(node) {
                    let level = node.getLevel();
                    node.visitParents(function(parent) {
                        if (!isNaN(parseInt(parent.key)) && $.inArray(parent.key, selectedNode) == -1) {
                            parent.partsel = true;
                            parent.setActive();
                            selectedNode.push(parent.key);
                        }
                    })
                    selectedNode.push(node.key);
                })
            }
        })
        $(".fancytree-container").addClass("fancytree-connectors");
        tree = $.ui.fancytree.getTree("#role-menu-tree");
    }

    const eventHandler = () => {
        $(document).on("click", ".btn-menu-role", async function() {
            const id = $(this).data("id")
            try {
                selectedNode = []
                roleId = id
                await treeHandler(id)
                $("#role-menu-modal").modal('show')
            } catch (error) {
                console.error(error)
            }
        })

        $(document).on("click", "#role-menu-submit-btn", async function() {
            let filteredNode = [...selectedNode].filter(node => node != "")
            let param = {
                _token: "{{ csrf_token() }}",
                role_id: roleId,
                menu: JSON.stringify(filteredNode)
            }
            try {
                const data = await $.post("setmenurole", param)
                $("#role-menu-modal").modal('hide')
                initTable()
                toastr.success('Role Menu berhasil diperbarui')
            } catch (error) {
                console.error(error)
            }
        })

        // $('#btn-submit').on('click', function(e) {
        $(document).on("click", "#btn-submit", async function(e) {
            e.preventDefault();
            console.log('submiting....')
            var form = $(this).parents('form');
            console.log(form)
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
                    form.submit();
                } else if (result.dismiss == 'cancel') {
                    console.log('cancel');
                    return false;
                }
            });
        });
    }


    $(document).ready(function() {
        //console.log("Ready!");
        eventHandler();
    });
</script>
@endsection