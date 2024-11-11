<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachmentRequest;
use App\Models\ProjectAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProjectAttachmentController extends Controller
{
    public function upload (AttachmentRequest $request)
    {
        $validated = $request->validated();
        $data['url'] = uploadFile($validated['file'], 'project-attachments');
        $data['slug'] = $validated['file']->getClientOriginalName();
        $attachment = ProjectAttachment::create($data);
        Session::flash('flash_data', [
            'file' => $attachment,
        ]);
    }

    public function download($attachment)
    {
        $attachment = ProjectAttachment::findOrFail($attachment);
        return response()->download(public_path($attachment->url), $attachment->slug);
    }
}
