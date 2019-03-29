
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
        <section class="register section1">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-md-offset-3 col-sm-offset-3 registerarea section_area">

                        <!-- ALERT -->
                         @if($errors->has("wrong"))
                            <span class="help-block">
                                <strong>{{$errors->first("wrong")}}</strong>
                            </span>

                        @endif
                        
                        <!-- /ALERT -->

                        <div class="box-static box-transparent box-bordered padding-30">

                            <div class="box-title margin-bottom-30">
                                <h3 class="title">Don't have an account yet?</h2>
                            </div>

                            <form class="nomargin sky-form" action="{{url('/register')}}" method="post">
                                    {{ csrf_field() }}
                           

                                   
                                        <div class="row form-group col-md-12">
                                        
                                            <div class="col-md-6 form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                                                
                                               
                                                <input class="form-control" placeholder="Your First Name" required="" 
                                                type="text" name="firstname" value="{{ old('firstname') }}" autocomplete="off">
                                                @if ($errors->has('firstname'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('firstname') }}</strong>
                                                    </span>
                                                @endif
                                                    
                                            </div>

                                            <div class="col-md-6 form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">                                             
                                              
                                                <input class="form-control" placeholder="Your Last Name" required="" type="text" 
                                                name="lastname" value="{{ old('lastname') }}"  autocomplete="off">
                                                @if ($errors->has('lastname'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('lastname') }}</strong>
                                                    </span>
                                                @endif
                                                      
                                            </div>

                                        </div>
                                   
                                        <div class="row form-group col-md-12">

                                            <div class="col-md-6 form-group{{ $errors->has('email') ? ' has-error' : '' }}">                                               
                                                <input class="form-control" required="" placeholder="Your Email" type="text"
                                                 name="email" value="{{ old('email') }}"  autocomplete="off">
                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif                                                  
                                            </div>

                                            <div class="col-md-6 form-group{{ $errors->has('phone ') ? ' has-error' : '' }}">                                               
                                                <input class="form-control" type="text" placeholder="Your Phone (optional)" 
                                                name="phone" value="{{ old('phone') }}"  autocomplete="off">
                                                @if ($errors->has('phone'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('phone') }}</strong>
                                                    </span>
                                                @endif                                                      
                                            </div>

                                        </div>
                                   
                                        <div class="row form-group col-md-12">

                                            <div class="col-md-6 form-group{{ $errors->has('password') ? ' has-error' : '' }}">                                               
                                                <input required="" type="password" placeholder="Password" class="form-control"
                                                 name="password"  autocomplete="off">
                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="col-md-6">
                                                <input required="" type="password" placeholder="Confirm Password" 
                                                class="form-control" name="password_confirmation"  autocomplete="off">
                                            </div>

                                        </div>
                                   

                                    <hr />


                                

                                <div class="row form-group col-md-12">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> REGISTER</button>
                                    </div>
                                </div>

                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </section>
        <!-- / -->



@endsection
