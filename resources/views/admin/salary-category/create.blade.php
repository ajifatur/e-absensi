@extends('template/main')

@section('title', 'Tambah Kategori')

@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-money"></i> Tambah Kategori</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">Penggajian</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.salary-category.index') }}">Kategori</a></li>
            <li class="breadcrumb-item">Tambah Kategori</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="tile">
                <form method="post" action="{{ route('admin.salary-category.store') }}">
                    @csrf
                    <div class="tile-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Grup <span class="text-danger">*</span></label>
                                <select name="group_id" id="group" class="form-control {{ $errors->has('group_id') ? 'is-invalid' : '' }}" {{ Auth::user()->role == role('super-admin') ? '' : 'disabled' }}>
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
                            <div class="form-group col-md-12">
                                <label>Jabatan <span class="text-danger">*</span></label>
                                <select name="position_id" class="form-control {{ $errors->has('position_id') ? 'is-invalid' : '' }}" id="position" {{ $disabled_selected }}>
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
                            <div class="form-group col-md-12">
                                <label>Nama Kategori <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}">
                                @if($errors->has('name'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('name')) }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-12">
                                <label>Tipe <span class="text-danger">*</span></label>
                                <select name="type_id" class="form-control {{ $errors->has('type_id') ? 'is-invalid' : '' }}" id="category">
                                    <option value="" selected>--Pilih--</option>
                                    <option value="1" {{ old('type_id') == 1 ? 'selected' : '' }}>Manual</option>
                                    <option value="2" {{ old('type_id') == 2 ? 'selected' : '' }}>Masa Kerja (Bulan)</option>
                                    <option value="3" {{ old('type_id') == 3 ? 'selected' : '' }}>Kehadiran per Bulan</option>
                                </select>
                                @if($errors->has('type_id'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('type_id')) }}</div>
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

<script type="text/javascript">
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
                $("#position").html(html).removeAttr("disabled");
            }
        });
    });
</script>

@endsection