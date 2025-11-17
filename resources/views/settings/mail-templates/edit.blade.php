    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">@lang('translation.EditTemplate')</h5>
    </div>
    <div class="modal-body">
        <form name="master_form" method="post" action="{{ route('settings.update-mail-template', $result->id) }}"
            novalidate="">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label for="subject" class="form-label">@lang('translation.Subject') <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control " id="subject" name="subject"
                            placeholder="@lang('translation.Subject')" autocomplete="off" value="{{ $result->subject }}"
                            required="">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label for="html_template" class="form-label">@lang('translation.HTMLTemplate') <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control ckeditor-classic" id="html_template" name="html_template" rows="5" required=""><?php echo $result->html_template; ?></textarea>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger modal-close">Close</button>
                        <button type="submit" class="btn btn-primary form-button">Update</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="{{ URL::asset('assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>

    <!-- init js -->
    <script>
        ClassicEditor
            .create(document.querySelector('.ckeditor-classic'))
            .then(function(editor) {
                editor.ui.view.editable.element.style.height = '200px';
            })
            .catch(function(error) {
                console.error(error);
            });
    </script>
