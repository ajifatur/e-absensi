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
                                <label>Nama Kategori <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}">
                                @if($errors->has('name'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('name')) }}</div>
                                @endif
                            </div>
                            @if(Auth::user()->role == role('super-admin'))
                            <div class="form-group col-md-12">
                                <label>Grup <span class="text-danger">*</span></label>
                                <select name="group_id" class="form-control {{ $errors->has('group_id') ? 'is-invalid' : '' }}">
                                    <option value="" disabled selected>--Pilih--</option>
                                    @foreach($groups as $group)
                                    <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('group_id'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('group_id')) }}</div>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="tile-footer"><button class="btn btn-primary icon-btn" type="submit"><i class="fa fa-save mr-2"></i>Simpan</button></div>
                </form>
            </div>
        </div>
    </div>
</main>

@endsection