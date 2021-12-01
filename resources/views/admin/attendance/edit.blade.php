@extends('template/main')

@section('title', 'Edit Absensi')

@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-clipboard"></i> Edit Absensi</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.attendance.index') }}">Absensi</a></li>
            <li class="breadcrumb-item">Edit Absensi</li>
        </ul>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="tile">
                <form method="post" action="{{ route('admin.attendance.update') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $attendance->id }}">
                    <div class="tile-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Karyawan <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <select name="user_id" class="form-control {{ $errors->has('user_id') ? 'is-invalid' : '' }}" id="member" disabled>
                                    <option value="" selected>--Pilih--</option>
                                    @foreach(\App\Models\Office::find($attendance->office_id)->users()->where('role','=',role('member'))->where('end_date','=',null)->orderBy('name','asc')->get() as $user)
                                    <option value="{{ $user->id }}" {{ $attendance->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('user_id'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('user_id')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Jam Kerja <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <select name="workhour_id" class="form-control {{ $errors->has('workhour_id') ? 'is-invalid' : '' }}" id="work-hour">
                                    <option value="" selected>--Pilih--</option>
                                    @foreach($work_hours as $work_hour)
                                    <option value="{{ $work_hour->id }}" {{ $attendance->workhour_id == $work_hour->id ? 'selected' : '' }}>{{ date('H:i', strtotime($work_hour->start_at)) }} - {{ date('H:i', strtotime($work_hour->end_at)) }} ({{ $work_hour->name }})</option>
                                    @endforeach
                                </select>
                                @if($errors->has('workhour_id'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('workhour_id')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Tanggal Absensi <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <input type="text" name="date" class="form-control datepicker {{ $errors->has('date') ? 'is-invalid' : '' }}" value="{{ date('d/m/Y', strtotime($attendance->date)) }}" placeholder="Format: dd/mm/yyyy" autocomplete="off">
                                @if($errors->has('date'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('date')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Waktu Masuk <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-10">
                                <div class="form-row">
                                    <div class="col-lg-5">
                                        <input type="text" name="entry_at[0]" class="form-control datepicker {{ $errors->has('entry_at.0') ? 'is-invalid' : '' }}" value="{{ date('d/m/Y', strtotime($attendance->entry_at)) }}" placeholder="Format: dd/mm/yyyy" autocomplete="off">
                                        @if($errors->has('entry_at.0'))
                                        <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('entry_at.0')) }}</div>
                                        @endif
                                    </div>
                                    <div class="col-lg-5 mt-2 mt-lg-0">
                                        <input type="text" name="entry_at[1]" class="form-control clockpicker {{ $errors->has('entry_at.1') ? 'is-invalid' : '' }}" value="{{ date('H:i', strtotime($attendance->entry_at)) }}" autocomplete="off">
                                        @if($errors->has('entry_at.1'))
                                        <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('entry_at.1')) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Waktu Keluar</label>
                            <div class="col-md-9 col-lg-10">
                                <div class="form-row">
                                    <div class="col-lg-5">
                                        <input type="text" name="exit_at[0]" class="form-control datepicker {{ $errors->has('exit_at.0') ? 'is-invalid' : '' }}" value="{{ $attendance->exit_at != null ? date('d/m/Y', strtotime($attendance->exit_at)) : '' }}" placeholder="Format: dd/mm/yyyy" autocomplete="off">
                                        @if($errors->has('exit_at.0'))
                                        <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('exit_at.0')) }}</div>
                                        @endif
                                    </div>
                                    <div class="col-lg-5 mt-2 mt-lg-0">
                                        <input type="text" name="exit_at[1]" class="form-control clockpicker {{ $errors->has('exit_at.1') ? 'is-invalid' : '' }}" value="{{ $attendance->exit_at != null ? date('H:i', strtotime($attendance->exit_at)) : '' }}" autocomplete="off">
                                        @if($errors->has('exit_at.1'))
                                        <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('exit_at.1')) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-muted">Kosongi saja jika belum melakukan absen keluar.</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Alasan Terlambat</label>
                            <div class="col-md-9 col-lg-4">
                                <input type="text" name="late" class="form-control {{ $errors->has('late') ? 'is-invalid' : '' }}" value="{{ $attendance->late }}">
                                <div class="text-muted">Kosongi saja jika tidak terlambat.</div>
                                @if($errors->has('late'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('late')) }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="tile-footer"><button class="btn btn-primary icon-btn" type="submit"><i class="fa fa-save mr-2"></i>Simpan</button></div>
                </form>
            </div>
        </div>
    </div>
</main>

@endsection

@section('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/jquery-clockpicker.min.js" integrity="sha512-x0qixPCOQbS3xAQw8BL9qjhAh185N7JSw39hzE/ff71BXg7P1fkynTqcLYMlNmwRDtgdoYgURIvos+NJ6g0rNg==" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{ asset('templates/vali-admin/js/plugins/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">
    // Input Datepicker
    $(".datepicker").datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayHighlight: true
    });

    // Clockpicker
    $(".clockpicker").clockpicker({
        autoclose: true
    });
</script>

@endsection

@section('css')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/jquery-clockpicker.min.css" integrity="sha512-Dh9t60z8OKsbnVsKAY3RcL2otV6FZ8fbZjBrFENxFK5H088Cdf0UVQaPoZd/E0QIccxqRxaSakNlmONJfiDX3g==" crossorigin="anonymous" />

@endsection