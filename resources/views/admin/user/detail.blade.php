@extends('template/main')

@section('title', 'Detail User')

@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-user"></i> Detail User</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">User</a></li>
            <li class="breadcrumb-item">Detail User</li>
        </ul>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="tile">
                <div class="tile-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between p-1">
                            <span class="font-weight-bold">Nama:</span>
                            <span>{{ $user->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between p-1">
                            <span class="font-weight-bold">Tanggal lahir:</span>
                            <span>{{ date('d/m/Y', strtotime($user->birthdate)) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between p-1">
                            <span class="font-weight-bold">Jenis Kelamin:</span>
                            <span>{{ $user->gender == 'L' ? 'Laki-Laki' : 'Perempuan' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between p-1">
                            <span class="font-weight-bold">Nomor HP:</span>
                            <span>{{ $user->phone_number }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between p-1">
                            <span class="font-weight-bold">Alamat:</span>
                            <span>{{ $user->address != '' ? $user->address : '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between p-1">
                            <span class="font-weight-bold">Pendidikan Terakhir:</span>
                            <span>{{ $user->latest_education != '' ? $user->latest_education : '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between p-1">
                            <span class="font-weight-bold">Email:</span>
                            <span>{{ $user->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between p-1">
                            <span class="font-weight-bold">Username:</span>
                            <span>{{ $user->username }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mt-3 mt-lg-0">
            <div class="tile">
                <div class="tile-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between p-1">
                            <span class="font-weight-bold">Role:</span>
                            <span>{{ role($user->role) }}</span>
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
                        <li class="list-group-item d-flex justify-content-between p-1">
                            <span class="font-weight-bold">Status:</span>
                            <span class="badge {{ $user->end_date == null ? 'badge-success' : 'badge-danger' }}">{{ $user->end_date == null ? 'Aktif' : 'Tidak Aktif' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between p-1">
                            <span class="font-weight-bold">Mulai Bekerja:</span>
                            <span>{{ date('d/m/Y', strtotime($user->start_date)) }}</span>
                        </li>
                        @if($user->end_date != null)
                        <li class="list-group-item d-flex justify-content-between p-1">
                            <span class="font-weight-bold">Akhir Bekerja:</span>
                            <span>{{ date('d/m/Y', strtotime($user->end_date)) }}</span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection

@section('css')

<style type="text/css">
    label {font-weight: bold;}
</style>

@endsection