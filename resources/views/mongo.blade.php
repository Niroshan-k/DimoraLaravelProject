<p>hii</p>
<ul>
@foreach($users as $user)
    <li>{{ json_encode($user) }}</li>
@endforeach
</ul>