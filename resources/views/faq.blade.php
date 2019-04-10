@extends("layout.layout") 
@section("contents")
<!-- faq -->
<section id="faq" class="faq">
	<div class="container home-content">
		<div class="row">
			<div class="col-sm-12">
				<h1 class="big">FAQ</h1>
			</div>
		</div>
		<div class="row" id="accordion">
			{{-- @foreach($faqs as $index => $faq)
			<div class="col-sm-12">
				<h3>{{$index+1}}.&emsp; {{ $faq->question }}</h3>
				<a class="collapse-button pull-right {{$index != 0 ? 'collapsed' : ''}}" data-toggle="collapse" href="#collapse-{{ $index }}"></a>
			</div>
			<div id="collapse-{{ $index }}" class="collapse {{$index == 0 ? 'show' : ''}}" data-parent="#accordion">
				
				@foreach ($faq->groups as $g_index=>$group) 
				@if ($group->title)
				<h4>{{$g_index+1}}.&emsp; {{$group->title}}</h4>
				@endif
				@foreach ($group->answers as $a_index=>$answer)
				<p>{{$a_index+1}}.&emsp; {{$answer->answer}}</p>
				@endforeach
				@if ($group->more)
				<p>{{$group->more}}</p>
				<p><a class="link" href="{{$group->url}}">{{$group->url}}</a></p>
				@endif
				@endforeach
			</div>
			@endforeach --}}
			<div class="col-sm-12">
				<h3>1.&emsp; What is Merge Transit - The Spot Market Agency?</h3>
				<a class="collapse-button pull-right" data-toggle="collapse" href="#collapse-0"></a>
				<div id="collapse-0" class="collapse show" data-parent="#accordion">
					<p>We are a full service Dispatching Service and Mobile App Provider. We provide ways for carriers to Organise their operations and Optimise their rates, when operating in The Spot Market. We have services to handle paperwork for all your shipments and provide a FREE TMS to organise it all and keep track of your numbers. We also offer a service which helps optimise your rates when booking loads in The Spot Market. Our business is the resource centre for Spot Market Carriers and offer more than any other Dispatch Service and for less!</p>
				</div>
			</div>
			
			<div class="col-sm-12">
				<h3>2.&emsp; Are you like other Dispatch Services?</h3>
				<a class="collapse-button pull-right collapsed" data-toggle="collapse" href="#collapse-1"></a>
				<div id="collapse-1" class="collapse" data-parent="#accordion">
					<p>We are not like any other service which labels itself as a Dispatch Service. We handle all dispatch duties for carriers but, we do so much more and have actual skin in the game. We are licensed and bonded with our broker authority, we develop custom tech for small market carriers, so they can stay organised and have complete visibility into their business, we subscribe and use leading industry data tools such as SONAR, which can cost carriers thousands of dollars per month on their own, and we train our staff daily on market trends/conditions for better route planning. We do all this for 1/2 of what most charge for just a basic service.</p>
				</div>
			</div>
			
			<div class="col-sm-12">
				<h3>3.&emsp; What makes you different?</h3>
				<a class="collapse-button pull-right collapsed" data-toggle="collapse" href="#collapse-2"></a>
				<div id="collapse-2" class="collapse" data-parent="#accordion">
					<p>We invest back in our business non-stop to provide a better service. We developed custom tools and reporting for our customers and the entire market. We have countless reviews from carriers and brokers, which validates our claims of superior service and routing. We have been featured in 2 of the largest online publications (FreightWaves and OverDrive Online).</p>
					<p>Link to Articles:</p>
					<p><a class="link" href="https://overdriveonline.com/new-crop-of-dispatch-services-offer-tech-relationship-hands-in-spot-negotiations/">https://overdriveonline.com/new-crop-of-dispatch-services-offer-tech-relationship-hands-in-spot-negotiations/</a></p>
					<p><a class="link" href="https://www.freightwaves.com/news/technology/merge-transit-helps-owner-operators-and-small-fleets-manage-their-business-on-the-go">https://www.freightwaves.com/news/technology/merge-transit-helps-owner-operators-and-small-fleets-manage-their-business-on-the-go</a></p>
				</div>
			</div>
			
			<div class="col-sm-12">
				<h3>4.&emsp; How much for Full Dispatch Service and what kind of rates will I get?</h3>
				<a class="collapse-button pull-right collapsed" data-toggle="collapse" href="#collapse-3"></a>
				<div id="collapse-3" class="collapse" data-parent="#accordion">
					<p>Our Optimisation Program is our Full Dispatch Service and only cost $125 flat rate per week. There are never any contracts and our service keeps our customers. When we figure against weekly revenues, our fee is less than 3% on average. Our rate increases for our customers area achieved by a few factors….One, strategic routing through market data. We attain better rates through knowing the markets in real-time, which allows us to route plan before the week starts and then adjust as the market moves on any given day. This typically accounts for the meat of the raise in rates we get for our carriers. Our company consistently beats market averages by a sizeable amount week in and week out. Our carriers will typically see a 5-8% raise in their RPM when using our services, which amounts to a 2-5% increase in their total rates after accounting for our low fees.</p>
				</div>
			</div>
			
			<div class="col-sm-12">
				<h3>5.&emsp; Are there any contracts?</h3>
				<a class="collapse-button pull-right collapsed" data-toggle="collapse" href="#collapse-4"></a>
				<div id="collapse-4" class="collapse" data-parent="#accordion">
					<p>Never. We have a simple pricing agreement and list of duties to allow us to operate as your dispatcher but, our service is our way of keeping our customers…Not a contract!</p>
				</div>
			</div>
		</div>
</section>

<!-- /faq -->
@endsection