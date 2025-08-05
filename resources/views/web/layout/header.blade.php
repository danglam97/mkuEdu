<header>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-lg-4 my-2 my-lg-1">
                <div class="d-flex align-items-center">
                    <a href="" class="logo me-1">
                        <img
                            src="{{asset('/style/images/logo-truong.png')}}"
                            alt="Trường đại học cửu long"
                        />
                    </a>
                    <div class="d-flex align-items-center flex-column">
                        <h4>TRƯỜNG ĐẠI HỌC CỬU LONG</h4>
                        <p class="sologan">Toàn diện–Sáng tạo–Hội nhập–Phát triển</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8 my-2 my-lg-1">
                <div class="d-flex align-items-center justify-content-end flex-wrap">
                    <p class="element d-flex align-items-center ms-3">
                        <img
                            class="vector me-1"
                            src="https://c.animaapp.com/mdrent4ttKlpku/img/vector.svg"
                        />
                        <a href="tel:02703832538">02703832538</a>
                    </p>
                    <p class="element d-flex align-items-center ms-3">
                        <img
                            class="envelope-solid me-1"
                            src="{{asset('/style/images/envelope-solid.png')}}"
                        />
                        <a href="mailto:cuulonguniversity@mku.edu.vn"
                        >cuulonguniversity@mku.edu.vn</a
                        >
                    </p>
                    <p class="element d-flex align-items-center ms-3">
                        <img
                            class="hand-point-up-solid me-1"
                            src="{{asset('/style/images/hand-point-up-solid.png')}}"
                        />
                        Tuyển sinh: Thông báo tuyển sinh Đại học hệ chính quy
                    </p>
                </div>
            </div>
        </div>
    </div>
</header>
<section id="navBar" class="w-100">
    <nav class="navbar navbar-expand-lg navbar-light bg-light mku-menu">
        <div class="container">
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav position-relative mku-menu-list">
                    @foreach ($menus as $item)
                        <li class="nav-item {{ $item->children->isNotEmpty() ? 'has-children' : '' }}">
                            <a class="nav-link" href="#">{{ $item->name }}</a>

                            @if ($item->children->isNotEmpty())
                                <div class="mku-megamenu clearfix halfmenu container-fluid">
                                    <div class="row d-flex">
                                        @foreach ($item->children as $child)
                                            <ul>
                                                <li class="title mega-title">{{ $child->name }}</li>

                                                @if ($child->children->isNotEmpty())
                                                    @foreach ($child->children as $subChild)
                                                        <li>
                                                            <i class="fa fa-angle-right"></i>
                                                            <a href="{{ url($subChild->slug) }}">{{ $subChild->name }}</a>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>

            </div>
        </div>
    </nav>

    <div id="slideBar" class="banner-index">
        <div class="banner-slider w-100">
            <div class="banner slider w-100">
                <div
                    class="banner-item"
                    style="background-image: url('{{asset('/style/images/banner/1.jpg')}}')"
                >
                    <div class="banner-caption container">
                        <div class="content box">
                            <h2 class="text-uppercase wow zoomInDown">H2 title</h2>
                            <h2>
                                <span class="sub-title"> Subtitle</span>
                            </h2>
                            <div class="note">Ghi chú</div>
                            <a href="#" class="btn-custom-01 text-uppercase">
                                Thử ngay
                            </a>
                        </div>
                    </div>
                </div>
                <div
                    class="banner-item"
                    style="background-image: url('{{asset('/style/images/banner/2.jpg')}}')"
                >
                    <div class="banner-caption container">
                        <div class="content box">
                            <h2 class="text-uppercase wow zoomInDown">H2 title</h2>
                            <h2>
                                <span class="sub-title"> Subtitle</span>
                            </h2>
                            <div class="note">Ghi chú</div>
                            <a href="#" class="btn-custom-01 text-uppercase">
                                Thử ngay
                            </a>
                        </div>
                    </div>
                </div>
                <div
                    class="banner-item"
                    style="background-image: url('{{asset('/style/images/banner/3.jpg')}}')"
                >
                    <div class="banner-caption container">
                        <div class="content box">
                            <h2 class="text-uppercase wow zoomInDown">H2 title</h2>
                            <h2>
                                <span class="sub-title"> Subtitle</span>
                            </h2>
                            <div class="note">Ghi chú</div>
                            <a href="#" class="btn-custom-01 text-uppercase">
                                Thử ngay
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
