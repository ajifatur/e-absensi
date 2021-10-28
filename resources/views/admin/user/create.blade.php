@extends('template/main')

@section('title', 'Tambah User')

@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-user"></i> Tambah User</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">User</a></li>
            <li class="breadcrumb-item">Tambah User</li>
        </ul>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="tile">
                <form method="post" action="{{ route('admin.user.store') }}">
                    @csrf
                    <div class="tile-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Role <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <select name="role" class="form-control {{ $errors->has('role') ? 'is-invalid' : '' }}" id="role" {{ Auth::user()->role == role('manager') ? 'disabled' : '' }}>
                                    <option value="" disabled selected>--Pilih--</option>
                                    @if(Auth::user()->role != role('manager'))
                                        @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    @else
                                        @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ Auth::user()->role == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if($errors->has('role'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('role')) }}</div>
                                @endif
                            </div>
                        </div>
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
                            <label class="col-md-3 col-lg-2 col-form-label">Kantor <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <select name="office_id" class="form-control {{ $errors->has('office_id') ? 'is-invalid' : '' }}" id="kantor" {{ $disabled_selected }}>
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
                            <label class="col-md-3 col-lg-2 col-form-label">Jabatan <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <select name="position_id" class="form-control {{ $errors->has('position_id') ? 'is-invalid' : '' }}" id="jabatan" {{ $disabled_selected }}>
                                @if(Auth::user()->role == role('super-admin'))
                                    @if(old('position_id') != null || old('group_id') != null)
                                        <option value="" selected>--Pilih--</option>
                                        @foreach(\App\Models\Group::find(old('group_id'))->positions as $position)
                                            <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>{{ $position->name }}</option>
                                        @endforeach
                                    @else
                                        <option value="" selected>--Pilih--</option>
                                    @endif
                                @else
                                    <option value="" selected>--Pilih--</option>
                                    @foreach(\App\Models\Group::find(Auth::user()->group_id)->positions as $position)
                                    <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>{{ $position->name }}</option>
                                    @endforeach
                                @endif
                                </select>
                                @if($errors->has('position_id'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('position_id')) }}</div>
                                @endif
                            </div>
                        </div>
                        <hr>
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
                            <label class="col-md-3 col-lg-2 col-form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <input type="text" name="birthdate" class="form-control datepicker {{ $errors->has('birthdate') ? 'is-invalid' : '' }}" value="{{ old('birthdate') }}" placeholder="Format: dd/mm/yyyy" autocomplete="off">
                                @if($errors->has('birthdate'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('birthdate')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="gender-L" name="gender" class="custom-control-input" value="L" {{ old('gender') == 'L' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="gender-L">Laki-Laki</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="gender-P" name="gender" class="custom-control-input" value="P" {{ old('gender') == 'P' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="gender-P">Perempuan</label>
                                </div>
                                @if($errors->has('gender'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('gender')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Alamat <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-10">
                                <textarea name="address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" rows="3">{{ old('address') }}</textarea>
                                @if($errors->has('address'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('address')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Pendidikan Terakhir</label>
                            <div class="col-md-9 col-lg-10">
                                <textarea name="latest_education" class="form-control {{ $errors->has('latest_education') ? 'is-invalid' : '' }}" rows="3">{{ old('latest_education') }}</textarea>
                                @if($errors->has('latest_education'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('latest_education')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Mulai Bekerja <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <input type="text" name="start_date" class="form-control datepicker {{ $errors->has('start_date') ? 'is-invalid' : '' }}" value="{{ old('start_date') }}" placeholder="Format: dd/mm/yyyy" autocomplete="off">
                                @if($errors->has('start_date'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('start_date')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Akhir Bekerja</label>
                            <div class="col-md-9 col-lg-4">
                                <input type="text" name="end_date" class="form-control datepicker {{ $errors->has('end_date') ? 'is-invalid' : '' }}" value="{{ old('end_date') }}" placeholder="Format: dd/mm/yyyy" autocomplete="off">
                                <div class="text-muted">Kosongi saja jika masih aktif bekerja.</div>
                                @if($errors->has('end_date'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('end_date')) }}</div>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Email <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-10">
                                <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}">
                                @if($errors->has('email'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('email')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Nomor HP <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-10">
                                <input type="text" name="phone_number" class="form-control number-only {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" value="{{ old('phone_number') }}">
                                @if($errors->has('phone_number'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('phone_number')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Username <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <input type="text" name="username" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" value="{{ old('username') }}">
                                @if($errors->has('username'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('username')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Password <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}">
                                    <div class="input-group-append">
                                    <a href="#" class="btn btn-toggle-password input-group-text {{ $errors->has('password') ? 'border-danger' : '' }}"><i class="fa fa-eye"></i></a>
                                    </div>
                                </div>
                                @if($errors->has('password'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('password')) }}</div>
                                @endif
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Status <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <select name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
                                    <option value="" disabled selected>--Pilih--</option>
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @if($errors->has('status'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('status')) }}</div>
                                @endif
                            </div>
                        </div> -->
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
  
    // Button Toggle Password
    $(document).on("click", ".btn-toggle-password", function(e){
        e.preventDefault();
        if($(this).find("i").hasClass("fa-eye")){
            $(this).find("i").removeClass("fa-eye").addClass("fa-eye-slash");
            $("input[name=password]").attr("type","text");
        }
        else{
            $(this).find("i").addClass("fa-eye").removeClass("fa-eye-slash");
            $("input[name=password]").attr("type","password");
        }
    });

    // Change Role
    $(document).on("change", "#role", function() {
        var role = $(this).val();
        var admins = ["{{ role('super-admin') }}", "{{ role('admin') }}", "{{ role('manager') }}"];
        if(admins.indexOf(role) >= 0) {
            $("#kantor").attr("disabled","disabled");
            $("#jabatan").attr("disabled","disabled");
        }
        else {
            $("#kantor").removeAttr("disabled");
            $("#jabatan").removeAttr("disabled");
        }
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
                $("#kantor").html(html);
            }
        });
        $.ajax({
            type: 'get',
            url: "{{ route('api.position.index') }}",
            data: {group: group},
            success: function(result){
                var html = '<option value="" selected>--Pilih--</option>';
                $(result).each(function(key,value){
                    html += '<option value="' + value.id + '">' + value.name + '</option>';
                });
                $("#jabatan").html(html);
            }
        });
        var role = $("#role").val();
        var admins = ["{{ role('super-admin') }}", "{{ role('admin') }}", "{{ role('manager') }}"];
        if(admins.indexOf(role) >= 0) {
            $("#kantor").attr("disabled","disabled");
            $("#jabatan").attr("disabled","disabled");
        }
        else {
            $("#kantor").removeAttr("disabled");
            $("#jabatan").removeAttr("disabled");
        }
    });

    // Change Office
    $(document).on("change", "#kantor", function(){
        var value = $(this).val();
        value == 0 ? $("#jabatan").attr("disabled","disabled") : $("#jabatan").removeAttr("disabled");
    });

    // Input Number Only
    $(document).on("keypress", ".number-only", function(e){
        var charCode = (e.which) ? e.which : e.keyCode;
        if(charCode >= 48 && charCode <= 57) return true;
        else return false;
    });
</script>

@endsection