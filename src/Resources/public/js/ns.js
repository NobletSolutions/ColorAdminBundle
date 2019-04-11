$.fn.extend({findInclusive: function(selector) { return this.find(selector).addBack(selector); }});

function initForms(scope)
{
    scope = scope ? scope : $(document);

    if(!(scope instanceof $))
    {
        scope = $(scope);
    }

    scope.findInclusive('.datepicker-input').datepicker({todayHighlight: true, autoclose: true});

    scope.findInclusive('.select2').select2();

    scope.findInclusive('.password-indicator').each(function() {
        let $this = $(this);
        $this.passwordStrength({targetDiv: '#'+$this.attr('id')+'_passstrength'});
    });

    scope.findInclusive('.password-indicator ~ a[href="#"]').remove();

    scope.findInclusive('input[data-mask]').each(function(i, el)
    {
        $.extend($.mask.definitions, $(el).data('definitions'));

        $(el).mask($(el).data('mask'), {placeholder:$(el).data('placeholder')});
    });

    bsCustomFileInput.init();
}

$(document).on('shown.bs.modal, ns.form.update', function()
{
    initForms($(this));
}).ready(function()
{
    initForms($(this));
});
