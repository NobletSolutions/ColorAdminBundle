$(document).ready(function() {
    $('input.nsMasked').each(function(i, el)
    {
        $.extend($.mask.definitions, $(el).data('definitions'));

        $(el).mask($(el).data('mask'), {placeholder:$(el).data('placeholder')});
        $(el).parents('div.form-group').children('label').append(' <small class="text-info">'+$(el).data('mask')+'</small>');
    });

    $('.readmore').each(function()
    {
        $(this).css({'max-height':$(this).data('max-height')});
    });

    $('.readmore-expander').on('click', function () {
        var readme = $(this).prev('.readmore');
        if (!this.expanded) {
            readme.css({'overflow-y': 'visible', 'max-height': 'inherit'});
            this.expanded = true;
            $(this).find('i').removeClass('fa-angle-double-down').addClass('fa-angle-double-up');
        }
        else {
            readme.css({'overflow-y': 'hidden', 'max-height': readme.data('max-height')});
            this.expanded = false;
            $(this).find('i').removeClass('fa-angle-double-up').addClass('fa-angle-double-down');
        }
    });
});
