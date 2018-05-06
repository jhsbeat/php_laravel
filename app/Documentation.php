<?php

namespace App;

use File;

class Documentation
{
    public function get($file = 'documentation.md'){
        $content = File::get($this->path($file));
        return $this->replaceLinks($content);
    }

    protected function path($file){
        $file = ends_with($file, '.md') ? $file : $file . '.md';
        $path = base_path('docs' . DIRECTORY_SEPARATOR . $file);

        if(!File::exists($path)){
            abort(404, '요청하신 파일이 없습니다.');
        }
        return $path;
    }

    protected function replaceLinks($content){
        return str_replace('/docs/{{version}}', '/docs', $content);
    }
}
