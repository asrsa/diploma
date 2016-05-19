@if($data->lastPage() > 1)
    <ul class="pagination pagination-sm">
        <li class="page-item" style="{{ ($data->currentPage() == 1)? 'pointer-events: none; cursor: default;': '' }}">
            <a class="page-link" href="{{ $data->previousPageUrl() }}{{ $append or '' }}" aria-label="Previous" style="{{ ($data->currentPage() == 1)? 'color: grey;': '' }}">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Previous</span>
                {{ trans('views\pagination.previous') }}
            </a>
        </li>

         {{--show 1 link on side: 1|...|2|3|4|....|9--}}

        <?php
            $start = $data->currentPage() - 1;
            $finish = $data->currentPage() + 1;

            $start = ($start < 1)? 1: $start;
            $finish = ($finish >= $data->lastPage())? $data->lastPage(): $finish;
        ?>
        @if($start != 1)
            <li class="page-item">
                <a class="page-link" href="{{ $data->url(1) }}{{ $append or '' }}">1</a>
            </li>
            <li class="page-item" style="pointer-events: none; cursor: default;">
                <a class="page-link" href="{{ $data->url(1) }}{{ $append or '' }}">..</a>
            </li>
        @endif

        @for($i = $start; $i <= $finish; $i++)
            <li class="page-item {{ ($data->currentPage() == $i)?'active': '' }}">
                <a class="page-link" href="{{ $data->url($i) }}{{ $append or '' }}">{{ $i }}</a>
            </li>
        @endfor

        @if($finish != $data->lastPage())
            <li class="page-item" style="pointer-events: none; cursor: default;">
                <a class="page-link" href="{{ $data->url(1) }}{{ $append or '' }}">..</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="{{ $data->url($data->lastPage()) }}{{ $append or '' }}">{{ $data->lastPage() }}</a>
            </li>
        @endif

        <li class="page-item" style="{{ ($data->currentPage() == $data->lastPage())? 'pointer-events: none; cursor: default;': '' }}">
            <a class="page-link" href="{{ $data->nextPageUrl() }}{{ $append or '' }}" aria-label="Next" style="{{ ($data->currentPage() == $data->lastPage())? 'color: grey;': '' }}">
                {{ trans('views\pagination.next') }}
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
            </a>
        </li>
    </ul>
@endif