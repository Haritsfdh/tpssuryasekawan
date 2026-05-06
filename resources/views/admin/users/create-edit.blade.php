@extends('layouts.master')

@section('title') User @endsection
@section('page_name') <i class="fas fa-user"></i> User Data @endsection
@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      @if (count($errors) > 0)
        <div class="row">
          <div class="col-12">
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
          </div>
        </div>
      @endif
      @if($user->id != '')
        <form id="formProfile" 
              action="/administrator/users/{{$user->id}}" 
              method="post" 
              enctype="multipart/form-data">        
          @method('PUT')
      @else
        <form id="formProfile" 
              action="/administrator/users"
              method="post"
              enctype="multipart/form-data">   
      @endif      
        @include('forms.user')
      </form>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection

@section('footer')
<script>
  function validatePass(pass) {
    var upperCase= new RegExp('[A-Z]');
    var lowerCase= new RegExp('[a-z]');
    var numbers = new RegExp('[0-9]');
    var regSym = /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
    var confirmPass = $('#confirmPassword').val();

    if(pass.length < 12){
      toastr.error("Minimum 12 Characters for password.", "Failed!", {timeOut: 3000, closeButton: true,progressBar: true});

      return false;
    }
    if(!upperCase.test(pass)){
      toastr.error("Characters must contain Upper Case Letter.", "Failed!", {timeOut: 3000, closeButton: true,progressBar: true});

      return false;
    }
    if(!lowerCase.test(pass)){
      toastr.error("Characters must contain Lower Case Letter.", "Failed!", {timeOut: 3000, closeButton: true,progressBar: true});

      return false;
    }
    if(!numbers.test(pass)){
      toastr.error("Characters must contain Number.", "Failed!", {timeOut: 3000, closeButton: true,progressBar: true});

      return false;
    }
    if(!regSym.test(pass)){
      toastr.error("Characters must contain Symbol.", "Failed!", {timeOut: 3000, closeButton: true,progressBar: true});

      return false;
    }

    if(pass != confirmPass){
      toastr.error("Please input same password in Confirmation Password.", "Failed!", {timeOut: 3000, closeButton: true,progressBar: true});

      return false;
    }

    return btoa(pass);
  }
  jQuery(document).ready(function(){
    $(document).on('click', '.eye1', function(){
      $('#inputPassword').get(0).type = 'text';      
      $(this).addClass('d-none');
      $('.slash1').removeClass('d-none');
    });
    $(document).on('click', '.slash1', function(){
      $('#inputPassword').get(0).type = 'password';      
      $(this).addClass('d-none');
      $('.eye1').removeClass('d-none');
    });
    $(document).on('click', '.eye2', function(){
      $('#confirmPassword').get(0).type = 'text';      
      $(this).addClass('d-none');
      $('.slash2').removeClass('d-none');
    });
    $(document).on('click', '.slash2', function(){
      $('#confirmPassword').get(0).type = 'password';      
      $(this).addClass('d-none');
      $('.eye2').removeClass('d-none');
    });
    $(document).on('click', '#btnSubmit', function(e){
      e.preventDefault();
      var pass = $('#inputPassword').val();
      if(pass != ''){
        var validatedPass = validatePass(pass);
        if(!validatedPass){
          return false;
        }
        $('#formProfile').submit();
      }

      $('#formProfile').submit();

    });
  });
</script>
@endsection