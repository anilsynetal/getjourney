function actionSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (section) {
        section.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}


$(document).on('click', '.loadRecordModal, .loadRecordModalLarge, .viewRecordModal, .loadRecordModalPassword', function (e) {
    var modalId = $(this).hasClass('loadRecordModal') ? '#commonModal' : '#commonModalLarge';
    modalId = $(this).hasClass('loadRecordModalPassword') ? '#passwordModal' : modalId;
    loadModalRecord(modalId, $(this).data('url'));
});

const loadModalRecord = (modalId, url) => {
    $.ajax({
        type: "get",
        url,
        success: function (response) {
            $(modalId).find(".modal-content").html(response);
            $(modalId).modal('show');
        }
    });
};


$(document).on('submit', "form[name=form_action]", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    let fail = false;
    $('.has_error').remove();
    $('.response').html('');
    $('.subscription-response').html('');

    $(this).find('select, textarea, input').each(function () {
        if ($(this).prop('required') && !$(this).val()) {
            fail = true;
            const name = $(this).attr('name').replace(/\[\d+\]/g, '').replace(/_id/g, '').replace(/_/g, ' ').toLowerCase();
            const defaultMessage = `Please enter ${name}`;
            if ($(this).closest('.input-group').length) {
                $(this).closest('.input-group').after(`<div class="text-danger has_error">${$(this).data('error') || defaultMessage}</div>`);
            } else {
                $(this).closest('.form-group').append(`<div class="text-danger has_error">${$(this).data('error') || defaultMessage}</div>`);
            }
        }
    });

    if (!fail) {
        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () { $("#spinner").show(); $("button[type='submit']").prop("disabled", true); },
            success: function (data, status, xhr) {
                handleFormSuccess(data, xhr);
            },
            complete: function () { $("#spinner").hide(); $("button[type='submit']").prop("disabled", false); }
        }).fail(handleFormError);
    } else {
        // Focus on first error field
        $(this).find('.has_error').first().prev().focus();
    }
});

// Handle form success response
const handleFormSuccess = (data, xhr) => {
    if (xhr.status === 500) {
        $(data.type == 'contact' ? ".response" : ".subscription-response").html(`
            <div class="alert alert-danger">${data.message}</div>
            `)
    } else {
        $("form[name=form_action]")[0].reset();
        $(data.type == 'contact' ? ".response" : ".subscription-response").html(`
            <div class="alert alert-success">${data.message}</div>
            `)
    }
    $("#spinner").hide();
};

// Handle form error response
const handleFormError = (response, status, error) => {
    const data = response.responseJSON;
    if (status === 'error') {
        if (!data.errors && data.message) {
            $(data.type == 'contact' ? ".response" : ".subscription-response").html(`
            <div class="alert alert-danger">${data.message}</div>
            `)
        } else {
            let errorHtml = '<div class="alert alert-danger"><ul class="mb-0">';
            $.each(data.errors, function (key, value) {
                errorHtml += `<li>${value}</li>`;
            });
            errorHtml += '</ul></div>';
            $(data.type == 'contact' ? ".response" : ".subscription-response").html(errorHtml);
        }
        $("#spinner").hide();
    }
};
