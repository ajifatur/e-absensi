@extends('template/main')

@section('title', 'Kelola Jam Kerja')

@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-clock-o"></i> Kelola Jam Kerja</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.work-hour.index') }}">Jam Kerja</a></li>
            <li class="breadcrumb-item">Kelola Jam Kerja</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-title-w-btn">
                    <div></div>
                    <div class="btn-group">
                        <a class="btn btn-sm btn-primary" href="{{ route('admin.work-hour.create') }}"><i class="fa fa-lg fa-plus"></i> Tambah Data</a>
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
                                    <th>Jam Kerja</th>
                                    <th width="150">Kantor</th>
                                    <th width="150">Jabatan</th>
                                    <th width="60">Kuota</th>
                                    <th width="150">Grup</th>
                                    <th width="40">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($work_hours as $work_hour)
                                    <tr>
                                        <td align="center"><input type="checkbox"></td>
                                        <td>
                                            {{ $work_hour->name }}<br>
                                            <small class="text-muted">{{ date('H:i', strtotime($work_hour->start_at)) }} - {{ date('H:i', strtotime($work_hour->end_at)) }}</small>
                                        </td>
                                        <td>
                                            @if($work_hour->office)
                                                <a href="{{ route('admin.office.detail', ['id' => $work_hour->office->id]) }}">{{ $work_hour->office->name }}</a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($work_hour->position)
                                                <a href="{{ route('admin.position.detail', ['id' => $work_hour->position->id]) }}">{{ $work_hour->position->name }}</a>
                                            @endif
                                        </td>
                                        <td align="right">{{ number_format($work_hour->quota,0,',',',') }}</td>
                                        <td>
                                            @if($work_hour->group)
                                                <a href="{{ route('admin.group.detail', ['id' => $work_hour->group->id]) }}">{{ $work_hour->group->name }}</a>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ $work_hour->name != 'Head work_hour' ? route('admin.work-hour.edit', ['id' => $work_hour->id]) : '#' }}" class="btn btn-warning btn-sm" style="{{ $work_hour->name != 'Head work_hour' ? '' : 'cursor: not-allowed' }}" title="{{ $work_hour->name != 'Head work_hour' ? 'Edit' : 'Tidak diizinikan mengedit data ini' }}"><i class="fa fa-edit"></i></a>
                                                <a href="#" class="btn btn-danger btn-sm {{ $work_hour->name != 'Head work_hour' ? 'btn-delete' : '' }}" data-id="{{ $work_hour->id }}" style="{{ $work_hour->name != 'Head work_hour' ? '' : 'cursor: not-allowed' }}" title="{{ $work_hour->name != 'Head work_hour' ? 'Hapus' : 'Tidak diizinikan menghapus data ini' }}"><i class="fa fa-trash"></i></a>
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

<form id="form-delete" class="d-none" method="post" action="{{ route('admin.work-hour.delete') }}">
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