$.fn.extend({
    findInclusive: function(selector)
    {
        return this.find(selector).addBack(selector);
    }
});

function initForms(scope)
{
    scope = scope ? scope : $(document);

    if(!(scope instanceof $))
    {
        scope = $(scope);
    }

    // scope.findInclusive('.datepicker-input').datepicker({todayHighlight: true, autoclose: true});

    scope.findInclusive('.select2').select2();

    scope.findInclusive('.password-indicator').each(function()
    {
        let $this = $(this);
        $this.passwordStrength({targetDiv: '#' + $this.attr('id') + '_passstrength'});
    });

    scope.findInclusive('.password-indicator ~ a[href="#"]').remove();

    scope.findInclusive('input[data-mask]').each(function(i, el)
    {
        $.extend($.mask.definitions, $(el).data('mask-definitions'));

        $(el).mask($(el).data('mask'), {placeholder: $(el).data('placeholder')});
    });

    bsCustomFileInput.init();
}

function initEvents()
{
    $(document).on('click', '.ns-ajax-updater', function(event)
    {
        event.preventDefault();
        let $updater = $(this);

        $updater.click(function(event)
        {
            if($updater.data('confirm'))
            {
                nsConfirm($updater.data('confirm-message'),
                    $updater.data('confirm-title'),
                    $updater.data('confirm-type'),
                    $updater.data('confirm-button-text')
                ).then(() =>
                {
                    nsAjaxUpdater($updater);
                });
            }
            else
            {
                nsAjaxUpdater($updater);
            }
        });
    });

    $(document).on('submit', 'form.ns-ajax-form', function(event)
    {
        let $form = $(this);
        $form.submit(function (event)
        {
            event.preventDefault();

            let success  = $form.data('success');
            let error    = $form.data('error');
            let complete = $form.data('complete');
            let formData = new FormData($form[0]);

            $form.trigger('ns.ajax.form-send');

            $.ajax($form.attr('action'), {
                method: $form.attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                success: (responsedata, textStatus, jqXHR) =>
                {
                    let $update = $($form.data('update'));
                    let $tgt = $(document);

                    if($update.length)//update is optional
                    {
                        $tgt = $update;
                        $update.html(responsedata);
                    }

                    if(success)
                    {
                        success($form, responsedata, textStatus, jqXHR);
                    }

                    $tgt.trigger('ns:AjaxFormComplete');
                },
                error: (jqXHR, textStatus, errorThrown) =>
                {
                    if(error)
                    {
                        error($form, jqXHR, textStatus, errorThrown);
                    }
                },
                complete: (jqXHR, textStatus) =>
                {
                    if(complete)
                    {
                        complete($form, jqXHR, textStatus);
                    }
                }
            });

            return false;
        });
    });

    $(document).on('click', '.ns-add-form', function(event)
    {
        let target = $(event.currentTarget);

        if (target.is('.ns-add-form')) {
            event.preventDefault();
            let $collection = $('[data-collection=' + target.data('collectionholder') + ']').first();
            let prototype_name = $collection.data('prototype-name');
            if (typeof prototype_name !== "undefined") {
                prototype_name = new RegExp(prototype_name, 'g');
            } else {
                prototype_name = new RegExp('__name__', 'g');
            }

            let index = $collection.data('index');
            let newForm = $($collection.data('prototype').replace(prototype_name, index));
            $collection.append(newForm);
            $collection.data('index', index + 1);

            let $form = collection.closest('form');
            if ($form.length > 0 && $form[0].ContextualForm) {
                $form[0].ContextualForm.AddConfigFromPrototype($form, index);
            }

            $(document).trigger('ns.form.update').trigger('ns-add-form');
        }
    });

    $(document).on('click', 'a.ns-confirm, button.ns-confirm', nsConfirmCallback).on('submit', 'form.ns-confirm', nsConfirmCallback);

    $(document).on('click', '.ns-collection-add', function(event)
    {
        let $button = $(event.currentTarget);
        let $container = $($button.attr('href'));
        let count = parseInt($container.data('child-count'));

        let $newContent = $($container.data('prototype').replace(/__name__/g, count));

        $newContent.hide();

        $container.append($newContent);

        $container.data('child-count', count + 1);

        $newContent.fadeIn();

        $container.trigger('ns.form.update');
    });

    $(document).on('click', '.ns-collection-remove', function(event)
    {
        let $button = $(event.currentTarget);
        let $row = $($button.attr('href'));
        $row.remove();
    });
}

function nsAjaxUpdater($updater)
{
    $updater.trigger('ns.ajax.send');

    $.ajax($updater.attr('href'), {
        success: (responsedata, status, jqxhr) =>
        {
            let $update = $($updater.data('update'));
            $update.trigger('ns.ajax.complete');
            $update.html(responsedata);
        }
    });

    return false;
}

function nsConfirmCallback(event)
{
    event.preventDefault();

    let $this = $(this);

    nsConfirm($this.data('confirm-message'),
        $this.data('confirm-title'),
        $this.data('confirm-type'),
        $this.data('confirm-button-text')
    ).then(() =>
    {
        if(this.nodeName === 'FORM')
        {
            this.submit();
        }
        else if(this.nodeName === 'A' && this.href)
        {
            if(this.target)
            {
                window.open(this.href, this.target);
            }
            else
            {
                window.location.href = this.href;
            }
        }
    });
}

function nsConfirm(msg, title, type, buttonText)
{
    return new Promise((resolve, reject) =>
    {
        msg = msg ? msg : 'Are you sure you want to continue?';

        if(typeof swal !== 'undefined')
        {
            type = type ? type : 'info';

            buttonText = buttonText ? buttonText : 'OK';

            swal({
                title: title,
                text: msg,
                type: type,
                icon: type !== 'danger' ? type : 'error', //the icon for danger is actually 'error'
                buttons: {
                    cancel: {
                        text: 'Cancel',
                        className: 'btn btn-default',
                        visible: true,
                        value: false,
                        closeModal: true
                    },
                    confirm: {
                        text: buttonText,
                        value: true,
                        visible: true,
                        className: 'btn btn-' + type,
                        closeModal: true
                    }
                }
            }).then(confirmed => confirmed ? resolve() : reject());
        }
        else
        {
            return confirm(msg) ? resolve() : reject();
        }
    });
}

$(document).on('shown.bs.modal, ns.form.update', function()
{
    initForms($(this));
}).ready(function()
{
    initForms($(this));
    initEvents();
});
