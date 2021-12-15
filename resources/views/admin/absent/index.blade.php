@extends('template/main')

@section('title', 'Kelola Ketidakhadiran')

@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-clipboard"></i> Kelola Ketidakhadiran</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.absent.index') }}">Ketidakhadiran</a></li>
            <li class="breadcrumb-item">Kelola Ketidakhadiran</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-title-w-btn">
                    <div></div>
                    <div class="btn-group">
                        <a class="btn btn-sm btn-primary" href="{{ route('admin.absent.create') }}"><i class="fa fa-lg fa-plus"></i> Tambah Data</a>
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
                                    <th width="200">Tidak Hadir</th>
                                    <th width="80">Tanggal</th>
                                    <th width="150">Grup</th>
                                    <th width="40">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($absents as $absent)
                                    <tr>
                                        <td align="center"><input type="checkbox"></td>
                                        <td><a href="{{ route('admin.user.detail', ['id' => $absent->user->id]) }}">{{ $absent->user->name }}</a></td>
                                        <td>
                                            @if($absent->category_id == 1)
                                                <span class="badge badge-warning">Sakit</span>
                                            @elseif($absent->category_id == 2)
                                                <span class="badge badge-info">Izin</span>
                                            @endif
                                            <br>
                                            {{ $absent->note }}
                                        </td>
                                        <td>
                                            <span class="d-none">{{ $absent->note }}</span>
                                            {{ date('d/m/Y', strtotime($absent->date)) }}
                                        </td>
                                        <td>
                                            @if($absent->user->group)
                                                <a href="{{ route('admin.group.detail', ['id' => $absent->user->group->id]) }}">{{ $absent->user->group->name }}</a>
                                            @endif
                                        </td>
                                        <td align="center">
                                            <div class="btn-group">
                                                @if($absent->attachment != '')
                                                <a href="https://izin.campus.co.id/assets/images/absent/{{ $absent->attachment }}" target="_blank" class="btn btn-info btn-sm d-none" data-id="{{ $absent->id }}" title="Lihat Bukti"><i class="fa fa-photo"></i></a>
                                                @endif
                                                <a href="{{ route('admin.absent.edit', ['id' => $absent->id]) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                                                <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="{{ $absent->id }}" title="Hapus"><i class="fa fa-trash"></i></a>
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

<form id="form-delete" class="d-none" method="post" action="{{ route('admin.absent.delete') }}">
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