@extends('template/main')

@section('title', 'Input Absensi')

@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-clipboard"></i> Input Absensi</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.attendance.index') }}">Absensi</a></li>
            <li class="breadcrumb-item">Input Absensi</li>
        </ul>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="tile">
                <form method="post" action="{{ route('admin.attendance.store') }}">
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
                            if(Auth::user()->role == role('super-admin')) {
                                if(old('group_id') == null) $disabled_selected = 'disabled';
                                elseif(in_array(old('role'), [role('admin'), role('manager')])) $disabled_selected = 'disabled';
                            }
                            else {
                                if(in_array(old('role'), [role('admin'), role('manager')])) $disabled_selected = 'disabled';
                            }
                        @endphp
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Karyawan <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <select name="user_id" class="form-control {{ $errors->has('user_id') ? 'is-invalid' : '' }}" id="member" {{ $disabled_selected }}>
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
                            <label class="col-md-3 col-lg-2 col-form-label">Nama <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-10">
                                <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}">
                                @if($errors->has('name'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('name')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Tanggal Absen <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <input type="text" name="date" class="form-control datepicker {{ $errors->has('date') ? 'is-invalid' : '' }}" value="{{ old('date') }}" placeholder="Format: dd/mm/yyyy" autocomplete="off">
                                @if($errors->has('date'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('date')) }}</div>
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
            url: "{{ route('api.user.index') }}",
            data: {group: group},
            success: function(result){
                var html = '<option value="" selected>--Pilih--</option>';
                $(result).each(function(key,value){
                    html += '<option value="' + value.id + '">' + value.name + '</option>';
                });
                $("#member").html(html).removeAttr("disabled");
            }
        });
    });
</script>

@endsection