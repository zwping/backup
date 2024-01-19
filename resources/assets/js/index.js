
$(function () {
	
    $(".backup-run").click(function() {
        var $btn = $(this);
        $btn.button('loading');

        NProgress.start();
        $.ajax({
            url: $btn.attr('href'),
            data : {
                _token: LA.token
            },
            method: 'POST',
            success: function (data){

                if (data.status) {
                    $('.output-box').removeClass('hide');
                    $('.output-box .output-body').html(data.message)
                }

                $btn.button('reset');
                NProgress.done();
            }
        });

        return false;
    });

    $(".backup-delete").click(function() {

        var $btn = $(this);

        $.ajax({
            url: $btn.attr('href'),
            data : {
                _token: LA.token
            },
            method: 'DELETE',
            success: function (data){

                $.pjax.reload('#pjax-container');

                if (typeof data === 'object') {
                    if (data.status) {
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }

                $btn.button('reset');
            }
        });

        return false;
    });

});