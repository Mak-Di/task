<div class="errorMsg">
    {% for error in errorMsg %}
        {{ error.getMessage() }}
    {% endfor %}
</div>
{{ form('employee/add', 'method': 'post') }}
    {{ form.render('first_name') }} <br>
    {{ form.render('last_name') }} <br>
    {{ form.render('position') }} <br>
    {{ form.render('email') }} <br>
    {{ form.render('phone') }} <br>
    {{ form.render('note') }} <br>
    {{ form.render('parent_id') }} <br>

    {{ submit_button("Add") }}
    {{ link_to('/', 'Cancel') }}
{{ endForm() }}