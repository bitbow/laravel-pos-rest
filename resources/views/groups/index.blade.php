@extends('layouts.admin')

@section('title', __('group.Group_List'))
@section('content-header', __('group.Group_List'))
@section('content-actions')
<a href="{{route('groups.create')}}" class="btn btn-primary">{{ __('group.Create_Group') }}</a>
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endsection
@section('content')
<div class="card product-list">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('group.ID') }}</th>
                    <th>{{ __('group.Name') }}</th>
                    <th>{{ __('group.Image') }}</th>
                    <th>{{ __('group.Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groups as $group)
                <tr>
                    <td>{{$group->id}}</td>
                    <td>{{$group->name}}</td>
                    <td><img class="product-img" src="{{ Storage::url($group->image) }}" alt=""></td> 
                    <td>
                        <span class="right badge badge-{{ $group->status ? 'success' : 'danger' }}">{{$group->status ? __('common.Active') : __('common.Inactive') }}</span>
                    </td>
                    <td>
                        <a href="{{ route('groups.edit', $group) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-danger btn-delete" data-url="{{route('groups.destroy', $group)}}"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $groups->render() }}
    </div>
</div>
@endsection

@section('js')
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script type="module">
        $(document).ready(function() {
            $(document).on('click', '.btn-delete', function() {
                var $this = $(this);
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: '{{ __('group.sure') }}',
                    text: '{{ __('group.really_delete') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('group.yes_delete') }}',
                    cancelButtonText: '{{ __('group.No') }}',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post($this.data('url'), {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        }, function(res) {
                            $this.closest('tr').fadeOut(500, function() {
                                $(this).remove();
                            });
                        });
                    }
                });
            });
        });
    </script>

@endsection
