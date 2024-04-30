<div>
    @php
        $displayParts = strpos($childFieldToDisplay, '-') !== false ? explode('-', $childFieldToDisplay) : null;
    @endphp
    @forelse ($parents as $parent)
        <optgroup label="{{ $parent->name }}">
            @forelse ($parent->$with as $child)
                @if ($displayParts !== null)
                    @php
                        $firstPart = $displayParts[0];
                        $secondPart = $displayParts[1];
                    @endphp
                    <option value="{{$child->id}}">{{$child->$firstPart->$secondPart}}</option>
                @else
                    <option value="{{$child->id}}">{{$child->$childFieldToDisplay}}</option>
                @endif
            @empty
            @endforelse
        </optgroup>
    @empty
    @endforelse
</div>

