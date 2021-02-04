@extends('layouts.site')

@push('styles')
    <link rel="stylesheet" href="{{ asset('site/css/prism.css') }}">
@endpush

@section('content')
    <section class="page-breadcrumb" style="background: linear-gradient(to right,rgba(12, 41, 116, 0.84) 0%,rgba(35, 107, 237, 0.84) 48%), url({{ asset('site/img/home-top-bg.jpg') }});">
        <nav>
            <ul>
                <li><a href="{{ route('web.index') }}">{{ __('site.home') }}</a></li>
                @foreach($parentTree as $pTree)
                    <li><a href="{{ createUrl($pTree['url']) }}">{{ $pTree['title'] }}</a></li>
                @endforeach
                <li>{{ $post->title }}</li>
            </ul>
        </nav>
    </section>

    <section class="container mb-5">
        <div class="row">
            <div class="col-lg-9">
                <div class="banner">
                    <img src="{{ resize($post->featured_image, 1200) }}" class="w-100" alt="">
                    <div class="content-info">
                        <ul class="d-flex">
                            {{--<li><a href="#"><i class="bi bi-pencil"></i> {{ $content->created_by_name }}</a></li>--}}
                            <vote count="{{ $vote }}" vote-route="{{ route('web.vote') }}" post-id="{{ $post->id }}"></vote>
                            <li><span><i class="bi bi-clock"></i> {{ $post->created_at->diffForHumans() }}</span></li>
                            <li><span><i class="bi bi-chat-text"></i> {{ $commentCount }} {{ __('site.comment.self_plural') }}</span></li>
                        </ul>
                        <ul class="d-flex">
                            <li><a href="#"><i class="bi bi-linkedin linkedin"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="full-content">
                    {!! $post->full !!}
                </div>
                <div class="content-tags">
                    @foreach(array_map('trim', explode(',', $post->meta_tags)) as $tag)
                        <a href="{{ createUrl('t/' . $tag) }}" title="{{ $tag }}">{{ ucfirst($tag) }}</a>
                    @endforeach
                </div>
                <div class="alert alert-dismissible fade show d-none ce-alert comment-alert" role="alert">
                    <span></span>
                    <button type="button" class="btn-close" aria-label="Close" onclick="replyTo = 0;this.closest('.comment-alert').classList.add('d-none')"></button>
                </div>
                <div class="write-comment">
                    @if(auth()->check())
                    <h5>
                        {{ __('site.comment.leave_a_comment') }}
                    </h5>
                    {{ Form::open(['id' => 'comment-form', 'class' => 'd-flex']) }}
                    {{ Form::textarea('comment', '', ['rows' => 4, 'placeholder' => __('site.comment.enter_comment'), 'required' => true]) }}
                    <button class="ce-btn me-0" type="submit">Send <i class="bi bi-check-all"></i></button>
                    {{ Form::close() }}
                    @else
                        <h6>
                            {!! __('site.comment.must_login') !!}
                        </h6>
                    @endif

                </div>
                <div class="comments">
                    <h5>{{ __('site.comment.self_plural') }} ({{ $commentCount }})</h5>
                    <ul>
                        @each('site.partials.comment', $comments, 'comment')
                    </ul>
                </div>
            </div>
            <div class="col-lg-3">
                <aside>
                    <div class="item">
                        <input type="search" placeholder="Search...">
                    </div>
                    <div class="item">
                        <h4 class="title">{{ __('site.categories') }}</h4>
                        <ul>
                            @foreach($categoryLinks as $categoryLink)
                                <li><a href="{{ createUrl($categoryLink->url) }}"><i class="bi bi-chevron-right"></i> {{ $categoryLink->title }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="item">
                        <h4 class="title">{{ __('site.relational_posts') }}</h4>
                        @foreach($relationalPosts as $relationalPost)
                            <div class="recent-post">
                                <a href="{{ createUrl($relationalPost->url) }}"><img src="{{ resize($relationalPost->featured_image, 200) }}" alt=""></a>
                                <div class="info">
                                    <h4><a href="{{ createUrl($relationalPost->url) }}">{{ $relationalPost->title }}</a></h4>
                                    <span class="timestamp">{{ $relationalPost->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        @if(auth()->check())
        let replyTo = 0

        document.getElementById('comment-form').addEventListener('submit', async e => {
            e.preventDefault()
            const data = serialize(e.target, {hash: true, empty: true})
            data.post_id = {{ $post->id }}
            data.parent_id = replyTo

            const {data: {status, message}} = await request.post('{{ route('web.comment') }}', data)

            const alertEl = document.querySelector('.comment-alert')
            if (status){
                replaceClasses(alertEl, ['d-none', 'alert-danger'], ['alert-success'])
                e.target.reset()
            }else{
                replaceClasses(alertEl, ['d-none', 'alert-success'], ['alert-danger'])
            }
            alertEl.querySelector('span').textContent = message
        })

        const __replyTo = (id, name) => {
            replyTo = id
            const alertEl = document.querySelector('.comment-alert')
            replaceClasses(alertEl, ['d-none', 'alert-danger', 'alert-success'], ['alert-primary'])
            alertEl.querySelector('span').textContent = '{{ __('site.comment.reply_to', ['name' => ':name']) }}'.replace(':name', name)
        }
        @endif
    </script>
@endpush
