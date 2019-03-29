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
        <section class=" section1">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 section_area">                     
                     

                        @if($status = Session::get("message"))
                            <div class="alert alert-success form-group">
                                <span class="help-block">
                                    <strong>{{$status}}</strong>
                                </span>
                            </div><br>
                        @endif                    

                        <div class="box-static box-border-top padding-30">
                            <div class="box-title margin-bottom-30">
								<h3 class="title">404 Page - Oops, Page not found.</h3>
                            </div>
                          
                            <div class="row">

                                <div class="col-md-12 col-sm-6 col-xs-6 text-right">

                                    <a href="/" type="submit" class="btn btn-info">GO TO HOMEPAGE</a>

                                </div>

                            </div>

                            

                        </div>

                    </div>
                </div>

            </div>
        </section>
        <!-- / -->


    </div>
    <!-- /wrapper -->
@endsection