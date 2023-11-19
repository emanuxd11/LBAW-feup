<li class="item" data-id="{{$user->id}}">
    <a href="{{ route('profilePage', ['username' => $user->username]) }}"><span>{{ $user->name }}</span></a>
</li>
