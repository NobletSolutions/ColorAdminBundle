$.fn.extend({
    findInclusive: function (selector) {
        return this.find(selector).addBack(selector);
    }
});

function initForms(scope) {
    scope = scope ? scope : $(document);

    if (!(scope instanceof $)) {
        scope = $(scope);
    }

    // scope.findInclusive('.datepicker-input').datepicker({todayHighlight: true, autoclose: true});

    scope.findInclusive('.select2').select2();

    scope.findInclusive('.password-indicator').each(function () {
        let $this = $(this);
        $this.passwordStrength({targetDiv: '#' + $this.attr('id') + '_passstrength'});
    });

    scope.findInclusive('.password-indicator ~ a[href="#"]').remove();

    scope.findInclusive('input[data-mask]').each(function (i, el) {
        $.extend($.mask.definitions, $(el).data('mask-definitions'));

        $(el).mask($(el).data('mask'), {placeholder: $(el).data('placeholder')});
    });

    bsCustomFileInput.init();
}

function initEvents() {
    $(document).on('click', '.ns-ajax-updater', function (event) {
        event.preventDefault();
        let $updater = $(this);

        $updater.click(function (event) {
            if ($updater.data('confirm')) {
                nsConfirm($updater.data('confirm-message'),
                    $updater.data('confirm-title'),
                    $updater.data('confirm-type'),
                    $updater.data('confirm-button-text')
                ).then(() => {
                    nsAjaxUpdater($updater);
                });
            } else {
                nsAjaxUpdater($updater);
            }
        });
    });

    $(document).on('submit', 'form.ns-ajax-form', function (event) {
        let $form = $(this);
        $form.submit(function (event) {
            event.preventDefault();

            let success = $form.data('success');
            let error = $form.data('error');
            let complete = $form.data('complete');
            let formData = new FormData($form[0]);

            $form.trigger('ns.ajax.form-send');

            $.ajax($form.attr('action'), {
                method: $form.attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                success: (responsedata, textStatus, jqXHR) => {
                    var $update = $($form.data('update'));
                    var $tgt = $(document);

                    if ($update.length)//update is optional
                    {
                        $tgt = $update;
                        $update.html(responsedata);
                    }

                    if (success) {
                        success($form, responsedata, textStatus, jqXHR);
                    }

                    $tgt.trigger('ns:AjaxFormComplete');
                },
                error: (jqXHR, textStatus, errorThrown) => {
                    if (error) {
                        error($form, jqXHR, textStatus, errorThrown);
                    }
                },
                complete: (jqXHR, textStatus) => {
                    if (complete) {
                        complete($form, jqXHR, textStatus);
                    }
                }
            });

            return false;
        });
    });

    $(document).on('click', '.ns-add-form', function (event) {
        var target = $(event.currentTarget);

        if (target.is('.ns-add-form')) {
            event.preventDefault();
            var $collection = $('[data-collection=' + target.data('collectionholder') + ']').first();
            var prototype_name = $collection.data('prototype-name');
            if (typeof prototype_name !== "undefined") {
                prototype_name = new RegExp(prototype_name, 'g');
            } else {
                prototype_name = new RegExp('__name__', 'g');
            }

            var index = $collection.data('index');
            var prototype = $collection.data('prototype');
            if (!prototype) {
                console.debug('No prototype defined on element data-collection=' + target.data('collectionholder') + ']');
                return;
            }

            var newForm = $(prototype.replace(prototype_name, index));
            if ($collection.data('insert-position') === 'prepend') {
                $collection.prepend(newForm);
            } else {
                $collection.append(newForm);
            }

            if ($collection.data('scroll-to-view')) {
                newForm[0].scrollIntoView();
            }

            $collection.data('index', index + 1);

            var $form = $collection.closest('form');
            if ($form.length > 0 && $form[0].ContextualForm) {
                $form[0].ContextualForm.AddConfigFromPrototype($form, index);
            }

            $(document).trigger('ns.form.update').trigger('ns-add-form');
        }
    });

    $(document).on('click', 'a.ns-confirm, button.ns-confirm', nsConfirmCallback).on('submit', 'form.ns-confirm', nsConfirmCallback);

    $(document).on('click', '.ns-collection-add', function (event) {
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

    $(document).on('click', '.ns-collection-remove', function (event) {
        let $button = $(event.currentTarget);
        let $row = $($button.attr('href'));
        $row.remove();
    });

    $('.nsSelect2').each(function (i, el) {
        if (el.nsFieldActive !== true) {
            el.nsFieldActive = true;

            const $this = $(this);

            let url    = $this.data('url');
            let config = {debug: true};
            if (url) {
                let method = $this.data('method');
                config.ajax = {
                    url:            url,
                    delay:          $this.data('ajax-delay') ?? 250,
                    method:         method ? method.toUpperCase() : 'GET',
                    processResults: (data) => {
                        const append = $this.data('append');

                        if (append && Array.isArray(append)) {
                            append.forEach(el => {
                                if (typeof el === 'string') {
                                    data.results.push({raw: el})
                                } else {
                                    data.results.push({id: el.id, text: el.text});
                                }
                            })
                        }

                        return {results: data.results};
                    }
                };
                const templateResult = $this.data('nstemplateresult');
                config.templateResult = typeof window[templateResult] === 'function' ? window[templateResult] : state => {
                    if (state.raw) {
                        return state.raw;
                    }

                    return state.text;
                }
            }

            let modal = $(el).closest('.modal');

            //Select2 has issues if it's within a modal
            if (modal.length) {
                config.dropdownParent = modal;
            }

            let initCallback = $this.data('init-callback');

            if (window[initCallback]) {
                window[initCallback](this, config);
            }

            if (!$this.data('escape-all-markup')) {
                config.escapeMarkup = function (markup) {
                    return markup;
                };
            }

            let lang = $this.data('language-config');

            if (lang) {
                const langConfig = {};
                for (const key in lang) {
                    langConfig[key] = function (params) {
                        return $('<textarea />').html(lang[key]).val(); //Workaround to unescape html entities
                    }
                }

                config.language = langConfig;
            }
            $this.on('select2:open', function(ev) {
                const tags = $this.data('tags');
                const $container = $('.select2-container');

                if (tags) {
                    $container.addClass('tagged');
                } else {
                    $container.removeClass('tagged');
                }
            });

            $this.select2(config);
        }
    });
}

function nsAjaxUpdater($updater) {
    $updater.trigger('ns.ajax.send');

    $.ajax($updater.attr('href'), {
        success: (responsedata, status, jqxhr) => {
            var $update = $($updater.data('update'));
            $update.trigger('ns.ajax.complete');
            $update.html(responsedata);
        }
    });

    return false;
}

function nsConfirmCallback(event) {
    event.preventDefault();

    let $this = $(this);

    nsConfirm($this.data('confirm-message'),
        $this.data('confirm-title'),
        $this.data('confirm-type'),
        $this.data('confirm-button-text')
    ).then(() => {
        if (this.nodeName === 'FORM') {
            this.submit();
        } else if (this.nodeName === 'BUTTON' && this.type === "submit") {
            let $form = $this.closest('form');

            if (this.value && this.name) {
                $form.append('<input type="hidden" name="' + this.name + '" value="' + this.value + '" />');
            }

            $form.submit();
        } else if (this.nodeName === 'A' && this.href) {
            if (this.target) {
                window.open(this.href, this.target);
            } else {
                window.location.href = this.href;
            }
        }
    });
}

function nsConfirm(msg, title, type, buttonText) {
    return new Promise((resolve, reject) => {
        msg = msg ? msg : 'Are you sure you want to continue?';

        if (typeof swal !== 'undefined') {
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
        } else {
            return confirm(msg) ? resolve() : reject();
        }
    });
}

$(document).on('shown.bs.modal, ns.form.update', function () {
    initForms($(this));
}).ready(function () {
    initForms($(this));
    initEvents();
});
