<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
        <div class="mb-2 mb-md-0">
            {{ date('Y') }} ©
            {{ \App\Utils\Util::getSettingValue('app_name') ? \App\Utils\Util::getSettingValue('app_name') : config('app.name') }},
            Design & Developed by
            <a href="https://aparkitsolutions.com/">
                APARK IT Solutions
            </a>
        </div>
    </div>
</footer>
<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>
