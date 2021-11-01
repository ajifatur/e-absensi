@extends('template/main')

@section('title', 'Detail Grup')

@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dot-circle-o"></i> Detail Grup</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.group.index') }}">Grup</a></li>
            <li class="breadcrumb-item">Detail Grup</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-title-w-btn">
                    <h3 class="title">Detail Grup</h3>
                    <h5>{{ $group->name }}</h5>
                </div>
                <div class="tile-body">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-kantor-tab" data-toggle="pill" href="#pills-kantor" role="tab" aria-controls="pills-kantor" aria-selected="true">Kantor <span class="badge">{{ count($group->offices) }}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-jabatan-tab" data-toggle="pill" href="#pills-jabatan" role="tab" aria-controls="pills-jabatan" aria-selected="false">Jabatan <span class="badge">{{ count($group->positions) }}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-user-tab" data-toggle="pill" href="#pills-user" role="tab" aria-controls="pills-user" aria-selected="false">User <span class="badge">{{ count($group->users) }}</span></a>
                        </li>
                    </ul>
                    <div class="tab-content py-3" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-kantor" role="tabpanel" aria-labelledby="pills-kantor-tab">
                            @if(Session::get('message') != null)
                            <div class="alert alert-dismissible alert-success">
                                <button class="close" type="button" data-dismiss="alert">×</button>{{ Session::get('message') }}
                            </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="first-table">
                                    <thead>
                                        <tr>
                                            <th width="20"><input type="checkbox"></th>
                                            <th>Nama Kantor</th>
                                            <th width="40">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($group->offices as $office)
                                            <tr>
                                                <td align="center"><input type="checkbox"></td>
                                                <td>
                                                    <a href="{{ route('admin.office.index', ['id' => $office->id]) }}">{{ $office->name }}</a>
                                                    <br>
                                                    <small class="text-muted">{{ number_format(count($office->users),0,'.','.') }} orang</small>
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
                            <form id="form-delete-kantor" class="d-none" method="post" action="{{ route('admin.office.delete') }}">
                                @csrf
                                <input type="hidden" name="id">
                            </form>
                        </div>

                        <div class="tab-pane fade" id="pills-jabatan" role="tabpanel" aria-labelledby="pills-jabatan-tab">
                            @if(Session::get('message') != null)
                            <div class="alert alert-dismissible alert-success">
                                <button class="close" type="button" data-dismiss="alert">×</button>{{ Session::get('message') }}
                            </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="20"><input type="checkbox"></th>
                                            <th>Jabatan</th>
                                            <th width="100">Jam Kerja</th>
                                            <th width="40">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($group->positions as $position)
                                            <tr>
                                                <td align="center"><input type="checkbox"></td>
                                                <td>
                                                    <a href="{{ route('admin.position.detail', ['id' => $position->id]) }}">{{ $position->name }}</a>
                                                    <br>
                                                    <small class="text-muted">{{ number_format(count($position->users),0,'.','.') }} orang</small>
                                                </td>
                                                <td>{{ $position->work_hours == 1 ? 'Full-Time' : 'Part-Time' }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ $position->name != 'Head Office' ? route('admin.position.edit', ['id' => $position->id]) : '#' }}" class="btn btn-warning btn-sm" style="{{ $position->name != 'Head Office' ? '' : 'cursor: not-allowed' }}" title="{{ $position->name != 'Head Office' ? 'Edit' : 'Tidak diizinikan mengedit data ini' }}"><i class="fa fa-edit"></i></a>
                                                        <a href="#" class="btn btn-danger btn-sm {{ $position->name != 'Head Office' ? 'btn-delete' : '' }}" data-id="{{ $position->id }}" style="{{ $position->name != 'Head Office' ? '' : 'cursor: not-allowed' }}" title="{{ $position->name != 'Head Office' ? 'Hapus' : 'Tidak diizinikan menghapus data ini' }}"><i class="fa fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <form id="form-delete-jabatan" class="d-none" method="post" action="{{ route('admin.position.delete') }}">
                                @csrf
                                <input type="hidden" name="id">
                            </form>
                        </div>
                        <div class="tab-pane fade" id="pills-user" role="tabpanel" aria-labelledby="pills-user-tab">
                            @if(Session::get('message') != null)
                            <div class="alert alert-dismissible alert-success">
                                <button class="close" type="button" data-dismiss="alert">×</button>{{ Session::get('message') }}
                            </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="20"><input type="checkbox"></th>
                                            <th>Identitas</th>
                                            <th width="150">Kantor</th>
                                            <th width="150">Jabatan</th>
                                            <th width="40">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($group->users as $user)
                                            <tr>
                                                <td align="center"><input type="checkbox"></td>
                                                <td>
                                                    <a href="{{ route('admin.user.detail', ['id' => $user->id]) }}">{{ $user->name }}</a>
                                                    <br>
                                                    <small class="text-dark">{{ $user->email }}</small>
                                                    <br>
                                                    <small class="text-muted">{{ $user->phone_number }}</small>
                                                </td>
                                                <td>
                                                    @if($user->office)
                                                        <a href="{{ route('admin.office.detail', ['id' => $user->office->id]) }}">{{ $user->office->name }}</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($user->position)
                                                        <a href="{{ route('admin.position.detail', ['id' => $user->position->id]) }}">{{ $user->position->name }}</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ route('admin.user.edit', ['id' => $user->id]) }}" class="btn btn-warning btn-sm" data-id="{{ $user->id }}" title="Edit"><i class="fa fa-edit"></i></a>
                                                        @if(Auth::user()->role == role('super-admin'))
                                                        <a href="#" class="btn btn-danger btn-sm {{ $user->id > 1 ? 'btn-delete' : '' }}" data-id="{{ $user->id }}" style="{{ $user->id > 1 ? '' : 'cursor: not-allowed' }}" title="{{ $user->id <= 1 ? $user->id == Auth::user()->id ? 'Tidak dapat menghapus akun sendiri' : 'Akun ini tidak boleh dihapus' : 'Hapus' }}"><i class="fa fa-trash"></i></a>
                                                        @elseif(Auth::user()->role == role('admin'))
                                                        <a href="#" class="btn btn-danger btn-sm {{ $user->id != Auth::user()->id ? 'btn-delete' : '' }}" data-id="{{ $user->id }}" style="{{ $user->id != Auth::user()->id ? '' : 'cursor: not-allowed' }}" title="{{ $user->id == Auth::user()->id ? 'Tidak dapat menghapus akun sendiri' : 'Hapus' }}"><i class="fa fa-trash"></i></a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <form id="form-delete-user" class="d-none" method="post" action="{{ route('admin.user.delete') }}">
                                @csrf
                                <input type="hidden" name="id">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection

@section('js')

@include('template/js/datatable')

<script type="text/javascript">
    // DataTable
    var table = DataTable("#first-table");

    // Tabs
    $(document).on("click", "#pills-tab .nav-link", function(e){
        e.preventDefault();
        var tabname = $(this).attr("href");
        table.destroy();
        table = DataTable(tabname + " .table");
    });

    // Button Delete
    $(document).on("click", ".btn-delete", function(e){
        e.preventDefault();
        var id = $(this).data("id");
        var tabpane = $(this).parents(".tab-pane");
        var tabpane_id = $(tabpane).attr("id");
        var ask = confirm("Anda yakin ingin menghapus data ini?");
        if(ask){
            $("#"+tabpane_id+" input[name=id]").val(id);
            $("#"+tabpane_id+" form").submit();
        }
    });
</script>

@endsection