@extends('layouts.layout')
@section('title')
Home   
@endsection
@section('other')
@if(Session::has('error_message'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Error :</strong>{{Session::get('error_message')}} 
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif           
<div class="row">
  <div class="col-md-12 grid-margin">
    <div class="row">
      <div class="col-12 col-xl-8 mb-4 mb-xl-0">
        <h3 class="font-weight-bold">Welcome {{Auth::user()->name}}</h3>
        <h6 class="font-weight-normal mb-0">All systems are running smoothly! </h6>
      </div>

    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-6 grid-margin stretch-card">
    <div class="card tale-bg">
      <div class="card-people mt-auto">
        <img src="{{asset('backend/images/dashboard/people.svg')}}" alt="people">
        <div class="weather-info">
          <div class="d-flex">
            <div>
              <h2 class="mb-0 font-weight-normal"><i class="icon-sun mr-2"></i>31<sup>C</sup></h2>
            </div>
            <div class="ml-2">
              <h4 class="location font-weight-normal">Bangalore</h4>
              <h6 class="font-weight-normal">India</h6>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6 grid-margin transparent">
    <div class="row">
      <div class="col-md-6 mb-4 stretch-card transparent">
        <div class="card card-tale">
          <div class="card-body">
            <p class="mb-4">Total Super Admins</p>
            <p class="fs-30 mb-2">{{App\Models\Admin::where('type','admin')->count()}}</p>
           
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-4  stretch-card transparent">
        <div class="card card-light-blue">
          <div class="card-body">
            <p class="mb-4">Total Sub Admins</p>
            <p class="fs-30 mb-2">{{App\Models\Admin::where('type','sub_admin')->count()}}</p>
         
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-4 stretch-card transparent">
        <div class="card card-dark-blue">
          <div class="card-body">
            <p class="mb-4">Total Users</p>
            <p class="fs-30 mb-2">{{App\Models\User::count()}}</p>
            
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-4 stretch-card transparent">
        <div class="card card-light-danger">
          <div class="card-body">
            <p class="mb-4">Total Devices</p>
            <p class="fs-30 mb-2">{{App\Models\Device::count()}}</p>
            
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

@endsection