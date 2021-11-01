@extends('template/main')

@section('title', 'Kelola Kantor')

@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-home"></i> Kelola Kantor</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.office.index') }}">Kantor</a></li>
            <li class="breadcrumb-item">Kelola Kantor</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-title-w-btn">
                    <div></div>
                    <div class="btn-group">
                        <a class="btn btn-sm btn-primary" href="{{ route('admin.office.create') }}"><i class="fa fa-lg fa-plus"></i> Tambah Data</a>
                    </div>
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
                                    <th width="20"></th>
                                    <th>Nama</th>
                                    <th width="80">Karyawan</th>
                                    <th width="150">Grup</th>
                                    <th width="40">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($offices as $office)
                                    <tr>
                                        <td align="center"><input type="checkbox"></td>
                                        <td><a href="{{ route('admin.office.detail', ['id' => $office->id]) }}">{{ $office->name }}</a></td>
                                        <td align="right">{{ number_format($office->users()->where('role','=',role('member'))->where('end_date','=',null)->count(),0,',',',') }}</td>
                                        <td>
                                            @if($office->group)
                                                <a href="{{ route('admin.group.detail', ['id' => $office->group->id]) }}">{{ $office->group->name }}</a>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ $office->name != 'Head Office' ? route('admin.office.edit', ['id' => $office->id]) : '#' }}" class="btn btn-warning btn-sm" style="{{ $office->name != 'Head Office' ? '' : 'cursor: not-allowed' }}" title="{{ $office->name != 'Head Office' ? 'Edit' : 'Tidak diizinikan mengedit data ini' }}"><i class="fa fa-edit"></i></a>
                                                <a href="#" class="btn btn-danger btn-sm {{ $office->name != 'Head Office' ? 'btn-delete' : '' }}" data-id="{{ $office->id }}" style="{{ $office->name != 'Head Office' ? '' : 'cursor: not-allowed' }}" title="{{ $office->name != 'Head Office' ? 'Hapus' : 'Tidak diizinikan menghapus data ini' }}"><i class="fa fa-trash"></i></a>
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

<form id="form-delete" class="d-none" method="post" action="{{ route('admin.office.delete') }}">
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