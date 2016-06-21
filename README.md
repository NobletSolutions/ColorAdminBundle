# Intro to Color Admin Bundle

### Configuration

```yaml
    ns_color_admin:
        templates:
            theme: default|red|blue|purple|orange|black # template theme; Default: 'default'
            pagination:
                template: string # template for KNP Paginator - Default: 'NSColorAdminBundle:Pagination:pagination.html.twig'
                wrapper_class: string # css classname for wrapper <div> - Default: 'paginator-md'
                labels: true|false # select whether to display "Previous/Next" labels - Default: false
            use_pace: true|false # use the pace.js preloader script for fancy page-load animations - Do not use in development as page content will not display on 500-errors - Default: false
            header:
                fixed: true|false # page header fixed to top of window - Default: true
                inverse: true|false # Inverse (white on black) style for page header - Default: false
            sidebar:
                fixed: true|false # sidebar fixed to top left of window - Default: true
                scrolling: true|false # sidebar scrolls independently - Default: true
                grid: true|false # alternate sidebar style with borders - Default: false
                gradient: true|false # alternate colors with gradient style - Default: false
```

### Twig functions

There are some additional helper functions for creating common UI elements in twig:

#### beginPanel
```twig
{{ beginPanel(label, parameters, headerContent) }}
```
Creates a new widget panel with the opening tags for the widget content.

Parameters:

- label: the widget title
- parameters: additional configuration parameters:
    - style: default|inverse|primary|info|success|warning|danger; default: inverse
    - background: white|black|blue|green|orange|red|aqua; default: white
    - text: white|black; default: white
    - sortable: the sortable-id when using the sortables
- headerContent: an html string containg any additional header markup (controls, buttons, etc).  Appears before the widget title. (so use pull-left, etc)

#### endPanel
```twig
{{ endPanel() }}
```
Closes an open widget panel

#### button
```twig
{{ button(text, href, class) }}
```
Creates a simple button.

Parameters:

- text: the button text
- href: the button url
- class: the css classname for the button; default: "btn-default"

#### buttonDropdown
```twig
{{ buttonDropdown(text, primary_action, items, group_class, primary_class) }}
```
Creates a button with dropdown menu.

Parameters:

- text: The button text
- primary_action: the url for the "main" button
- items: the list of dropdown items (see buttonDropdownItems)
- group_class: css class for the button group
- primary_class: css class for the primary button; default: "btn-primary"

#### buttonDropdownItems
```html
{{ buttonDropdownItems(items) }}
```
Create items for a dropdown button. Called by buttonDropdown.

Parameters:

- items: array of key-value pairs; {'Button Label':'url'}.  Keys must be unique.  Use false for the value to create a separator.
```twig
{{ buttonDropdownItems({'Item 1':'http://www.google.com', 'Item 2':'http://www.yahoo.com', 'Separator 1':false, 'Item 3':'http://www.microsoft.com'}) }}
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