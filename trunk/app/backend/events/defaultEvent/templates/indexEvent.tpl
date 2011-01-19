{% extends "layout.tpl" %}

{% block stylesheet %}
   {{ parent() }}
   <link href="/css/admin/icons.css" rel="stylesheet" type="text/css" />
{% endblock %}

{% block javascript %}
   {{ parent() }}
   <script type="text/javascript" src="/js/admin/core.js"></script>
   <script type="text/javascript" src="/js/admin/default.js"></script>
{% endblock %}