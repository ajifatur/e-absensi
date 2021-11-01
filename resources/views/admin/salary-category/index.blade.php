@extends('template/main')

@section('title', 'Kelola Kategori Gaji')

@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-money"></i> Kelola Kategori Gaji</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">Penggajian</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.salary-category.index') }}">Kategori</a></li>
            <li class="breadcrumb-item">Kelola Kategori</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-title-w-btn">
                    <div></div>
                    <div class="btn-group">
                        <a class="btn btn-sm btn-primary" href="{{ route('admin.salary-category.create') }}"><i class="fa fa-lg fa-plus"></i> Tambah Data</a>
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
                                    <th>Kategori</th>
                                    <th width="100">Tipe</th>
                                    <th width="80">Indikator</th>
                                    <th width="150">Jabatan</th>
                                    <th width="150">Grup</th>
                                    <th width="40">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salary_categories as $category)
                                    <tr>
                                        <td align="center"><input type="checkbox"></td>
                                        <td>{{ $category->name }}</td>
                                        <td>
                                            @if($category->type_id == 1) Manual
                                            @elseif($category->type_id == 2) Masa Kerja (Bulan)
                                            @elseif($category->type_id == 3) Kehadiran per Bulan
                                            @endif
                                        </td>
                                        <td>{{ number_format($category->indicators()->count(),0,',',',') }}</td>
                                        <td>
                                            @if($category->position)
                                                <a href="{{ route('admin.position.detail', ['id' => $category->position->id]) }}">{{ $category->position->name }}</a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($category->group)
                                                <a href="{{ route('admin.group.detail', ['id' => $category->group->id]) }}">{{ $category->group->name }}</a>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.salary-category.set', ['id' => $category->id]) }}" class="btn btn-info btn-sm" title="Atur Indikator"><i class="fa fa-cog"></i></a>
                                                <a href="{{ route('admin.salary-category.edit', ['id' => $category->id]) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                                                <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="{{ $category->id }}" title="Hapus"><i class="fa fa-trash"></i></a>
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

<form id="form-delete" class="d-none" method="post" action="{{ route('admin.salary-category.delete') }}">
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