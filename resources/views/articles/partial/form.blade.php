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

{{--<div class="form-group {{ $errors->has('files') ? 'has-error' : '' }}">--}}
  {{--<label for="files">파일</label>--}}
  {{--<input type="file" name="files[]" id="files" class="form-control" multiple="multiple" />--}}
  {{--{!! $errors->first('files.0', '<span class="form-error">:message</span>') !!}--}}
{{--</div>--}}

<div class="form-group">
  <label for="my-dropzone">파일</label>
  <div id="my-dropzone" class="dropzone"></div>
</div>

@section('script')
  @parent
  <script>
    var myDropzone = new Dropzone('div#my-dropzone', {
      url: '/attachments',
      paramName: 'files',
      maxFilesize: 3,
      acceptedFiles: '.{{ implode(',.', config('project.mimes')) }}',
      uploadMultiple: true,
      params: {
          _token: $('meta[name="csrf-token"]').attr('content'),
          article_id: '{{ $article->id }}'
      },
      dictDefaultMessage: '<div class="text-center text-muted">' +
      "<h2>첨부할 파일을 끌어다 놓으세요!</h2>" +
      "<p>(또는 클릭하셔도 됩니다.)</p></div>",
      dictFileTooBig: "파일당 최대 크기는 3MB입니다.",
      dictInvalidFileType: '{{ implode(',', config('project.mimes')) }} 파일만 가능합니다.',
      addRemoveLinks: true
    });

    var form = $('form').first();
    // 파일 업로드 성공 이벤트 리스너.
    myDropzone.on('successmultiple', function(file, data) {
      for (var i= 0,len=data.length; i<len; i++) {
        $('<input>', {
          type: 'hidden',
          name: 'attachments[]',
          value: data[i].id
        }).appendTo(form);
      }
    });

    $("#tags").select2({
       placeholder: '태그를 선택하세요 (최대3개)',
       maximumSelectionLength: 3
    });
  </script>
@endsection