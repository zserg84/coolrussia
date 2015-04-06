$(function(){
    $(document).on('click', '.weekday_checkbox', function(){
        var day = $(this).data('day');
        var visibility = $(this).prop('checked') ? 'visible' : 'hidden';
        $(this).closest('table').find('.weekday_content.day_'+day).css('visibility', visibility);
    });

    $(document).on('click', '.add_timetable', function(){
        time_add(this);
    });
});

function time_add(el){
    var dateBlock = $(el).closest('.date_block');
    var date = dateBlock.data('date');
    $(dateBlock).find('.remove').css('display', '');
    dateBlock.find('.item_block').last().after(
        "<span class='item_block'>" +
        "<input type='text' name='dateCalendar["+date+"][]'> " +
        "<span class='remove'>X</span>" +
        "</span>"
    );
}