$(function(){
    /*
     * Обработчик универсален для всех gridView, даже если их несколько на странице.
     * При клике на все кнопки действий (отмечены классом grid-action) происходит ajax-запрос, после чего скрипт ищет pjax-контейнер для кнопки,
     * на которую кликнули, и заставляет pjax перезагрузить грид.
     * */
    $('body').on('click', '.grid-action', function(e){
        if($(this).data("confirm") != 'undefined'){
            if (confirm($(this).data("confirm"))){
                pjax_reload(this);
            }
        }else{
            pjax_reload(this);
        }

        return false;
    })
})

function pjax_reload(el){
    var href = $(el).attr('href');
    var self = el;
    $.get(href, function(){
        var pjax_id = $(self).closest('.pjax-wraper').attr('id');
        $.pjax.reload('#' + pjax_id);
    });
}