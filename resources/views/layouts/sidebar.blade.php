<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      <li class="nav-item">
        <a class="nav-link" href="{{url('admin/dashboard')}}">
          <i class="icon-grid menu-icon"></i>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>

     

      
      

  
      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
          <i class="icon-layout menu-icon"></i>
          <span class="menu-title">Users</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-basic">
          <ul class="nav flex-column sub-menu">
    
            <li class="nav-item"> <a class="nav-link" href="{{url('admin/users')}}">Show Users</a></li>
            <li class="nav-item"> <a class="nav-link" href="{{url('admin/add-edit-user')}}">Add User</a></li>
           
          </ul>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link " data-toggle="collapse" href="#ui-basic2" aria-expanded="false" aria-controls="ui-basic2">
          <i class="icon-layout menu-icon"></i>
          <span class="menu-title">Devices</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-basic2">
          <ul class="nav flex-column sub-menu">

            
            <li class="nav-item"> <a class="nav-link" href="{{url('admin/devices')}}">Devices</a></li>
            <li class="nav-item"> <a class="nav-link" href="{{url('admin/add-edit-device')}}">Add Device</a></li>
          </ul>
        </div>
      </li>

      <li class="nav-item">
  
        <a class="nav-link" data-toggle="collapse" href="#ui-basic1" aria-expanded="false" aria-controls="ui-basic1">
          <i class="icon-layout menu-icon"></i>
          <span class="menu-title">Admins</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-basic1">
          <ul class="nav flex-column sub-menu">
        
            <li class="nav-item"> <a class="nav-link" href="{{url('admin/add-edit-admin')}}">Add Admin</a></li>
            <li class="nav-item"> <a class="nav-link " href="{{url('admin/admins')}}">Admins</a></li>
          </ul>
        </div>
      </li>

      <li class="nav-item">
        <a href="{{url('admin/update-details')}}" class="nav-link" aria-expanded="false" aria-controls="ui-basic">
            <i class="icon-layout menu-icon"></i>
          <span class="menu-title">Update Details</span> 
        </a>
      </li>

      <li class="nav-item">
        <a href="{{url('admin/update-password')}}" class="nav-link" aria-expanded="false" aria-controls="ui-basic">
            <i class="icon-layout menu-icon"></i>
          <span class="menu-title">Update Password</span> 
        </a>
      </li>

    </ul>
  </nav>