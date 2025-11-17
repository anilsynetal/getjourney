@php
    if (Session::get('lang')) {
        $active_lang = session('iso_code');
    } else {
        $active_lang = 'en';
    }
@endphp
<script>
    var LANG = @json(__($active_lang));
</script>
<!-- JAVASCRIPT -->

<script src="{{ URL::asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ URL::asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ URL::asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ URL::asset('assets/vendor/libs/@algolia/autocomplete-js.js') }}"></script>
<script src="{{ URL::asset('assets/vendor/libs/pickr/pickr.js') }}"></script>

<script src="{{ URL::asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

<script src="{{ URL::asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
{{-- <script src="{{ URL::asset('assets/vendor/libs/i18n/i18n.js') }}"></script> --}}
<script src="{{ URL::asset('assets/vendor/js/menu.js') }}"></script>
<!-- Vendors JS -->
<script src="{{ URL::asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<!-- Main JS -->
<script src="{{ URL::asset('assets/js/main.js') }}"></script>
<!-- Page JS -->
<script src="{{ URL::asset('assets/js/dashboards-analytics.js') }}"></script>
<script src="{{ URL::asset('assets/vendor/js/helpers.js') }}"></script>

<script src="{{ URL::asset('assets/js/config.js') }}"></script>
<script src="{{ URL::asset('assets/libs/intro/intro.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/custom.js') }}"></script>


<script src="https://cdn.tiny.cloud/1/b82nrf66yji3718np2wxbcjz01q5uu8gm1lw0rkzt5goc2ij/tinymce/8/tinymce.min.js"
    referrerpolicy="origin" crossorigin="anonymous"></script>
@yield('script')



<!-- Main JS -->
@if (Auth::user()->is_password_updated === 0)
    <script>
        //on page load
        $(document).ready(function() {
            $('.loadRecordModalPassword').trigger('click');
            //Prevent the modal from being closed
            $('#passwordModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            //Hide close button if text having
            $('#passwordModal').on('show.bs.modal', function(e) {
                if (e.target.id === 'passwordModal') {
                    $('.close').hide();
                }
            });
        });
    </script>
@endif
<script>
    document.getElementById('search-input').addEventListener('input', function() {
        let query = this.value.trim();

        if (query.length > 0) {
            fetch(`/admin/search-suggestions?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    let suggestionsBox = document.getElementById('suggestions');
                    suggestionsBox.innerHTML = '';

                    if (data.length > 0) {
                        data.forEach(item => {
                            let listItem = document.createElement('li');
                            listItem.textContent = item.menu_name;
                            listItem.classList.add('list-group-item', 'list-group-item-action');

                            // Add a click event to select the suggestion
                            listItem.addEventListener('click', function() {
                                document.getElementById('search-input').value = item
                                    .menu_name;
                                document.getElementById('route_name').value = item
                                    .route_name;
                                suggestionsBox.style.display = 'none';
                            });

                            suggestionsBox.appendChild(listItem);
                        });
                        suggestionsBox.style.display = 'block';
                    } else {
                        suggestionsBox.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error fetching suggestions:', error));
        } else {
            document.getElementById('suggestions').style.display = 'none';
        }
    });
</script>
