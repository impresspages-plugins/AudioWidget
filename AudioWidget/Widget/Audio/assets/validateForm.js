$('form').validator(validatorConfig);
$('form').submit(function(e) {
    var form = $(this);

    // client-side validation OK.
    if (!e.isDefaultPrevented()) {
        $.ajax({
            url: ip.baseUrl, //we assume that for already has m, g, a parameters which will lead this request to required controller
            dataType: 'json',
            type : 'POST',
            data: form.serialize(),
            success: function (response){
                if (response.status && response.status == 'success') {
                    //form has been successfully submitted. 
                } else {
                    //PHP controller says there are some errors
                    if (response.errors) {
                        form.data("validator").invalidate(response.errors);
                    }
                }
            }
        });
    }
    e.preventDefault();
});