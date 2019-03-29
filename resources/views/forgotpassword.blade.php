
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
        <section class="forgotpassword section1">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 forgotarea section_area">                      
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
                                <h3 class="title">Please enter your registered email address.</h3>
                            </div>

                            <form class="nomargin" method="post" action="{{url('forgotpassword')}}" autocomplete="off">
                              {{ csrf_field() }}
                                <div class="clearfix">

                                    <!-- Email -->
                                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                        <input type="text" name="email" class="form-control" placeholder="Email" required="" value="{{ old('email') }}">
                                         @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-12 col-sm-6 col-xs-6 text-right">

                                        <button type="submit" class="btn btn-info">RESET PASSWORD</button>

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