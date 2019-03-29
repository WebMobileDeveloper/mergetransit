
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


<section  class="changepassword section1">       
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 section_area">

                @if (Session::has('success'))
                    <div class="alert alert-success">{!! Session::get('success') !!}</div>
                @endif
                @if (Session::has('failure'))
                    <div class="alert alert-danger">{!! Session::get('failure') !!}</div>
                @endif

                <div class="box-static box-border-top padding-30">
                    <div class="box-title margin-bottom-10">
                        <h3 class="title">Change Password</h3>
                    </div>
                    <form action="" method="post" class="nomargin">
                    {{csrf_field()}}
                        <div class="clearfix">
                            <div class="form-group{{ $errors->has('old') ? ' has-error' : '' }}">
                                <input id="password" type="password" class="form-control" name="old" placeholder="Old Password">
                                @if ($errors->has('old'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('old') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <input id="password" type="password" class="form-control" name="password" placeholder="New Password">
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-6 col-xs-6 text-center">
                                <button class="btn btn-primary">OK, UPDATE PASSWORD</button>
                            </div>
                        </div>
                    </form>
                </div>
    
            </div>
        </div>
    </div>
</section>
@endsection
