<style>
    header {
        width: 550px;
        height: 128px;
        margin: auto;
    }

    header img {
        width: 100%;   
    }
</style>
<header >
    @php $logo = public_path('images/small_header.webp'); @endphp
    @inlinedImage($logo)

    <!-- <h1>Hello Header test</h1> -->
    
</header>