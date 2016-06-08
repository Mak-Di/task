{{ link_to('employee/show', 'Back') }}

<div class="errorMsg">
    {% for error in errorMsg %}
        {{ error.getMessage() }}
    {% endfor %}
</div>
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