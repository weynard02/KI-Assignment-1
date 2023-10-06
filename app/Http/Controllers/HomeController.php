<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Aes;
use App\Models\Des;
use App\Models\Rc4;
use App\Http\Controllers\HomeController\Rc4encrypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // if (!Auth::user()) return redirect('/login');
        $rc4s = Rc4::where('user_id', Auth::id())->get();
        return view('home.index', compact('rc4s'));
    }

    public function create()
    {
        // if (!Auth::user()) return redirect('/login');
        return view('home.create');
    }

    public function store(Request $request)
    {
        // if (!Auth::user()) return redirect('/login');
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

        $fullname_rc4 = $this->Rc4encrypt($request->fullname, date('ymdhis'), 0);

        $id_card_file = $request->file('id_card');
        $id_card_ext = $id_card_file->extension();
        $id_card_new = date('ymdhis') . "." . $id_card_ext;
        $id_card_file->storeAs('public/id-card/aes', $id_card_new);
        $id_card_file->storeAs('public/id-card/des', $id_card_new);
        $id_card_file->storeAs('public/id-card/rc4', $id_card_new);
        $this->Rc4encrypt(storage_path('app/public/id-card/rc4/' . $id_card_new), date('ymdhis'), 1);

        $document_file = $request->file('document');
        $document_ext = $document_file->extension();
        $document_new = date('ymdhis') . "." . $document_ext;
        $document_file->storeAs('public/document/aes', $document_new);
        $document_file->storeAs('public/document/des', $document_new);
        $document_file->storeAs('public/document/rc4', $document_new);
        $this->Rc4encrypt(storage_path('app/public/document/rc4/' . $document_new), date('ymdhis'), 1);

        $video_file = $request->file('video');
        $video_ext = $video_file->extension();
        $video_new = date('ymdhis') . "." . $video_ext;
        $video_file->storeAs('public/video/aes', $video_new);
        $video_file->storeAs('public/video/des', $video_new);
        $video_file->storeAs('public/video/rc4', $video_new);
        $this->Rc4encrypt(storage_path('app/public/video/rc4/' . $video_new), date('ymdhis'), 1);

        Aes::create([
            'fullname' => $request->fullname,
            'id_card' => $id_card_new,
            'document' => $document_new,
            'video' => $video_new,
            'user_id' => Auth::user()->id,
            'key' => date('ymdhis')
        ]);
        
        Des::create([
            'fullname' => $request->fullname,
            'id_card' => $id_card_new,
            'document' => $document_new,
            'video' => $video_new,
            'user_id' => Auth::user()->id,
            'key' => date('ymdhis')
        ]);

        RC4::create([
            'fullname' => $fullname_rc4,
            'id_card' => $id_card_new,
            'document' => $document_new,
            'video' => $video_new,
            'user_id' => Auth::user()->id,
            'key' => date('ymdhis')
        ]);

        return redirect('/home');
    }

    public function Rc4encrypt($data, $key, $verse)
    {
        if ($verse == 1) $plaintext = file_get_contents($data);
        else $plaintext = $data;
        $len = strlen($key);
        $S = range(0, 255);
        $j = 0;
        
        for ($i = 0; $i < 256; $i++) {
            $j = ($j + $S[$i] + ord($key[$i % $len])) % 256;
            [$S[$i], $S[$j]] = [$S[$j], $S[$i]];
        }
        
        $i = 0;
        $j = 0;
        $ciphertext = '';
        
        for ($k = 0; $k < strlen($plaintext); $k++) {
            $i = ($i + 1) % 256;
            $j = ($j + $S[$i]) % 256;
            [$S[$i], $S[$j]] = [$S[$j], $S[$i]];
            $char = $plaintext[$k] ^ chr($S[($S[$i] + $S[$j]) % 256]);
            $ciphertext .= $char;
        }
        $ciphertext = bin2hex($ciphertext);

        if ($verse == 1) file_put_contents($data, $ciphertext);
        else return $ciphertext;
    }
}
