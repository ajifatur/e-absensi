@extends('template/main')

@section('title', 'Kelola User')

@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-user"></i> Kelola User</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">User</a></li>
            <li class="breadcrumb-item">Kelola User</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-title-w-btn">
                    <h3 class="title">Kelola User</h3>
                    <div class="d-flex">
                        @if((Auth::user()->role == role('admin') || Auth::user()->role == role('manager')) && $_GET['role'] == 'member')
                        <select name="office" id="office" class="form-control form-control-sm mr-2">
                            <option value="">Semua Kantor</option>
                            @foreach($offices as $office)
                            <option value="{{ $office->id }}" {{ isset($_GET['office']) && $_GET['office'] == $office->id ? 'selected' : '' }}>{{ $office->name }}</option>
                            @endforeach
                        </select>
                        @endif
                        <a class="btn btn-sm btn-primary" href="{{ route('admin.user.create') }}"><i class="fa fa-lg fa-plus"></i> Tambah User</a>
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
                                    <th>Identitas</th>
                                    <th>Kantor, Jabatan</th>
                                    <th width="40">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
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
                                        @if(Auth::user()->role == role('super-admin') && $user->role == role('super-admin'))
                                            SUPER ADMIN
                                        @else
                                            {{ in_array($user->role, [role('admin'), role('manager')]) ? strtoupper(role($user->role)) : $user->office->name }}
                                            <br>
                                            @if(Auth::user()->role == role('super-admin'))
                                            <small><a href="{{ route('admin.group.detail', ['id' => $user->group->id]) }}">{{ $user->group->name }}</a></small>
                                            <br>
                                            @endif
                                            <small class="text-muted">{{ $user->position ? $user->position->name : '' }}</small>
                                        @endif
                                        </td>
                                        <td align="center">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.user.edit', ['id' => $user->id]) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                                                @if(Auth::user()->role == role('super-admin'))
                                                <a href="#" class="btn btn-danger btn-sm {{ $user->id > 1 ? 'btn-delete' : '' }}" data-id="{{ $user->id }}" style="{{ $user->id > 1 ? '' : 'cursor: not-allowed' }}" title="{{ $user->id <= 1 ? $user->id == Auth::user()->id ? 'Tidak dapat menghapus akun sendiri' : 'Akun ini tidak boleh dihapus' : 'Hapus' }}"><i class="fa fa-trash"></i></a>
                                                @elseif(Auth::user()->role == role('admin') || Auth::user()->role == role('manager'))
                                                <a href="#" class="btn btn-danger btn-sm {{ $user->id != Auth::user()->id ? 'btn-delete' : '' }}" data-id="{{ $user->id }}" style="{{ $user->id != Auth::user()->id ? '' : 'cursor: not-allowed' }}" title="{{ $user->id == Auth::user()->id ? 'Tidak dapat menghapus akun sendiri' : 'Hapus' }}"><i class="fa fa-trash"></i></a>
                                                @endif
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

<form id="form-delete" class="d-none" method="post" action="{{ route('admin.user.delete') }}">
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

    // Change office
    $(document).on("change", "#office", function() {
        var office = $(this).val();
        if(office == '')
            window.location.href = "{{ route('admin.user.index', ['role' => $_GET['role']]) }}";
        else
            window.location.href = "{{ route('admin.user.index', ['role' => $_GET['role']]) }}" + "&office=" + office;
    });
</script>

@endsection