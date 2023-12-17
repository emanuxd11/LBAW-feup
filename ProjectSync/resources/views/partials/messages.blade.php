{{-- 
    This has many @ifs on purpose since we're being inconsistent with the 
    type of errors/messages we send and this way it doesn't matter.
--}}

<div class="errors">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            <ul>
                {{ session('error') }}
            </ul>
        </div>
    @endif
</div>