@extends('layouts.app')

@section('title', $task->name)

@section('content')
<div id="divtask">
    <h3>{{$task->name}}</h3>
    <p>Description: {{$task->description}}</p>
    <p>Task started on {{$task->start_date}}</p>
    <p>Needs to be done by {{$task->delivery_date}}</p>
    <p>Status: {{$task->status}}</p>
    <p>Assigned to:</p> <p>@each('partials.member', $task->members, 'user')</p>
    <form method="POST" action="{{ route('edit_task',['id' => $task->id]) }}">
        @method('POST')
        @csrf
        Name: <input type="text" name="name">
        Description: <input type="text" name="description">
        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="" selected disabled>{{$task->status}}</option>
            <option value="To Do">To Do</option>
            <option value="Doing">Doing</option>
            <option value="Done">Done</option>
        </select>
        Delivery Date: <input type="date" name="delivery_date">
        Add user: <input type="text" name="username">
        <button type="submit" class="button">Edit</button>
    </form>

    <form method="POST" action="{{ route('delete_task',['id' => $task->id]) }}">
        @method('DELETE')
        @csrf
        <button type="submit" class="button">Delete</button>
    </form>
    
</div>

@endsection