@extends('template/main')

@section('title', 'Edit Jam Kerja')

@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-clock-o"></i> Edit Jam Kerja</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.work-hour.index') }}">Jam Kerja</a></li>
            <li class="breadcrumb-item">Edit Jam Kerja</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="tile">
                <form method="post" action="{{ route('admin.work-hour.update') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $work_hour->id }}">
                    <div class="tile-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Nama Jam Kerja <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-10">
                                <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ $work_hour->name }}">
                                @if($errors->has('name'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('name')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Kantor <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <select name="office_id" class="form-control {{ $errors->has('office_id') ? 'is-invalid' : '' }}" id="office">
                                    <option value="" selected>--Pilih--</option>
                                    @foreach(\App\Models\Group::find($work_hour->group_id)->offices as $office)
                                    <option value="{{ $office->id }}" {{ $work_hour->office_id == $office->id ? 'selected' : '' }}>{{ $office->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('office_id'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('office_id')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Jabatan <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <select name="position_id" class="form-control {{ $errors->has('position_id') ? 'is-invalid' : '' }}" id="position">
                                    <option value="" selected>--Pilih--</option>
                                    @foreach(\App\Models\Group::find($work_hour->group_id)->positions as $position)
                                    <option value="{{ $position->id }}" {{ $work_hour->position_id == $position->id ? 'selected' : '' }}>{{ $position->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('position_id'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('position_id')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Kuota <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <input type="text" name="quota" class="form-control {{ $errors->has('quota') ? 'is-invalid' : '' }}" value="{{ $work_hour->quota }}">
                                @if($errors->has('quota'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('quota')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Jam Mulai <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <input type="text" name="start_at" class="form-control clockpicker {{ $errors->has('start_at') ? 'is-invalid' : '' }}" value="{{ date('H:i', strtotime($work_hour->start_at)) }}" autocomplete="off">
                                @if($errors->has('start_at'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('start_at')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label">Jam Selesai <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-4">
                                <input type="text" name="end_at" class="form-control clockpicker {{ $errors->has('end_at') ? 'is-invalid' : '' }}" value="{{ date('H:i', strtotime($work_hour->end_at)) }}" autocomplete="off">
                                @if($errors->has('end_at'))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('end_at')) }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="tile-footer"><button class="btn btn-primary icon-btn" type="submit"><i class="fa fa-save mr-2"></i>Simpan</button></div>
                </form>
            </div>
        </div>
    </div>
</main>

@endsection

@section('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/jquery-clockpicker.min.js" integrity="sha512-x0qixPCOQbS3xAQw8BL9qjhAh185N7JSw39hzE/ff71BXg7P1fkynTqcLYMlNmwRDtgdoYgURIvos+NJ6g0rNg==" crossorigin="anonymous"></script>
<script type="text/javascript">
    // Clockpicker
    $(".clockpicker").clockpicker({
        autoclose: true
    });

    // Change Group
    $(document).on("change", "#group", function() {
        var group = $(this).val();
        $.ajax({
            type: "get",
            url: "{{ route('api.office.index') }}",
            data: {group: group},
            success: function(result){
                var html = '<option value="" selected>--Pilih--</option>';
                $(result).each(function(key,value){
                    html += '<option value="' + value.id + '">' + value.name + '</option>';
                });
                $("#office").html(html).removeAttr("disabled");
            }
        });
        $.ajax({
            type: 'get',
            url: "{{ route('api.position.index') }}",
            data: {group: group},
            success: function(result){
                var html = '<option value="" selected>--Pilih--</option>';
                $(result).each(function(key,value){
                    html += '<option value="' + value.id + '">' + value.name + '</option>';
                });
                $("#position").html(html).removeAttr("disabled");
            }
        });
    });
</script>

@endsection

@section('css')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/jquery-clockpicker.min.css" integrity="sha512-Dh9t60z8OKsbnVsKAY3RcL2otV6FZ8fbZjBrFENxFK5H088Cdf0UVQaPoZd/E0QIccxqRxaSakNlmONJfiDX3g==" crossorigin="anonymous" />

@endsection