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

                <form name="subadminform" id="subadminform" @if(empty($subadmindata->id)) action="{{url('admin/add-edit-admin')}}" @else action="{{url('admin/add-edit-admin',$subadmindata->id)}}" @endif method="POST" enctype="multipart/form-data" >
                    @csrf
                    <div class="card-body">
                      <div class="form-group col-6">
                        <label for="name">Name*</label>
                        <input type="text" class="form-control" value="{{$subadmindata->name ?? ""}}" id="name" name="name" placeholder="Enter Name">
                      </div>
                      <div class="form-group col-6">
                        <label for="email">Email*</label>
                        <input style="background-color: #d4d4d4" type="email" @if($subadmindata->id !="") disabled="" @else required @endif  class="form-control" value="{{$subadmindata->email ?? ""}}"  id="email" name="email" placeholder="email">
                      </div>
                    
                      <div class="form-group col-6">
                        <label for="mobile">Phone</label>
                        <input type="text" class="form-control" value="{{$subadmindata->phone ?? ""}}" id="mobile" name="phone" placeholder="Enter Meta mobile">
                      </div>    

                      <div class="form-group col-6">
                        <label for="mobile">City</label>
                        <input type="text" class="form-control" value="{{$subadmindata->city ?? ""}}" id="mobile" name="city" placeholder="Enter Meta mobile">
                      </div>    
                      <div class="form-group col-6">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" value="{{$subadmindata->password ?? ""}}" id="password" name="password" placeholder="Enter Meta Description">
                    </div>
                        @if(!empty($subadmindata->image))
                        <div class="form-group">
                          <img src="{{asset('storage/'.$subadmindata->image)}}" class="img-thumbnail" width="200px" height="200px" alt="...">
                          
                          <input type="hidden" value="{{$subadmindata->image}}" name="currentadminimage">
                        </div>
                        @endif
                        
                        <div class="form-group col-6">
                          <label for="image">Photo</label>
                          <input type="file"  class="form-control" id="admin_photo"  name="image">
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