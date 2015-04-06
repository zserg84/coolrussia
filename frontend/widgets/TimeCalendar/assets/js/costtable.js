$(function(){
    $(document).on('click', '.remove_cost', function(){
        block_remove(this);
    });

    $(document).on('click', '.add_cost', function(){
        block_add(this);
    });
});

function block_remove(el){
    var costBlock = $(el).closest('.cost-block');
    $(el).closest('.item_block').remove();
    removeBtnVisibility(costBlock);
}

function block_add(el){
    var costBlock = $(el).closest('.cost-block');
    var firsItemBlock = costBlock.find('.item_block').first();
    var clone = $(firsItemBlock).clone();
    $(clone).find('input').val('');
    costBlock.find('.item_block').last().after(clone);
    removeBtnVisibility(costBlock);
}

function removeBtnVisibility(costBlock){
    var item_block_count = costBlock.find('.item_block').length;
    var visibility = item_block_count == 1 ? 'hidden' : 'visible';
    $(costBlock).find('.item_block .remove_cost').css('visibility', visibility);
}