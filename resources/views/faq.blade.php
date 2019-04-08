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
				<h3>{{ $faq['question'] }}</h3>
				<a class="pull-right {{$index != 0 ? 'collapsed' : ''}}" data-toggle="collapse" href="#collapse-{{ $index }}"></a>
			</div>
			<p id="collapse-{{ $index }}" class="collapse {{$index == 0 ? 'show' : ''}}" data-parent="#accordion">
				{{ $faq['answer'] }}
			</p>
			@endforeach
		</div>
</section>

<!-- /faq -->
@endsection