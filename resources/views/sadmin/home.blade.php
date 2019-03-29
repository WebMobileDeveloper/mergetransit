  @extends("layout.customerLayout")
  @section("contents")
  <!-- Content Wrapper. Contains page content -->
  
<style>
.box-header {

  color: #444;
  display: block;
  padding: 20px;
  position: relative;
  text-align: center;

}

h1.total-title {
  font-size: 30px !important
}

h3.total-title {
  font-size: 24px !important
}

.info-box-content {
  margin-left: 10px !important;
}

.table-bx {
  /* min-height: 200px !important; */
  text-align: center;
  /* padding: 0px; */
}

.table-bx h1 {
  /* margin: 0 !important */
}
.table-bx div{
  padding: 5px 0;
  margin: 0;
}
.table-bx p{
  padding: 0;
  margin: 0;
}

.cost_summary .summary {
  padding: 20px 0 !important
}
.business_summary .summary{
  padding: 0px 0 !important
}
 .summary {
 min-height: 200px;
}
.cost_pm p {
  color : #5a5a5a !important
}
/* .all_rpm {
  min-height: 67px;
}
.cost_pm {
  min-height: 66px;
}
.profit_pm {
  min-height: 67px;
} */

p.label {
  font-size: 23px;
}
p.value {
  font-size: 22px;
}
</style>



  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">      
        <section class="col-lg-12 business_summary">

          <div class="box box-success">
            <div class="box-header ui-sortable-handle" style="">          
              <h3 class="box-title total-title">Business Summary YTD</h3>
            </div>

            <div class="box-body">
              <div class="col-md-12">
                <div class="col-md-6 summary bg-aqua" >
                  <div class="table-bx ">
                    <h1 class="total-title"> Totals </h1>

                    <div class="col-md-6">
                      <p class="label">Revenue</p>
                      <p class="value">{{$total_data['total_revenue']}}</p>
                    </div>
                    <div class="col-md-6">
                      <p class="label">Miles</p>
                      <p class="value">{{$total_data['total_mile']}}</p>
                    </div>
                    <div class="col-md-6">
                      <p class="label">Cost</p>
                      <p class="value">{{$total_data['total_cost']}}</p>
                    </div>
                    <div class="col-md-6">
                      <p class="label">Profit</p>
                      <p class="value">{{$total_data['total_profit']}}</p>
                    </div>                
                  
                  </div>
                </div>

                <div class="col-md-6 ">
                    <div class="table-bx ">
                    
                      <div class="all_rpm bg-red">
                        <p class="label">All Mile RPM</p>
                        <p class="value">{{$total_data['total_rpm']}}</p>
                      </div>
                      <div class="cost_pm bg-white">
                        <p class="label">Cost Per Mile</p>
                        <p class="value">{{$total_data['cost_rpm']}}</p>
                      </div>
                      <div class="profit_pm bg-red">
                        <p class="label">Profit Per Mile</p>
                        <p class="value">{{$total_data['profit_rpm']}}</p>
                      </div>
                               
                    
                    </div>
                  </div>
              </div>
            </div>
        
          </div>

        </section>
      </div>

      <div class="row">      
          <section class="col-lg-12 cost_summary">
  
            <div class="box box-success">
              <div class="box-header ui-sortable-handle" style="">          
                <h3 class="box-title total-title">Cost Summary 12 month</h3>
              </div>
  
              <div class="box-body">
                <div class="col-md-12">
                  <div class="col-md-6 summary  bg-aqua" >
                    <div class="table-bx">
                    
                      <div class="col-md-6">
                        <p class="label">Maintenance</p>
                        <p class="value">{{$total_data['maintenance_y']}}</p>
                      </div>
                      <div class="col-md-6">
                        <p class="label">Fixed Cost</p>
                        <p class="value">{{$total_data['fixed_cost_y']}}</p>
                      </div>
                      <div class="col-md-6">
                        <p class="label">Variable</p>
                        <p class="value">{{$total_data['loadexpense_y']}}</p>
                      </div>
                      <div class="col-md-6">
                        <p class="label">Total</p>
                        <p class="value">{{$total_data['total_cost']}}</p>
                      </div>                
                    
                    </div>
                  </div>
  
                  <div class="col-md-6 " >
                      <div class="table-bx">
                      
                        <div class="all_rpm bg-red">
                          <p class="label">Monthly Fixed</p>
                          <p class="value">{{$total_data['fixed_cost']}}</p>
                        </div>
                        <div class="cost_pm bg-white">
                          <p class="label">Cost Per Mile</p>
                          <p class="value">{{$total_data['cost_rpm']}}</p>
                        </div>
                        <div class="profit_pm bg-red">
                          <p class="label">Operating Ratio</p>
                          <p class="value">{{$total_data['ratio']}}</p>
                        </div>
                                 
                      
                      </div>
                    </div>
                </div>
              </div>
          
            </div>
  
          </section>
        </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <script src="{{asset('assets/admin/js/chart.js')}}"></script>
  <script src="{{asset('assets/customer/js/dashboard.js')}}"></script>
 
  @endsection

