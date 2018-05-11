<div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
  <label for="title">제목</label>
  <input type="text" name="title" value="{{ old('title', $article->title) }}" class="form-control" />
  {!! $errors->first('title', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
  <label for="content">본문</label>
  <textarea id="content" name="content" rows="10" class="form-control">{{ old('content', $article->content) }}</textarea>
  {!! $errors->first('content', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('tags') ? 'has-error' : '' }}">
  <label for="tags">태그</label>
  <select class="form-control" name="tags[]" id="tags" multiple="multiple">
    @foreach($allTags as $tag)
      <option value="{{ $tag->id }}" {{ $article->tags->contains($tag->id) ? 'selected=selected' : '' }}>
        {{ $tag->name }}
      </option>
    @endforeach
    {!! $errors->first('tags', '<span class="form-error">:message</span>') !!}
  </select>
</div>

@section('script')
  @parent
  <script>
    $("#tags").select2({
       placeholder: '태그를 선택하세요 (최대3개)',
       maximumSelectionLength: 3
    });
  </script>
@endsection