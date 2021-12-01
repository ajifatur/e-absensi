@extends('template/main')

@section('title', 'Report Absensi')

@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-clipboard"></i> Report Absensi</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.attendance.index') }}">Absensi</a></li>
            <li class="breadcrumb-item">Report Absensi</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-lg-auto mx-auto">
            <div class="tile">
                <div class="tile-body">
                    <form id="form-tanggal" class="form-inline" method="get" action="">
                        @if(Auth::user()->role == role('super-admin'))
                        <select name="group" id="group" class="form-control form-control-sm mb-2 mr-sm-2">
                            <option value="0">Semua Grup</option>
                            @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ isset($_GET) && isset($_GET['group']) && $_GET['group'] == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                            @endforeach
                        </select>
                        @endif
                        <select name="office" id="kantor" class="form-control form-control-sm mb-2 mr-sm-2">
                            <option value="0">Semua Kantor</option>
                            @if(Auth::user()->role == role('super-admin'))
                                @if(isset($_GET) && isset($_GET['group']) && $_GET['group'] != 0)
                                    @foreach(\App\Models\Group::find($_GET['group'])->offices as $office)
                                    <option value="{{ $office->id }}" {{ isset($_GET) && isset($_GET['office']) && $_GET['office'] == $office->id ? 'selected' : '' }}>{{ $office->name }}</option>
                                    @endforeach
                                @endif
                            @elseif(Auth::user()->role == role('admin') || Auth::user()->role == role('manager'))
                                @foreach(\App\Models\Group::find(Auth::user()->group_id)->offices as $office)
                                <option value="{{ $office->id }}" {{ isset($_GET) && isset($_GET['office']) && $_GET['office'] == $office->id ? 'selected' : '' }}>{{ $office->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <input type="text" id="t1" name="t1" class="form-control form-control-sm mb-2 mr-sm-2 input-tanggal" value="{{ isset($_GET) && isset($_GET['t1']) ? $_GET['t1'] : date('d/m/Y') }}" placeholder="Dari Tanggal" title="Dari Tanggal">
                        <input type="text" id="t2" name="t2" class="form-control form-control-sm mb-2 mr-sm-2 input-tanggal" value="{{ isset($_GET) && isset($_GET['t2']) ? $_GET['t2'] : date('d/m/Y') }}" placeholder="Sampai Tanggal" title="Sampai Tanggal">
                        <button type="submit" class="btn btn-sm btn-primary btn-submit mb-2">Filter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
        <div class="tile">
            <div class="tile-title-w-btn">
                <div></div>
                <div>
                    <a class="btn btn-sm btn-primary" href="{{ route('admin.attendance.create') }}"><i class="fa fa-lg fa-plus"></i> Input Absensi</a>
                </div>
            </div>
            <div class="tile-body">
                @if(Session::get('message') != null)
                <div class="alert alert-dismissible alert-success">
                    <button class="close" type="button" data-dismiss="alert">×</button>{{ Session::get('message') }}
                </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered" id="table">
                        <thead>
                            <tr>
                                <th width="20"></th>
                                <th>Identitas User</th>
                                <th width="120">Jam Kerja</th>
                                <th width="80">Tanggal</th>
                                <th>Absen Masuk</th>
                                <th>Absen Keluar</th>
                                <th width="40">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $attendance)
                                <tr>
                                    <td align="center"><input type="checkbox"></td>
                                    <td>
                                        <a href="{{ route('admin.user.detail', ['id' => $attendance->user->id]) }}">{{ $attendance->user->name }}</a>
                                        <br>
                                        <small class="text-dark">{{ $attendance->user->group->name }}</small>
                                        <br>
                                        <small class="text-muted">{{ $attendance->user->office->name }}</small>
                                    </td>
                                    <td>
                                        {{ $attendance->workhour ? $attendance->workhour->name : '-' }}
                                        <br>
                                        <small class="text-muted">{{ date('H:i', strtotime($attendance->start_at)) }} - {{ date('H:i', strtotime($attendance->end_at)) }}</small>
                                    </td>
                                    <td>
                                        <span class="d-none">{{ date('Y-m-d', strtotime($attendance->entry_at)).' '.$attendance->start_at }}</span>
                                        {{ date('d/m/Y', strtotime($attendance->date)) }}
                                    </td>
                                    <td>
                                        @php $date = $attendance->start_at <= $attendance->end_at ? $attendance->date : date('Y-m-d', strtotime('-1 day', strtotime($attendance->date))); @endphp
                                        <i class="fa fa-clock-o mr-2"></i>{{ date('H:i', strtotime($attendance->entry_at)) }} WIB
                                        <br>
                                        <small class="text-muted"><i class="fa fa-calendar mr-2"></i>{{ date('d/m/Y', strtotime($attendance->entry_at)) }}</small>
                                        @if(strtotime($attendance->entry_at) < strtotime($date.' '.$attendance->start_at) + 60)
                                            <br>
                                            <strong class="text-success"><i class="fa fa-check-square-o mr-2"></i>Masuk sesuai dengan waktunya.</strong>
                                        @else
                                            <br>
                                            <strong class="text-danger"><i class="fa fa-warning mr-2"></i>Terlambat {{ time_to_string(abs(strtotime($date.' '.$attendance->start_at) - strtotime($attendance->entry_at))) }}.</strong>
                                        @endif
                                        @if($attendance->late != '')
                                        <br>
                                        <strong class="text-danger"><i class="fa fa-pencil mr-2"></i>Terlambat karena {{ $attendance->late }}.</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->exit_at != null)
                                            <i class="fa fa-clock-o mr-2"></i>{{ date('H:i', strtotime($attendance->exit_at)) }} WIB
                                            <br>
                                            <small class="text-muted"><i class="fa fa-calendar mr-2"></i>{{ date('d/m/Y', strtotime($attendance->exit_at)) }}</small>
                                            @php $attendance->end_at = $attendance->end_at == '00:00:00' ? '23:59:59' : $attendance->end_at @endphp
                                            @if(strtotime($attendance->exit_at) > strtotime($attendance->date.' '.$attendance->end_at))
                                                <br>
                                                <strong class="text-success"><i class="fa fa-check-square-o mr-2"></i>Keluar sesuai dengan waktunya.</strong>
                                            @else
                                                <br>
                                                <strong class="text-danger"><i class="fa fa-warning mr-2"></i>Keluar lebih awal {{ time_to_string(abs(strtotime($attendance->exit_at) - strtotime($attendance->date.' '.$attendance->end_at))) }}.</strong>
                                            @endif
                                        @else
                                            <strong class="text-info"><i class="fa fa-question-circle mr-2"></i>Belum melakukan absen keluar.</strong>
                                        @endif
                                    </td>
                                    <td align="center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.attendance.edit', ['id' => $attendance->id]) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                                            <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="{{ $attendance->id }}" title="Hapus"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
    </div>
