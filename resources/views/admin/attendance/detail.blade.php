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
        <div class="col-lg-4">
            <div class="tile">
                <div class="tile-body">
                    <ul class="list-group list-group-flush">
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
                </div>
            </div>
        </div>
        <div class="col-lg-8 mt-3 mt-lg-0">
            <div class="tile">
                <div class="tile-body">
                    <h6 class="mb-3">Absensi periode {{ date('d/m/Y', strtotime($dt1)) }} sampai {{ date('d/m/Y', strtotime($dt2)) }}:</h6>
                    <div class="table-responsive">
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
	DataTable("#table");
</script>

@endsection