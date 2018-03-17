{{--<div id="sidebar-blog-sidebar" class="sidebar group">--}}

    <div class="widget-first widget recent-posts">
        <h3>{{ Lang::get('ru.latest_projects') }}</h3>
        <div class="recent-post group">
        @if(!$portfolios->isEmpty())
            @foreach($portfolios as $portfolio)
                <div class="hentry-post group">
                    <div class="thumb-img"><img src="{{asset(env('THEME'))}}/images/projects/{{$portfolio->img->mini}}" style="width: 55px" alt="001" title="001" /></div>
                    <div class="text">
                        <a href="{{ route('portfolios.show', ['alias' => $portfolio->alias]) }}" title="{!! $portfolio->title !!}" class="title">{!! $portfolio->title !!}</a>
                        <p>{!! str_limit($portfolio->text, 130) !!}</p>
                        <a class="read-more" href="article.html">&rarr; {{ Lang::get('ru.read_more') }}</a>
                    </div>
                </div>
            @endforeach
        @endif

        </div>
    </div>


    @if(!$comments->isEmpty())

    <div class="widget-last widget recent-comments">
        <h3>{{ Lang::get('ru.recent_comments') }}</h3>
        <div class="recent-post recent-comments group">

            @foreach($comments as $comment)

            <div class="the-post group">
                <div class="avatar">
                    @set($hash,md5(($comment->email)? $comment->email : $comment->user->email))
                    <img alt="" src="https://www.gravatar.com/avatar/{{$hash}}?d=mm&s=55" class="avatar" />
                </div>
                <span class="author"><strong><a href="#">{{isset($comment->user)? $comment->user->name : $comment->name}}</a></strong> in</span>
                <a class="title" href="{{ route('articles.show', ['alias' => $comment->article->alias]) }}">{{ $comment->article->title }}</a>
                <p class="comment">
                    {{ $comment->text }}<a class="goto" href="article.html">&#187;</a>
                </p>
            </div>

            @endforeach

    @endif

        </div>
    </div>

{{--</div>--}}
