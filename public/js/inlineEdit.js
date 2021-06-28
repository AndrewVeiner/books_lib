$('form.tr').each(function () {
    let form = this;
    $(form).find('input,textarea').on('blur', function () {
        let input = this;
        $.ajax({
            url: $(form).attr('action'),
            data: new FormData(form),
            processData: false,
            contentType: false,
            type: 'POST',
            success: (data) => {
                if ($(input).attr('type') == 'file') {
                    let inputId = $(input).attr('id');
                    let newUrl = $(data).find(`label[for="${inputId}"]>img`).attr('src')
                    $(input.nextElementSibling).find('img').attr('src', newUrl);
                }
            }
        })
        if ($(input).attr('type') != 'file') {
            $(input.nextElementSibling).text($(input).val())
        }
    })
})

$('form.aut').each(function () {
    let form = this;
    $(form).find('input,textarea').on('blur', function () {
        let input = this;
        $.ajax({
            url: $(form).attr('action'),
            data: new FormData(form),
            processData: false,
            contentType: false,
            type: 'POST',
        })
        $(input.nextElementSibling).text($(input).val())

    })
})