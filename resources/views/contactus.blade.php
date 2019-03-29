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

	<!-- CONTACT -->
	<section class="contact">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<h2 class="big f--white">
						Contact
					</h2>
				</div>
				<div class="col-12 contact__wrapper">
					<div class="col-6 box box--light-blue">
						<p>
							<i class="fas fa-map-marker-alt"></i>
							<span>
								Merge Transit LLC
							</span>
						</p>
						<p>
							<i class="fas fa-phone"></i>
							<a href='phone:8662728001'>
								(866) 272-8001
							</a>
						</p>
						<p>
							<i class="fas fa-envelope"></i>
							<a href="mailto:info@MergeTransit.com">
								info@MergeTransit.com
							</a>
						</p>
						<p>
							<i class="fab fa-facebook-f"></i>
							<a href="https://www.facebook.com/MergeTransit/">
								facebook
							</a>
						</p>
					</div>
					<div class="col-6 box box--form">
                        @if($status = Session::get("status"))
                            <div class="alert alert-success form-group">
                                <span class="help-block">
                                    <strong>{{$status}}</strong>
                                </span>
                            </div><br>
                        @endif
						<form id="contactform" action="{{url('/mailsend')}}" method="post">
							{{ csrf_field() }}
                            <div class="row col-md-12">
                                <p class="{{ $errors->has('name') ? ' has-error' : '' }} col-md-6"><span>
                                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Name">
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </span></p>
                                
                                <p class="{{ $errors->has('company') ? ' has-error' : '' }} col-md-6"><span>
                                    <input type="text" name="company" value="{{ old('company') }}" placeholder="Company">
                                    @if ($errors->has('company'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('company') }}</strong>
                                        </span>
                                    @endif
                                </span></p>
                            </div>
                            <div class="row col-md-12">
                                <p class="{{ $errors->has('phone') ? ' has-error' : '' }} col-md-6"><span>
                                    <input type="phone" name="phone" value="{{ old('phone') }}" placeholder="Phone">
                                    @if ($errors->has('phone'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                    @endif
                                </span></p>

                                <p class="{{ $errors->has('email') ? ' has-error' : '' }} col-md-6"><span>
                                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email">
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </span></p>
                            </div>
                            <div class="row col-md-12">
                                <p class="{{ $errors->has('message') ? ' has-error' : '' }} col-md-12"><span>
                                    <textarea name="message" placeholder="Message" rows="8" value="{{ old('message') }}"></textarea>
                                    @if ($errors->has('message'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('message') }}</strong>
                                        </span>
                                    @endif
                                </span></p>
							</div>
							
							<div class="row col-md-12">
								<div class="col-md-12">
									<div class="g-recaptcha" data-sitekey="{{ env('CAPTCHA_SITE_KEY') }}"></div>
									@if ($errors->has('g-recaptcha-response'))
										<span class="invalid-feedback" style="display: block;">
											<strong>{{ $errors->first('g-recaptcha-response') }}</strong>
										</span>
									@endif
									<span class="invalid-feedback" style="display: none;">
										<strong>Complete the reCAPTCHA to submit the form</strong>
									</span>
								</div>
							</div>

							<div class="row col-md-12 btn-box">
								<button class="button button--dark-blue send">Send</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--/CONTACT -->
	
		<!-- GET IT FROM  -->
		<section class="google">
			<div class="container">
				<div class="row">
					<div class="col-md-12 col-sm-12 getin">
						<h2 class="big f--white">
								Get app from Google Play & APP Store 
							<span class="small">
								and make your job easier.
							</span>
						</h2>
						<a href="https://play.google.com/store/apps/details?id=com.cleaningapp" class="get">
							<img src="{{asset('assets/images/en_badge_web_generic.png')}}" alt="">
						</a>
						<a href="https://itunes.apple.com/us/app/merge-transit-spot-market-app/id1434054240?mt=8" class="get">
							<img src="{{asset('assets/images/AppStoreIcon.png')}}" alt="">
						</a>
					</div>
				</div>
			</div>
		</section>
		<!-- /GET IT FROM -->
       

@endsection
@push('javascript')

<script>
		$("#contactform .send").on('click', function(e){
			$(".invalid-feedback").hide()
			e.preventDefault();
		
			var response = grecaptcha.getResponse();
			if (!response) {
				$(".invalid-feedback").show()
				return false;
			}  else {
				$("#contactform").submit()
			}
		})
		</script>
@endpush