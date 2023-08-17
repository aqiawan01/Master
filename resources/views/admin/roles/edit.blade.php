@extends('admin.layouts.master')

@section('page-title')
  Edit Role
@endsection

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Edit Role</h4>
              </div>
              {!! Form::model($role, ['method' => 'PUT','route' => ['roles.update', $role->id], 'id' => 'roleUpdate']) !!}
                <div class="card-body">
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Role Name</label>
                  <div class="col-sm-12 col-md-7">
                      {!! Form::text('name', null, array('placeholder' => 'Name', 'id' => 'name', 'class' => 'form-control')) !!}
                      <span class="invalid-feedback" role="alert" id="nameError"><strong></strong></span>
                  </div>
                </div>
                <table class="table table-bordered table-striped text-center mb-3 table-responsive-xl">
                    <thead>
                    <tr>
                        <th>Model</th>
                        <th>List</th>
                        <th>Create</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Roles</td>
                            <td>
                                <div class="pretty p-icon p-jelly p-round p-bigger">
                                  {{ Form::checkbox('permission[role-list]', 'role-list', $role->hasPermissionTo('role-list')) }}
                                  <div class="state p-info">
                                    <i class="icon material-icons">done</i>
                                    <label>List</label>
                                  </div>
                                </div>
                            </td>
                            <td>
                               <div class="pretty p-icon p-jelly p-round p-bigger">
                                  {{ Form::checkbox('permission[role-create]', 'role-create', $role->hasPermissionTo('role-create')) }}
                                  <div class="state p-info">
                                    <i class="icon material-icons">done</i>
                                    <label>Create</label>
                                  </div>
                                </div> 
                            </td>
                            <td>
                                <div class="pretty p-icon p-jelly p-round p-bigger">
                                  {{ Form::checkbox('permission[role-edit]', 'role-edit', $role->hasPermissionTo('role-edit')) }}
                                  <div class="state p-info">
                                    <i class="icon material-icons">done</i>
                                    <label>Edit</label>
                                  </div>
                                </div>
                            </td>
                            <td>
                               <div class="pretty p-icon p-jelly p-round p-bigger">
                                  {{ Form::checkbox('permission[role-delete]', 'role-delete', $role->hasPermissionTo('role-delete')) }}
                                  <div class="state p-info">
                                    <i class="icon material-icons">done</i>
                                    <label>Delete</label>
                                  </div>
                                </div> 
                            </td>
                        </tr>
                        <tr>
                            <td>Permissions</td>
                            <td>
                                <div class="pretty p-icon p-jelly p-round p-bigger">
                                  {{ Form::checkbox('permission[permission-list]', 'permission-list', $role->hasPermissionTo('permission-list')) }}
                                  <div class="state p-info">
                                    <i class="icon material-icons">done</i>
                                    <label>List</label>
                                  </div>
                                </div>
                            </td>
                            <td>
                               <div class="pretty p-icon p-jelly p-round p-bigger">
                                  {{ Form::checkbox('permission[permission-create]', 'permission-create', $role->hasPermissionTo('permission-create')) }}
                                  <div class="state p-info">
                                    <i class="icon material-icons">done</i>
                                    <label>Create</label>
                                  </div>
                                </div> 
                            </td>
                            <td>
                                <div class="pretty p-icon p-jelly p-round p-bigger">
                                  {{ Form::checkbox('permission[permission-edit]', 'permission-edit', $role->hasPermissionTo('permission-edit')) }}
                                  <div class="state p-info">
                                    <i class="icon material-icons">done</i>
                                    <label>Edit</label>
                                  </div>
                                </div>
                            </td>
                            <td>
                               <div class="pretty p-icon p-jelly p-round p-bigger">
                                  {{ Form::checkbox('permission[permission-delete]', 'permission-delete', $role->hasPermissionTo('permission-delete')) }}
                                  <div class="state p-info">
                                    <i class="icon material-icons">done</i>
                                    <label>Delete</label>
                                  </div>
                                </div> 
                            </td>
                        </tr>
                        <tr>
                            <td>Users</td>
                            <td>
                                <div class="pretty p-icon p-jelly p-round p-bigger">
                                  {{ Form::checkbox('permission[user-list]', 'user-list', $role->hasPermissionTo('user-list')) }}
                                  <div class="state p-info">
                                    <i class="icon material-icons">done</i>
                                    <label>List</label>
                                  </div>
                                </div>
                            </td>
                            <td>
                               <div class="pretty p-icon p-jelly p-round p-bigger">
                                  {{ Form::checkbox('permission[user-create]', 'user-create', $role->hasPermissionTo('user-create')) }}
                                  <div class="state p-info">
                                    <i class="icon material-icons">done</i>
                                    <label>Create</label>
                                  </div>
                                </div> 
                            </td>
                            <td>
                                <div class="pretty p-icon p-jelly p-round p-bigger">
                                  {{ Form::checkbox('permission[user-edit]', 'user-edit', $role->hasPermissionTo('user-edit')) }}
                                  <div class="state p-info">
                                    <i class="icon material-icons">done</i>
                                    <label>Edit</label>
                                  </div>
                                </div>
                            </td>
                            <td>
                               <div class="pretty p-icon p-jelly p-round p-bigger">
                                  {{ Form::checkbox('permission[user-delete]', 'user-delete', $role->hasPermissionTo('user-delete')) }}
                                  <div class="state p-info">
                                    <i class="icon material-icons">done</i>
                                    <label>Delete</label>
                                  </div>
                                </div> 
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                  <div class="col-sm-12 col-md-7">
                    <button type="submit" id="submit" class="btn btn-primary">Update</button>
                  </div>
                </div>
              </div>
              {!! Form::close() !!}
            </div>
          </div>
        </div>
    </div>
</section>

<!-- Role Update Start -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<script type = "text/javascript">
  $(document).ready(function(e) {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

      $('#roleUpdate').submit(function(e) {
          e.preventDefault();

          $('.invalid-feedback').children("strong").text("");
          $('#roleUpdate input').removeClass('is-invalid');
          $('#roleUpdate select').removeClass('is-invalid');

          $('#submit').html('Please Wait...');
          $("#submit").attr("disabled", true);

          var formData = new FormData(this);
          $.ajax({
              type: 'POST',
              url: "{{ route('roles.update',$role->id) }}",
              data: formData,
              cache: false,
              contentType: false,
              processData: false,
              success:function(response){
               if (response == 1) {
                  toastr.success("Role Updated Successfully");
                  window.location.href = "{{ route('roles.index') }}";
                  $('#submit').html('Finish');
                  $("#submit").attr("disabled", false);
               }
             },
             error: function(response) {
                $.each(response.responseJSON.errors, function (k, v) {
                     toastr.error(v[0]);
                 });
                if (response.status === 422) {
                     let errors = response.responseJSON.errors;
                     Object.keys(errors).forEach(function(key) {
                         $("#roleUpdate #" + key).addClass("is-invalid");
                         $("#roleUpdate #" + key + "Error").children("strong").text(errors[
                             key][0]);
                     });
                 } else {
                     window.location.reload();
                 }
                $('#submit').html('Update');
                $('#submit').attr("disabled", false);
             }
          });
      });
  }); 
</script>
<!-- Role Update End -->
@endsection