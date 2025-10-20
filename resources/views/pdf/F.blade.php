<style>
    footer {
        position: relative;
        width: 100%;
    }

    footer .img {
        width: 550px;
        /* height: 128px; */
        margin-inline: auto;
    }

    footer img {
        max-width: 100%;
    }

    footer .pageNum {
        position: absolute;
        bottom: 0px;
        left: 15px;
        font-size: 10pt;
    }
</style>
<footer >
    <div class="img">
        @php $logo = public_path('images/footer.png'); @endphp
        @inlinedImage($logo)
    </div>
    <!-- <h1>Hello footer test</h1> -->
    
    <div class="pageNum">
        <span>@pageNumber/@totalPages</span>
    </div>
</footer>