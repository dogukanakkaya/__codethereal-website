@extends('site.layouts.base')

@section('content')
    <section class="page-breadcrumb">
        <nav>
            <ul class="d-flex justify-content-center align-items-center">
                <li><a href="">Home</a></li>
                <li><a href="">PHP</a></li>
            </ul>
        </nav>
    </section>

    <section class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="row gy-5 contents">
                    @for ($i = 0; $i < 6; $i++)
                        <div class="col-md-6">
                            <div class="card">
                                <span class="date">27 December</span>
                                <div class="image">
                                    <a href="#">
                                        <img src="{{ asset('site/img/code_382x260.jpg') }}" alt="">
                                    </a>
                                    <div class="item-overlay">
                                        <a href="#"> <i class="bi bi-link-45deg"></i></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><a href="#">Real Time Chat with NodeJS & Socket.io</a></h5>
                                    <p class="card-text">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quas, voluptatum?</p>
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center card-bottom">
                                        <a href="#"><i class="bi bi-pencil"></i> Admin</a>
                                        <a href="#"><i class="bi bi-chevron-double-right"> Read More</i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                    <div class="col-md-12 mb-5">
                        <ul class="pagination d-flex justify-content-center">
                            <li><a href="#"><i class="bi bi-arrow-left"></i></a></li>
                            <li><a href="#">3</a></li>
                            <li class="active"><a href="#">4</a></li>
                            <li><a href="#">5</a></li>
                            <li><a href="#"><i class="bi bi-arrow-right"></i></a></li>
                        </ul>
                    </div>
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
