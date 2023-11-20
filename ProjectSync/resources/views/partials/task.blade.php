<li class="task" data-id="{{$task->id}}">
    <h3>{{$task->name}}</h3>
    <p>Description: {{$task->description}}</p>
    <p>Task started on {{$task->start_date}}</p>
    <p>Needs to be done by {{$task->delivery_date}}</p>
    <p>Status: {{$task->status}}</p>
    <form method="POST" action="{{ route('edit_task',['id' => $task->id]) }}">
        @method('POST')
        @csrf
        Name: <input type="text" name="name">
        Description: <input type="text" name="description">
        Delivery Date: <input type="date" name="delivery_date">
        Add user: <input type="text" name="username">
        <button type="submit" class="button">+</button>
    </form>

    <form method="POST" action="{{ route('delete_task',['id' => $task->id]) }}">
        @method('DELETE')
        @csrf
        <button type="submit" class="button">Delete</button>
    </form>
</li>