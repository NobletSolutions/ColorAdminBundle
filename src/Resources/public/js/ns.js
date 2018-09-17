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

$(document).on('click', '.nsAddForm', function(ev)
{
    var target = $(ev.currentTarget);

    if (target.is('.nsAddForm')) {
        ev.preventDefault();
        var collection = $('[data-collection=' + target.data('collectionholder') + ']').first();
        var prototype_name = collection.data('prototype-name');
        if (typeof prototype_name !== "undefined") {
            prototype_name = new RegExp(prototype_name, 'g');
        } else {
            prototype_name = new RegExp('__name__', 'g');
        }

        var index = collection.data('index');
        var newForm = collection.data('prototype').replace(prototype_name, index);
        collection.append(newForm);
        collection.data('index', index + 1);

        var $form = collection.closest('form');
        if ($form.length > 0 && $form[0].ContextualForm) {
            $form[0].ContextualForm.AddConfigFromPrototype($form, index);
        }

        $(document).trigger('nsFormUpdate').trigger('nsAddForm');
    }
});
