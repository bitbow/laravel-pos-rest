@extends('layouts.admin')

@section('title', __('group.Edit_Group'))
@section('content-header', __('group.Edit_Group'))

@section('content')

<div class="card">
    <div class="card-body">

        <form action="{{ route('groups.update', $group) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">{{ __('group.Name') }}</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name"
                    placeholder="{{ __('group.Name') }}" value="{{ old('name', $group->name) }}">
                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div> 
            
            <div class="form-group">
                <label for="image">{{ __('group.Image') }}</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="image" id="image">
                    <label class="custom-file-label" for="image">{{ __('group.Choose_file') }}</label>
                </div>
                @error('image')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>            

            <div class="form-group">
                <label for="status">{{ __('group.Status') }}</label>
                <select name="status" class="form-control @error('status') is-invalid @enderror" id="status">
                    <option value="1" {{ old('status', $group->status) === 1 ? 'selected' : ''}}>{{ __('common.Active') }}</option>
                    <option value="0" {{ old('status', $group->status) === 0 ? 'selected' : ''}}>{{ __('common.Inactive') }}</option>
                </select>
                @error('status')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <button class="btn btn-primary" type="submit">{{ __('common.Update') }}</button>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
    $(document).ready(function () {
        bsCustomFileInput.init();
    });
</script>
@endsection