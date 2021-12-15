@extends('template/main')

@section('title', 'Input Ketidakhadiran')

@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-clipboard"></i> Input Ketidakhadiran</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.absent.index') }}">Ketidakhadiran</a></li>
            <li class="breadcrumb-item">Input Ketidakhadiran</li>
        </ul>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="tile">
                <form method="post" action="{{ route('admin.absent.store') }}">
                    @csrf
                    <div class="tile-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Grup <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <select name="group_id" class="form-control {{ $errors->has('group_id') ? 'is-invalid' : '' }}" id="group" {{ Auth::user()->role == role('super-admin') ? '' : 'disabled' }}>
                                    <option value="" disabled selected>--Pilih--</option>
                                    @if(Auth::user()->role == role('super-admin'))
                                        @foreach($groups as $group)
                                        <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                                        @endforeach
                                    @else
                                        @foreach($groups as $group)
                                        <option value="{{ $group->id }}" {{ Auth::user()->group_id == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if($errors->has('group_id'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('group_id')) }}</div>
                                @endif
                            </div>
                        </div>
                        @php
                            $disabled_selected = '';
                            if(Auth::user()->role == role('super-admin') && old('group_id') == null) $disabled_selected = 'disabled';
                        @endphp
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Kantor <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <select name="office_id" class="form-control {{ $errors->has('office_id') ? 'is-invalid' : '' }}" id="office" {{ $disabled_selected }}>
                                @if(Auth::user()->role == role('super-admin'))
                                    @if(old('office_id') != null || old('group_id') != null)
                                        <option value="" selected>--Pilih--</option>
                                        @foreach(\App\Models\Group::find(old('group_id'))->offices as $office)
                                            <option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>{{ $office->name }}</option>
                                        @endforeach
                                    @else
                                        <option value="" selected>--Pilih--</option>
                                    @endif
                                @else
                                    <option value="" selected>--Pilih--</option>
                                    @foreach(\App\Models\Group::find(Auth::user()->group_id)->offices as $office)
                                    <option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>{{ $office->name }}</option>
                                    @endforeach
                                @endif
                                </select>
                                @if($errors->has('office_id'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('office_id')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Karyawan <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <select name="user_id" class="form-control {{ $errors->has('user_id') ? 'is-invalid' : '' }}" id="member" disabled>
                                @if(Auth::user()->role == role('super-admin'))
                                    @if(old('user_id') != null || old('group_id') != null)
                                        <option value="" selected>--Pilih--</option>
                                        @foreach(\App\Models\Group::find(old('group_id'))->users()->where('role','=',role('member'))->where('end_date','=',null)->orderBy('name','asc')->get() as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    @else
                                        <option value="" selected>--Pilih--</option>
                                    @endif
                                @else
                                    <option value="" selected>--Pilih--</option>
                                    @foreach(\App\Models\Group::find(Auth::user()->group_id)->users()->where('role','=',role('member'))->where('end_date','=',null)->orderBy('name','asc')->get() as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                @endif
                                </select>
                                @if($errors->has('user_id'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('user_id')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Tanggal Tidak Hadir <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <input type="text" name="date" class="form-control datepicker {{ $errors->has('date') ? 'is-invalid' : '' }}" value="{{ old('date') }}" placeholder="Format: dd/mm/yyyy" autocomplete="off">
                                @if($errors->has('date'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('date')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Tidak Hadir Kenapa? <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <select name="category_id" class="form-control {{ $errors->has('category_id') ? 'is-invalid' : '' }}">
                                    <option value="" selected>--Pilih--</option>
                                    <option value="1" {{ old('category_id') == 1 ? 'selected' : '' }}>Sakit</option>
                                    <option value="2" {{ old('category_id') == 2 ? 'selected' : '' }}>Izin</option>
                                </select>
                                @if($errors->has('category_id'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('category_id')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Alasan Tidak Hadir</label>
                            <div class="col-md-9 col-lg-4">
                                <textarea name="note" class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" rows="3">{{ old('note') }}</textarea>
                                @if($errors->has('note'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('note')) }}</div>
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

<script type="text/javascript" src="{{ asset('templates/vali-admin/js/plugins/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">
    // Input Datepicker
    $(".datepicker").datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayHighlight: true
    });

    // Change Group
    $(document).on("change", "#group", function() {
        var group = $(this).val();
        $.ajax({
            type: "get",
            url: "{{ route('api.office.index') }}",
            data: {group: group},
            success: function(result){
                var html = '<option value="" selected>--Pilih--</option>';
                $(result).each(function(key,value){
                    html += '<option value="' + value.id + '">' + value.name + '</option>';
                });
                $("#office").html(html).removeAttr("disabled");
                $("#member").val(null).attr("disabled","disabled");
                $("#work-hour").val(null).attr("disabled","disabled");
            }
        });
    });

    // Change Office
    $(document).on("change", "#office", function() {
        var office = $(this).val();
        var group = $("#group").val();
        $.ajax({
            type: "get",
            url: "{{ route('api.user.index') }}",
            data: {group: group, office: office},
            success: function(result){
                var html = '<option value="" selected>--Pilih--</option>';
                $(result).each(function(key,value){
                    html += '<option value="' + value.id + '">' + value.name + '</option>';
                });
                $("#member").html(html).removeAttr("disabled");
            }
        });
    });

    // Change User
    $(document).on("change", "#member", function() {
        var user = $(this).val();
        $.ajax({
            type: "get",
            url: "{{ route('api.work-hour.index') }}",
            data: {user: user},
            success: function(result){
                var html = '<option value="" selected>--Pilih--</option>';
                $(result).each(function(key,value){
                    html += '<option value="' + value.id + '">' + value.start_at.substr(0,5) + ' - ' + value.end_at.substr(0,5) + ' (' + value.name + ')' + '</option>';
                });
            }
        });
    });
</script>

@endsection