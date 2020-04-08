<div class="col-md-10 col-sm-10 col-md-offset-1">
    <div class="clearfix visible-sm-block"></div>
    <div class="panel panel-chat shoutbox">
        <div class="panel-heading">
            <h4><i class="{{ config('other.font-awesome') }} fa-comments"></i> @lang('blocks.latest-posts')</h4>
        </div>
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered mobile-table">
                <thead>
                    <tr>
                        <th class="torrents-filename" scope="col">@lang('forum.post')</th>
                        <th scope="col">@lang('forum.topic')</th>
                        <th scope="col">@lang('forum.author')</th>
                        <th scope="col">@lang('forum.created')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $p)
                        @if ($p->topic->viewable())
                            <tr>
                                <td data-label="@lang('forum.post')">
                                    <a
                                        href="{{ route('forum_topic', ['id' => $p->topic->id]) }}?page={{ $p->getPageNumber() }}#post-{{ $p->id }}">{{ preg_replace('#\[[^\]]+\]#', '', Str::limit(htmlspecialchars_decode($p->content)), 75) }}
                                        ...</a></td>
                                <td data-label="@lang('forum.topic')">{{ $p->topic->name }}</td>
                                <td data-label="@lang('forum.author')">{{ $p->user->username }}</td>
                                <td data-label="@lang('forum.created')">{{ $p->updated_at->diffForHumans() }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <thead>
                    <tr>
                        <th colspan="4" class="text-right"><a href="{{ route('forum_latest_posts') }}"
                                class="text-info">@lang('articles.read-more')</a></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
