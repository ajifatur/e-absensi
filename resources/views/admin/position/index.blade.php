@extends('template/main')

@section('title', 'Kelola Jabatan')

@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-home"></i> Kelola Jabatan</h1>
            <p>Menu untuk mengelola data jabatan</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.position.index') }}">Jabatan</a></li>
            <li class="breadcrumb-item">Kelola Jabatan</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-title-w-btn">
                    <h3 class="title">Kelola Jabatan</h3>
                    <div class="btn-group">
                        <a class="btn btn-sm btn-primary" href="{{ route('admin.position.create') }}"><i class="fa fa-lg fa-plus"></i> Tambah Data</a>
                    </div>
                </div>
                <div class="tile-body">
                    @if(Session::get('message') != null)
                    <div class="alert alert-dismissible alert-success">
                        <button class="close" type="button" data-dismiss="alert">×</button>{{ Session::get('message') }}
                    </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="table">
                            <thead>
                                <tr>
                                    <th width="20"><input type="checkbox"></th>
                                    <th>Nama Jabatan</th>
                                    <th>Grup</th>
                                    <th width="80">Jam Kerja</th>
                                    <th width="40">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($positions as $position)
                                    <tr>
                                        <td align="center"><input type="checkbox"></td>
                                        <td><a href="{{ route('admin.position.detail', ['id' => $position->id]) }}">{{ $position->name }}</a><br><small class="text-muted">{{ number_format(count($position->users),0,'.','.') }} orang</small></td>
                                        <td>
                                            @if($position->group)
                                                <a href="{{ route('admin.group.detail', ['id' => $position->group->id]) }}">{{ $position->group->name }}</a></td>
                                            @else
                                                -
                                            @endif
                                        <td>{{ $position->work_hours == 1 ? 'Full-Time' : 'Part-Time' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ $position->name != 'Head position' ? route('admin.position.edit', ['id' => $position->id]) : '#' }}" class="btn btn-warning btn-sm" style="{{ $position->name != 'Head position' ? '' : 'cursor: not-allowed' }}" title="{{ $position->name != 'Head position' ? 'Edit' : 'Tidak diizinikan mengedit data ini' }}"><i class="fa fa-edit"></i></a>
                                                <a href="#" class="btn btn-danger btn-sm {{ $position->name != 'Head position' ? 'btn-delete' : '' }}" data-id="{{ $position->id }}" style="{{ $position->name != 'Head position' ? '' : 'cursor: not-allowed' }}" title="{{ $position->name != 'Head position' ? 'Hapus' : 'Tidak diizinikan menghapus data ini' }}"><i class="fa fa-trash"></i></a>
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

<form id="form-delete" class="d-none" method="post" action="{{ route('admin.position.delete') }}">
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