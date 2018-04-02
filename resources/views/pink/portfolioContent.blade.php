<div id="content-page" class="content group">
    @if($portfolios && $mainPortfolio)
    <div class="clear"></div>
    <div class="posts">
        <div class="group portfolio-post internal-post">
            <div id="portfolio" class="portfolio-full-description">

                <div class="fulldescription_title gallery-filters">
                    <h1>{{ $mainPortfolio->title }}</h1>
                </div>

                <div class="portfolios hentry work group">
                    <div class="work-thumbnail">
                        <a class="thumb"><img src="{{asset(env('THEME'))}}/images/projects/{{ $mainPortfolio->img->max }}" alt="0081" title="0081" /></a>
                    </div>
                    <div class="work-description">
                        <p>{!!   $mainPortfolio->text !!}</p>
                        <div class="clear"></div>
                        <div class="work-skillsdate">
                            <p class="skills"><span class="label">Filter:</span> {{ $mainPortfolio->filter_alias }}</p>
                            <p class="workdate"><span class="label">Customer:</span> {{ $mainPortfolio->customer }}</p>
                            <p class="workdate"><span class="label">Year:</span> {{ $mainPortfolio->created_at->format('Y') }}</p>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="clear"></div>

                <h3>Other Projects</h3>

                <div class="portfolio-full-description-related-projects">

                    @foreach($portfolios as $portfolio)

                    <div class="related_project">
                        <a class="related_proj related_img" href="{{ route('portfolios.show', ['alias' => $portfolio->alias]) }}" title="Love"><img src="{{asset(env('THEME'))}}/images/projects/{{ $portfolio->img->mini }}" alt="0061" title="0061" /></a>
                        <h4><a href="{{ route('portfolios.show', ['alias' => $portfolio->alias]) }}">{{ $portfolio->title }}</a></h4>
                    </div>

                    @endforeach


                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
        @endif
</div>