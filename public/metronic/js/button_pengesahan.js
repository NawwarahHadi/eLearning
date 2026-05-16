var toggleNeedConsent = function () {
    var isEnableCheckbox = $('#pengesahan').prop('checked');
    $('.perlu_pengesahan').prop('disabled', !isEnableCheckbox);
    if (!isEnableCheckbox) {
        if (!$(".needConsentButtonOutterDiv").length) {
            $('.perlu_pengesahan').wrap(
                "<div class='needConsentButtonOutterDiv' style='display: inline-block; position:relative'></div>"
            );
        }
        $('.perlu_pengesahan').after(
            '<div class="needConsentButtonDiv" style="position:absolute; top:0; left:0; width: 100%; height:100%; ;"></div></div>'
        );

        $('.needConsentButtonDiv').click(function (e) {

            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Sila klik pengesahan terlebih dahulu!',
            })

        })
    } else {
        $(".needConsentButtonDiv").remove();
    }
}

$(function () {
    toggleNeedConsent();
    $('#pengesahan').click(function () {
        toggleNeedConsent();
    });

    $("#approveButton").click(function () {
        // alert('You are a wizard, Harry!');
    })

})
