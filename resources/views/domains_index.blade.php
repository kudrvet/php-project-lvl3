
<table>
    <tr><th>id</th><th>name</th><th>updated_at</th><th>created_at</th></tr> <!--ряд с ячейками заголовков-->
@foreach($domains as $domain)
        <tr><td>{{$domain->id}}</td><td>{{ link_to_route('domains.show', $domain->name, $parameters = ['id'=> $domain->id], $attributes = [])}}
        </td><td>{{$domain->updated_at}}</td><td>{{$domain->created_at}}</td></tr> <!--ряд с ячейками тела таблицы-->
@endforeach
</table>
