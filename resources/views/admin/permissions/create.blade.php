@extends('admin.layouts.master')

@section('page-title')
  Create Permission
@endsection

@section('content')
<section class="section">
    <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Create Permission</h4>
              </div>
              <form action="{{ route('permissions.store') }}" method="POST" id="permissionAdd">
                @csrf
                <input type="hidden" name="guard_name" value="web">
                <div class="card-body">
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Name</label>
                  <div class="col-sm-12 col-md-7">
                    <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                    <span class="invalid-feedback" role="alert" id="nameError"><strong></strong></span>
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                  <div class="col-sm-12 col-md-7">
                    <button type="submit" id="submit" class="btn btn-primary">Create</button>
                  </div>
                </div>
              </div>
              </form>
            </div>
          </div>
        </div>
    </div>
</section>

<!-- Permission Creation Start -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<script type = "text/javascript">
  $(document).ready(function(e) {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

      $('#permissionAdd').submit(function(e) {
          e.preventDefault();

          $('.invalid-feedback').children("strong").text("");
          $('#permissionAdd input').removeClass('is-invalid');
          $('#permissionAdd select').removeClass('is-invalid');

          $('#submit').html('Please Wait...');
          $("#submit").attr("disabled", true);

          var formData = new FormData(this);
          $.ajax({
              type: 'POST',
              url: "{{ route('permissions.store') }}",
              data: formData,
              cache: false,
              contentType: false,
              processData: false,
              success:function(response){
               if (response == 1) {
                  toastr.success("Permission Created Successfully");
                  window.location.href = "{{ route('permissions.index') }}";
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
                         $("#permissionAdd #" + key).addClass("is-invalid");
                         $("#permissionAdd #" + key + "Error").children("strong").text(errors[
                             key][0]);
                     });
                 } else {
                     window.location.reload();
                 }
                $('#submit').html('Create');
                $('#submit').attr("disabled", false);
             }
          });
      });
  }); 
</script>
<!-- Permission Creation End -->
@endsection