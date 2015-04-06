$(function(){
    /*удаление даты*/
    $(document).on('click', '.remove_date', function(){
        date_remove(this);
    });

    /*добавление поля со временем*/
    $(document).on('click', '.add_datepicker', function(){
        var date_block = $(this).closest('.date_block');
        var date = date_block.data('date');
        $(date_block).find('.remove').css('display', '');
        date_block.find('.item_block').last().after(
            "<span class='item_block'>" +
                "<input type='text' name='dateCalendar["+date+"][]'> " +
                "<span class='remove'>X</span>" +
            "</span>"
        );
    });

    /*удаление поля со временем*/
    $(document).on('click', '.remove', function(){
        var date_block = $(this).closest('.date_block');
        $(this).closest('.item_block').remove();
        var item_block_count = $(date_block).find('.item_block').length;
        if(item_block_count == 1){
            $(date_block).find('.remove').css('display', 'none');
        }
    });
});

function date_remove(el){
    var datapicker = $(el).parent('div').parent('div').data().datepicker;
    $(el).closest('.date_block').remove();

    var div = $(el).closest('.date_block');
    var date = div.data('date');
    date = date.toString();
    date = date.split('.');

    var day = date[0];
    var month = date[1] - 1;
    var year = date[2];
    date = new Date(Date.UTC.apply(Date, [year, month, day]));

    datapicker._setDate(date);
}