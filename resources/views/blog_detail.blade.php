@extends("layout.layout") 
@section("contents")
<!-- blog-detail -->
<section id="blog-detail" class="blog-detail">
    <div class="container home-content">
        <div class="row">
            <div class="col-sm-12 blog-header">
                <h3 class="f--dark-blue">{{$blog->title}}</h3>
                <h4 class="f--red"> {{$blog->tag}}</h4>
                <img class="blog-title-image" src="{{asset('assets/images/blogs/'.$blog->image)}}" alt="" />
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 blog-content">
                <p class="bold"><b>{{$blog->description}}</b></p>
            </div>
        </div>
        @foreach ($contents as $content)
        <div class="row ">
            <div class="col-sm-12 blog-content">
                @if ($content->type==0) {{-- if text --}}
                <div>
                    @foreach (explode("\n", $content->text) as $line)
                    <p>{{ $line }}</p>
                    @endforeach
                </div>
                @else
                <img class="blog-title-image" src="{{asset('assets/images/blogs/'.$content->image)}}" alt="" />
                <div>
                    @isset($content->image_description)
                    <p class="image-description">{{$content->image_description}}</p>
                    @endisset
                </div>
                @endif
            </div>
        </div>
        @endforeach

        <!-- related blog -->
        @isset($related_blog)
        <div class="row">
            <div class="col-sm-12 blog-header">
                <h2 class="small">read also</h2>
            </div>
            <div class="col-sm-12 blog-div">
                <img class="blog-title-image" src="{{asset('assets/images/blogs/'.$related_blog->image)}}" alt="" />
            </div>
            <div class="col-sm-12 blog-content">
                <h4 class="f--red selected">{{ $related_blog->tag }}</h4>
                <a href="{{'/blog/detail/'.$related_blog->id}}">
                    <h3 class="f--dark-blue">{{$related_blog->title}}</h3>
                </a>
                <p>{{$related_blog->description}}</p>
                <br>
            </div>
        </div>
        @endisset
    </div>
</section>
<!-- /blog-detail -->
@endsection