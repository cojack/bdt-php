{% spaceless %}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" />
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
   <head>
      <meta http-equiv="Content-Type" content="text/xhtml; charset=utf-8" />
      <title>{% block title %}Panel Administratora{% endblock %}</title>
      {% block stylesheet %}
         <link href="/js/ext/resources/css/ext-all.css" rel="stylesheet" type="text/css" />
      {% endblock %}
      {% block javascript %}
         <script type="text/javascript" src="/js/ext/adapter/ext/ext-base.js"></script>
         <script type="text/javascript" src="/js/ext/ext-all.js"></script>
      {% endblock %}
   </head>
   <body>
      {% block body %}{% endblock %}
   </body>
</html>
{% endspaceless %}