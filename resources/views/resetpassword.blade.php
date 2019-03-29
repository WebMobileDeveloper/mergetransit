
@extends("layout.layout")
@section("contents")
        <!-- HERO -->
	    <section class="hero">
            <div class="container-fluid">	
                <div class="row">
                    <div class="background"></div>
                </div>
            </div>
        </section>	
        <!-- /HERO -->
        <!-- -->
        <section class="resetpassword section1">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 resetarea section_area">                      
                        @if($status = Session::get("status"))
                            <div class="alert alert-info form-group">
                                <span class="help-block">
                                    <strong>{{$status}}</strong>
                                </span>
                            </div><br>
                        @endif

                        @if($status = Session::get("message"))
                            <div class="alert alert-success form-group">
                                <span class="help-block">
                                    <strong>{{$status}}</strong>
                                </span>
                            </div><br>
                        @endif
                     

                        <div class="box-static box-border-top padding-30">
                            <div class="box-title margin-bottom-30">
                                <h3 class="title">Please enter New Password</h3>
                            </div>

                            <form class="nomargin" method="post" action="{{url('resetpassword')}}" autocomplete="off">
                              {{ csrf_field() }}
                                <div class="clearfix">

                                    <input  type="hidden" name="action_key" value="{{$_GET['action']}}" >
                                        <div class=" form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                            <input required="" type="password" class="form-control"  placeholder="password" class="err" name="password">
                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif

                                        </div>

                                        <div class="form-group">
                                            <input required="" type="password" class="form-control"  class="err" placeholder="password Again" name="password_confirmation">                                            
                                        </div>
                                </div>

                                <div class="row ">

                                    <div class="col-md-12 col-sm-6 col-xs-6 text-right">

                                        <button type="submit" class="btn btn-info">SAVE</button>

                                    </div>

                                </div>

                            </form>

                        </div>

                    </div>
                </div>

            </div>
        </section>
        <!-- / -->


    </div>
    <!-- /wrapper -->
@endsection