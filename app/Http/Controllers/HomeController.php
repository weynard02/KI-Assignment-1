<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Aes;
use App\Models\Des;
use App\Models\Rc4;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (!Auth::user()) return redirect('/login');
        return view('home.index');
    }

    public function create()
    {
        if (!Auth::user()) return redirect('/login');
        return view('home.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()) return redirect('/login');
        $request->validate(
            [
                'fullname' => 'required',
                'id_card' => 'required|mimes:jpg,jpeg,png',
                'document' => 'required|mimes:pdf,docx,xls',
                'video' => 'required|mimes:mp4,mov,avi',
            ],
            [
                'fullname.required' => 'Fullname can\'t be empty!',
                'id_card.required' => 'ID Card can\'t be empty!',
                'id_card.mimes' => 'Allowed ID Card extension are JPG, JPEG, and PNG!',
                'document.required' => 'Document can\'t be empty!',
                'document.mimes' => 'Allowed ID Card extension are PDF, DOCX, and XLS!',
                'video.required' => 'Video can\'t be empty!',
                'video.mimes' => 'Allowed ID Card extension are MP4, MOV, and AVI!'
            ]
        );

        $id_card_file = $request->file('id_card');
        $id_card_ext = $id_card_file->extension();
        $id_card_new = date('ymdhis') . "." . $id_card_ext;
        $id_card_file->storeAs('public/id-card', $id_card_new);

        $document_file = $request->file('document');
        $document_ext = $document_file->extension();
        $document_new = date('ymdhis') . "." . $document_ext;
        $document_file->storeAs('public/document', $document_new);

        $video_file = $request->file('video');
        $video_ext = $video_file->extension();
        $video_new = date('ymdhis') . "." . $video_ext;
        $video_file->storeAs('public/video', $video_new);


        $data = [
            'fullname' => $request->fullname,
            'id_card' => $id_card_new,
            'document' => $document_new,
            'video' => $video_new,
            'user_id' => Auth::user()->id,
        ];

        Aes::create($data);
        Des::create($data);
        RC4::create($data);

        return redirect('/home');
    }
}
