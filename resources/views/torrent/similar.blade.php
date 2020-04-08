@extends('layout.default')

@section('title')
    <title>@lang('torrent.torrents') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('torrent.torrents')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('torrents') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('torrent.torrents')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('torrents.similar', ['category_id' => $torrents->first()->category_id, 'tmdb' => $torrents->first()->tmdb]) }}"
            itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('torrent.similar')</span>
        </a>
    </li>
@endsection

@section('content')
    @php $client = new \App\Services\MovieScrapper(config('api-keys.tmdb') , config('api-keys.tvdb') ,
    config('api-keys.omdb')) @endphp
@php $meta = null; @endphp
    @if ($torrents->first()->category->tv_meta)
@php $meta = $client->scrape('tv', null, $tmdb); @endphp
    @endif
    @if ($torrents->first()->category->movie_meta)
@php $meta = $client->scrape('movie', null, $tmdb); @endphp
    @endif
    <div class="container-fluid">
        <div class="block">
            <div class="header gradient light_blue">
                <div class="inner_content">
                    <h1>{{ $meta->title }} ({{ $meta->releaseYear }})</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 movie-list">
                    <div class="pull-left">
                        <a href="#">
                            <img src="{{ $meta->poster }}" style="height:200px; margin-right:10px;"
                                alt="{{ $meta->title }} @lang('torrent.poster')">
                        </a>
                    </div>
                    <h2 class="movie-title text-bold">
                        {{ $meta->title }} ({{ $meta->releaseYear }})
                        <span class="badge-user text-bold text-gold">@lang('torrent.rating'):
                            <span class="movie-rating-stars">
                                <i class="{{ config('other.font-awesome') }} fa-thumbs-up"></i>
                            </span>
                            @if ($user->ratings == 1)
                                {{ $meta->imdbRating }}/10 ({{ $meta->imdbVotes }} @lang('torrent.votes'))
                            @else
                                {{ $meta->tmdbRating }}/10 ({{ $meta->tmdbVotes }} @lang('torrent.votes'))
                            @endif
                        </span>
                    </h2>
                    <div class="movie-details">
                        <p class="movie-plot">{{ $meta->plot }}</p>
                        <strong>ID:</strong>

                        <span class="badge-user"><a href="https://www.imdb.com/title/{{ $meta->imdb }}"
                                target="_blank">{{ $meta->imdb }}</a></span>
                        @if ($torrents->first()->category_id == "2" && $torrents->first()->tmdb != 0 &&
                            $torrents->first()->tmdb != null)
                            <span class="badge-user"><a
                                    href="https://www.themoviedb.org/tv/{{ $meta->tmdb }}?language={{ config('app.locale') }}"
                                    target="_blank">{{ $meta->tmdb }}</a></span>
                        @elseif ($torrents->first()->tmdb != 0 && $torrents->first()->tmdb != null)
                            <span class="badge-user"><a
                                    href="https://www.themoviedb.org/movie/{{ $meta->tmdb }}?language={{ config('app.locale') }}"
                                    target="_blank">{{ $meta->tmdb }}</a></span>
                        @endif
                        <strong>@lang('torrent.genre'): </strong>
                        @if ($meta->genres)
                            @foreach ($meta->genres as $genre)
                                <span class="badge-user text-bold text-green">{{ $genre }}</span>
                            @endforeach
                        @endif
                    </div>
                    <br>
                    <ul class="list-inline">
                        <li><i class="{{ config('other.font-awesome') }} fa-files"></i> <strong>@lang('torrent.torrents'):
                            </strong> {{ $torrents->count() }}</li>
                        <li>
                            <a href="{{ route('upload_form', ['title' => $meta->title, 'imdb' => $meta->imdb, 'tmdb' => $meta->tmdb]) }}"
                                class="btn btn-xs btn-danger">
                                @lang('common.upload') {{ $meta->title }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-condensed table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>@lang('torrent.category')</th>
                            <th>@lang('torrent.name')</th>
                            <th><i class="{{ config('other.font-awesome') }} fa-clock"></i></th>
                            <th><i class="{{ config('other.font-awesome') }} fa-file"></i></th>
                            <th><i class="{{ config('other.font-awesome') }} fa-arrow-circle-up"></i></th>
                            <th><i class="{{ config('other.font-awesome') }} fa-arrow-circle-down"></i></th>
                            <th><i class="{{ config('other.font-awesome') }} fa-check-square"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($torrents as $torrent)
                            @if ($torrent->sticky == 1)
                                <tr class="success">
                                @else
                                <tr>
                                @endif
                                <td>
                                    <a href="{{ route('categories.show', ['id' => $torrent->category->id]) }}">
                                        <div class="text-center">
                                            <i class="{{ $torrent->category->icon }} torrent-icon" data-toggle="tooltip"
                                                data-original-title="{{ $torrent->category->name }} {{ strtolower(trans('torrent.torrent')) }}"
                                                style="padding-bottom: 6px;"></i>
                                        </div>
                                    </a>
                                    <div class="text-center">
                                        <span class="label label-success" data-toggle="tooltip"
                                            data-original-title="@lang('torrent.type')">
                                            {{ $torrent->type }}
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <a class="view-torrent" href="{{ route('torrent', ['id' => $torrent->id]) }}" data-toggle="tooltip" data-original-title="{{$torrent->name}}">

                                      @if(!empty($torrent->mediainfo))
                                        @php $parsedMediaInfo = $torrent->getParsedMediaInfo() @endphp
                                      @else
                                        @php $parsedMediaInfo = null @endphp
                                      @endif


                                      @if(in_array($torrent->type, [ 'UHD-100','UHD-66','UHD-50', 'BD50','BD25' ]))
                                        {{ $torrent->type }}
                                      @else
                                        @if(strpos(Arr::get($parsedMediaInfo,'video.0.writing_library'), 'x265') !== false)
                                          x265
                                        @elseif(strpos(Arr::get($parsedMediaInfo,'video.0.writing_library'), 'x264') !== false)
                                          x264
                                        @elseif(strpos(Arr::get($parsedMediaInfo,'video.0.format'), 'VC-1') !== false)
                                          VC-1
                                        @elseif(strpos(Arr::get($parsedMediaInfo,'video.0.format_profile'), 'High@L4.1') !== false || strpos(Arr::get($parsedMediaInfo,'video.0.format_profile'), 'High@L4') !== false)
                                          H.264
                                        @elseif(strpos(Arr::get($parsedMediaInfo,'video.0.format_profile'), 'Main 10@L5.1@High') !== false)
                                          H.265
                                        @endif
                                      @endif

                                      /

                                      @if(strpos(Arr::get($parsedMediaInfo,'general.format'), 'MPEG-4') !== false)
                                        MP4
                                      @elseif(strpos(Arr::get($parsedMediaInfo,'general.format'), 'Matroska') !== false)
                                        MKV
                                      @elseif(in_array($torrent->type, [ 'UHD-100','UHD-66','UHD-50', 'BD50','BD25' ]))
                                        m2ts
                                      @endif

                                      /

                                      {{ $torrent->type }}

                                      /

                                      Resolution

                                      @php $atmos_track = false; @endphp
                                      @if(!empty(Arr::get($parsedMediaInfo,'audio',[])))
                                        @foreach(Arr::get($parsedMediaInfo,'audio',[]) as $audio_track)
                                          @if(strpos(Arr::get($audio_track,'codec'), 'A_TRUEHD') !== false && strpos(Arr::get($audio_track,'channels'), '8ch') !== false)
                                            @php $atmos_track = true; @endphp
                                          @endif
                                        @endforeach
                                      @endif

                                      @if($atmos_track == true)
                                        /
                                        Dolby Atmos
                                      @endif

                                      @php $video_hdr = false; @endphp

                                      @foreach(Arr::get($parsedMediaInfo,'video',[]) as $video_track)
                                        @if(strpos(Arr::get($video_track,'title'), 'BT.2020') !== false)
                                          @php $video_hdr = true; @endphp
                                        @endif
                                      @endforeach

                                      @if($video_hdr == true)
                                        /
                                        HDR
                                      @endif


                                    </a>
                                    @if (config('torrent.download_check_page') == 1)
                                        <a href="{{ route('download_check', ['id' => $torrent->id]) }}">
                                            <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="@lang('common.download')">
                                                <i class="{{ config('other.font-awesome') }} fa-download"></i>
                                            </button>
                                        </a>
                                    @else
                                        <a href="{{ route('download', ['id' => $torrent->id]) }}">
                                            <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="@lang('common.download')">
                                                <i class="{{ config('other.font-awesome') }} fa-download"></i>
                                            </button>
                                        </a>
                                    @endif

                                    @php $history = \App\Models\History::where('user_id', '=', $user->id)->where('info_hash',
                                    '=', $torrent->info_hash)->first(); @endphp
                                    @if ($history)
                                        @if ($history->seeder == 1 && $history->active == 1)
                                            <button class="btn btn-success btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="@lang('torrent.currently-seeding')!">
                                                <i class="{{ config('other.font-awesome') }} fa-arrow-up"></i>
                                            </button>
                                        @endif

                                        @if ($history->seeder == 0 && $history->active == 1)
                                            <button class="btn btn-warning btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="@lang('torrent.currently-leeching')!">
                                                <i class="{{ config('other.font-awesome') }} fa-arrow-down"></i>
                                            </button>
                                        @endif

                                        @if ($history->seeder == 0 && $history->active == 0 && $history->completed_at == null)
                                            <button class="btn btn-info btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="@lang('torrent.not-completed')!">
                                                <i class="{{ config('other.font-awesome') }} fa-spinner"></i>
                                            </button>
                                        @endif

                                        @if ($history->seeder == 0 && $history->active == 0 && $history->completed_at != null)
                                            <button class="btn btn-danger btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="@lang('torrent.completed-not-seeding')!">
                                                <i class="{{ config('other.font-awesome') }} fa-thumbs-down"></i>
                                            </button>
                                        @endif
                                    @endif

                                    <br>

                                    @if(!empty(Arr::get($parsedMediaInfo,'audio',[])))
                                      <span>Audio:</span>
                                      @php $audio_array = array() @endphp

                                      @foreach(Arr::get($parsedMediaInfo,'audio',[]) as $audio_track)
                                        @php $audio_array[] = getLanguageFlag(Arr::get($audio_track, 'language','')) @endphp
                                      @endforeach

                                      @php $audio_array = array_unique($audio_array) @endphp

                                      @foreach($audio_array as $audio_track)
                                      <img src="{{ $audio_track }}" alt="{{ $audio_track }}"
                                      width="20" height="13" data-toggle="tooltip" data-original-title="{{ $audio_track }}">
                                      @endforeach
                                    @endif

                                    @if(!empty(Arr::get($parsedMediaInfo,'subtitle',[])))
                                      <span class="ml-3">Subtitles:</span>
                                      @php $subtitle_array = array() @endphp

                                      @foreach(Arr::get($parsedMediaInfo,'subtitle',[]) as $subtitle_track)
                                        @php $subtitle_array[] = getLanguageFlag(Arr::get($subtitle_track, 'language','')) @endphp
                                      @endforeach

                                      @php $subtitle_array = array_unique($subtitle_array) @endphp

                                      @foreach($subtitle_array as $subtitle_track)
                                        <img src="{{ $subtitle_track }}" alt="{{ $subtitle_track }}"
                                        width="20" height="13" data-toggle="tooltip" data-original-title="{{ $subtitle_track }}">
                                      @endforeach
                                    @endif

                                    <br>

                                    @if ($torrent->anon == 1)
                                        <span class="badge-extra text-bold">
                                            <i class="{{ config('other.font-awesome') }} fa-upload" data-toggle="tooltip"
                                                data-original-title="@lang('torrent.uploader')"></i> @lang('common.anonymous')
                                            @if ($user->id == $torrent->user->id || $user->group->is_modo)
                                                <a href="{{ route('users.show', ['username' => $torrent->user->username]) }}">
                                                    ({{ $torrent->user->username }})
                                                </a>
                                            @endif
                                        </span>
                                    @else
                                        <span class="badge-extra text-bold">
                                            <i class="{{ config('other.font-awesome') }} fa-upload" data-toggle="tooltip"
                                                data-original-title="@lang('torrent.uploader')"></i>
                                            <a href="{{ route('users.show', ['username' => $torrent->user->username]) }}">
                                                {{ $torrent->user->username }}
                                            </a>
                                        </span>
                                    @endif

                                        <span class="badge-extra text-bold text-pink">
                                            <i class="{{ config('other.font-awesome') }} fa-heart" data-toggle="tooltip"
                                                data-original-title="@lang('torrent.thanks-given')"></i>
                                            {{ $torrent->thanks_count }}
                                        </span>

                                        <span class="badge-extra text-bold text-green">
                                            <i class="{{ config('other.font-awesome') }} fa-comment" data-toggle="tooltip"
                                                data-original-title="@lang('common.comments')"></i>
                                            {{ $torrent->comments_count }}
                                        </span>

                                        @if ($torrent->internal == 1)
                                            <span class='badge-extra text-bold'>
                                                <i class='{{ config('other.font-awesome') }} fa-magic' data-toggle='tooltip'
                                                    title='' data-original-title='@lang(' torrent.internal-release')'
                                                    style="color: #baaf92;"></i> @lang('torrent.internal')
                                            </span>
                                        @endif

                                        @if ($torrent->stream == 1)
                                            <span class='badge-extra text-bold'>
                                                <i class='{{ config('other.font-awesome') }} fa-play text-red' data-toggle='tooltip'
                                                    title='' data-original-title='@lang(' torrent.stream-optimized')'></i>
                                                @lang('torrent.stream-optimized')
                                            </span>
                                        @endif

                                        @if ($torrent->featured == 0)
                                            @if ($torrent->doubleup == 1)
                                                <span class='badge-extra text-bold'>
                                                    <i class='{{ config('other.font-awesome') }} fa-gem text-green'
                                                        data-toggle='tooltip' title='' data-original-title='@lang('
                                                        torrent.double-upload')'></i> @lang('torrent.double-upload')
                                                </span>
                                            @endif
                                            @if ($torrent->free == 1)
                                                <span class='badge-extra text-bold'>
                                                    <i class='{{ config('other.font-awesome') }} fa-star text-gold'
                                                        data-toggle='tooltip' title='' data-original-title='@lang('
                                                        torrent.freeleech')'></i> @lang('torrent.freeleech')
                                                </span>
                                            @endif
                                        @endif

                                        @if ($personal_freeleech)
                                            <span class='badge-extra text-bold'>
                                                <i class='{{ config('other.font-awesome') }} fa-id-badge text-orange'
                                                    data-toggle='tooltip' title='' data-original-title='@lang('
                                                    torrent.personal-freeleech')'></i> @lang('torrent.personal-freeleech')
                                            </span>
                                        @endif

                                        @php $freeleech_token = \App\Models\FreeleechToken::where('user_id', '=',
                                        $user->id)->where('torrent_id', '=', $torrent->id)->first(); @endphp
                                        @if ($freeleech_token)
                                            <span class='badge-extra text-bold'>
                                                <i class='{{ config('other.font-awesome') }} fa-star text-bold'
                                                    data-toggle='tooltip' title='' data-original-title='@lang('
                                                    torrent.freeleech-token')'></i> @lang('torrent.freeleech-token')
                                            </span>
                                        @endif

                                        @if ($torrent->featured == 1)
                                            <span class='badge-extra text-bold' style='background-image:url(/img/sparkels.gif);'>
                                                <i class='{{ config('other.font-awesome') }} fa-certificate text-pink'
                                                    data-toggle='tooltip' title='' data-original-title='@lang('
                                                    torrent.featured')'></i> @lang('torrent.featured')
                                            </span>
                                        @endif

                                        @if ($user->group->is_freeleech == 1)
                                            <span class='badge-extra text-bold'>
                                                <i class='{{ config('other.font-awesome') }} fa-trophy text-purple'
                                                    data-toggle='tooltip' title='' data-original-title='@lang('
                                                    torrent.special-freeleech')'></i> @lang('torrent.special-freeleech')
                                            </span>
                                        @endif

                                        @if (config('other.freeleech') == 1)
                                            <span class='badge-extra text-bold'>
                                                <i class='{{ config('other.font-awesome') }} fa-globe text-blue'
                                                    data-toggle='tooltip' title='' data-original-title='@lang('
                                                    torrent.global-freeleech')'></i> @lang('torrent.global-freeleech')
                                            </span>
                                        @endif

                                        @if (config('other.doubleup') == 1)
                                            <span class='badge-extra text-bold'>
                                                <i class='{{ config('other.font-awesome') }} fa-globe text-green'
                                                    data-toggle='tooltip' title='' data-original-title='@lang('
                                                    torrent.double-upload')'></i> @lang('torrent.double-upload')
                                            </span>
                                        @endif

                                                    @if ($user->group->is_double_upload == 1)
                                                        <span class='badge-extra text-bold'>
                                                <i class='{{ config('other.font-awesome') }} fa-trophy text-purple'
                                                   data-toggle='tooltip' title='' data-original-title='@lang('
                                                    torrent.special-double_upload')'></i> @lang('torrent.special-double_upload')
                                            </span>
                                                    @endif

                                        @if ($torrent->leechers >= 5)
                                            <span class='badge-extra text-bold'>
                                                <i class='{{ config('other.font-awesome') }} fa-fire text-orange'
                                                    data-toggle='tooltip' title='' data-original-title='@lang(' common.hot')!'></i>
                                                @lang('common.hot')!
                                            </span>
                                        @endif

                                        @if ($torrent->sticky == 1)
                                            <span class='badge-extra text-bold'>
                                                <i class='{{ config('other.font-awesome') }} fa-thumbtack text-black'
                                                    data-toggle='tooltip' title='' data-original-title='@lang('
                                                    torrent.sticky')!'></i> @lang('torrent.sticky')
                                            </span>
                                        @endif

                                        @if ($user->updated_at->getTimestamp() < $torrent->created_at->getTimestamp())
                                                <span class='badge-extra text-bold'>
                                                    <i class='{{ config('other.font-awesome') }} fa-magic text-black'
                                                        data-toggle='tooltip' title='' data-original-title='@lang('
                                                        common.new')!'></i> @lang('common.new')
                                                </span>
                                            @endif

                                            @if ($torrent->highspeed == 1)
                                                <span class='badge-extra text-bold'>
                                                    <i class='{{ config('other.font-awesome') }} fa-tachometer text-red'
                                                        data-toggle='tooltip' title='' data-original-title='@lang('
                                                        common.high-speeds')'></i> @lang('common.high-speeds')
                                                </span>
                                            @endif

                                            @if ($torrent->sd == 1)
                                                <span class='badge-extra text-bold'>
                                                    <i class='{{ config('other.font-awesome') }} fa-ticket text-orange'
                                                        data-toggle='tooltip' title='' data-original-title='@lang('
                                                        torrent.sd-content')!'></i> @lang('torrent.sd-content')
                                                </span>
                                            @endif
                                </td>

                                <td>
                                    <time>{{ $torrent->created_at->diffForHumans() }}</time>
                                </td>
                                <td>
                                    <span class='badge-extra text-blue text-bold'>{{ $torrent->getSize() }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('peers', ['id' => $torrent->id]) }}">
                                        <span class='badge-extra text-green text-bold'>
                                            {{ $torrent->seeders }}
                                        </span>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('peers', ['id' => $torrent->id]) }}">
                                        <span class='badge-extra text-red text-bold'>
                                            {{ $torrent->leechers }}
                                        </span>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('history', ['id' => $torrent->id]) }}">
                                        <span class='badge-extra text-orange text-bold'>
                                            {{ $torrent->times_completed }} @lang('common.times')
                                        </span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
