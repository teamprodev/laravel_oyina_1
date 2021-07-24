{{-- Allomalar haqida section --}}
<section class="scholars">

    <div class="container mx-auto flex items-center align-middle justify-between">
      <div id="filters-scholars" class="filters-scholars flex">
        <button class="filter-option-scholars active-2" data-filter="ancestor" onclick=filterscholars(event)>@lang('site.content_menus.our_gen') <div class="div-bootom-line-scholars"></div></button>
        <button class="filter-option-scholars"  data-filter="hero" onclick=filterscholars(event)>@lang('site.content_menus.our_hero') <div class="div-bootom-line-scholars"></div></button>
      </div>
      <a href="{{ route('generations') }}" class="all-media">@lang('site.navbar.all')</a>
    </div>
    <div class="flex flex-row items-center align-middle">
      <div class="transform">@lang('site.content_menus.heroes_life')</div>
      <div class="swiper-container mySwiper">
        <div class="swiper-wrapper">
            @foreach ($generations as $item)

            @if ($item->type == 'option1')
              <div class="swiper-slide ancestor">
                <a href="{{ route('generation', ['id' => $item->id]) }}">
                  <div class="scholars-img">
                    @php
                      $images = json_decode($item->image);
                    @endphp
                  @foreach ($images as $i)
                    @if($loop->first)
                      <img src="{{ Voyager::image($i) }}" alt="">
                    @endif
                  @endforeach
                  </div>
                  <div class="cholars-text">
                    <p>{{ $item->fio }}</p>
                    </div>
                  </a>
                </div>
            @endif

            @if ($item->type == 'option2')

            {{-- Zomonamiz qahramonlar --}}

              <div class="swiper-slide hero hidden">
                <a href="{{ route('generation', ['id' => $item->id]) }}">
                  <div class="scholars-img">
                  @php
                    $images = json_decode($item->image);
                  @endphp
                  @foreach ($images as $i)
                    @if($loop->first)
                      <img src="{{ Voyager::image($i) }}" alt="">
                    @endif
                  @endforeach
                  </div>
                  <div class="cholars-text">
                    <p>{{ $item->fio }}</p>
                  </div>
                </a>
              </div>

            @endif
            @endforeach

        </div>
      </div>

    </div>
  </section>
