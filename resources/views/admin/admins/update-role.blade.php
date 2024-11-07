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
              <li class="breadcrumb-item"><a href="#">Home</a></li>
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
            @if(Session::has('success_message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Success :</strong>{{Session::get('success_message')}} 
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
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

                <form name="subadminform" id="subadminform" action="{{url('admin/update-role',$id)}}"  method="POST"  >
                    @csrf
                    <input type="hidden" name="subadmin_id" value="{{$id}}">
                    {{-- @if(!empty($subadminroles))
                      @foreach($subadminroles as $role)
                        @if($role['module']=="cmspages")
                          @if($role['view_access']==1)
                              @php $viewpage="checked" @endphp
                          @else
                              @php $viewpage="" @endphp
                          @endif
                          @if($role['edit_access']==1)
                              @php $editpage="checked" @endphp
                          @else
                              @php $editpage="" @endphp
                          @endif
                          @if($role['fill_access']==1)
                              @php $fullpage="checked" @endphp
                          @else
                              @php $fullpage="" @endphp
                          @endif
                        @endif
                      @endforeach
                    @endif --}}
                    <div class="card-body">
                 
                      <div class="form-group col-6">
                        <label for="mobile">Admins  &nbsp;&nbsp;</label>
                        @if($subadminroles->where('module', 'admins')->isEmpty())
                            <!-- في حالة عدم وجود صلاحيات للمنتجات -->
                            <input type="checkbox" name="admins[view]" value="1">View Access&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="admins[edit]" value="1">View /Edit Access&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="admins[full]" value="1">Full Access&nbsp;&nbsp;&nbsp;
                        @else
                            @foreach($subadminroles as $subadminrole)
                                @if($subadminrole->module == "admins")
                                    <input type="checkbox" name="admins[view]" value="1" @if(isset($subadminrole->view_access)) {{ $subadminrole->view_access == 1 ? "checked" : "" }} @endif >View Access&nbsp;&nbsp;&nbsp;
                                    <input type="checkbox" name="admins[edit]" value="1" @if(isset($subadminrole->edit_access)) {{ $subadminrole->edit_access == 1 ? "checked" : "" }} @endif >View /Edit Access&nbsp;&nbsp;&nbsp;
                                    <input type="checkbox" name="admins[full]" value="1" @if(isset($subadminrole->full_access)) {{ $subadminrole->full_access == 1 ? "checked" : "" }} @endif >Full Access&nbsp;&nbsp;&nbsp;
                                @endif
                            @endforeach
                        @endif
                      </div>
                      <div class="form-group col-6">
                        <label for="mobile">Users  &nbsp;&nbsp;</label>
                        @if($subadminroles->where('module', 'users')->isEmpty())
                            <!-- في حالة عدم وجود صلاحيات للمنتجات -->
                            <input type="checkbox" name="users[view]" value="1">View Access&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="users[edit]" value="1">View /Edit Access&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="users[full]" value="1">Full Access&nbsp;&nbsp;&nbsp;
                        @else
                            @foreach($subadminroles as $subadminrole)
                                @if($subadminrole->module == "users")
                                    <input type="checkbox" name="users[view]" value="1" @if(isset($subadminrole->view_access)) {{ $subadminrole->view_access == 1 ? "checked" : "" }} @endif >View Access&nbsp;&nbsp;&nbsp;
                                    <input type="checkbox" name="users[edit]" value="1" @if(isset($subadminrole->edit_access)) {{ $subadminrole->edit_access == 1 ? "checked" : "" }} @endif >View /Edit Access&nbsp;&nbsp;&nbsp;
                                    <input type="checkbox" name="users[full]" value="1" @if(isset($subadminrole->full_access)) {{ $subadminrole->full_access == 1 ? "checked" : "" }} @endif >Full Access&nbsp;&nbsp;&nbsp;
                                @endif
                            @endforeach
                        @endif
                      </div>
                      <div class="form-group col-6">
                        <label for="mobile">devices  &nbsp;&nbsp;</label>
                        @if($subadminroles->where('module', 'devices')->isEmpty())
                            <!-- في حالة عدم وجود صلاحيات للمنتجات -->
                            <input type="checkbox" name="devices[view]" value="1">View Access&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="devices[edit]" value="1">View /Edit Access&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="devices[full]" value="1">Full Access&nbsp;&nbsp;&nbsp;
                        @else
                            @foreach($subadminroles as $subadminrole)
                                @if($subadminrole->module == "devices")
                                    <input type="checkbox" name="devices[view]" value="1" @if(isset($subadminrole->view_access)) {{ $subadminrole->view_access == 1 ? "checked" : "" }} @endif >View Access&nbsp;&nbsp;&nbsp;
                                    <input type="checkbox" name="devices[edit]" value="1" @if(isset($subadminrole->edit_access)) {{ $subadminrole->edit_access == 1 ? "checked" : "" }} @endif >View /Edit Access&nbsp;&nbsp;&nbsp;
                                    <input type="checkbox" name="devices[full]" value="1" @if(isset($subadminrole->full_access)) {{ $subadminrole->full_access == 1 ? "checked" : "" }} @endif >Full Access&nbsp;&nbsp;&nbsp;
                                @endif
                            @endforeach
                        @endif
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