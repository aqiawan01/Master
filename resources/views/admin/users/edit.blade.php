@extends('admin.layouts.master')

@section('page-title')
  Edit User
@endsection

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Edit User</h4>
              </div>
              {!! Form::model($user, ['method' => 'PUT','route' => ['users.update', $user->id], 'id' => 'userUpdate']) !!}
                <div class="card-body">
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Name</label>
                  <div class="col-sm-12 col-md-7">
                    {!! Form::text('name', null, array('placeholder' => 'Name', 'id' => 'name', 'class' => 'form-control')) !!}
                    <span class="invalid-feedback" role="alert" id="nameError"><strong></strong></span>
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email</label>
                  <div class="col-sm-12 col-md-7">
                    {!! Form::text('email', null, array('placeholder' => 'Email', 'id' => 'email', 'class' => 'form-control')) !!}
                    <span class="invalid-feedback" role="alert" id="emailError"><strong></strong></span>
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Password</label>
                  <div class="col-sm-12 col-md-7">
                    {!! Form::password('password', array('placeholder' => 'Password', 'id' => 'password', 'class' => 'form-control')) !!}
                    <span class="invalid-feedback" role="alert" id="passwordError"><strong></strong></span>
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Confirm Password</label>
                  <div class="col-sm-12 col-md-7">
                    {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Select Role</label>
                  <div class="col-sm-12 col-md-7">
                    {!! Form::select('roles[]', $roles,$userRole, array('class' => 'form-control selectric', 'id' => 'roles')) !!}
                    <span class="invalid-feedback" role="alert" id="rolesError"><strong></strong></span>
                  </div>
                </div>
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

<!-- User Update Start -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<script type = "text/javascript">
  $(document).ready(function(e) {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

      $('#userUpdate').submit(function(e) {
          e.preventDefault();

          $('.invalid-feedback').children("strong").text("");
          $('#userUpdate input').removeClass('is-invalid');
          $('#userUpdate select').removeClass('is-invalid');

          $('#submit').html('Please Wait...');
          $("#submit").attr("disabled", true);

          var formData = new FormData(this);
          $.ajax({
              type: 'POST',
              url: "{{ route('users.update',$user->id) }}",
              data: formData,
              cache: false,
              contentType: false,
              processData: false,
              success:function(response){
               if (response == 1) {
                  toastr.success("User Updated Successfully");
                  window.location.href = "{{ route('users.index') }}";
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
                         $("#userUpdate #" + key).addClass("is-invalid");
                         $("#userUpdate #" + key + "Error").children("strong").text(errors[
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
<!-- User Update End -->
@endsection