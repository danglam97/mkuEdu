@extends('web.layout.manin')
@section('content')
    <!-- Tin tức -->
    <section id="news">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="item-head mt-3">
                        <h2 class="text-center mt-3">Tin tức</h2>
                        <p class="head-title text-center mb-3">
                            Thông tin mới nhất và kiến thức hữu ích từ Trường đại học Cửu Long
                        </p>
                    </div>
                </div>
            </div>

            {{-- Tin nổi bật đầu tiên --}}
            @if($postNews->count() > 0)
                @php $highlight = $postNews->first(); @endphp
                <div class="row">
                    <div class="col-12">
                        <div class="item-first position-relative">
                            <img src="{{ get_image_url($highlight->image) }}" alt="{{ $highlight->name }}" class="cover-img" />
                            <div class="title w-100 bottom-0 position-absolute py-3">
                                <h3 class="text-center">TIN TỨC NỔI BẬT</h3>
                                <p class="w-100 text-center">
                                    <a href="{{ route('detail.post_new',['slug'=> $highlight->slug]) }}">{{ $highlight->name }}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Các tin còn lại --}}
            <div class="row my-0 my-md-3">
                @foreach($postNews->slice(1) as $post)
                    <div class="col-sm-6 col-md-4 col-lg-3 my-2">
                        <div class="item border">
                            <div class="img">
                                <img src="{{ get_image_url($post->image) }}" alt="{{ $post->name }}" class="w-100" />
                            </div>
                            <div class="item-content">
                                <a href="{{ route('detail.post_new',['slug'=> $post->slug]) }}" class="d-block p-2 mt-1">{{ $post->name }}</a>
                                <p class="d-flex justify-content-end align-items-end p-2">
                                    <span class="me-1">{{ $post->created_at->format('d/m/Y') }}</span> |
                                    <a href="{{ route('detail.post_new',['slug'=> $post->slug]) }}" class="ms-1">Xem tiếp</a>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Nút xem thêm --}}
            <div class="row my-0 my-md-3">
                <div class="col-12 text-center">
                    <a href="{{ route('post_new.index') }}" class="btn button px-3 py-2 rounded-0">Xem thêm</a>
                </div>
            </div>
        </div>
    </section>

    <!-- tuyển sinh -->
    <section id="tuyen-sinh">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="item-head mt-3">
                        <h2 class="text-center">Tuyển sinh</h2>
                        <p class="head-title text-center mb-3">
                            Thông tin mới nhất và kiến thức hữu ích từ Trường đại học cửu
                            long
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <img src="{{ asset('/style/images/banner-chuong-trinh-dao-tao.png')}}" alt="" class="w-100 mt-3" />
    </section>
    <!-- Sự kiện -->
    <section id="event">
        <div class="container ">
            <div class="row">
                <div class="col-12">
                    <div class="item-head mt-3">
                        <h2 class="text-center mt-3">Sự kiện</h2>
                        <p class="head-title text-center mb-3">
                            Những sự kiện nổi bật sắp diễn ra tại Trường Đại Học Cửu Long
                        </p>
                    </div>
                </div>
            </div>
            <div class="row my-0 my-md-3">
                @if($postEvents->isNotEmpty())
                @foreach($postEvents as $postEvent)
                <div class="col-sm-6 col-md-4 col-lg-3 my-2">
                    <div class="item border">
                        <div class="img">
                            <img src="{{ get_image_url($postEvent->image)}}" alt="2" class="w-100" />
                        </div>
                        <div class="item-content">
                            <a href="{{ route('detail.post_event',['slug'=> $postEvent->slug]) }}" class="d-block p-2 mt-1"
                            >{{$postEvent->name}}</a
                            >
                            <p class="d-flex justify-content-end align-items-end p-2">
                                <span class="me-1">{{ $postEvent->created_at->format('d/m/Y') }}</span> |
                                <a href="{{ route('detail.post_event',['slug'=> $postEvent->slug]) }}" class="ms-1">Xem tiếp</a>
                            </p>
                        </div>
                    </div>
                </div>
                    @endforeach
                @endif
            </div>
            <div class="row my-0 my-md-3">
                <div class="col-12 text-center">
                    <a href="{{ route('post_event') }}" class="btn button px-3 py-2 rounded-0">Xem thêm</a>
                </div>
            </div>
        </div>
        <div class="w-100 home-video">
            <iframe width="100%" height="777" src="https://www.youtube.com/embed/dNOmLwFSVlc" title="Giới thiệu Trường Đại học Cửu Long - MKU" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
    </section>
    <!-- khoa học công nghệ  -->
    <section id="khcn">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="item-head mt-3">
                        <h2 class="text-center mt-3">Khoa Học Công Nghệ</h2>
                        <p class="head-title text-center mb-3">
                            Nghiên cứu vì lợi ích cộng đồng
                        </p>
                    </div>
                </div>
            </div>

            <div class="row my-0 my-md-3">
                @foreach($menuScienceTechnology->children as $menuScienceTechnologyChildren)
                <div class="col-sm-6 col-md-4 col-lg-3 my-2">
                    <div class="item border">
                        <div class="img">
                            <img src="{{get_image_url($menuScienceTechnologyChildren->icon)}}" alt="kh-cn-1.png" class="w-100" />
                        </div>
                        <div class="item-content">
                            <h4 class="text-center ">
                                <a href="{{ route('danh-muc')}}" class="d-block">{{$menuScienceTechnologyChildren->name}}</a>
                            </h4>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
            <div class="row my-0 my-md-3">
                <div class="col-12 text-center">
                    <a href="{{ route('danh-muc')}}" class="btn button px-3 py-2 rounded-0">Xem thêm</a>
                </div>
            </div>
        </div>
    </section>
    <!-- Đào taoj -->
    <section id="dao-tao">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="item-head mt-3">
                        <h2 class="text-center mt-3">Đào tạo</h2>
                        <p class="head-title text-center mb-3">
                            Đào tạo công dân toàn cầu, hành động vì sự phát triển bền vững
                        </p>
                    </div>
                </div>
            </div>

            <div class="row my-0 my-md-3">
                @foreach($menuTrain->children as $menuTrainchildren)
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="item border">
                        <div class="img">
                            <img src="{{get_image_url($menuTrainchildren->icon)}}" alt="kh-cn-1.png" class="w-100" />
                        </div>
                        <div class="item-content">
                            <h4 class="text-center ">
                                <a href="{{ route('danh-muc')}}" class="d-block">{{$menuTrainchildren->name}}</a>
                            </h4>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
            <div class="row my-0 my-md-3">
                <div class="col-12 text-center">
                    <h5 class="pt-4">Trường/Khoa/Viện đào tạo</h5>
                    <div class="d-flex justify-content-between align-items-center py-3 flex-wrap">
                        <div class="item box-shadow-none">
                            <h3>11</h3>
                            <p>Giáo sư</p>
                        </div>
                        <div class="item box-shadow-none">
                            <h3>75</h3>
                            <p>Phó giáo sư</p>
                        </div>
                        <div class="item box-shadow-none">
                            <h3>350</h3>
                            <p>Tiến sĩ</p>
                        </div>
                        <div class="item box-shadow-none">
                            <h3>75</h3>
                            <p>Phó giáo sư</p>
                        </div>
                        <div class="item box-shadow-none">
                            <h3>366</h3>
                            <p>Thạc sĩ</p>
                        </div>
                        <div class="item box-shadow-none">
                            <h3>150</h3>
                            <p>Chứng chỉ quốc tế</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-0 my-md-3">
                <div class="col-12 text-center">
                    @if($majors->isNotEmpty())
                        <a href="{{ route('danh-muc')}}">
                            {{ $majors->pluck('name')->implode(' | ') }}
                        </a>
                    @endif

                </div>
            </div>

        </div>
    </section>
@endsection
