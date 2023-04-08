(function($)
{
    /**
     *
     * @param element string #The container to search for forms within.  Accepts either a DOM element or JQuery selector. Defaults to document.
     * @param globalConfig Object #Basic configuration for the class.  Two properties:
     *          event: The form event to listen for.  Defaults to 'input' so we don't have to defocus the field to get feedback
     *          ignoreClass: Class name of elements to be ignored from the toggling functionality.  Defaults to 'ignore'
     * @param autoinit Boolean #Whether or not to automatically init the form handlers as soon as the class is instantiated.  Defaults to true
     * @param events string Document events to initialize the contextual form on
     * @param onSuccessEvent string Event to throw when a form field is shown or hidden
     * @constructor
     */
    $.ContextualForm = function(element, globalConfig, autoinit, events, onSuccessEvent)
    {
        autoinit = typeof(autoinit) !== 'undefined' ? autoinit: true;
        events   = typeof(events) !== 'undefined' ? events : 'nsFormUpdate shown.bs.tab shown.bs.collapse sonata.add_element ajaxComplete shown.ace.widget';

        var defaultConfig = {
            'event': 'input',
            'ignoreClass': 'ignore'
        };

        //Just use the document if we don't pass an element
        if(element === undefined)
        {
            element = document;
        }

        this.globalConfig = $.extend(defaultConfig, globalConfig); //Merge the default and provided configs
        this.element   = (element instanceof $) ? element: $(element); //Convert the element arg to a JQuery object if it isn't one
        this.forms     = null;

        if(autoinit)
        {
            var cf = this;
            cf.Init();

            if(events)
            {
                $(document).on(events, function()
                {
                    cf.Init();
                });
            }
        }
    };

    $.ContextualForm.prototype = {
        /**
         * Init the contextual form plugin
         * @constructor
         */
        Init: function()
        {
            this.onSuccessEvent = typeof(onSuccessEvent) !== 'undefined' ? onSuccessEvent : 'contextFormUpdate';
            this.activityMap = {}; //We need to store a mapping of what fields are currently active and "visible"; storing this info right on the field is problematic because it may or may not be an actual <input> element
            this.toBeProcessed = {};
            this.isFirstRun = true;
            this.collectionref = 1;
            this.allTargets = $(); //Straight list of every child element for rapid access
            this.elementList = [];

            var cform   = this; //#JustJavascriptScopeThings
            cform.isFirstRun = true;
            cform.forms = cform.element.find('form[data-context-config]').addBack('form[data-context-config]'); //Find any forms that have a config

            //Parse the config for each form and add it to the DOM element
            cform.forms.each(function()
            {
                //Make sure this isn't a reference because we need to modify the copy
                var conf = $(this).data('context-config');
                var prototypes = $(this).data('context-prototypes');
                this.contextConfig = $.extend(true, {}, conf);
                this.prototypeConfig = $.extend(true, {}, prototypes);
                this.ContextualForm = cform; // Give the form element a reference back to this ContextualForm object
            });

            cform.AddListeners();
            $('.ctxtc').hide();//Hide everything by default and then selectively show what we need

            $.each(cform.elementList, function(key, el)
            {
                cform.Go(el.field, el.conf);
            });

            cform.isFirstRun = false;
        },

        AddConfigFromPrototype: function(form, index, replace)
        {
            replace = replace !== undefined ? replace : '__name__';
            var $form = form;
            form = $form[0];

            if(form.prototypeConfig)
            {
                $.each(form.prototypeConfig, function(key, values)
                {
                    var name = key.replace(replace, index);
                    if(!$form.data('context-config')[name])
                    {
                        $form.data('context-config')[name] = [];
                        $.each(values, function(key, val)
                        {
                            var display = Array.isArray(val.display) ? val.display.slice() : [val.display];
                            var values = Array.isArray(val.values) ? val.values.slice() : [val.values];
                            $.each(display, function(k, v)
                            {
                                display[k] = v.replace(replace, index);
                            });

                            $form.data('context-config')[name].push({'display':display, 'values':values});
                        });
                    }
                });
            }
        },

        /**
         * Loop through the form configs, find which fields we need to add handlers to, and start the process
         *
         */
        AddListeners: function()
        {
            var cform = this;

            //Get the config back from the DOM element
            cform.forms.each(function()
            {
                var $form  = $(this); //This is the <form> element in this context
                var config = this.contextConfig;

                //Grab each element from the config and add the event listeners for it
                $.each(config, function(index, value){
                    data = cform.ProcessFormConfig($form, index, value); //'this' refers to the current config item, because loop

                    if (data && data.length === 2) {
                        cform.elementList.push({'field':data[0], 'conf':data[1]});
                    }
                });
            });
        },

        /**
         * Convert the fields defined in the form config to actual Jquery objects, then add the event listeners
         *
         * @param $form Object #The current form element (JQuery object)
         * @param field String #The name of the field to process the config for
         * @param config Object #The config (JSON) for this field
         */
        ProcessFormConfig: function($form, field, config)
        {
            var cform = this;
            //Get the actual form field element
            var $field = $($form[0].querySelectorAll('[name="'+field+'"]'));
            $field = $field.add($($form[0].querySelectorAll('[name="'+field+'[]"]')));

            if ($field.length === 0) {
                return;
            }

            //Determine if we're looking at a single field (text input, select, or boolean checkbox, or multiple elements (expanded checkboxes)
            if($field.length > 1 || $field.attr('name').indexOf('[]') >= 0)
            {
                cform.collectionref++;

                $field.attr('data-iscollection', true);
                $field.data('iscollection', true);
                $field.attr('data-collectionref', cform.collectionref);
                $field.data('collectionref', cform.collectionref);
            }
            else
            {
                $field.data('iscollection', false);
                $field.removeAttr('iscollection');
            }

            this.toBeProcessed[$field.attr('id')] = true;

            //We're doing all of this before the event handler so it only happens once on init.

            //Loop through the config, convert the parameters to actual JQuery objects, and update the config
            $.each(config, function(index, conf)
            {
                var targets = [];
                if(conf.display instanceof Array) {
                    $.each(conf.display, function(i, dis)
                    {
                        var $sel = cform.DisplayConfToSelector($form, dis);
                        if($sel.length)
                        {
                            $sel.data('visibleParents', []);
                            targets.push($sel);
                            cform.allTargets = cform.allTargets.add($sel);
                            $sel.addClass('ctxtc');
                        }
                    });
                } else {
                    var $sel = cform.DisplayConfToSelector($form, conf.display);
                    if($sel.length)
                    {
                        $sel.data('visibleParents', []);
                        targets = [$sel];
                        cform.allTargets = cform.allTargets.add($sel);
                        $sel.addClass('ctxtc');
                    }
                }

                //Make this always an array for ease of comparison
                if(!(conf.values instanceof Array)) {
                    conf.values = [conf.values];
                }

                //It's HTML, so deep down these were always strings, anyway. Make it so.
                var arr = [];
                $.each(conf.values, function(i, v) {
                    arr.push(String(v));
                });

                conf.values = arr;

                conf.display = targets;
            });

            this.AddListener($field, config);

            return [$field, config];
        },

        /**
         * Actually create the event listener
         *
         * @param $field Object #The form field.  JQuery object.
         * @param config
         */
        AddListener: function($field, config)
        {
            var cform = this;

            var event = $field.is('[type=checkbox], [type=radio], select') ? 'change':cform.globalConfig.event; //Sadly, chrome only supports oninput on text fields

            $field.off(event+'.cf'); //Init() could have been called more than once; remove any previous handlers so we don't get doubles

            $field.on(event+'.cf', function($event, param1)
            {
                if(!param1)
                {
                    cform.activityMap[$field.attr('id')] = true;//param1 is only true if this came from a trigger() call; we only want to update the activityMap after the user actually changes a field
                }

                cform.Go($(this), config, $event)
            });
        },

        /**
         * Get a Jquery object based on the nature of the arg
         *
         * @param $form Object #The current form
         * @param dis String #The name or ID of the element we want to toggle
         * @returns {*}
         */
        DisplayConfToSelector: function($form, dis)
        {
            if(dis.indexOf('#') > -1) //If we passed an id selector, just use that ID.
            {
                return $(dis);
            }
            else
            {
                var $els = $($form[0].querySelector('[name="'+dis+'"]'));
                $els = $els.add($($form[0].querySelector('[name="'+dis+'[]"]')));

                return this.FindWrapper($form, $els); //Otherwise, find the field by name.
            }

        },

        /**
         * We usually don't want to hide JUST the form field, since there are things like form-group divs that need to be hidden as well.  Find them.
         *
         * @param $form Object #The current form
         * @param $el Object #Form field we're toggling
         * @returns {*}
         */
        FindWrapper: function($form, $el)
        {
            //Find the parent element to toggle, if appropriate
            var $f = this.FindWrapperForFieldType($form, $el);

            //If that gave us something, return it.  Otherwise, merge the form field and its label into a single collection we can toggle at once
            var $wrapper = $f ? $f : $el.add($form.find($('label[for='+$el.attr('id')+']')));
            $wrapper.data('fieldId', $el.attr('id')); //we need a quick way to get the actual form field ID from the wrapper element if needed
            return $wrapper;
        },

        /**
         * Try to intelligently find the appropriate container to toggle based on the form input type
         *
         * @param $form Object #The current form
         * @param $field Object #The current field
         * @returns {boolean}
         */
        FindWrapperForFieldType: function($form, $field)
        {
            var ret = false;

            if(!ret && $field.is('input[type=checkbox]'))
            {
                ret = this.FindWrapperForCheckbox($form, $field);
            }

            if(!ret && $field.is('input[type=radio]'))
            {
                ret = this.FindWrapperForRadioButton($form, $field);
            }

            if(!ret && $field.is('input, select, textarea'))
            {
                ret =  this.FindWrapperForInput($form, $field);
            }

            return ret;
        },

        /**
         * For standard text inputs, try to find a parent form-group, and we'll hide the whole thing
         *
         * @param $form Object #The current form
         * @param $field Object #The current field
         * @returns {*}
         */
        FindWrapperForInput: function($form, $field)
        {
            var $parent = $field.closest('.form-group').not(this.GetIgnoreSelector());

            if ($parent.length) {
                return $parent;
            }

            $parent = $field.parent('label').not(this.GetIgnoreSelector());

            if ($parent.length) {
                return $parent;
            }

            return $field;
        },

        /**
         * Checkboxes and radiobuttons have a couple extra layers of parents we need to account for.
         *
         * @param $form Object #The current form
         * @param $field Object #The current field
         * @returns {*}
         */
        FindWrapperForCheckbox: function($form, $field)
        {
            var $group = $field.closest('.form-group').not(this.GetIgnoreSelector());

            if($group.length) {
                return $group;
            }

            var $parent = $field.closest('.checkbox').not(this.GetIgnoreSelector());

            if($parent.length) {
                return $parent;
            }

            return false;
        },

        /**
         * Checkboxes and radiobuttons have a couple extra layers of parents we need to account for.
         *
         * @param $form Object #The current form
         * @param $field Object #The current field
         * @returns {*}
         */
        FindWrapperForRadioButton: function($form, $field)
        {
            var $group = $field.closest('.form-group').not(this.GetIgnoreSelector());

            if($group.length)
            {
                return $group;
            }

            var $parent = $field.closest('.radio').not(this.GetIgnoreSelector());

            if($parent.length)
            {
                return $parent;
            }

            return false;
        },

        /**
         * Convert the ignore arg to a Jquery selector so we don't have to concat the . every time
         *
         * @returns {string}
         */
        GetIgnoreSelector: function()
        {
            return '.'+this.globalConfig.ignoreClass;
        },

        /**
         * We need to do some pre-processing for fields like checkboxes to get the correct value
         *
         * @param $field Object #The current field
         * @param match Array #The values we want to match the field against
         * @returns {boolean}
         */
        MatchFieldValue: function($field, match)
        {
            var vals = [];
            var $fields;
            if($field.is('[type=checkbox]') || $field.is('[type=radio]'))
            {
                //If the field is an expanded collection
                if($field.data('iscollection'))
                {
                    $fields = $('[data-collectionref='+$field.data('collectionref')+']').filter(':checked');
                    $fields.each(function ()
                    {
                        vals.push(String($(this).val()));
                    });
                }
                //if the field is a boolean
                else
                {
                    //This will get the true/false state of the boolean field, and we can match it against the true/false value in the config.  Also convert it to string, because all the conf values we're matching against will be strings
                    if($field.is(':checked'))
                    {
                        vals.push(true, 1, "true", "1");
                    }
                    else
                    {
                        vals.push(false, 0, "false", "0");
                    }
                }
            }
            else if($field.is('select[multiple]'))
            {
                $fields = $field.find('option:selected');
                $fields.each(function()
                {
                    vals.push(String($(this).val()));
                });
            }
            else
            {
                vals.push(String($field.val()));
            }

            var intersect = ns_array_intersect([vals, match]); //Some form fields return multiple values, so we have to intersect those with the ones we're looking for

            return intersect.length > 0;
        },

        TriggerChangeEvent: function($field)
        {
            var $fInput;

            if($field.is('input, select, textarea'))
            {
                $fInput = $field;
            }
            else
            {
                $fInput = $field.find('input, select, textarea');
            }

            var event = $fInput.is('[type=checkbox], [type=radio], select') ? 'change':this.globalConfig.event; //Sadly, chrome only supports oninput on text fields
            $fInput.trigger(event, ['fromShow']); //Pass a parameter so we can tell whether the event was fired from a trigger() call or user input
        },

        TriggerSuccessEvent: function()
        {
            $(document).trigger(this.onSuccessEvent);
        },

        /**
         * Fire the show/hide event
         *
         * @param $field Object #The form field having the event
         * @param config Object #The config for this form field
         */
        Go: function($field, config)
        {
            var cform = this;
            var show  = [];
            var id    = $field.attr('id');

            if(cform.isFirstRun && !cform.toBeProcessed[id])
            {
                return;
            }
            else if(cform.activityMap[id] === undefined)
            {
                cform.activityMap[id] = true;
            }

            //There are potentially multiple configs for this field, for different sets of child fields dependent on different form values.
            $.each(config, function(index, conf)
            {
                //Loop through each "child" field that is controlled by this "parent" field, in this config
                $.each(conf.display, function(index, $disField)
                {
                    var dId = $disField.data('fieldId');

                    if($disField.is(':visible')) //Every time an event is triggered for a field, we hide it, and then determine if it should still be shown.
                    {
                        $disField.hide();
                    }

                    cform.activityMap[dId] = false;

                    // delete $disField.data('visibleParents')[id];
                    let visibleParents = $disField.data('visibleParents');
                    if (visibleParents) {
                        visibleParents.splice(id, 1);
                    }

                    //If the parent field value matches the value in the config, display the child fields
                    if((cform.activityMap[id] || visibleParents.length) && cform.MatchFieldValue($field, conf.values))
                    {
                        show.push($disField);
                        if(cform.activityMap[id])
                        {
                            cform.activityMap[dId] = true;//If we get here, this field is supposed to be visible, so update the activity map
                            var vparents = $disField.data('visibleParents');
                            if (vparents) {
                                vparents[id] = true;
                                $disField.data('visibleParents', vparents);
                            }
                        }
                    }

                    cform.TriggerChangeEvent($disField);
                });
            });

            //We do this after the loop, because if we do it within, the disField.hide() call could hide a field we just showed in the previous loop
            $.each(show, function(index, $disField)
            {
                $disField.show();

                cform.TriggerSuccessEvent();
            });

            cform.toBeProcessed[id] = false;
        }
    }
}(jQuery));

/**
 * Calculate the intersection between two or more arrays
 *
 * @param arrays
 */
var ns_array_intersect = function(arrays)
{
    return arrays.shift().filter(function(v) {
        return arrays.every(function(a) {
            return a.indexOf(v) !== -1;
        });
    });
};
