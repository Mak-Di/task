{% if errorMsg IS DEFINED %}
    <div class="errorMsg">
        {% for error in errorMsg %}
            {{ error.getMessage() }}
        {% endfor %}
    </div>
{% endif %}
{{ form('employee/add', 'method': 'post') }}
    {{ form.render('firstName') }} <br>
    {{ form.render('lastName') }} <br>
    {{ form.render('position') }} <br>
    {{ form.render('email') }} <br>
    {{ form.render('phone') }} <br>
    {{ form.render('note') }} <br>
    {{ form.render('parentId') }} <br>

    {{ submit_button("Add") }}
    {{ link_to('/', 'Cancel') }}
{{ endForm() }}