@extends('layouts.layout')
@section('other')
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Settings</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Update password</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- left column -->
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Update password</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            @if(Session::has('erorr_message'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Erorr :</strong>{{Session::get('erorr_message')}} 
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            @endif
            @if(Session::has('success_message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Success :</strong>{{Session::get('success_message')}} 
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            @endif
            <form action="{{url('admin/update-password')}}" method="POST">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Email address</label>
                  <input type="email" value="{{Auth::guard('admin')->user()->email}}" class="form-control" id="exampleInputEmail1" readonly="">
                </div>
                <div class="form-group">
                  <label for="currentpassword">Current Password</label>
                  <input type="password" class="form-control" name="currentpassword" id="currentpassword" placeholder="Password">
                  <span id="check_password"></span>
                </div>
                <div class="form-group">
                  <label for="newpassword">New Password</label>
                  <input type="password" class="form-control" name="newpassword" id="newpassword" placeholder="Password">
                </div>
                <div class="form-group">
                  <label for="confirmpassword">Confirm New Password</label>
                  <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" placeholder="Password">
                </div>
              
              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
          <!-- /.card -->


        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
{{-- @section('script')
<script src="{{asset('admin/js/custom.js')}}"></script>
@endsection --}}
@endsection