<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttachmentsController extends Controller
{
    public function store(Request $request){

        $attachments = [];

        if($request->has('files')){
            $files = $request->file('files');
            foreach($files as $file){
                $filename = str_random().filter_var($file->getClientOriginalName(), FILTER_SANITIZE_URL);

                $file->move(attachments_path(), $filename);

                $payload = [
                    'filename' => $filename,
                    'bytes' => $file->getClientSize(),
                    'mime' => $file->getClientMimeType()
                ];

                $attachments[] = ($id = $request->input('article_id'))
                    ? \App\Article::findOrFail($id)->attachments()->create($payload)
                    : \App\Attachment::create($payload);
            }
        }
        return response()->json($attachments);

    }

    public function destroy($if){

    }
}
