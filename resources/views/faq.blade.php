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
			@foreach($faqs as $index => $faq)
			<div class="col-sm-12">
				<h3>{{$index+1}}.&emsp; {{ $faq->question }}</h3>
				<a class="collapse-button pull-right {{$index != 0 ? 'collapsed' : ''}}" data-toggle="collapse" href="#collapse-{{ $index }}"></a>
			</div>
			<div id="collapse-{{ $index }}" class="pl-5 collapse {{$index == 0 ? 'show' : ''}}" data-parent="#accordion">
				{{-- {{ $faq->answer }} --}}
				<!-- groups -->
				@foreach ($faq->groups as $g_index=>$group) 
				<!-- -->
				@if ($group->title)
				<h4>{{$g_index+1}}.&emsp; {{$group->title}}</h4>
				@endif
				<!-- answers -->
				@foreach ($group->answers as $a_index=>$answer)
				<p>{{$a_index+1}}.&emsp; {{$answer->answer}}</p>
				@endforeach
				<!--end answers -->
				@if ($group->more)
				<p>{{$group->more}}</p>
				<p><a class="link" href="{{$group->url}}">{{$group->url}}</a></p>
				@endif
				<!-- -->
				@endforeach
				<!--end groups -->
			</div>
			@endforeach
		</div>
</section>

<!-- /faq -->
@endsection