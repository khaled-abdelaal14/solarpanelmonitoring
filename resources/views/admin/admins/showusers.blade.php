@extends('layouts.layout')
@section('other')

   
        
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
                  <h3 class="card-title">Show Users</h3>
                  <a class="btn btn-block btn-primary" style="max-width: 150px; float:right; display:inline-block; " href="{{url('admin/add-edit-user')}}">Add user</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="users" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                      <th>Id</th>
                      <th>Name</th>
                      <th>Mobile</th>
                      <th>Email</th>
                      <th>Created by</th>
                      <th>Created on</th>
                      <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            
                  
                    <tr>
                      <td>#</td>
                      <td>{{$user->name}}</td>
                      <td>{{$user->phone}}</td>
                      <td>{{$user->email}}</td>
                      <td>{{$user->admin->name}}</td>

                      <td>{{date("F j, Y, g:i a", strtotime($user->created_at));}}</td>
                      <td>
                        @if($usersModule['edit_access']==1 || $usersModule['full_access']==1)
                        <a style='color:#3f6ed3'; href="{{url('admin/add-edit-user',$user->id)}}"> <i class="fas fa-edit"></i></a>
                         &nbsp;&nbsp;
                        @endif

                        @if($usersModule['full_access']==1)
                         <a class="confirmdelete" name="user" title="Delete user" style='color:#3f6ed3'; href="javascript:void{0}" record="user" recordid={{$user->id}} <?php /* href="{{url('user/delete-user',$subuser->id)}}" */ ?>> <i class="fas fa-trash"></i></a>
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
