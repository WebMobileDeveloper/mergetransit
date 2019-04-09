@extends("layout.layout") 
@section("contents")
<!-- CONTACT -->
<section class="contact">
	<div class="container  home-content">
		<div class="row">
			<div class="col-12">
				<h1 class="big">GET IN TOUCH</h1>
				<p>
					Find out more about our services and team today.
				</p>
			</div>
		</div>
		<div class="row contact__wrapper">
			<div class="col-md-8 col-sm-6">
				@if($status = Session::get("status"))
				<div class="alert alert-success form-group">
					<span class="help-block">{{$status}}</span>
				</div>
				<br>
				<!-- end if -->
				@endif
				<form id="contactform" action="{{url('/mailsend')}}" method="post">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-md-6 {{ $errors->has('message') ? ' has-error' : '' }}">
							<input type="text" name="name" value="{{ old('name') }}" placeholder="Name">
							<!-- if -->
							@if ($errors->has('name'))
							<span class="help-block">{{ $errors->first('name') }}</span>
							<!-- end if -->
							@endif
						</div>
						<div class="col-md-6 {{ $errors->has('message') ? ' has-error' : '' }}">
							<input type="text" name="company" value="{{ old('company') }}" placeholder="Company">
							<!-- if -->
							@if ($errors->has('company'))
							<span class="help-block">{{ $errors->first('company') }}</span>
							<!-- end if -->
							@endif
						</div>
						<div class="col-md-6 {{ $errors->has('message') ? ' has-error' : '' }}">
							<input type="phone" name="phone" value="{{ old('phone') }}" placeholder="Phone">
							<!-- if -->
							@if ($errors->has('phone'))
							<span class="help-block">{{ $errors->first('phone') }}</span>
							<!-- end if -->
							@endif
						</div>
						<div class="col-md-6 {{ $errors->has('message') ? ' has-error' : '' }}">
							<input type="email" name="email" value="{{ old('email') }}" placeholder="Email">
							<!-- if -->
							@if ($errors->has('email'))
							<span class="help-block">{{ $errors->first('email') }}</span>
							<!-- end if -->
							@endif
						</div>
						<div class="col-md-12 {{ $errors->has('message') ? ' has-error' : '' }}">
							<textarea name="message" placeholder="Message" rows="8" value="{{ old('message') }}"></textarea>
							<!-- if -->
							@if ($errors->has('message'))
							<span class="help-block">{{ $errors->first('message') }}</span>
							<!-- end if -->
							@endif
						</div>
						<div class="col-lg-6 col-md-12 right">
							<button class="button send button--dark-blue  mr-3">&emsp;Send&emsp;</button>
						</div>
						<div class="col-lg-6 col-md-12">
							<div class="g-recaptcha" data-sitekey="{{ env('CAPTCHA_SITE_KEY') }}"></div>
							@if ($errors->has('g-recaptcha-response'))
							<span class="invalid-feedback" style="display: block;">{{ $errors->first('g-recaptcha-response') }}</span>
							<!-- end if -->
							@endif
							<span class="invalid-feedback" style="display: none;">
								Complete the reCAPTCHA to submit the form
							</span>
						</div>
					</div>
				</form>
			</div>
			<div class="col-md-4 col-sm-6 center contact-info">
				<div class="left pl-3">
					<img src="{{asset('assets/images/logo-colored.png')}}" alt="Marge Transit">
					<p>
						<i class="fas fa-map-marker-alt"></i>
						<span>Merge Transit LLC</span>
					</p>
					<p>
						<i class="fas fa-phone"></i>
						<a href='phone:8662728001'>(866) 272-8001</a>
					</p>
					<p>
						<i class="fas fa-envelope"></i>
						<a href="mailto:info@MergeTransit.com">info@MergeTransit.com</a>
					</p>
					<p>
						<i class="fab fa-facebook-f"></i>
						<a href="https://www.facebook.com/MergeTransit/">facebook</a>
					</p>
				</div>

			</div>
		</div>
	</div>
</section>
<!--/CONTACT -->
@endsection






<!--javascript -->
@push('javascript')

<script>
	$(document).ready(function(){
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
	})

</script>

@endpush