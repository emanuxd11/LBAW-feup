<article class="project" data-id="{{ $project->id }}">
    <header>
        <h2><a href="/projects/{{ $project->id }}">{{ $project->name }}</a></h2>
        <a href="#" class="delete">&#10761;</a>
    </header>
    <ul>
        
    </ul>
    <form class="new_item">
        <input type="text" name="description" placeholder="new item">
    </form>
</article>