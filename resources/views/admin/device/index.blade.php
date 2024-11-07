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
                  <h3 class="card-title">Show devices</h3>
                  <a class="btn btn-block btn-primary" style="max-width: 150px; float:right; display:inline-block; " href="{{url('admin/add-edit-device')}}">Add Device</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="devices" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                      <th>Id</th>
                      <th>Serial Number</th>
                      <th>status</th>
                      <th>Owner</th>
                      <th>Created at</th>
                      <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($devices as $device)
                            
                  
                    <tr>
                      <td>#</td>
                      <td>{{$device->serial_number }}</td>
                      <td>{{$device->status}}</td>
                      <td>{{$device->user->name}}</td>
                    

                      <td>{{date("F j, Y, g:i a", strtotime($device->created_at));}}</td>
                      <td>
                     
                        @if($devicesModule['edit_access']==1 || $devicesModule['full_access']==1)

                        <a style='color:#3f6ed3'; href="{{url('admin/add-edit-device',$device->id)}}"> <i class="fas fa-edit"></i></a>
                         &nbsp;&nbsp;
                        @endif

                        @if($devicesModule['full_access']==1)
                         <a class="confirmdelete" name="device" title="Delete device" style='color:#3f6ed3'; href="javascript:void{0}" record="device" recordid={{$device->id}} <?php /* href="{{url('admin/delete-device',$device->id)}}" */ ?>> <i class="fas fa-trash"></i></a>
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
