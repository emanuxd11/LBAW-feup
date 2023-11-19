<li class="task" data-id="{{$task->id}}">
    <h3>{{$task->name}}</h3>
    <p>{{$task->description}}</p>
    <p>Task started on {{$task->start_date}}</p>
    <p>Needs to be done by {{$task->delivery_date}}</p>
    <p>Status: {{$task->status}}</p>
</li>