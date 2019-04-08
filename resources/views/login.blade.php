@extends("layout.layout") 
@section("contents")
<!-- HERO -->
<section class="hero login-hero">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="hero-wrapper">
                    <div class="hero-content">
                        <!-- status -->
                        @if($status = Session::get("status"))
                        <div class="alert alert-info form-group">
                            <span class="f--white">{{$status}}</span>
                        </div>
                        @endif
                        <!-- home -->
                        <div class="box-static box-border-top padding-30">
                            <h3 class="title f--white">Login</h3>
                            <form class="nomargin" method="post" action="{{url('/login')}}" autocomplete="off">
                                {{ csrf_field() }}
                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <!-- Email -->
                                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                            <label class="f--white">E-mail</label>
                                            <input type="text" name="email" class="form-control" placeholder="Email" required="" value="{{ old('email') }}">

                                            <!-- error -->
                                            @if ($errors->has('email'))
                                            <span class="f--white">{{ $errors->first('email') }}</span>
                                            <!--  -->
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <!-- Password -->
                                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                            <label class="f--white">password</label>
                                            <input type="password" name="password" class="form-control" placeholder="Password" required="">

                                            <!-- error -->
                                            @if ($errors->has('password'))
                                            <span class="f--white">{{ $errors->first('password') }}</span>
                                            <!--  -->
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <button class="btn bordered">&emsp;Login&emsp;</button>
                                    </div>
                                    <div class="col-sm-12 pt-3">
                                        <a class="f--white" href="/forgotpassword">Forgot Password?</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="background"></div>
</section>
<!-- /HERO -->

</div>
<!-- /wrapper -->
@endsection