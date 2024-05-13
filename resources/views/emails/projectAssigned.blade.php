<p>Ypu haven been assigned to projects</p>
<ol>
@forelse ($user->projects as $project)
    <li>{{$project->title}}</li>
@empty
    <li>No projects</li>
@endforelse
</ol>
<p>To {{$user->username}}</p>