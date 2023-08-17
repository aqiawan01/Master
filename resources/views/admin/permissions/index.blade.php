@extends('admin.layouts.master')

@section('page-title')
  Manage Permissions
@endsection

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Permissions</h4>
              </div>
              @can('permission-create')
              <div class="card-header">
                <a class="btn btn-primary" href="{{ route('permissions.create') }}">Create Permission</a>
              </div>
              @endcan
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-striped" id="table-1">
                    <thead>
                      <tr>
                          <th>No</th>
                          <th>Name</th>
                          <th>Created At</th>
                          <th width="280px">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($permissions as $permission)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $permission->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($permission->created_at)->diffForHumans() }}</td>
                        <td>
                            @can('permission-edit')
                            <a class="btn btn-primary" href="{{ route('permissions.edit',$permission->id) }}">Edit</a>
                            @endcan
                            @can('permission-delete')
                            <button class="btn btn-danger" type="button" onclick="deleteItem({{ $permission->id }})">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                            <form id="delete-form-{{ $permission->id }}" action="{{ route('permissions.destroy', $permission->id) }}" method="post"
                                  style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            @endcan
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
    </div>
</section>
@endsection

@push('js')
    <!-- Sweet Alert Js -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.29.1/dist/sweetalert2.all.min.js"></script>

    <script type="text/javascript">
        function deleteItem(id) {
            const swalWithBootstrapButtons = swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
            })

            swalWithBootstrapButtons({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    event.preventDefault();
                    document.getElementById('delete-form-'+id).submit();
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons(
                        'Cancelled',
                        'Your data is safe :)',
                        'error'
                    )
                }
            })
        }
    </script>
@endpush