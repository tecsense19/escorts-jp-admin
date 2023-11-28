@if ($paginator->hasPages())
<div class="row justify-content-between mt-3 p-2">
    <div id="user-list-page-info" class="col-md-6">
        <!-- <span>Showing {{ $paginator->currentPage() }} to {{ $paginator->perPage() }} of {{ $paginator->lastPage() }} entries</span> -->
    </div>
    <div class="col-md-6">
        <div aria-label="Page navigation example">
            <ul class="pagination justify-content-end mb-0">
                @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <a class="page-link" tabindex="-1" aria-disabled="true" rel="prev">Previous</a>
                </li>
                @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" tabindex="-1" aria-disabled="true" rel="prev">Previous</a>
                </li>
                @endif
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="disabled"><span>{{ $element }}</span></li>
                    @endif
                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active"><a class="page-link" href="{{ $url }}">{{ $page }}</span></a></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <a class="page-link" tabindex="-1" aria-disabled="true" rel="next">Next</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
@endif