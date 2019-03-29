<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin_Mergetransit.com | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <link rel="icon" type="image/png" href="{{asset('assets/images/fav_ico.png')}}">
  
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/datepicker/datepicker3.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/daterangepicker/daterangepicker.css')}}">
  <!-- ../assets/admin/bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">



  <link rel="stylesheet" href="{{asset('assets/admin/bootstrap/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  
  <!-- Ionicons -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/admin/dist/css/AdminLTE.min.css')}}">
  <!-- <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables/dataTables.bootstrap.css')}}"> -->
  <link rel="stylesheet" href="{{asset('assets\admin\plugins\datatables\extensions\Responsive\css\dataTables.responsive.css')}}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{asset('assets/admin/dist/css/skins/_all-skins.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/datepicker/datepicker3.css')}}">

  <!-- Daterange picker -->
 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/daterangepicker/daterangepicker.css')}}">
  <!-- ../assets/admin/bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/select2/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/admin/css/style.css')}}">
  <!-- <link rel="stylesheet" href="{{asset('assets/customer/css/MonthPicker.min.css')}}"> -->

  
    
  <!-- jQuery 2.2.3 -->
  <script src="{{asset('assets/admin/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>

  <!-- jQuery UI 1.11.4 -->
  <!-- <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script> -->
  <!-- Bootstrap 3.3.6 -->
  <script src="{{asset('assets/admin/bootstrap/js/bootstrap.min.js')}}"></script>
  <!-- DataTables -->
  <script src="{{asset('assets/admin/plugins/datatables/jquery.dataTables.js')}}"></script>
  <script src="{{asset('assets/admin/plugins/datatables/dataTables.bootstrap.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
  <!-- SlimScroll -->
  <script src="{{asset('assets/admin/plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
  <!-- FastClick -->
  <script src="{{asset('assets/admin/plugins/fastclick/fastclick.js')}}"></script>
  <!-- AdminLTE App -->
  <script src="{{asset('assets/admin/dist/js/app.min.js')}}"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="{{asset('assets/admin/dist/js/demo.js')}}"></script>
  <script src="{{asset('assets\admin\plugins\datatables\extensions\Responsive\js\dataTables.responsive.js')}}"></script>


  <script src="{{asset('assets/admin/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
  {{--  <script src="{{asset('assets/admin/plugins/daterangepicker/daterangepicker.js')}}"></script>  --}}
  <!-- ../assets/admin/bootstrap WYSIHTML5 -->
  <script src="{{asset('assets/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
  <script src="{{asset('assets/admin/plugins/select2/select2.full.min.js')}}"></script>

  <script src="{{asset('assets/admin/js/jquery.validate.min.js')}}"></script>
  <!-- <script src="{{asset('assets/customer/js/MonthPicker.min.js')}}"></script> -->
  <script src="https://cdn.datatables.net/plug-ins/1.10.19/pagination/select.js"></script>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAr1HliRAne44OuG55a6FOOornx_dHgBjA&libraries=places"></script>
  <script src="{{asset('assets/admin/js/custom.js')}}"></script>

  
  <style>
  .customer-name {
    margin: auto;
    text-align: center;
    font-size: 26px;
    height: 100%;
    color: white;
  }
  .navbar.navbar-static-top .logo{
    width: 115px;
    height: 45px;
    position: absolute;
    top: 0;
    right: 0;
    background: transparent;
  }
  .logo img{
    max-width: 45px;
  }
  </style>
<script>
  var user_role = '<?php echo Auth::user()->role ?>'
  console.log(user_role)
 
  if (user_role == 4) {
    var total_get_url = "{{url('sadmin/total')}}";
    var yearly_get_url = "{{url('sadmin/yearly_get')}}";
  } else {
    var total_get_url = "{{url('sadmin/total')}}";
    var yearly_get_url = "{{url('sadmin/yearly_get')}}";
    var total_get_employee_url = "{{url('sadmin/period_broker_get')}}";
  }
  

</script>

</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="{{url('sadmin')}}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">CUSTOMER</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">CUSTOMER</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="customer-name">       
            {{Auth::user()->firstname}} {{Auth::user()->lastname}}
      </div>

      <div class="logo"><img src="{{asset('assets/images/logo.png')}}" /></div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <ul class="sidebar-menu">
     <?php echo Auth::user()->role?>
        @if(Auth::user()->role == 4)
        <li class="treeview">
         
          <a href="#">
            <i class="fa fa-user"></i> <span>Profile</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{url('/sadmin/drivers/create')}}"><i class="fa fa-user-plus"></i> Add Drivers</a></li>
            <li><a href="{{url('/sadmin/account/')}}"><i class="fa fa-edit"></i> My Account</a></li>
            <li><a href="{{url('/sadmin/service/')}}"><i class="fa fa-edit"></i> Service Upgrade</a></li>
            <li><a href="{{url('/sadmin/changepw')}}"><i class="fa fa-key"></i> Change Password</a></li>
          </ul>
        </li>
        <li class=""><a href="{{url('/sadmin/drivers')}}"><i class="fa fa-users"></i> <span>Drivers</span></a></li>

        @endif
        <li><a href="{{url('/sadmin/currentcustomer')}}"><i class="fa fa-dollar"></i> <span>Current drivers</span></a></li>
        <li><a href="{{url('/sadmin/details')}}"><i class="fa fa-book"></i> <span>Load Input</span></a></li>
        <li><a href="{{url('/sadmin/reports')}}"><i class="fa fa-table"></i> <span>Trip Sheets</span></a></li>  

        @if(Auth::user()->role == 4)
        <li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Cost Section</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{url('/sadmin/costreports')}}"><i class="fa fa-circle-o"></i> Cost Reports</a></li>
            <li><a href="{{url('/sadmin/fixedcost/')}}"><i class="fa fa-circle-o"></i> Fixed Cost</a></li>
            <li><a href="{{url('/sadmin/loadexpense')}}"><i class="fa fa-circle-o"></i> Load Expense</a></li>
            <li><a href="{{url('/sadmin/maintenance')}}"><i class="fa fa-circle-o"></i> Maintenance</a></li>
          </ul>
        </li>
        @endif

        <li><a href="{{url('/sadmin/billing')}}"><i class="fa fa-dollar"></i> <span>Billing</span></a></li>
        <li><a href="{{url('/sadmin/contactlist')}}"><i class="fa fa-list"></i> <span>Company List</span></a></li>
        <li><a href="{{url('/sadmin/profit_report')}}"><i class="fa fa-list"></i> <span>Reports</span></a></li>
        <li><a href="{{url('logout')}}" > <i class="fa fa-sign-out"></i><span class="hidden-xs">Logout</span> </a> </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>


  @yield("contents")

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.3.11
    </div>
    <strong>Copyright &copy; 2017 <a href="http://mergetransit.com">MergeTransit.com</a>.</strong> All rights
    reserved.
  </footer>

 
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->


