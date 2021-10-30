@extends('template/main')

@section('title', 'Atur Indikator Kategori')

@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-money"></i> Atur Indikator Kategori</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">Penggajian</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.salary-category.index') }}">Kategori</a></li>
            <li class="breadcrumb-item">Atur Indikator Kategori</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="tile">
                <div class="tile-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0">
                            <strong>Kategori:</strong>
                            <br>
                            {{ $salary_category->name }}
                        </li>
                        <li class="list-group-item px-0">
                            <strong>Tipe:</strong>
                            <br>
                            @if($salary_category->type_id == 1) Manual
                            @elseif($salary_category->type_id == 2) Masa Kerja (Bulan)
                            @endif
                        </li>
                        <li class="list-group-item px-0">
                            <strong>Grup:</strong>
                            <br>
                            {{ $salary_category->group ? $salary_category->group->name : '-' }}
                        </li>
                        <li class="list-group-item px-0">
                            <strong>Jabatan:</strong>
                            <br>
                            {{ $salary_category->position ? $salary_category->position->name : '-' }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-6 mt-3 mt-lg-0">
            <div class="tile">
                <div class="tile-body">
                    @if(Session::get('message') != null)
                    <div class="alert alert-dismissible alert-success">
                        <button class="close" type="button" data-dismiss="alert">×</button>{{ Session::get('message') }}
                    </div>
                    @endif
                    <div class="alert alert-warning">
                        Jika menggunakan bilangan desimal pada <b>batas bawah</b> dan <b>batas atas</b>, pastikan menggunakan titik (.), bukan koma (,).
                        <br>
                        Kosongi saja pada form batas atas jika batas atasnya tidak terhingga.
                    </div>
                    <form method="post" action="{{ route('admin.salary-category.update-indicator') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $salary_category->id }}">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th width="25%">Batas Bawah</th>
                                    <th width="25%">Batas Atas</th>
                                    <th>Jumlah</th>
                                    <th width="80">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salary_category->indicators as $key=>$indicator)
                                <tr data-id="{{ $key }}">
                                    <td>
                                        <input type="text" name="lower_range[]" class="form-control form-control-sm" value="{{ $indicator->lower_range }}" required>
                                    </td>
                                    <td>
                                        <input type="text" name="upper_range[]" class="form-control form-control-sm" value="{{ $indicator->upper_range }}">
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input type="text" name="amount[]" class="form-control form-control-sm" value="{{ $indicator->amount }}" required>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="hidden" name="ids[]" value="{{ $indicator->id }}">
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-success btn-sm btn-add-row" data-id="{{ $key }}" title="Tambah"><i class="fa fa-plus"></i></a>
                                            <a href="#" class="btn btn-danger btn-sm btn-delete-row" data-id="{{ $key }}" title="Hapus"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button class="btn btn-primary icon-btn" type="submit"><i class="fa fa-save mr-2"></i>Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection

@section('js')

<script type="text/javascript">
    // Load
    $(window).on("load", function() {
        var length = $(".table tbody tr").length;
        if(length == 0) $(".table tbody").append(starter_html());
    })

    // Button Add Row
    $(document).on("click", ".btn-add-row", function(e) {
        e.preventDefault();
        $(".table tbody").append(starter_html());
        recreate();
    });

    // Button Delete Row
    $(document).on("click", ".btn-delete-row", function(e) {
        e.preventDefault();
        var id = $(this).data("id");
        var length = $(".table tbody tr").length;
        var ask = confirm("Anda yakin ingin menghapus data ini?");
        if(ask) {
            if(length > 1) $(".table tbody tr[data-id="+id+"]").remove();
            else {
                $(".table tbody tr[data-id="+id+"]").find("input").val(null);
            }
            recreate();
        }
    });

    // Starter HTML
    function starter_html() {
        var html = '';
        html += '<tr data-id="0">';
        html += '<td><input type="text" name="lower_range[]" class="form-control form-control-sm" required></td>';
        html += '<td><input type="text" name="upper_range[]" class="form-control form-control-sm"></td>';
        html += '<td>';
        html += '<div class="input-group input-group-sm">';
        html += '<div class="input-group-prepend">';
        html += '<span class="input-group-text">Rp.</span>';
        html += '</div>';
        html += '<input type="text" name="amount[]" class="form-control form-control-sm" required>';
        html += '</div>';
        html += '</td>';
        html += '<td>';
        html += '<input type="hidden" name="ids[]">';
        html += '<div class="btn-group">';
        html += '<a href="#" class="btn btn-success btn-sm btn-add-row" data-id="0" title="Tambah"><i class="fa fa-plus"></i></a>';
        html += '<a href="#" class="btn btn-danger btn-sm btn-delete-row" data-id="0" title="Hapus"><i class="fa fa-trash"></i></a>';
        html += '</div>';
        html += '</td>';
        html += '</tr>';
        return html;
    }

    // Recreate data-id
    function recreate() {
        $(".table tbody tr").each(function(key,elem) {
            $(elem).attr("data-id",key);
            $(elem).find(".btn-add-row").attr("data-id",key);
            $(elem).find(".btn-delete-row").attr("data-id",key);
        });
    }
</script>

@endsection