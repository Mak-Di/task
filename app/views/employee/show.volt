{{ link_to('/', 'Home') }}

{{ search.render() }} <br>

<script>
    $(function() {
       $("#selectorStorage").change(function () {
           location.href = '/employee/show/?eid=' + $("#selectorStorage").val()
       });
    });
</script>

<table border="1px" width="90%">
    <tr>
        <th>Fist name</th>
        <th>Last name</th>
        <th>Position</th>
        <th>E-mail</th>
        <th>Phone number</th>
        <th>Note</th>
        <th>Chief</th>
        <th>Edit</th>
        <th>Remove</th>
    </tr>
    {% for item in paginator.items %}
        <tr>
            <td>{{ item.firstName | e }}</td>
            <td>{{ item.lastName | e }}</td>
            <td>{{ item.position | e }}</td>
            <td>{{ item.email | e }}</td>
            <td>{{ item.phone | e }}</td>
            <td>{{ item.note | e }}</td>
            <td>{{ item.chiefName | e }}</td>
            <td>{{ link_to('employee/edit/' ~ item.id ~ '/' ~ paginator.current, 'Edit') }}</td>
            <td>{{ link_to('employee/remove/' ~ item.id ~ '/' ~ paginator.current, 'Remove') }}</td>
        </tr>
    {% endfor %}
</table>

{{ link_to('employee/show', 'First') }} |
{{ link_to('employee/show/' ~ paginator.before, 'Previous') }} |
{{ link_to('employee/show/' ~ paginator.next, 'Next') }} |
{{ link_to('employee/show/' ~ paginator.last, 'Last') }} <br>
<p>
    You are in page {{ paginator.current }} of {{ paginator.total_pages }}
</p>