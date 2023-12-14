<div class="errors">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger">
            <ul>
                {{ session('error') }}
            </ul>
        </div>
    @endif
</div>