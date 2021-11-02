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
    @if(Request::query('role') == 'member')
    <div class="row">
        <div class="col-lg-auto mx-auto">
            <div class="tile">
                <div class="tile-body">
                    <form id="form-filter" class="form-inline" method="get" action="">
                        <input type="hidden" name="role" value="{{ Request::query('role') }}">
                        @if(Auth::user()->role == role('super-admin'))
                        <select name="group" id="group" class="form-control form-control-sm mb-2 mr-sm-2">
                            <option value="0">Semua Grup</option>
                            @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ isset($_GET) && isset($_GET['group']) && $_GET['group'] == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                            @endforeach
                        </select>
                        @endif
                        <select name="office" id="office" class="form-control form-control-sm mb-2 mr-sm-2">
                            <option value="" disabled selected>--Pilih Kantor--</option>
                            @if(Auth::user()->role == role('super-admin'))
                                @if(isset($_GET) && isset($_GET['group']) && $_GET['group'] != 0)
                                    @foreach(\App\Models\Group::find($_GET['group'])->offices as $office)
                                    <option value="{{ $office->id }}" {{ isset($_GET) && isset($_GET['office']) && $_GET['office'] == $office->id ? 'selected' : '' }}>{{ $office->name }}</option>
                                    @endforeach
                                @endif
                            @elseif(Auth::user()->role == role('admin') || Auth::user()->role == role('manager'))
                                @foreach(\App\Models\Group::find(Auth::user()->group_id)->offices as $office)
                                <option value="{{ $office->id }}" {{ isset($_GET) && isset($_GET['office']) && $_GET['office'] == $office->id ? 'selected' : '' }}>{{ $office->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <select name="position" id="position" class="form-control form-control-sm mb-2 mr-sm-2">
                            <option value="" disabled selected>--Pilih Jabatan--</option>
                            @if(Auth::user()->role == role('super-admin'))
                                @if(isset($_GET) && isset($_GET['group']) && $_GET['group'] != 0)
                                    @foreach(\App\Models\Group::find($_GET['group'])->positions as $position)
                                    <option value="{{ $position->id }}" {{ isset($_GET) && isset($_GET['position']) && $_GET['position'] == $position->id ? 'selected' : '' }}>{{ $position->name }}</option>
                                    @endforeach
                                @endif
                            @elseif(Auth::user()->role == role('admin') || Auth::user()->role == role('manager'))
                                @foreach(\App\Models\Group::find(Auth::user()->group_id)->positions as $position)
                                <option value="{{ $position->id }}" {{ isset($_GET) && isset($_GET['position']) && $_GET['position'] == $position->id ? 'selected' : '' }}>{{ $position->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary btn-submit mb-2" {{ Request::query('office') != null && Request::query('position') != null ? '' : 'disabled' }}>Filter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-title-w-btn">
                    <div></div>
                    <div>
                        <a class="btn btn-sm btn-primary" href="{{ route('admin.user.create') }}"><i class="fa fa-lg fa-plus"></i> Tambah User</a>
                    </div>
                </div>
                <div class="tile-body">
                    @if(Session::get('message'))
                    <div class="alert alert-dismissible alert-success">
                        <button class="close" type="button" data-dismiss="alert">×</button>{{ Session::get('message') }}
                    </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-bordered" id="table">
                            <thead>
                                <tr>
                                    <th rowspan="{{ Request::query('role') == 'member' && Request::query('office') != null && Request::query('position') != null && count($categories) > 0 ? 2 : 1 }}" width="20"></th>
                                    <th rowspan="{{ Request::query('role') == 'member' && Request::query('office') != null && Request::query('position') != null && count($categories) > 0 ? 2 : 1 }}">Identitas</th>
                                    @if(Request::query('office') == null && Request::query('position') == null)
                                    <th rowspan="{{ Request::query('role') == 'member' && Request::query('office') != null && Request::query('position') != null && count($categories) > 0 ? 2 : 1 }}">Kantor, Jabatan</th>
                                    @endif
                                    @if(Request::query('role') == 'member')
                                        <th rowspan="{{ Request::query('office') != null && Request::query('position') != null && count($categories) > 0 ? 2 : 1 }}" width="80">Tanggal Kontrak</th>
                                        <th rowspan="{{ Request::query('office') != null && Request::query('position') != null && count($categories) > 0 ? 2 : 1 }}" width="80">Masa Kerja (Bulan)</th>
                                        <th rowspan="{{ Request::query('office') != null && Request::query('position') != null && count($categories) > 0 ? 2 : 1 }}" width="80">Kehadiran per Bulan</th>
                                        @if(Request::query('office') != null && Request::query('position') != null)
                                            @if(count($categories) > 0)
                                            <th colspan="{{ count($categories) }}">Rincian Gaji (Rp.)</th>
                                            @endif
                                            <th rowspan="{{ count($categories) > 0 ? 2 : 1 }}" width="80">Total (Rp.)</th>
                                        @endif
                                    @endif
                                    <th rowspan="{{ Request::query('role') == 'member' && Request::query('office') != null && Request::query('position') != null && count($categories) > 0 ? 2 : 1 }}" width="40">Opsi</th>
                                </tr>
                                @if(Request::query('role') == 'member' && Request::query('office') != null && Request::query('position') != null && count($categories) > 0)
                                    <tr>
                                        @foreach($categories as $category)
                                        <th width="80">{{ $category->name }}</th>
                                        @endforeach
                                    </tr>
                                @endif
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
                                        @if(Request::query('office') == null && Request::query('position') == null)
                                        <td>
                                            @if($user->role == role('super-admin'))
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
                                        @endif
                                        @if(Request::query('role') == 'member')
                                            <td>
                                                <span class="d-none">{{ $user->end_date == null ? 1 : 0 }} {{ $user->start_date }}</span>
                                                @if($user->end_date == null)
                                                    {{ date('d/m/Y', strtotime($user->start_date)) }}
                                                @else
                                                    <span class="badge badge-danger">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td align="right">{{ $user->end_date == null ? number_format($user->period,1,'.',',') : '' }}</td>
                                            <td align="right">{{ $user->end_date == null ? number_format($user->attendances,0,'.',',') : '' }}</td>
                                            @if(Request::query('office') != null && Request::query('position') != null)
                                                @if(count($user->salaries) > 0)
                                                    @foreach($user->salaries as $salary)
                                                    <td align="right">{{ number_format($salary,0,',',',') }}</td>
                                                    @endforeach
                                                @endif
                                                <td align="right">{{ number_format(array_sum($user->salaries),0,',',',') }}</td>
                                            @endif
                                        @endif
                                        <td align="center">
                                            <div class="btn-group">
                                                @if($user->role == role('member'))
                                                <a href="{{ route('admin.user.edit-indicator', ['id' => $user->id]) }}" class="btn btn-info btn-sm" title="Edit Indikator"><i class="fa fa-cog"></i></a>
                                                @endif
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

    // Change Group
    $(document).on("change", "#group", function() {
        var group = $(this).val();
        $.ajax({
            type: "get",
            url: "{{ route('api.office.index') }}",
            data: {group: group},
            success: function(result){
                var html = '<option value="" disabled selected>--Pilih Kantor--</option>';
                $(result).each(function(key,value){
                    html += '<option value="' + value.id + '">' + value.name + '</option>';
                });
                $("#office").html(html);
            }
        });
        $.ajax({
            type: 'get',
            url: "{{ route('api.position.index') }}",
            data: {group: group},
            success: function(result){
                var html = '<option value="" disabled selected>--Pilih Jabatan--</option>';
                $(result).each(function(key,value){
                    html += '<option value="' + value.id + '">' + value.name + '</option>';
                });
                $("#position").html(html);
            }
        });
        $("#form-filter").find("button[type=submit]").attr("disabled","disabled");
    });

    // Change the Office and Position
    $(document).on("change", "#office, #position", function() {
        var office = $("#office").val();
        var position = $("#position").val();
        if(office !== null && position !== null)
            $("#form-filter").find("button[type=submit]").removeAttr("disabled");
        else
            $("#form-filter").find("button[type=submit]").attr("disabled","disabled");
    });
</script>

@endsection