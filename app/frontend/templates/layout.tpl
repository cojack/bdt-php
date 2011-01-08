{% spaceless %}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{{ view.lang }}" lang="{{ view.lang }}">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>{% block title %}Hello Application{% endblock %}</title>
      {% block stylesheet %}
         <link href="/css/reset.css" rel="stylesheet" type="text/css" />
         <link href="/css/main.css" rel="stylesheet" type="text/css" />
         <link href="/css/style.css" rel="stylesheet" type="text/css" />
      {% endblock %}
      {% block javascript %}
         <script type="text/javascript" src="/js/jquery/jquery-1.4.4.min.js"></script>
      {% endblock %}
   </head>
   <body>
      {% block body '' %}
   </body>
</html>
{% endspaceless %}