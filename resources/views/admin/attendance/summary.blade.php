@extends('template/main')

@section('title', 'Rekap Absensi')

@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-clipboard"></i> Rekap Absensi</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.attendance.index') }}">Absensi</a></li>
            <li class="breadcrumb-item">Rekap Absensi</li>
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
                        <input type="text" id="t1" name="t1" class="form-control form-control-sm mb-2 mr-sm-2 input-tanggal" value="{{ date('d/m/Y', strtotime($t1)) }}" placeholder="Dari Tanggal" title="Dari Tanggal">
                        <input type="text" id="t2" name="t2" class="form-control form-control-sm mb-2 mr-sm-2 input-tanggal" value="{{ date('d/m/Y', strtotime($t2)) }}" placeholder="Sampai Tanggal" title="Sampai Tanggal">
                        <button type="submit" class="btn btn-sm btn-primary btn-submit mb-2">Filter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered" id="table">
                        <thead>
                            <tr>
                                <th width="20"></th>
                                <th>Karyawan</th>
                                <th width="150">Kantor</th>
                                <th width="150">Jabatan</th>
                                <th width="150">Jam Kerja</th>
                                <th width="60">Hadir</th>
                                <th width="60">Terlambat</th>
                                <th width="60">Sakit</th>
                                <th width="60">Izin</th>
                                <th width="60">Cuti</th>
                                <th width="20">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                @foreach($user->workhours as $workhour)
                                    <tr>
                                        <td align="center"><input type="checkbox" data-id="{{ $user->id }}"></td>
                                        <td><a href="{{ route('admin.user.detail', ['id' => $user->id]) }}">{{ $user->name }}</a></td>
                                        <td>
                                            @if($user->office)
                                                <a href="{{ route('admin.office.detail', ['id' => $user->office->id]) }}">{{ $user->office->name }}</a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->position)
                                                <a href="{{ route('admin.position.detail', ['id' => $user->position->id]) }}">{{ $user->position->name }}</a>
                                            @endif
                                        </td>
                                        <td>{{ $workhour->name }}</td>
                                        <td align="right">
                                            <a href="{{ route('admin.attendance.detail', ['id' => $user->id, 'workhour' => $workhour->id, 'category' => 1, 't1' => date('d/m/Y', strtotime($t1)), 't2' => date('d/m/Y', strtotime($t2))]) }}">{{ number_format($workhour->present,0,',',',') }}</a>
                                        </td>
                                        <td align="right">
                                            <a href="{{ route('admin.attendance.detail', ['id' => $user->id, 'workhour' => $workhour->id, 'category' => 2, 't1' => date('d/m/Y', strtotime($t1)), 't2' => date('d/m/Y', strtotime($t2))]) }}">{{ number_format($workhour->late,0,',',',') }}</a>
                                        </td>
                                        <td align="right">
                                            <a href="{{ route('admin.attendance.detail', ['id' => $user->id, 'category' => 3, 't1' => date('d/m/Y', strtotime($t1)), 't2' => date('d/m/Y', strtotime($t2))]) }}">{{ number_format($user->absent1,0,',',',') }}</a>
                                        </td>
                                        <td align="right">
                                            <a href="{{ route('admin.attendance.detail', ['id' => $user->id, 'category' => 4, 't1' => date('d/m/Y', strtotime($t1)), 't2' => date('d/m/Y', strtotime($t2))]) }}">{{ number_format($user->absent2,0,',',',') }}</a>
                                        </td>
                                        <td align="right">0</td>
                                        <td align="center">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.attendance.detail', ['id' => $user->id, 'category' => 1, 't1' => date('d/m/Y', strtotime($t1)), 't2' => date('d/m/Y', strtotime($t2))]) }}" class="btn btn-info btn-sm" title="Detail"><i class="fa fa-list"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
    </div>
</main>

@endsection

@section('js')

@include('template/js/datatable')

<script type="text/javascript" src="{{ asset('templates/vali-admin/js/plugins/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">
	// DataTable
	DataTable("#table", [0, 1, 2, 3, 7, 8, 9]);

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

@section('css')

<style type="text/css">
	.hidden-date {display: none;}
</style>

@endsection