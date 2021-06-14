<section class="clients-logo-section pt-70 pb-70">
    <div class="container">
        <div id='logo-carousel' class="owl-carousel owl-theme">
            @foreach ($lst_brand as $key)
            <a href="{{ route('user/dien-thoai-theo-hang', ['brand' => $key['brand']]) }}"><img src="{{ $url_logo.$key['image']}}"></a>    
            @endforeach
        </div>
    </div>
</section>