</main>

<form id="form-delete" class="d-none" method="post" action="{{ route('admin.attendance.delete') }}">
    @csrf
    <input type="hidden" name="id">
</form>

@endsection

@section('js')

@include('template/js/datatable')

<script type="text/javascript" src="{{ asset('templates/vali-admin/js/plugins/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">
	// DataTable
	DataTable("#table");

    // Datepicker
    $(".input-tanggal").datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayHighlight: true
    });

    // Change Group
    $(document).on("change", "#group", function(){
        var group = $(this).val();
        $.ajax({
            type: 'get',
            url: "{{ route('api.office.index') }}",
            data: {group: group},
            success: function(result){
                var html = '<option value="0" selected>Semua Kantor</option>';
                $(result).each(function(key,value){
                    html += '<option value="' + value.id + '">' + value.name + '</option>';
                });
                $("#kantor").html(html);
            }
        });
    });

    // Change Date
    $(document).on("change", "#t1, #t2", function(){
        var t1 = $("#t1").val();
        var t2 = $("#t2").val();
        (t1 != '' && t2 != '') ? $("#form-tanggal .btn-submit").removeAttr("disabled") : $("#form-tanggal .btn-submit").attr("disabled","disabled");
    });

    // Button Delete
    $(document).on("click", ".btn-delete", function(e){
        e.preventDefault();
        var id = $(this).data("id");
        var ask = confirm("Anda yakin ingin menghapus data ini?");
        if(ask){
            $("#form-delete input[name=id]").val(id);
            $("#form-delete").submit();
        }
    });
</script>

@endsection