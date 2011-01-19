{% autoescape false %}
   {{ view.response.getValue()|json_encode }}
{% endautoescape %}