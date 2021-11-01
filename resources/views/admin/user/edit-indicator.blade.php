@extends('template/main')

@section('title', 'Edit Indikator User')

@section('content')

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-user"></i> Edit Indikator User</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">User</a></li>
            <li class="breadcrumb-item">Edit Indikator User</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="tile">
                @if(count($categories) > 0)
                <h5>{{ $user->name }}</h5>
                <hr>
                <form method="post" action="{{ route('admin.user.update-indicator') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <div class="tile-body">
                        <div class="row">
                            @foreach($categories as $category)
                            <div class="form-group col-md-12">
                                <label>{{ $category->name }} <span class="text-danger">*</span></label>
                                <input type="text" name="value[{{ $category->id }}]" class="form-control {{ $errors->has('value.'.$category->id) ? 'is-invalid' : '' }}" value="{{ in_array($category->id, $user->indicators()->pluck('category_id')->toArray()) ? $user->indicators()->where('category_id','=',$category->id)->first()->value('value') : '' }}">
                                @if($errors->has('value.'.$category->id))
                                <div class="form-control-feedback text-danger">{{ ucfirst($errors->first('value.'.$category->id)) }}</div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tile-footer"><button class="btn btn-primary icon-btn" type="submit"><i class="fa fa-save mr-2"></i>Simpan</button></div>
                </form>
                @endif
            </div>
        </div>
    </div>
</main>

@endsection