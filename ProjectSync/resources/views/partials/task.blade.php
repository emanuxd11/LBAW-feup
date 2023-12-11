<li class="task" data-id="{{$task->id}}">
    <h3>{{$task->name}}</h3>
    <p>Description: {{$task->description}}</p>
    <p>Task started on: {{$task->start_date}}</p>
    <p>Delivery Date: {{$task->delivery_date ?? "No delivery date available"}}</p>
    <p>Status: {{$task->status}}</p>

    <button id="toggleTaskWrapper" type="button" class="button">
        <i class="fas fa-edit"></i>
    </button>

    <div class="edit-task-wrapper hidden">
        <h4>Edit Task:</h4>
        
        <form class="project-form" method="POST" action="{{ route('edit_task',['id' => $task->id]) }}">
            @method('POST')
            @csrf
            Name: <br><input type="text" name="name" class="project-form">
            <br>Description:<br> <input type="text" name="description" class="project-form">
            <br>Delivery Date: <br><input type="date" name="delivery_date" class="project-form">
            <br>Add user:<br> <input type="text" name="username" class="project-form">
            {{-- here we should use ajax instead for user search like in coordinator add collaborator --}}
            <button type="submit" class="button">+</button>
        </form>

        <form class="project-form" method="POST" action="{{ route('delete_task',['id' => $task->id]) }}">
            @method('DELETE')
            @csrf
            <button type="submit" class="button">
                <i class="fas fa-trash"></i>
                Delete Task
            </button>
        </form>
    </div>

    <script src="{{ asset('js/taskedit.js') }}" defer></script>
</li>
