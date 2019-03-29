@extends("layout.adminLayout")
@section("contents")
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <section class="col-lg-12 statistic">
                <div class="box box-success">
                    <div class="box-header ui-sortable-handle" style="cursor: move;">
                        <h3 class="box-title">Statistic</h3>
                        <div class="box-tools pull-right">
                            <div class="styled-select green rounded">
                                <select id="period_search">
                                    <option value="total" selected>ToTal</option>
                                    <option value="today">Today</option>
                                    <option value="yesterday">Yesterday</option>
                                    <option value="n_week">This Week</option>
                                    <option value="l_week">Last Week</option>
                                    <option value="n_month">This Month</option>
                                    <option value="year_date">Year to Date</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3 class="detail_number">{{$infor_array['detail_num']}}</h3>

                                    <p>Total Detail</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-edit"></i>
                                </div>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3 class="total_revenue">${{$infor_array['total_revenue']}}</h3>

                                    <p>Total Revenue</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-currency"></i>
                                </div>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-yellow">
                                <div class="inner">
                                    <h3 class="avg_num">${{$infor_array['avg_dhrpm']}}</h3>
                                    <p>Avarage RPM</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3 class="total_mile">{{$infor_array['total_mile']}}KM</h3>
                                    <p>Total Miles</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                            </div>
                        </div>
                        <!-- ./col -->
                    </div>
                </div>
            </section>
        </div>
        <!-- /.row -->

        <div class="row">
            <section class="col-lg-12 yearly_total">
                <div class="box box-success">
                    <div class="box-header ui-sortable-handle" style="cursor: move;">
                        <h3 class="box-title">Yearly Total Revenue Tracker</h3>
                        {{-- 
                        <div class="box-tools pull-right">
                            <div class="styled-select green rounded">
                                <select>
                                    <option>2016</option>
                                    <option>2017</option>
                                </select>
                            </div>
                        </div>
                        --}}
                    </div>

                    <div class="box-body ">
                        <div class="text-box">
                            <canvas id="billings" style="height:300px;"></canvas>
                            <div id="chartjs-tooltip" style="z-index:15">
                                <div class="span"></div><i class="fa fa-close"
                                    style="float: right;margin-top: -30px;cursor:pointer"></i>
                                <table class="tooltip-table"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        @if(Auth::user()->role !== 3)
        <div class="row">
            <section class="col-lg-12 period_total">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Total Tracker By Broker</h3>
                        <div class="box-tools pull-right">
                            <div class="styled-select green rounded">
                                <select id="period_search">
                                    <option value="total" selected>ToTal</option>
                                    <option value="n_week">This Week</option>
                                    <option value="l_week">Last Week</option>
                                    <option value="n_month">This Month</option>
                                    <option value="year_date">Year to Date</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body ">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th>Broker</th>
                                        <th>Total Miles</th>
                                        <th>Loads Booked</th>
                                        <th>Revenue</th>
                                        <th>Avg Revenue</th>
                                        <th>Avg RPM</th>
                                    </tr>
                                </thead>
                                <tbody class="state_table_emp">
                                    @foreach($total_detail_broker as $row)
                                    <tr>
                                        <td><a href="#">{{$row['employee_name']}}</a></td>
                                        <td>{{$row['total_miles'] }}</td>
                                        <td>{{$row['detail_num']}}</td>
                                        <td>{{$row['total_revenue']}}</td>
                                        <td>{{$row['avg_revenue']}}</td>
                                        <td>{{$row['avg_dhrpm']}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                </div>
            </section>
            {{-- 
            <section class="col-lg-6 monthly_total">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Monthly Tracker</h3>

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body ">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Item</th>
                                        <th>Status</th>
                                        <th>Popularity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><a href="pages/examples/invoice.html">OR9842</a></td>
                                        <td>Call of Duty IV</td>
                                        <td><span class="label label-success">Shipped</span></td>
                                        <td>
                                            <div class="sparkbar" data-color="#00a65a" data-height="20"><canvas
                                                    style="display: inline-block; width: 34px; height: 20px; vertical-align: top;"
                                                    width="34" height="20"></canvas></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="pages/examples/invoice.html">OR1848</a></td>
                                        <td>Samsung Smart TV</td>
                                        <td><span class="label label-warning">Pending</span></td>
                                        <td>
                                            <div class="sparkbar" data-color="#f39c12" data-height="20"><canvas
                                                    style="display: inline-block; width: 34px; height: 20px; vertical-align: top;"
                                                    width="34" height="20"></canvas></div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>

                </div>
            </section>
            --}}
        </div>
        @endif
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script src="{{asset('assets/admin/js/chart.js')}}"></script>
<script src="{{asset('assets/admin/js/dashboard.js')}}"></script>

@endsection