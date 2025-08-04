@foreach ($menus as $menu)
    <li class="nav-item">
        {{-- Nếu là trang chủ --}}
        @if($menu->slug === 'trang-chu')
            <a class="nav-link text-white" href="{{ url('/') }}">
                {{ $menu->name }}
            </a>
        @else
            {{-- Nếu không có menu con thì điều hướng trực tiếp --}}
            @if($menu->children->isEmpty())
                <a class="nav-link text-white"
                   @if($menu->type == 0)
                       href="{{ $menu->slug }}" {{-- hoặc $menu->notes nếu URL nằm ở đó --}}
                   @else
                       href="{{ route('menu.posts', $menu->slug) }}"
                    @endif>
                    {{ $menu->name }}
                </a>
            @else
                {{-- Có menu con thì dùng collapse --}}
                <a class="nav-link text-white"
                   data-bs-toggle="collapse"
                   href="#{{ $menu->slug }}"
                   role="button"
                   aria-expanded="false"
                   aria-controls="{{ $menu->slug }}"
                   data-bs-parent="#navbarMobile">
                    {{ $menu->name }}
                </a>

                <div class="collapse" id="{{ $menu->slug }}">
                    <div class="card card-body">
                        @foreach ($menu->children as $child)
                            <div class="topic-box">
                                {{-- Nếu topic không có con thì cho click --}}
                                @if($child->children->isEmpty())
                                    <div class="topic-title">
                                        <a @if($child->type == 0)
                                               href="{{ $child->slug }}"
                                           @else
                                               href="{{ route('menu.posts', $child->slug) }}"
                                           @endif
                                           class="text-decoration-none text-dark">
                                            {{ $child->name }}
                                        </a>
                                    </div>
                                @else
                                    <div class="topic-title">{{ $child->name }}</div>
                                    @foreach ($child->children as $item)
                                        <div class="topic-item">
                                            <i class="fa-solid fa-caret-right"></i>
                                            <a @if($item->type == 0)
                                                   href="{{ $item->slug }}"
                                               @else
                                                   href="{{ route('menu.posts', $item->slug) }}"
                                               @endif
                                               class="text-decoration-none text-dark">
                                                {{ $item->name }}
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </li>
@endforeach
