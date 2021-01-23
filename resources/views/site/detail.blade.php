@extends('site.layouts.base')

@section('content')
    <section class="page-breadcrumb">
        <nav>
            <ul class="d-flex justify-content-center align-items-center">
                <li><a href="{{ route('web.index') }}">Home</a></li>
                <li><a href="">PHP</a></li>
                <li><a href="">PHP and GraphQL</a></li>
            </ul>
        </nav>
    </section>

    <section class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="banner">
                    <img src="{{ resize($content->featured_image, 1100) }}" class="w-100" alt="">
                    <div class="content-info">
                        <ul class="d-flex">
                            <li><a href="#"><i class="bi bi-pencil"></i> {{ $content->created_by_name }}</a></li>
                            <li><a href="#"><i class="bi bi-clock"></i> {{ $content->created_at->diffForHumans() }}</a></li>
                            <li><a href="#"><i class="bi bi-chat-text"></i> 8 comment</a></li>
                        </ul>
                        <ul class="d-flex">
                            <li><a href="#"><i class="bi bi-facebook facebook"></i></a></li>
                            <li><a href="#"><i class="bi bi-github github"></i></a></li>
                            <li><a href="#"><i class="bi bi-youtube youtube"></i></a></li>
                            <li><a href="#"><i class="bi bi-linkedin linkedin"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="full-content">
                    <h3>Install with composer</h3>
                    <pre class=" language-php"><code class=" language-php">composer <span class="token keyword">require</span> codethereal<span class="token operator">/</span>sqlite<span class="token operator">-</span>php</code></pre>
                    <p>&nbsp;</p>
                    <h4>Github</h4>
                    <p><a title="dogukan-akkaya" href="https://github.com/dogukanakkaya/sqlitethereal-php" target="_blank" rel="noopener">https://github.com/dogukanakkaya/sqlitethereal-php</a></p>
                </div>
                <div class="content-tags">
                    <a href="#" title="php">PHP</a>
                    <a href="#" title="node-js">Node.js</a>
                    <a href="#" title="laravel">Laravel</a>
                    <a href="#" title="socket-io">Socket.io</a>
                    <a href="#" title="html">HTML</a>
                </div>
                <div class="write-comment">
                    <h5>Leave a comment</h5>
                    <form onsubmit="return false;" class="d-flex">
                        <textarea rows="4" placeholder="Enter your comment..."></textarea>
                        <button class="ce-btn me-0" type="submit">Send <i class="bi bi-check-all"></i></button>
                    </form>
                </div>
                <div class="comments">
                    <h5>Comments (3)</h5>
                    <ul>
                        <li>
                            <div class="comment">
                                <span class="avatar">DA</span>
                                <div>
                                    <h6>Doğukan Akkaya</h6>
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem, ipsum.</p>
                                    <span><i class="bi bi-clock"></i> 16 hours ago</span>
                                    <span><i class="bi bi-reply ms-3"></i> Reply</span>
                                </div>
                            </div>
                            <ul>
                                <li>
                                    <div class="comment">
                                        <span class="avatar">DA</span>
                                        <div>
                                            <h6>Doğukan Akkaya</h6>
                                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem, ipsum.</p>
                                            <span><i class="bi bi-clock"></i> 16 hours ago</span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <div class="comment">
                                <span class="avatar">DA</span>
                                <div>
                                    <h6>Doğukan Akkaya</h6>
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem, ipsum.</p>
                                    <span><i class="bi bi-clock"></i> 16 hours ago</span>
                                    <span><i class="bi bi-reply ms-3"></i> Reply</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3">
                <aside>
                    <div class="item">
                        <input type="search" placeholder="Search...">
                    </div>
                    <div class="item">
                        <h4 class="title">Categories</h4>
                        <ul>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> HTML</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> CSS</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> PHP</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Javascript</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Python</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Java</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> C#</a></li>
                        </ul>
                    </div>
                    <div class="item">
                        <h4 class="title">Recent Posts</h4>
                        <div class="recent-post">
                            <a href="#"><img src="{{ asset('site/img/ejs-template.gif') }}" alt=""></a>
                            <div class="info">
                                <h4><a href="#">Using EJS Template with NodeJS</a></h4>
                                <span class="timestamp">16 hours ago</span>
                            </div>
                        </div>
                        <div class="recent-post">
                            <a href="#"><img src="{{ asset('site/img/ejs-template.gif') }}" alt=""></a>
                            <div class="info">
                                <h4><a href="#">Using EJS Template with NodeJS</a></h4>
                                <span class="timestamp">16 hours ago</span>
                            </div>
                        </div>
                        <div class="recent-post">
                            <a href="#"><img src="{{ asset('site/img/ejs-template.gif') }}" alt=""></a>
                            <div class="info">
                                <h4><a href="#">Using EJS Template with NodeJS</a></h4>
                                <span class="timestamp">16 hours ago</span>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <h4 class="title">Saved Posts</h4>
                        <div class="recent-post">
                            <a href="#"><img src="{{ asset('site/img/ejs-template.gif') }}" alt=""></a>
                            <div class="info">
                                <h4><a href="#">Using EJS Template with NodeJS</a></h4>
                                <span class="timestamp">16 hours ago</span>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
