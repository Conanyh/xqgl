@extends('admin.common.app')

@section('styles')
    <!-- Data Tables -->
    <link href="{{ asset('assets/admin/css/plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/plugins/chosen/chosen.css') }}" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="{{ asset('assets/admin/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><small>巡查记录</small></h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="table_data_tables.html#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="table_data_tables.html#">选项1</a>
                            </li>
                            <li><a href="table_data_tables.html#">选项2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form action="{{ route('admin.patrols.export') }}" method="get">
                        <div class="col-sm-2" style="display: inline-block">
                            <input class="form-control inline" type="date" name="timeStart">
                        </div>
                        <div class="col-sm-2" style="display: inline-block">
                            <input class="form-control inline" type="date" name="timeEnd">
                        </div>
                        <button class="btn btn-info" type="submit" style="display: inline-block"><i class="fa fa-paste"></i>巡查记录报表</button>
                    </form>
                    <form action="{{ route('admin.patrols.personExport') }}" method="get">
                        <div class="col-sm-2" style="display: inline-block">
                            <select class="form-control chosen-select" name="user_id" tabindex="2" required>
                                <option value="" hidden disabled selected>请选择人员,（必选）</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->user_id }}">{{ $user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2" style="display: inline-block">
                            <input class="form-control inline" type="date" name="timeStart">
                        </div>
                        <div class="col-sm-2" style="display: inline-block">
                            <input class="form-control inline" type="date" name="timeEnd">
                        </div>
                        <button class="btn btn-info" type="submit" style="display: inline-block"><i class="fa fa-paste"></i>个人巡查记录报表</button>
                    </form>
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>姓名</th>
                            <th>开始时间</th>
                            <th>结束时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($patrols as $patrol)
                            <tr class="gradeC">
                                <td>{{ $patrol->id }}</td>
                                <td>{{ $patrol->user->name }}</td>
                                <td>{{ $patrol->created_at }}</td>
                                <td>{{ $patrol->end_at }}</td>
                                <td class="center">
                                    <a href="{{ route('admin.patrols.show',['patrols' => $patrol->id]) }}"><button type="button" class="btn btn-danger btn-xs">查看</button></a>
                                    <button class="btn btn-warning btn-xs delete" data-id="{{ $patrol->id }}">删除</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>姓名</th>
                            <th>开始时间</th>
                            <th>结束时间</th>
                            <th>操作</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                {{ $patrols->links() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Data Tables -->
    <script src="{{ asset('assets/admin/js/plugins/dataTables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/dataTables/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('assets/admin/js/demo/form-advanced-demo.js') }}"></script>

    <!-- Sweet alert -->
    <script src="{{ asset('assets/admin/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
@endsection

@section('javascript')
    <script>
        $('.delete').click(function () {
            var id = $(this).data('id');
            swal({
                title: "您确定要删除这条信息吗",
                text: "删除后将无法恢复，请谨慎操作！",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "删除",
                cancelButtonText: "取消",
                closeOnConfirm: false
            }, function () {
                $.ajaxSetup({
                    headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type:"delete",
                    url: '/admin/patrol/'+id,
                    success:function (res) {
                        if (res.status == 1){
                            swal(res.msg, "您已经永久删除了这条信息。", "success");
                            location.reload();
                        }else {
                            swal(res.msg, "请稍后重试。", "waring");
                        }
                    },
                });
                $.ajax();
            });
        });
    </script>
@endsection