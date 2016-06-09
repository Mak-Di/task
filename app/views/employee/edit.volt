{{ link_to('employee/show', 'Back') }}

{% if errorMsg IS DEFINED %}
    <div class="errorMsg">
        {% if errorMsg is iterable %}
            {% for error in errorMsg %}
                {{ error.getMessage() }}
            {% endfor %}
        {% else %}
            {{ errorMsg }}
        {% endif %}
    </div>
{% endif %}

{{ form('employee/edit/' ~ id ~'/' ~ page, 'method': 'post') }}
    {{ form.render('firstName') }} <br>
    {{ form.render('lastName') }} <br>
    {{ form.render('position') }} <br>
    {{ form.render('email') }} <br>
    {{ form.render('phone') }} <br>
    {{ form.render('note') }} <br>
    {{ form.render('parentId') }} <br>

    {{ submit_button("Save") }}
    {{ link_to('employee/show/' ~ page, 'Cancel') }}
{{ endForm() }}