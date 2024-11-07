@extends('layouts.layout')
@section('other')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
         
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">{{$title}}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->


        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">{{$title}}</h3>


          </div>
          <!-- /.card-header -->
          <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(Session::has('error_message'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Error :</strong>{{Session::get('error_message')}} 
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            @endif            
            <div class="row">
              <div class="col-12">

                <form name="devices" id="devices" @if(empty($device->id)) action="{{url('admin/add-edit-device')}}" @else action="{{url('admin/add-edit-device',$device->id)}}" @endif method="POST" enctype="multipart/form-data" >
                    @csrf
                    <div class="card-body">
                      <div class="form-group col-6">
                        <label for="name">Serial Number*</label>
                        <input type="text" class="form-control" value="{{$device->serial_number ?? ""}}" id="name" name="serial_number" placeholder="Enter Name">
                      </div>
                      
                   

                      <div class="form-group col-6">
                        <label for="brand_id">Owner Name*</label>
                        <select name="user_id" class="form-control">
                        <option  value="">select</option>
                        @foreach($users as $user)
                        <option value="{{$user->id}}" @if(!empty($device->user_id) &&  $user->id == $device->user_id) selected @endif>{{$user->name}}</option>
                        @endforeach
                      </select>
                      </div>
                 
                  
                    </div>
                    <!-- /.card-body -->
    
                    <div class="form-group col-6" >
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                  </form>
                <!-- /.form-group -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
           
          </div>
        </div>
        <!-- /.card -->


        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection