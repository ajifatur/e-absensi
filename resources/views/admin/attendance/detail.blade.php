@extends('template/main')

@section('title', 'Detail Absensi')

@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-clipboard"></i> Detail Absensi</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.attendance.index') }}">Absensi</a></li>
            <li class="breadcrumb-item">Detail Absensi</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-lg-auto mx-auto">
            <div class="tile">
                <div class="tile-body">
                    <form id="form-tanggal" class="form-inline" method="get" action="">
                        <select name="workhour" id="workhour" class="form-control form-control-sm mb-2 mr-sm-2">
                            <option value="0">Semua Jam Kerja</option>
                            @foreach($workhours as $workhour)
                            <option value="{{ $workhour->id }}" {{ Request::query('workhour') == $workhour->id ? 'selected' : '' }}>{{ $workhour->name }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="category" value="{{ $category }}">
                        <input type="text" id="t1" name="t1" class="form-control form-control-sm mb-2 mr-sm-2 input-tanggal" value="{{ isset($_GET) && isset($_GET['t1']) ? $_GET['t1'] : date('d/m/Y') }}" placeholder="Dari Tanggal" title="Dari Tanggal">
                        <input type="text" id="t2" name="t2" class="form-control form-control-sm mb-2 mr-sm-2 input-tanggal" value="{{ isset($_GET) && isset($_GET['t2']) ? $_GET['t2'] : date('d/m/Y') }}" placeholder="Sampai Tanggal" title="Sampai Tanggal">
                        <button type="submit" class="btn btn-sm btn-primary btn-submit mb-2">Filter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="tile">
                <div class="tile-body">
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item d-flex justify-content-between p-1">
                            <span class="font-weight-bold">Nama:</span>
                            <span>{{ $user->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between p-1">
                            <span class="font-weight-bold">Grup:</span>
                            <span>{{ $user->group ? $user->group->name : '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between p-1">
                            <span class="font-weight-bold">Kantor:</span>
                            <span>{{ $user->office ? $user->office->name : '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between p-1">
                            <span class="font-weight-bold">Jabatan:</span>
                            <span>{{ $user->position ? $user->position->name : '-' }}</span>
                        </li>
                    </ul>
                    <ul class="nav nav-tabs" id="myTab" role="tablist" style="border-bottom-width: 0px;">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $category == 1 ? 'active' : '' }}" href="{{ route('admin.attendance.detail', ['id' => $user->id, 'category' => 1, 't1' => date('d/m/Y', strtotime($t1)), 't2' => date('d/m/Y', strtotime($t2))]) }}" role="tab" aria-selected="true">Hadir <span class="badge badge-warning">{{ $count[1] }}</span></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $category == 2 ? 'active' : '' }}" href="{{ route('admin.attendance.detail', ['id' => $user->id, 'category' => 2, 't1' => date('d/m/Y', strtotime($t1)), 't2' => date('d/m/Y', strtotime($t2))]) }}" role="tab" aria-selected="false">Terlambat <span class="badge badge-warning">{{ $count[2] }}</span></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $category == 3 ? 'active' : '' }}" href="{{ route('admin.attendance.detail', ['id' => $user->id, 'category' => 3, 't1' => date('d/m/Y', strtotime($t1)), 't2' => date('d/m/Y', strtotime($t2))]) }}" role="tab" aria-selected="false">Sakit <span class="badge badge-warning">{{ $count[3] }}</span></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $category == 4 ? 'active' : '' }}" href="{{ route('admin.attendance.detail', ['id' => $user->id, 'category' => 4, 't1' => date('d/m/Y', strtotime($t1)), 't2' => date('d/m/Y', strtotime($t2))]) }}" role="tab" aria-selected="false">Izin <span class="badge badge-warning">{{ $count[4] }}</span></a>
                        </li>
                    </ul>
                    <div class="tab-content py-3" id="myTabContent">
                        <div class="tab-pane fade show active" role="tabpanel">
                            <div class="table-responsive">
                                @if($category == 1 || $category == 2)
                                <table class="table table-sm table-hover table-bordered" id="table">
                                    <thead>
                                        <tr>
                                            <th width="20"></th>
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
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ route('admin.attendance.edit', ['id' => $attendance->id]) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                                                        <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="{{ $attendance->id }}" title="Hapus"><i class="fa fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @elseif($category == 3 || $category == 4)
                                <table class="table table-sm table-hover table-bordered" id="table">
                                    <thead>
                                        <tr>
                                            <th width="20"></th>
                                            <th width="80">Tanggal</th>
                                            <th>Alasan Tidak Hadir</th>
                                            <th width="40">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($attendances as $attendance)
                                            <tr>
                                                <td align="center"><input type="checkbox"></td>
                                                <td>
                                                    <span class="d-none">{{ date('Y-m-d', strtotime($attendance->entry_at)).' '.$attendance->start_at }}</span>
                                                    {{ date('d/m/Y', strtotime($attendance->date)) }}
                                                </td>
                                                <td>{!! nl2br($attendance->note) !!}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ route('admin.absent.edit', ['id' => $attendance->id]) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                                                        <a href="#" class="btn btn-danger btn-sm btn-delete-absent" data-id="{{ $attendance->id }}" title="Hapus"><i class="fa fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif
                            </div>
                        </div>
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

<form id="form-delete-absent" class="d-none" method="post" action="{{ route('admin.absent.delete') }}">
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

    // Button Delete Absent
    $(document).on("click", ".btn-delete-absent", function(e){
        e.preventDefault();
        var id = $(this).data("id");
        var ask = confirm("Anda yakin ingin menghapus data ini?");
        if(ask){
            $("#form-delete-absent input[name=id]").val(id);
            $("#form-delete-absent").submit();
        }
    });

    // Change Date
    $(document).on("change", "#t1, #t2", function(){
        var t1 = $("#t1").val();
        var t2 = $("#t2").val();
        (t1 != '' && t2 != '') ? $("#form-tanggal .btn-submit").removeAttr("disabled") : $("#form-tanggal .btn-submit").attr("disabled","disabled");
    });
</script>

@endsection