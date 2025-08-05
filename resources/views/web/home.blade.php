@extends('web.layout.manin')
@section('content')
    <section id="tin-tuc">
        <div class="container">
            <div class="section-header text-center row">
                <h1 class="text-wrapper-3 text-danger text-capitalize fw-bolder">
                    Tin Tức
                </h1>
                <p class="fw-bolder">
                    Thông tin mới nhất và kiến thức hữu ích từ Trường Đại Học Cửu Long
                </p>
            </div>

            {{-- Tin nổi bật (bài đầu tiên) --}}
            @if($postNews->isNotEmpty())
                @php $firstPostNew = $postNews->first(); @endphp
                <div class="tin-tc row">
                    <div class="col-12">
                        <div class="position-relative item-head">
                            <img src="{{ $firstPostNew->thumbnail ? get_image_url($firstPostNew->thumbnail) : asset('/style/images/banner-tin-tuc.png') }}"
                                 class="img-fluid w-100"
                                 alt="{{ $firstPostNew->name }}">
                            <div class="head-title position-absolute py-3">
                                <h3>{{ $firstPostNew->name }}</h3>
                                <p>
                                    {{ Str::limit(strip_tags($firstPostNew->description ?? ''), 100) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>


            {{-- Danh sách các tin khác --}}
            <div class="row my-0 my-md-3">
                @foreach($postNews->skip(1) as $postNew)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3 mt-md-0">
                        <div class="item">
                            <div class="img">
                                <img src="{{ $postNew->thumbnail ? get_image_url($postNew->thumbnail) : asset('/style/images/no-image.png') }}"
                                     alt="{{ $postNew->name }}"
                                     class="img-fluid w-100">
                            </div>
                            <div class="title p-2">
                                <h5 class="text-ellipsis-3">
                                    {{ $postNew->name }}
                                </h5>
                                <p class="text-end align-self-end">
                                    {{ $postNew->created_date->format('d/m/Y') }} |
                                    <a href="#">Xem tiếp</a>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Nút xem thêm --}}
            <div class="text-center w-100 section-footer">
                <a href="#" class="btn-danger">Xem thêm</a>
            </div>
            @endif
        </div>

    </section>
    <section id="tuyen-sinh">
        <div class="container">
            <div class="section-header">
                <div class="mt-3 mt-md-0 text-center w-100">
                    <h1>Tuyển sinh</h1>
                    <p>
                        Chất lượng vượt trội - Gắn liền thực tiễn - Vươn tầm quốc tế
                    </p>
                </div>
            </div>
        </div>
        <div class="w-100">
            <div class="mt-3 mt-md-0 position-relative p-0">
                <div class="img">
                    <img
                        src="/style/images/banner-chuong-trinh-dao-tao.png"
                        alt=""
                        class="img-fluid w-100"
                    />
                </div>
                <div class="position-absolute btn-chat">
                    <a href="#" class="btn-warning fw-bold p-2"
                    >Chat với bộ phân tuyển sinh</a
                    >
                </div>
            </div>
        </div>
    </section>
    <section id="su-kien">
        <div class="container">
            <div class="section-header">
                <h1 class="text-center text-danger text-capitalize">Sự kiện</h1>
                <p class="text-center fw-bold">
                    Những sự kiện nổi bật sắp diễn ra tại của trường Đại học Cửu Long
                </p>
            </div>
        </div>
        @if($postEvents->isNotEmpty())
        <div class="container">
            <div class="row">

                @foreach($postEvents as $event)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3 mt-md-0">
                        <div class="item position-relative">
                            <div class="img h-60">
                                <img
                                    src="{{ $event->image ? get_image_url($event->image) : asset('/style/images/default.png') }}"
                                    alt="{{ $event->name }}"
                                    class="img-fluid w-100"
                                />
                            </div>
                            <div class="title p-2 h-40">
                                <h5 class="text-ellipsis-3">
                                    {{ $event->name }}
                                </h5>
                                <div class="item-bottom d-flex align-items-end flex-column bd-highlight mb-3">
                                    <p class="mt-auto p-2 bd-highlight">
                                        {{ $event->created_at->format('d/m/Y') }} |
                                        <a href="#">Xem tiếp</a>
                                    </p>
                                </div>
                            </div>
                            <div class="img-date position-absolute">
                                <img src="{{ asset('/style/images/date.png') }}" alt="" />
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
            <div class="section-footer">
                <div class="text-center w-100">
                    <a href="#" class="btn-danger">Xem thêm</a>
                </div>
            </div>
        </div>
        @endif
        <div class="video w-100">
            @if($mediaVideo)
                <video controls autoplay muted loop playsinline class="video img-fluid p-0 w-100">
                    <source src="{{ get_video_url($mediaVideo->url) }}" type="video/mp4">
                </video>
            @else
                <img
                    class="video img-fluid p-0 w-100"
                    src="https://c.animaapp.com/mdrent4ttKlpku/img/video.png"
                />
            @endif
        </div>
    </section>

    <section id="khoa-hoc-cong-nghe">
        @if($menuScienceTechnology)
        <div class="container">
            <div class="section-header text-center row">
                <h1>{{$menuScienceTechnology ? $menuScienceTechnology->name : ""}}</h1>
                <p class="fw-bold">Nghiên cứu vì lợi ích cộng đồng</p>
            </div>

            <div class="row">

                @foreach($menuScienceTechnology->children as $menuScienceTechnologyChildren)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3 mt-md-0">
                    <div class="item-khoa-hoc">
                        <div class="img h-60">

                                <img
                                    src="{{get_image_url($menuScienceTechnologyChildren->icon)}}"
                                    alt=""
                                    class="img-fluid w-100"
                                />
                        </div>
                        <h3 class="text-center text-uppercase h-40">
                            {{$menuScienceTechnologyChildren->name}}
                        </h3>
                    </div>
                </div>
                @endforeach

            </div>
            @endif
        </div>
    </section>
    <section id="dao-tao">
        <div class="container">
            @if($menuTrain)
            <div class="section-header text-center row">
                <h1 class="text-danger">{{$menuTrain->name}}</h1>
                <p class="fw-bold">
                    Đào tạo công dân toàn cầu, hành động vì sự phát triển bền vững
                </p>
            </div>
            <div class="row">
               @foreach($menuTrain->children as $menuTrainchildren)
                <div class="col-12 col-sm-6 col-md-4 col-lg-4 mt-3 mt-md-0">
                    <div class="item-dao-tao">
                        <div class="img h-60">
                                <img
                                    src="{{get_image_url($menuTrainchildren->icon)}}"
                                    alt=""
                                    class="img-fluid w-100"
                                />
                        </div>
                        <h3 class="text-center text-uppercase">{{$menuTrainchildren->name}}</h3>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
            <div class="row">
                <h3 class="text-center text-danger my-4">
                    Trường/Khoa/Viện đào tạo
                </h3>
                <div class="row d-flex justify-content-around">
                    <div class="col-2 col-sm-2">
                        <h2 class="text-center text-danger">11</h2>
                        <p class="text-center">Giáo sư</p>
                    </div>
                    <div class="col-2 col-sm-2">
                        <h2 class="text-center text-danger">75</h2>
                        <p class="text-center">Phó giáo sư</p>
                    </div>
                    <div class="col-2 col-sm-2">
                        <h2 class="text-center text-danger">11</h2>
                        <p class="text-center">Giáo sư</p>
                    </div>
                    <div class="col-2 col-sm-2">
                        <h2 class="text-center text-danger">11</h2>
                        <p class="text-center">Giáo sư</p>
                    </div>
                    <div class="col-2 col-sm-2">
                        <h2 class="text-center text-danger">11</h2>
                        <p class="text-center">Giáo sư</p>
                    </div>
                </div>
            </div>
            <hr />
            <div class="row">
                <p class="text-center khoa-dao-tao">
                    @if($majors->isNotEmpty())
                        {{ $majors->pluck('name')->implode(' | ') }}
                    @endif
                </p>
            </div>
            <div class="row">
                <h3 class="text-center text-danger my-4">Các nghành đào tạo</h3>
                <div class="d-flex justify-content-around slide-dao-tao">
                    <button class="btn-prev me-2">
                        <img src="/style/images/circle-left-solid.png" alt="" />
                    </button>

                    <div class="slider-dao-tao row">
                        @if($majors->isNotEmpty())
                        @foreach($majors as $major)
                        <div
                            class="item-slide col-12 col-sm-6 col-md-4 col-lg-3 mt-3 mt-md-0"
                        >
                            <div class="item-slide-dao-tao border-0 px-2">
                                <div class="img h-60">
                                    <img
                                        src="{{get_image_url($major->icon)}}"
                                        alt=""
                                        class="img-fluid w-100"
                                    />
                                </div>
                                <p class="text-center fw-bold">{{$major->name}}</p>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    <button class="btn-next ms-2">
                        <img src="/style/images/circle-right-solid.png" alt="" />
                    </button>
                </div>
                <div class="text-center section-footer">
                    <a href="#" class="btn-danger">Đăng ký ngay</a>
                </div>
            </div>
        </div>
    </section>
    <section id="hop-tac-qt">

        <div class="container">
            <div class="section-header text-center">
                <h1><h1>{{$menuCooperate ? $menuCooperate->name : ""}}</h1></h1>
                <p class="fw-bold">
                    Kết nối cộng đồng - Lan tỏa tri thức - Hành động bền vững
                </p>
            </div>
            <div class="row">
                @if($menuCooperate->children)
                @foreach($menuCooperate->children as $cooperate)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-3 mt-md-0">
                    <div class="item">
                        <div class="img h-60">
                            <img
                                src="{{get_image_url($cooperate->icon)}}"
                                alt=""
                                class="img-fluid w-100"
                            />
                        </div>
                        <div class="title p-2">
                            <p>
                                {{$cooperate->name}}
                            </p>
                            <a class="d-flex justify-content-end" href="#">Xem tiếp</a>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>

    </section>
    <section id="phuong-cham">
        <div class="container">
            <div class="section-header text-center">
                <h1 class="text-danger">Trường đại học Cửu Long</h1>
                <p>Phương châm đào tạo : "Đạo đức - Tri thức - Tận dụng"</p>
            </div>
            <div class="img">
                <img src="/style/images/banner-3.png" alt="" class="img-fluid w-100" />
            </div>
            <div class="section-footer text-center">
                <a href="#" class="btn-danger">Xem thêm</a>
            </div>
        </div>
    </section>
    <section id="gioi-thieu">
        <div class="container">
            <div class="section-header text-center">
                <h1 class="">Giới thiệu</h1>
                <p class="fw-bold">Toàn diện - Sáng tạo - Hội nhập - Phát triển</p>
            </div>
            <div class="row d-flex justify-content-around">
                <div class="col-2 col-sm-2">
                    <h2 class="text-center text-danger">2000</h2>
                    <p class="text-center">Thành lập</p>
                </div>
                <div class="col-2 col-sm-2">
                    <h2 class="text-center text-danger">11</h2>
                    <p class="text-center">Phòng ban</p>
                </div>
                <div class="col-2 col-sm-2">
                    <h2 class="text-center text-danger">2</h2>
                    <p class="text-center">Phân hiệu</p>
                </div>
                <div class="col-2 col-sm-2">
                    <h2 class="text-center text-danger">6</h2>
                    <p class="text-center">Đơn vị khoa học,kinh doanh</p>
                </div>
                <div class="col-2 col-sm-2">
                    <h2 class="text-center text-danger">37.830</h2>
                    <p class="text-center">Quy mô sinh viên</p>
                </div>
            </div>
            <div class="section-footer text-center">
                <a href="#" class="btn-danger">Xem thêm giới thiệu về trường</a>
            </div>
        </div>
    </section>
    <section id="kham-pha ">
        <div class="kham-pha-container position-relative">
            <div class="img">
                <img
                    src="/style/images/bg-banner-4.png"
                    alt=""
                    class="img-fluid w-100"
                />
            </div>
            <div class="overlay position-absolute w-100 h-100">
                <div
                    class="container position-absolute top-50 start-50 translate-middle"
                >
                    <div class="section-header">
                        <h1 class="text-center">Khám phá Trường Đai học Cửu Long</h1>
                        <p class="text-center fw-bold">
                            lorem ipsum dolor sit amet, consectetur
                        </p>
                    </div>
                    <div class="noi-dung">
                        <h4 class="text-center text-uppercase">
                            XÉT TUYỂN TRỰC TUYẾN | CỔNG THÔNG TIN SINH VIÊN | ĐÀO TẠO TỪ
                            XA | THƯ VIỆN SỐ | TRA CỨU VĂN BẰNG | TRA CỨU ĐIỂM TIẾNG ANH
                            B1 | TRA CỨU VĂN BẰNG THẠC SĨ | HOẠT ĐỘNG ĐBCL GD
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="hoat-dong">
        <div class="section-header">
            <h1 class="text-center text-danger">
                Hoạt động sinh viên - Đoàn đội
            </h1>
            <p class="text-center fw-bold">
                Những hoạt động sôi nổi của đoàn sinh viên trường
            </p>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div
                        class="slide-sinhvien d-flex align-items-center position-relative"
                    >
                        <button class="btn-prev me-2">
                            <img src="/style/images/circle-left-solid.png" alt="" />
                        </button>

                        <div class="slider w-100">
                            @if($albumMedias->isNotEmpty() )
                            @foreach($albumMedias as $album)
                                <div class="album-container">
                                    <div class="left">
                                        @if($album->mainImage)
                                            <img src="{{  get_image_url($album->mainImage->image) }}" alt="{{ $album->title }}">
                                        @endif
                                    </div>
                                    <div class="right">
                                        @foreach($album->subImages as $image)
                                            <img src="{{ get_image_url($image->image) }}" alt="Ảnh phụ">
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                            @endif
                        </div>

                        <button class="btn-next ms-2">
                            <img src="/style/images/circle-right-solid.png" alt="" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
