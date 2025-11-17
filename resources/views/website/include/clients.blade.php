<section class="company-sb pt-30 pb-30">
    <div class="container-fluid">
        <div class="company-slider">
            @foreach ($clients as $client)
                <div class="company-item">
                    <a target="_blank" href="{{ $client->link }}">
                        <div class="company-img p-3">
                            <img src="{{ asset($client->logo) }}" alt="Company Thumbnail" width="150">
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
