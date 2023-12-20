<link rel="stylesheet" href="{{ asset('css/notifications.css') }}">

@extends('layouts.app')

@section('title', 'Forum')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="NotificationsBody">
    <div class="checkAll">
        <h2 class="title">Your Notifications</h2>
        @if(count($notifications) > 0)
        <form method="POST" action="{{ route('notification.checkAll', ['username' => request('username')]) }}">
            @method('POST')
            @csrf
            <button type="submit" class="checkAll">CHECK ALL</button>
        </form>
        @endif
    </div>
    <div class="listOfNotifications">
        <ul class="listOfPosts" id="postList">
            @forelse ($notifications as $notification)
                <div class="notificationCard">
                    <div class="notificationHeader">
                        <p>{{$notification->date}}</p>
                        <form method="POST" action="{{ route('notification.check', ['username' => request('username')]) }}">
                            @method('POST')
                            @csrf
                            <input type="hidden" name="notification_id" value="{{$notification->id}}">
                            <button type="submit" class="checkAll">CHECK</button>
                        </form>
                    </div>
                    <h4 class="notDesc">{{$notification->description}}</h4>
                </div>
            @empty
                <h4 class="noPosts">No unseen notifications</h4>   
            @endforelse
        </ul>
    </div>

</div>

@endsection