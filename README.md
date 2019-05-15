# Intro to Color Admin Bundle

### Configuration

```yaml
    color_admin:
        use_knp_menu: false #if set to true and KnpMenuBundle is installed, we'll set the template to our knp_menu.html.twig template 
        templates:
            theme: apple|default|facebook|material|transparent # template theme; Default: 'default'
            theme_color: aqua|black|blue|default|green|indigo|lime|orange|pink|purple|red|yellow
            pagination:
                template: string # template for KNP Paginator - Default: 'NSColorAdminBundle:Pagination:pagination.html.twig'
                wrapper_class: string # css classname for wrapper <div> - Default: 'paginator-md'
                labels: true|false # select whether to display "Previous/Next" labels - Default: false
            use_pace: true|false # use the pace.js preloader script for fancy page-load animations - Do not use in development as page content will not display on 500-errors - Default: false
            draggable_panel: true|false # allow widget panels to be rearranged - Default: false
            header:
                fixed: true|false # page header fixed to top of window - Default: true
                inverse: true|false # Inverse (white on black) style for page header - Default: false
            sidebar:
                fixed: true|false # sidebar fixed to top left of window - Default: true
                scrolling: true|false # sidebar scrolls independently - Default: true
                grid: true|false # alternate sidebar style with borders - Default: false
                gradient: true|false # alternate colors with gradient style - Default: false
```

### Main navigation configuration
#### MenuBuilder

See sample MenuBuilder in ColorAdminBundle/Menu/MenuBuilder.php

#### Services config
```yaml
    ns_color_admin.menu.sidebar:
        class: NS\ColorAdminBundle\Menu\MenuBuilder
        arguments: ["@knp_menu.factory"]
        tags:
            - { name: knp_menu.menu_builder, method: createSidebarMenu, alias: sidebar } # The alias is what is used to retrieve the menu
            - { name: knp_menu.menu_builder, method: createSidebarMenu, alias: breadcrumbs }
```

#### Other config
```yaml
    knp_paginator:
        template: NS\ColorAdminBundle\Resources\Paginator\paginator.html.twig #optional
```

### Forms
This bundle adds some new form types, and adds additional features to existing ones.

#### New Form Types

##### Datepicker
NS\ColorAdminBundle\Form\Type\DatepickerType

Provides an interactive datepicker.
```yaml
options:
    start_date: \DateTime
    date_format: String # "yyyy-mm-dd"
```

##### Telephone
NS\ColorAdminBundle\Form\Type\DatepickerType

Provides a formatted ((999) 999-9999) phone number input.  Actually likely redundant with the extension to the text input
```yaml
No options
```

##### SearchableSelect
NS\ColorAdminBundle\Form\Type\FilterableSelectType

Provides a filterable select field with typeahead and remote autocomplete support. Inherits all options from ChoiceType with the exception of 'expanded'
```yaml
options:
    allow_new_value: Boolean # Allow the user to submit what they typed if there is no matching option. Default false.
    data_url: String # Remote URL for field options.  If provided, enables remote Autocomplete support. See the class annotations for example request/response format.  Default false.
    data_type: String # Format to expect for autocomplete responses.  Passing anything other than 'json' requires developer to provide custom response handling callback. See https://select2.org for full docs. Default 'json'.
    minimum_input_length: Integer # Minimum number of characters that must be typed before commencing search. Default 0.
    maximum_input_length: Integer # Maximum number of characters that can be used for search.  Why would you want that? Default 0.
    maximum_selection_length: Integer # If multiple=true, the maximum number of results that can be selected. 0 for unlimited. Default 0.
```

#### Extended Form Types

##### All Types
Additional options for all form types.
```yaml
options:
    note: String # Provide a text note that will be displayed below the field. Useful for stating password requirements, etc.
    wrapper_class: String # CSS class of the div that wraps the form widget (within div.form-group, next to the label). Default 'col-md-9'.
    state: String [valid|invalid] # Provide an input state with accompanying styling. Visual only, provides no user functionality.
    vertical: Boolean # Optionally use a vertical (label above field) layout when using the default form template.  Default false.
```

##### TextType
Additional options for TextType and types that extend it (Email, Password, etc)
```yaml
options:
    icon: String|false # FontAwesome-compatible icon to display alongside the text input. Default false.
    icon_position: String [left|right] # Side of the input on which to place the icon.  Default 'left'.
    mask: String # Text to display as an input mask. 9 = number, a = letter, * = wildcard. Example: (999) 999-9999 for a phone number, a9a 9a9 for postal code. Default false.
    mask_placeholder: String # Character to display as a placeholder. Example: mask_placeholder="_" and mask="(999) 999-9999 will have the input display "(___) ___-____" as the user types.  Default '_'.
    mask_definitions: JSON # Custom mask definition.  See annotations in NS\ColorAdmin\Form\Extension\MaskExtension for full details.  Default false.
```

##### PasswordType
Additional options for PasswordType and types that extend it.
```yaml
options:
    toggle: Boolean # Provide a button to allow the user to toggle between hidden and visible password. Doesn't seem to play nice with autofill. Default false.
    placement: String [before|after] # Display the toggle button before or after the input.  Default 'before'.
    indicator: Boolean # Display a password strength indicator.  Default false.
    # Note: toggle and indicator cannot be used at the same time.
```

##### CollectionType
Additional options for CollectionType and types that extend it.
```yaml
options:
    add_button_label: String #label for the Add button. Default "Add"
    add_button_icon: string #icon for the Add button. Default "plus"
```

### Error Templates

There are error templates located in views/Exception;  Copy/extend these in app/Resources/TwigBundle/views/Exception to use the custom error page templates.
