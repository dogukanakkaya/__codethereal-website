@extends('layouts.site')

@push('styles')
    <link rel="stylesheet" href="{{ asset('site/css/prism.css') }}">
@endpush

@section('content')
    <section class="page-breadcrumb" style="background: linear-gradient(to right,rgba(12, 41, 116, 0.84) 0%,rgba(35, 107, 237, 0.84) 48%);">
        <nav>
            <ul>
                <li><a href="{{ route('web.index') }}">{{ __('site.home') }}</a></li>
                @foreach($parent_tree as $tree_items)
                    <li>
                    @foreach($tree_items as $item)
                        <a href="{{ createUrl($item['url']) }}">{{ $item['title'] }}</a>
                    @endforeach
                    </li>
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
                            <vote :sum="{{ $vote_sum }}" vote-route="{{ route('web.vote') }}" :post-id="{{ $post->id }}" :is-voted="{{ $voted ? $voted->vote : 0 }}" :authenticated="{{ auth()->check() ? 'true' : 'false' }}"></vote>

                            <li><span><i class="bi bi-clock"></i> {{ $post->created_at->diffForHumans() }}</span></li>
                            <li onclick="document.querySelector('.write-comment').scrollIntoView()" class="c-pointer">
                                <span><i class="bi bi-chat-text"></i> {{ $comment_count }}</span>
                            </li>
                            <li><span><i class="bi bi-eye"></i> {{ $post->views }}</span></li>
                        </ul>
                        <ul class="d-flex">
                            <save-post save-post-route="{{ route('web.save_post') }}" :post-id="{{ $post->id }}" :is-saved="{{ $saved ? 'true' : 'false' }}" :authenticated="{{ auth()->check() ? 'true' : 'false' }}"></save-post>
                        </ul>
                    </div>
                </div>
                <div class="full-content customize-content">
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
                    @auth
                    <h5>
                        {{ __('site.comment.leave_a_comment') }}
                    </h5>
                    <form id="comment-form" class="d-flex">
                        <textarea name="comment" rows="4" placeholder="{{ __('site.comment.enter_comment') }}" required></textarea>
                        <button class="ce-btn me-0" type="submit">Send <i class="bi bi-check-all"></i></button>
                    </form>
                    @endauth
                    @guest
                        <h6>
                            {!! __('site.comment.must_login') !!}
                        </h6>
                    @endguest

                </div>
                <div class="comments">
                    <h5>{{ __('site.comment.self_plural') }} ({{ $comment_count }})</h5>
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
                            @foreach($category_links as $category_link)
                                <li><a href="{{ createUrl($category_link->url) }}"><i class="bi bi-chevron-right"></i> {{ $category_link->title }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="item">
                        <h4 class="title">{{ __('site.relational_posts') }}</h4>
                        @foreach($relational_posts as $relational_post)
                            <div class="recent-post">
                                <a href="{{ createUrl($relational_post->url) }}"><img src="{{ resize($relational_post->featured_image, 200) }}" alt=""></a>
                                <div class="info">
                                    <h4><a href="{{ createUrl($relational_post->url) }}">{{ $relational_post->title }}</a></h4>
                                    <span class="timestamp">{{ $relational_post->created_at->diffForHumans() }}</span>
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
    <script src="{{ asset('site/js/prism.js') }}"></script>
    <script>
        @auth
        let replyTo = 0

        document.addEventListener('DOMContentLoaded', () => {
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
        })

        const __replyTo = (id, name) => {
            replyTo = id
            const alertEl = document.querySelector('.comment-alert')
            replaceClasses(alertEl, ['d-none', 'alert-danger', 'alert-success'], ['alert-primary'])
            alertEl.querySelector('span').textContent = '{{ __('site.comment.reply_to', ['name' => ':name']) }}'.replace(':name', name)
        }
        @endauth
    </script>
@endpush
