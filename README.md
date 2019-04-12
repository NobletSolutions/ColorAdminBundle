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

### Error Templates

There are error templates located in views/Exception;  Copy/extend these in app/Resources/TwigBundle/views/Exception to use the custom error page templates.

### jQuery Full Calendar

Add the routing configuration
```yml
#app/config/routing.yml
ns_ace:
  resource: "@NSAceBundle/Resources/config/routing.yml"
```

Include the following on the page you want to use the calendar

```twig

{% block page_javascripts %}
    {{ parent() }}
    <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.1/fullcalendar.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();

            $('#calendar-holder').fullCalendar({
                header: {
                    left: 'prev, next',
                    center: 'title',
                    right: 'month, basicWeek, basicDay,'
                },
                lazyFetching: true,
                timeFormat: {
                    // for agendaWeek and agendaDay
                    agenda: 'h:mmt',    // 5:00 - 6:30

                    // for all other views
                    '': 'h:mmt'         // 7p
                },
                eventSources: [
                    {
                        url: '{{ path('color_admin_calendar_loader') }}',
                        type: 'POST',
                        // A way to add custom filters to your event listeners
                        data: {
                        },
                        error: function() {
                            //alert('There was an error while fetching Google Calendar!');
                        }
                    }
                ]
            });
        });
    </script>
{% endblock %}

{% block page_stylesheets %}
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.1/fullcalendar.min.css" />
    <link rel="stylesheet" media="print" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.1/fullcalendar.print.css" />
    {{ parent() }}
{% endblock %}

{% block body %}
... 
<div id="calendar-holder"></div>
...
{% endblock %}
```
