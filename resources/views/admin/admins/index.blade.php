@extends('layouts.layout')
@section('other')
<!-- Content Wrapper. Contains page content -->


  <!-- Content Header (Page header) -->

  <!-- /.content-header -->

    <!-- Main content -->
   
        
          <div class="row">
            <div class="col-12">
              @if(Session::has('success_message'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success :</strong>{{Session::get('success_message')}} 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              @endif
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Subadmins</h3>
                  <a class="btn btn-block btn-primary" style="max-width: 150px; float:right; display:inline-block; " href="{{url('admin/add-edit-admin')}}">Add admin</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="admins" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                      <th>Id</th>
                      <th>Name</th>
                      <th>Mobile</th>
                      <th>Email</th>
                      <th>Created on</th>
                      <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($admins as $admin)
                            
                  
                    <tr>
                      <td>#</td>
                      <td>{{$admin->name}}</td>
                      <td>{{$admin->phone}}</td>
                      <td>{{$admin->email}}</td>

                      <td>{{date("F j, Y, g:i a", strtotime($admin->created_at));}}</td>
                      <td>
                     
                        @if($adminsModule['edit_access']==1 || $adminsModule['full_access']==1)
                         <a style='color:#4586ff'; href="{{url('admin/add-edit-admin',$admin->id)}}"> <i class="fas fa-edit"></i></a>
                         &nbsp;&nbsp;
                         @endif
                         @if($adminsModule['full_access']==1)
                         <a class="confirmdelete" name="subadmin" title="Delete admin" style='color:#4586ff'; href="javascript:void{0}" record="admin" recordid={{$admin->id}} <?php /* href="{{url('admin/delete-subadmin',$subadmin->id)}}" */ ?>> <i class="fas fa-trash"></i></a>
                        &nbsp;&nbsp;
                        @endif
                        @if($adminsModule['full_access']==1)
                        <a style='color:#4586ff'; href="{{url('admin/update-role',$admin->id)}}"> <i class="fas fa-unlock"></i></a>
                        @endif
                      </td>
                    </tr>
                    @endforeach


                    </tbody>

                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        
        <!-- /.container-fluid -->
      
      <!-- /.content -->

<!-- /.content-wrapper -->
@endsection
