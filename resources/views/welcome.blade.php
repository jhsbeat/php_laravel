@extends('layouts.master')

@section('content')
    <p>저는 자식 뷰의 'content' 섹션입니다.</p>

    @if($itemCount = count($items))
        <p>{{ $itemCount }} 종류의 과일이 있습니다.</p>
    @else
        <p>엥~ 아무것도 없는데요!</p>
    @endif

    <ul>
        @forelse($items as $item)
            <li>{{ $item }}</li>
        @empty
            <li>엥~ 아무것도 없는데요!</li>
        @endforelse
    </ul>

    @include('partials.footer')
@endsection

@section('script')
    <script>
        alert("저는 자식 뷰의 'script' 섹션입니다.");
    </script>
@endsection