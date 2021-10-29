@extends('template/main')

@section('title', 'Kelola Grup')

@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dot-circle-o"></i> Kelola Grup</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.group.index') }}">Grup</a></li>
            <li class="breadcrumb-item">Kelola Grup</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-title-w-btn">
                    <div></div>
                    @if(Auth::user()->role == role('super-admin'))
                    <div class="btn-group">
                        <a class="btn btn-sm btn-primary" href="{{ route('admin.group.create') }}"><i class="fa fa-lg fa-plus"></i> Tambah Data</a>
                    </div>
                    @endif
                </div>
                <div class="tile-body">
                    @if(Session::get('message') != null)
                    <div class="alert alert-dismissible alert-success">
                        <button class="close" type="button" data-dismiss="alert">×</button>{{ Session::get('message') }}
                    </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-bordered" id="table">
                            <thead>
                                <tr>
                                    <th rowspan="2" width="20"></th>
                                    <th rowspan="2">Nama</th>
                                    <th rowspan="2" width="70">Kantor</th>
                                    <th rowspan="2" width="70">Jabatan</th>
                                    <th colspan="4">User</th>
                                    <th rowspan="2" width="40">Opsi</th>
                                </tr>
                                <tr>
                                    <th width="70">Admin</th>
                                    <th width="70">Manager</th>
                                    <th width="70">Karyawan Aktif</th>
                                    <th width="70">Karyawan Nonaktif</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groups as $group)
                                    <tr>
                                        <td align="center"><input type="checkbox"></td>
                                        <td><a href="{{ route('admin.group.detail', ['id' => $group->id]) }}">{{ $group->name }}</a></td>
                                        <td>{{ number_format($group->offices->count(),0,',',',') }}</td>
                                        <td>{{ number_format($group->positions->count(),0,',',',') }}</td>
                                        <td>{{ number_format($group->users()->where('role','=',role('admin'))->count(),0,',',',') }}</td>
                                        <td>{{ number_format($group->users()->where('role','=',role('manager'))->count(),0,',',',') }}</td>
                                        <td>{{ number_format($group->users()->where('role','=',role('member'))->where('end_date','=',null)->count(),0,',',',') }}</td>
                                        <td>{{ number_format($group->users()->where('role','=',role('member'))->where('end_date','!=',null)->count(),0,',',',') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.group.edit', ['id' => $group->id]) }}" class="btn btn-warning btn-sm" data-id="{{ $group->id }}" title="Edit"><i class="fa fa-edit"></i></a>
                                                <a href="#" class="btn btn-danger btn-sm {{ Auth::user()->role == role('super-admin') ? 'btn-delete' : '' }}" data-id="{{ $group->id }}" style="{{ Auth::user()->role == role('super-admin') ? '' : 'cursor: not-allowed' }}" title="Hapus"><i class="fa fa-trash"></i></a>
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

<form id="form-delete" class="d-none" method="post" action="{{ route('admin.group.delete') }}">
    @csrf
    <input type="hidden" name="id">
</form>

@endsection

@section('js')

@include('template/js/datatable')

<script type="text/javascript">
	// DataTable
	DataTable("#table");

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
</script>

@endsection