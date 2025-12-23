@if($recipes->isEmpty())
    <div class="text-center text-secondary py-4">No recipes found.</div>
@else
    <div class="list-group w-100">
        @foreach($recipes as $recipe)
            <a href="/recipe-posts/{{ $recipe->id ?? $recipe->id }}" class="list-group-item list-group-item-action d-flex align-items-center">
                <div class="me-3" style="width:64px;height:64px;flex:0 0 64px;overflow:hidden;border-radius:6px;background:#f2f2f2;display:flex;align-items:center;justify-content:center;">
                    @if(!empty($recipe->image))
                        <img src="/images/{{ ltrim($recipe->image, '/') }}" alt="" style="width:100%;height:100%;object-fit:cover;"/>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold">{{ $recipe->title }}</div>
                    <div class="small text-muted">by {{ optional($recipe->user)->name ?? 'Unknown' }} • {{ optional($recipe->updated_at)->diffForHumans() ?? '' }}</div>
                </div>
            </a>
        @endforeach
    </div>
@endif
