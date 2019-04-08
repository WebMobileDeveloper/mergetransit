@extends("layout.layout") 
@section("contents")
<!-- blog -->
<section id="blog" class="blog">
    <div class="container home-content">
        <div class="row">
            <div class="col-sm-12 blog-header">
                <h1 class="big">blog</h1>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                    Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                </p>
            </div>
            <div class="col-sm-12">
                <a>
                    <h4 class="f--black-blue">TAGS:</h4>
                </a>
                <span>
                    @foreach ($tags as $tag)
                <a href="{{'/blog?tag='.urlencode ($tag->tag)}}"><h4 class="f--red {{$curr_tag==$tag->tag ? 'selected': ''}}">{{ $tag->tag }}</h4></a>@endforeach
                </span>
            </div>
        </div>
        @foreach ($blogs as $blog)
        <div class="row">
            <div class="col-sm-12 blog-div">
                <img class="blog-title-image" src="{{asset('assets/images/blogs/'.$blog->image)}}" alt="" />
            </div>
            <div class="col-sm-12 blog-content">
                <h4 class="f--red selected">{{ $blog->tag }}</h4>
                <a href="{{'/blog/detail/'.$blog->id}}">
                    <h3 class="f--black-blue">{{$blog->title}}</h3>
                </a>
                <p class="f--black-blue">{{$blog->description}}</p>
                <br>
            </div>
        </div>
        @endforeach
        <div class="row">
            <div class="col-md-12 center">{{ $blogs->links() }}</div>
        </div>
    </div>
</section>
<!-- /blog -->
@endsection