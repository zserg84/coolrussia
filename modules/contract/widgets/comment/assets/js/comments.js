(function ($) {
    // Comments plugin
    $.comments = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.comments');
            return false;
        }
    };

    var defaults = {
        listSelector: '[data-comment="list"]',
        modalSelector: '[data-comment="modal"]',
        commenttypeSelector: '[data-comment="comment-type"]',
        formGroupSelector: '[data-comment="form-group"]',
        errorSummarySelector: '[data-comment="form-summary"]',
        errorSummaryToggleClass: 'hidden',
        errorClass: 'has-error',
        offset: 0
    };

    // Delete comment
    $(document).on('click', '[data-comment="delete"]', function (evt) {
        evt.preventDefault();

        var data = $.data(document, 'comments'),
            $this = $(this);

        if($this.data('comment-confirm')){
            bootbox.confirm($this.data('comment-confirm'), function(result) {
                if(result)
                    deleteComment($this);
            });
        }
        else
            deleteComment($this);
    });

    function deleteComment(link){
        $.ajax({
            url: $(link).data('comment-url'),
            type: 'DELETE',
            error: function (xhr, status, error) {
                alert('error');
            },
            success: function (result, status, xhr) {
                $.comments('listReload');
            }
        });
    }

    // change comment type
    $(document).on('click', '[data-comment="comment-type"]', function (evt) {
        var data = $.data(document, 'comments'),
            $this = $(this);
        var commentType = $this.data('comment-type')
        $.comments('listReload', commentType);
    });

    // AJAX create form submit
    $(document).on('submit', '[data-comment-action="create"]', function (evt) {
        evt.preventDefault();

        var data = $.data(document, 'comments'),
            $this = $(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            beforeSend: function (xhr, settings) {
                $this.find('[type="submit"]').attr('disabled', true);
            },
            complete: function (xhr, status) {
                $this.find('[type="submit"]').attr('disabled', false);
            },
            error: function (xhr, status, error) {
                $this.find('[type="submit"]').attr('disabled', false);
                if (xhr.status === 400) {
                    response = xhr.responseJSON;
                    response = jQuery.parseJSON(response);
                    $.comments('updateErrors', $this, response.error);
                } else {
                    alert(error);
                }
            },
            success: function (response, status, xhr) {
                if(response){
                    response = jQuery.parseJSON(response);
                    if(response.output == 'success'){
                        $this.trigger('reset');
                        $.comments('clearErrors', $this);

                        setTimeout(function(){
                            $.comments('listReload');
                        }, 500);

                    }
                }
            }
        });
    });

    var methods = {
        init: function (options) {
            if ($.data(document, 'comments') !== undefined) {
                return;
            }

            // Set plugin data
            $.data(document, 'comments', $.extend({}, defaults, options || {}));

            return this;
        },
        destroy: function () {
            $(document).unbind('.comments');
            $(document).removeData('comments');
        },
        data: function () {
            return $.data(document, 'comments');
        },
        listReload: function (commentType) {
            var data = $.data(document, 'comments');
            var url = $('.comments').data('comment-url');
            $.ajax({
                url: url,
                type: 'GET',
                error: function (xhr, status, error) {
                    alert('error');
                },
                success: function (result, status, xhr) {
                    $(data.listSelector).html(result);
                }
            });
            //$.pjax({url: url, container: data.listSelector, data:{commentType:commentType}, push:false, replace:false});
        },
        updateErrors: function ($form, response) {
            var data = $.data(document, 'comments'),
                message = '';

            console.log(response);
            $.each(response, function (id, msg) {
                $('#' + id).parent().find('.help-block').html(msg);
                $('#' + id).closest(data.formGroupSelector).addClass(data.errorClass);
                message += msg;
            });

            $form.find(data.errorSummarySelector).toggleClass(data.errorSummaryToggleClass).text(message);
        },
        clearErrors: function ($form) {
            var data = $.data(document, 'comments');

            $form.find('.' + data.errorClass).removeClass(data.errorClass);
            $form.find(data.errorSummarySelector).toggleClass(data.errorSummaryToggleClass).text('');
        }
    };

})(window.jQuery);