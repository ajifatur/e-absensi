@extends('template/main')

@section('title', 'Edit Kategori')

@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-money"></i> Edit Kategori</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">Penggajian</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.salary-category.index') }}">Kategori</a></li>
            <li class="breadcrumb-item">Edit Kategori</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="tile">
                <form method="post" action="{{ route('admin.salary-category.update') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $salary_category->id }}">
                    <div class="tile-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Grup <span class="text-danger">*</span></label>
                                <select name="group_id" class="form-control {{ $errors->has('group_id') ? 'is-invalid' : '' }}" disabled>
                                    <option value="" disabled selected>--Pilih--</option>
                                    @foreach($groups as $group)
                                    <option value="{{ $group->id }}" {{ $salary_category->group_id == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('group_id'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('group_id')) }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-12">
                                <label>Jabatan <span class="text-danger">*</span></label>
                                <select name="position_id" class="form-control {{ $errors->has('position_id') ? 'is-invalid' : '' }}" id="position">
                                    <option value="" selected>--Pilih--</option>
                                    @foreach(\App\Models\Group::find($salary_category->group_id)->positions as $position)
                                    <option value="{{ $position->id }}" {{ $salary_category->position_id == $position->id ? 'selected' : '' }}>{{ $position->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('position_id'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('position_id')) }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-12">
                                <label>Nama Kategori <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ $salary_category->name }}">
                                @if($errors->has('name'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('name')) }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-12">
                                <label>Tipe <span class="text-danger">*</span></label>
                                <select name="type_id" class="form-control {{ $errors->has('type_id') ? 'is-invalid' : '' }}" id="category">
                                    <option value="" selected>--Pilih--</option>
                                    <option value="1" {{ $salary_category->type_id == 1 ? 'selected' : '' }}>Manual</option>
                                    <option value="2" {{ $salary_category->type_id == 2 ? 'selected' : '' }}>Masa Kerja (Bulan)</option>
                                    <option value="3" {{ $salary_category->type_id == 3 ? 'selected' : '' }}>Kehadiran per Bulan</option>
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