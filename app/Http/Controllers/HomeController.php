<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Aes;
use App\Models\Des;
use App\Models\Rc4;
use App\Http\Controllers\HomeController\Rc4encrypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;

class HomeController extends Controller
{
    public function index()
    {
        // if (!Auth::user()) return redirect('/login');
        $des = Des::where('user_id', Auth::id())->get();
        $rc4s = Rc4::where('user_id', Auth::id())->get();
        $aess = Aes::where('user_id', Auth::id())->get();
        return view('home.index', compact('des', 'rc4s', 'aess'));
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

        $key_des = random_bytes(7);
        $iv_des = random_bytes(8);
        $fullname_des = $this->Desencrypt($request->fullname, $key_des, $iv_des, 0);
        $fullname_rc4 = $this->Rc4encrypt($request->fullname, date('ymdhis'), 0);
        $fullname_aes = $this->AESencrypt($request->fullname, 0);

        $id_card_file = $request->file('id_card');
        $id_card_ext = $id_card_file->extension();
        $id_card_new = date('ymdhis') . "." . $id_card_ext;
        $id_card_file->storeAs('public/id-card/aes', $id_card_new);
        $id_card_file->storeAs('public/id-card/des', $id_card_new);
        $id_card_file->storeAs('public/id-card/rc4', $id_card_new);
        $this->Desencrypt(storage_path('app/public/id-card/des/' . $id_card_new), $key_des, $iv_des, 1);
        $this->Rc4encrypt(storage_path('app/public/id-card/rc4/' . $id_card_new), date('ymdhis'), 1);
        $this->AESencrypt(storage_path('app/public/id-card/aes/' . $id_card_new), 1);

        $document_file = $request->file('document');
        $document_ext = $document_file->extension();
        $document_new = date('ymdhis') . "." . $document_ext;
        $document_file->storeAs('public/document/aes', $document_new);
        $document_file->storeAs('public/document/des', $document_new);
        $document_file->storeAs('public/document/rc4', $document_new);
        $this->Desencrypt(storage_path('app/public/document/des/' . $document_new), $key_des, $iv_des, 1);
        $this->Rc4encrypt(storage_path('app/public/document/rc4/' . $document_new), date('ymdhis'), 1);
        $this->AESencrypt(storage_path('app/public/document/aes/' . $document_new), 1);

        $video_file = $request->file('video');
        $video_ext = $video_file->extension();
        $video_new = date('ymdhis') . "." . $video_ext;
        $video_file->storeAs('public/video/aes', $video_new);
        $video_file->storeAs('public/video/des', $video_new);
        $video_file->storeAs('public/video/rc4', $video_new);
        $this->Desencrypt(storage_path('app/public/video/des/' . $video_new), $key_des, $iv_des, 1);
        $this->Rc4encrypt(storage_path('app/public/video/rc4/' . $video_new), date('ymdhis'), 1);
        $this->AESencrypt(storage_path('app/public/video/aes/' . $video_new), 1);

        Aes::create([
            'fullname' => $fullname_aes,
            'id_card' => $id_card_new,
            'document' => $document_new,
            'video' => $video_new,
            'user_id' => Auth::user()->id,
            'key' => Config::get('app.key')
        ]);
        
        Des::create([
            'fullname' => $fullname_des,
            'id_card' => $id_card_new,
            'document' => $document_new,
            'video' => $video_new,
            'user_id' => Auth::user()->id,
            'key' => bin2hex($key_des),
            'iv' => bin2hex($iv_des)
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

    public function AESencrypt($data, $is_file) // AES-256 CBC
    {
        if ($is_file == 1) $plaintext = file_get_contents($data);
        else $plaintext = $data;

        $ciphertext = Crypt::encryptString($plaintext);
        
        if ($is_file == 1) file_put_contents($data, $ciphertext);
        else return $ciphertext;

    }
    public function Rc4encrypt($data, $key, $is_file)
    {
        if ($is_file == 1) $plaintext = file_get_contents($data);
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

        if ($is_file == 1) file_put_contents($data, $ciphertext);
        else return $ciphertext;
    }

    public function Desencrypt($data, $key, $iv, $is_file)
    {
        if ($is_file == 1) $plaintext = file_get_contents($data);
        else $plaintext = $data;

        $ciphertext = openssl_encrypt($plaintext, 'des-ede-cfb', $key, 0, $iv);
        $ciphertext = bin2hex($ciphertext);

        if ($is_file == 1) file_put_contents($data, $ciphertext);
        else return $ciphertext;
    }
}
