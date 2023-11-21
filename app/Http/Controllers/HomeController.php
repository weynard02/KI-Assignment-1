<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Aes;
use App\Models\Des;
use App\Models\Rc4;
use App\Http\Controllers\HomeController\Rc4encrypt;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;

class HomeController extends Controller
{
    public function index()
    {
        $dess = Des::where('user_id', Auth::user()->id)->get();
        $rc4s = Rc4::where('user_id', Auth::user()->id)->get();
        $aess = Aes::where('user_id', Auth::user()->id)->get();
        return view('home.index', compact('dess', 'rc4s', 'aess'));
    }

    public function seeUsers() {
        $aess = Aes::where('user_id', Auth::user()->id)->get();
        $usernames = User::select('id', 'username')->get();
        return view('home.users', compact('usernames', 'aess'));
    }

    public function create()
    {
        $aess = Aes::where('user_id', Auth::user()->id)->get();
        if (!$aess->isEmpty()) return redirect('/home/edit');
        return view('home.create', compact('aess'));
    }

    public function edit()
    {
        $aess = Aes::where('user_id', Auth::user()->id)->get();
        return view('home.edit', compact('aess'));
    }

    public function store(Request $request)
    {
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
                'document.mimes' => 'Allowed document extension are PDF, DOCX, and XLS!',
                'video.required' => 'Video can\'t be empty!',
                'video.mimes' => 'Allowed video extension are MP4, MOV, and AVI!'
            ]
        );
        $key_aes = openssl_random_pseudo_bytes(32);
        $iv_aes = openssl_random_pseudo_bytes(16);
        $key_des = random_bytes(7);
        $iv_des = random_bytes(8);
        $key_rc4 = date('ymdhis');
        $fullname_des = $this->Desencrypt($request->fullname, $key_des, $iv_des, 0);
        $fullname_rc4 = $this->Rc4encrypt($request->fullname, $key_rc4, 0);
        $fullname_aes = $this->AESencrypt($request->fullname, $key_aes, $iv_aes, 0);
        $fullname_key = $key_aes;
        $fullname_iv = $iv_aes;

        $key_aes = openssl_random_pseudo_bytes(32);
        $iv_aes = openssl_random_pseudo_bytes(16);
        $id_card_file = $request->file('id_card');
        $id_card_ext = $id_card_file->extension();
        $id_card_new = date('ymdhis') . "." . $id_card_ext;
        $id_card_file->storeAs('public/id-card/aes', $id_card_new);
        $id_card_file->storeAs('public/id-card/des', $id_card_new);
        $id_card_file->storeAs('public/id-card/rc4', $id_card_new);
        $this->Desencrypt(storage_path('app/public/id-card/des/' . $id_card_new), $key_des, $iv_des, 1);
        $this->Rc4encrypt(storage_path('app/public/id-card/rc4/' . $id_card_new), $key_rc4, 1);
        $this->AESencrypt(storage_path('app/public/id-card/aes/' . $id_card_new), $key_aes, $iv_aes, 1);
        $id_card_key = $key_aes;
        $id_card_iv = $iv_aes;

        $key_aes = openssl_random_pseudo_bytes(32);
        $iv_aes = openssl_random_pseudo_bytes(16);
        $document_file = $request->file('document');
        $document_ext = $document_file->extension();
        $document_new = date('ymdhis') . "." . $document_ext;
        $document_file->storeAs('public/document/aes', $document_new);
        $document_file->storeAs('public/document/des', $document_new);
        $document_file->storeAs('public/document/rc4', $document_new);
        $this->Desencrypt(storage_path('app/public/document/des/' . $document_new), $key_des, $iv_des, 1);
        $this->Rc4encrypt(storage_path('app/public/document/rc4/' . $document_new), $key_rc4, 1);
        $this->AESencrypt(storage_path('app/public/document/aes/' . $document_new), $key_aes, $iv_aes, 1);
        $document_key = $key_aes;
        $document_iv = $iv_aes;

        $key_aes = openssl_random_pseudo_bytes(32);
        $iv_aes = openssl_random_pseudo_bytes(16);
        $video_file = $request->file('video');
        $video_ext = $video_file->extension();
        $video_new = date('ymdhis') . "." . $video_ext;
        $video_file->storeAs('public/video/aes', $video_new);
        $video_file->storeAs('public/video/des', $video_new);
        $video_file->storeAs('public/video/rc4', $video_new);
        $this->Desencrypt(storage_path('app/public/video/des/' . $video_new), $key_des, $iv_des, 1);
        $this->Rc4encrypt(storage_path('app/public/video/rc4/' . $video_new), $key_rc4, 1);
        $this->AESencrypt(storage_path('app/public/video/aes/' . $video_new), $key_aes, $iv_aes, 1);
        $video_key = $key_aes;
        $video_iv = $iv_aes;

        Aes::create([
            'fullname' => $fullname_aes,
            'id_card' => $id_card_new,
            'document' => $document_new,
            'video' => $video_new,
            'user_id' => Auth::user()->id,
            'fullname_key' => base64_encode($fullname_key),
            'fullname_iv' => base64_encode($fullname_iv),
            'id_card_key' => base64_encode($id_card_key),
            'id_card_iv' => base64_encode($id_card_iv),
            'document_key' => base64_encode($document_key),
            'document_iv' => base64_encode($document_iv),
            'video_key' => base64_encode($video_key),
            'video_iv' => base64_encode($video_iv),
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
            'key' => $key_rc4
        ]);

        return redirect('/home');
    }

    public function update(Request $request)
    {
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
                'document.mimes' => 'Allowed document extension are PDF, DOCX, and XLS!',
                'video.required' => 'Video can\'t be empty!',
                'video.mimes' => 'Allowed video extension are MP4, MOV, and AVI!'
            ]
        );

        $key_aes = openssl_random_pseudo_bytes(32);
        $iv_aes = openssl_random_pseudo_bytes(16);
        $key_des = random_bytes(7);
        $iv_des = random_bytes(8);
        $key_rc4 = date('ymdhis');
        $fullname_des = $this->Desencrypt($request->fullname, $key_des, $iv_des, 0);
        $fullname_rc4 = $this->Rc4encrypt($request->fullname, $key_rc4, 0);
        $fullname_aes = $this->AESencrypt($request->fullname, $key_aes, $iv_aes, 0);
        $fullname_key = $key_aes;
        $fullname_iv = $iv_aes;

        $key_aes = openssl_random_pseudo_bytes(32);
        $iv_aes = openssl_random_pseudo_bytes(16);
        $id_card_file = $request->file('id_card');
        $id_card_ext = $id_card_file->extension();
        $id_card_new = date('ymdhis') . "." . $id_card_ext;
        $id_card_file->storeAs('public/id-card/aes', $id_card_new);
        $id_card_file->storeAs('public/id-card/des', $id_card_new);
        $id_card_file->storeAs('public/id-card/rc4', $id_card_new);
        $this->Desencrypt(storage_path('app/public/id-card/des/' . $id_card_new), $key_des, $iv_des, 1);
        $this->Rc4encrypt(storage_path('app/public/id-card/rc4/' . $id_card_new), $key_rc4, 1);
        $this->AESencrypt(storage_path('app/public/id-card/aes/' . $id_card_new), $key_aes, $iv_aes, 1);
        $id_card_key = $key_aes;
        $id_card_iv = $iv_aes;

        $key_aes = openssl_random_pseudo_bytes(32);
        $iv_aes = openssl_random_pseudo_bytes(16);
        $document_file = $request->file('document');
        $document_ext = $document_file->extension();
        $document_new = date('ymdhis') . "." . $document_ext;
        $document_file->storeAs('public/document/aes', $document_new);
        $document_file->storeAs('public/document/des', $document_new);
        $document_file->storeAs('public/document/rc4', $document_new);
        $this->Desencrypt(storage_path('app/public/document/des/' . $document_new), $key_des, $iv_des, 1);
        $this->Rc4encrypt(storage_path('app/public/document/rc4/' . $document_new), $key_rc4, 1);
        $this->AESencrypt(storage_path('app/public/document/aes/' . $document_new), $key_aes, $iv_aes, 1);
        $document_key = $key_aes;
        $document_iv = $iv_aes;

        $key_aes = openssl_random_pseudo_bytes(32);
        $iv_aes = openssl_random_pseudo_bytes(16);
        $video_file = $request->file('video');
        $video_ext = $video_file->extension();
        $video_new = date('ymdhis') . "." . $video_ext;
        $video_file->storeAs('public/video/aes', $video_new);
        $video_file->storeAs('public/video/des', $video_new);
        $video_file->storeAs('public/video/rc4', $video_new);
        $this->Desencrypt(storage_path('app/public/video/des/' . $video_new), $key_des, $iv_des, 1);
        $this->Rc4encrypt(storage_path('app/public/video/rc4/' . $video_new), $key_rc4, 1);
        $this->AESencrypt(storage_path('app/public/video/aes/' . $video_new), $key_aes, $iv_aes, 1);
        $video_key = $key_aes;
        $video_iv = $iv_aes;

        Aes::where('user_id', Auth::user()->id)->update([
            'fullname' => $fullname_aes,
            'id_card' => $id_card_new,
            'document' => $document_new,
            'video' => $video_new,
            'fullname_key' => base64_encode($fullname_key),
            'fullname_iv' => base64_encode($fullname_iv),
            'id_card_key' => base64_encode($id_card_key),
            'id_card_iv' => base64_encode($id_card_iv),
            'document_key' => base64_encode($document_key),
            'document_iv' => base64_encode($document_iv),
            'video_key' => base64_encode($video_key),
            'video_iv' => base64_encode($video_key),
        ]);

        Des::where('user_id', Auth::user()->id)->update([
            'fullname' => $fullname_des,
            'id_card' => $id_card_new,
            'document' => $document_new,
            'video' => $video_new,
            'key' => bin2hex($key_des),
            'iv' => bin2hex($iv_des)
        ]);

        RC4::where('user_id', Auth::user()->id)->update([
            'fullname' => $fullname_rc4,
            'id_card' => $id_card_new,
            'document' => $document_new,
            'video' => $video_new,
            'key' => $key_rc4
        ]);

        return redirect('/home');
    }

    public function download($algo, $type, $id, $akey) {
        if ($algo == 'aes') {
            $data = Aes::findorfail($id);
            $key = $data->fullname_key;
            $iv = $data->fullname_iv;
            if ($type == 'id_card') {
                $file = $data->id_card;
                $filePath = storage_path('app/public/id-card/aes/' . $file);
                $copyFilePath = storage_path('app/public/id-card/aes/download_' . $file);
                $key = $data->id_card_key;
                $iv = $data->id_card_iv;

            }
            else if ($type == 'document') {
                $file = $data->document;
                $filePath = storage_path('app/public/document/aes/' . $file);
                $copyFilePath = storage_path('app/public/document/aes/download_' . $file);
                $key = $data->document_key;
                $iv = $data->document_iv;
            }
            else if ($type == 'video') {
                $file = $data->video;
                $filePath = storage_path('app/public/video/aes/' . $file);
                $copyFilePath = storage_path('app/public/video/aes/download_' . $file);
                $key = $data->video_key;
                $iv = $data->video_iv;
            }
            $checkKey = str_replace('/', '', $key);
            if ($akey != $checkKey) return abort('403');
            File::copy($filePath, $copyFilePath);

            $this->AESDecrypt($copyFilePath, $key, $iv, 1);
            $downloadFilePath = $copyFilePath;

            return response()->download($downloadFilePath)->deleteFileAfterSend(true);
        }

        else if ($algo == 'des') {
            $data = Des::findorfail($id);
            if ($type == 'id_card') {
                $file = $data->id_card;
                $filePath = storage_path('app/public/id-card/des/' . $file);
                $copyFilePath = storage_path('app/public/id-card/des/download_' . $file);
            }
            else if ($type == 'document') {
                $file = $data->document;
                $filePath = storage_path('app/public/document/des/' . $file);
                $copyFilePath = storage_path('app/public/document/des/download_' . $file);
            }
            else if ($type == 'video') {
                $file = $data->video;
                $filePath = storage_path('app/public/video/des/' . $file);
                $copyFilePath = storage_path('app/public/video/des/download_' . $file);
            }

            File::copy($filePath, $copyFilePath);

            $this->Desdecrypt($copyFilePath, $data->key, $data->iv, 1);
            $downloadFilePath = $copyFilePath;

            return response()->download($downloadFilePath)->deleteFileAfterSend(true);
        }
        else if($algo == 'rc4') {
            $data = Rc4::findorfail($id);
            if ($type == 'id_card') {
                $file = $data->id_card;
                $filePath = storage_path('app/public/id-card/rc4/' . $file);
                $copyFilePath = storage_path('app/public/id-card/rc4/download_' . $file);
            }
            else if ($type == 'document') {
                $file = $data->document;
                $filePath = storage_path('app/public/document/rc4/' . $file);
                $copyFilePath = storage_path('app/public/document/rc4/download_' . $file);
            }
            else if ($type == 'video') {
                $file = $data->video;
                $filePath = storage_path('app/public/video/rc4/' . $file);
                $copyFilePath = storage_path('app/public/video/rc4/download_' . $file);
            }

            File::copy($filePath, $copyFilePath);

            $this->Rc4decrypt($copyFilePath, $data->key, 1);
            $downloadFilePath = $copyFilePath;

            return response()->download($downloadFilePath)->deleteFileAfterSend(true);
        }
    }

    public function AESencrypt($data, $key, $iv, $is_file)
    {
        if ($is_file == 1) $plaintext = file_get_contents($data);
        else $plaintext = $data;

        $ciphertext = openssl_encrypt($plaintext, 'AES-256-CBC', $key, 0, $iv); // AES-256 CBC

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

    public function AESdecrypt($data, $key, $iv, $is_file) {

        $key = base64_decode($key);
        $iv = base64_decode($iv);
        if ($is_file == 1) $ciphertext = file_get_contents($data);
        else $ciphertext = $data;

        // Start calculating usage statistics
        $start_usage = getrusage();

        $plaintext = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, 0, $iv);

        // Stop calculating usage statistics
        $end_usage = getrusage();

        // Output user run time and system run time from usage statistics
        $user_time = $end_usage["ru_utime.tv_sec"] - $start_usage["ru_utime.tv_sec"];
        $user_time += ($end_usage["ru_utime.tv_usec"] - $start_usage["ru_utime.tv_usec"]) / 1000000;

        $system_time = $end_usage["ru_stime.tv_sec"] - $start_usage["ru_stime.tv_sec"];
        $system_time += ($end_usage["ru_stime.tv_usec"] - $start_usage["ru_stime.tv_usec"]) / 1000000;

        error_log("AES Decryption user run time : " . $user_time . " second");
        error_log("AES Decryption system run time : " . $system_time . " second");

        $total_time = $user_time + $system_time;
        error_log("AES Decryption total time : " . $total_time . " second");

        if ($is_file == 1) file_put_contents($data, $plaintext);
        else return $plaintext;
    }

    public function Rc4decrypt($data, $key, $is_file) {
        if ($is_file == 1) $ciphertext = file_get_contents($data);
        else $ciphertext = $data;

        // Start calculating usage statistics
        $start_usage = getrusage();

        $ciphertext = hex2bin($ciphertext);
        $len = strlen($key);
        $S = range(0, 255);
        $j = 0;

        for ($i = 0; $i < 256; $i++) {
            $j = ($j + $S[$i] + ord($key[$i % $len])) % 256;
            [$S[$i], $S[$j]] = [$S[$j], $S[$i]];
        }

        $i = 0;
        $j = 0;
        $plaintext = '';

        for ($k = 0; $k < strlen($ciphertext); $k++) {
            $i = ($i + 1) % 256;
            $j = ($j + $S[$i]) % 256;
            [$S[$i], $S[$j]] = [$S[$j], $S[$i]];
            $char = $ciphertext[$k] ^ chr($S[($S[$i] + $S[$j]) % 256]);
            $plaintext .= $char;
        }

        // Stop calculating usage statistics
        $end_usage = getrusage();

        // Output user run time and system run time from usage statistics
        $user_time = $end_usage["ru_utime.tv_sec"] - $start_usage["ru_utime.tv_sec"];
        $user_time += ($end_usage["ru_utime.tv_usec"] - $start_usage["ru_utime.tv_usec"]) / 1000000;

        $system_time = $end_usage["ru_stime.tv_sec"] - $start_usage["ru_stime.tv_sec"];
        $system_time += ($end_usage["ru_stime.tv_usec"] - $start_usage["ru_stime.tv_usec"]) / 1000000;

        error_log("RC4 Decryption user run time : " . $user_time . " second");
        error_log("RC4 Decryption system run time : " . $system_time . " second");

        $total_time = $user_time + $system_time;
        error_log("RC4 Decryption total time : " . $total_time . " second");

        if ($is_file == 1) file_put_contents($data, $plaintext);
        else return $plaintext;
    }

    public function Desdecrypt($data, $key, $iv, $is_file) {
        if ($is_file == 1) $ciphertext = file_get_contents($data);
        else $ciphertext = $data;

        // Start calculating usage statistics
        $start_usage = getrusage();

        $ciphertext = hex2bin($ciphertext);
        $iv = hex2bin($iv);
        $key = hex2bin($key);

        $plaintext = openssl_decrypt($ciphertext, 'des-ede-cfb', $key, 0, $iv);

        // Stop calculating usage statistics
        $end_usage = getrusage();

        // Output user run time and system run time from usage statistics
        $user_time = $end_usage["ru_utime.tv_sec"] - $start_usage["ru_utime.tv_sec"];
        $user_time += ($end_usage["ru_utime.tv_usec"] - $start_usage["ru_utime.tv_usec"]) / 1000000;

        $system_time = $end_usage["ru_stime.tv_sec"] - $start_usage["ru_stime.tv_sec"];
        $system_time += ($end_usage["ru_stime.tv_usec"] - $start_usage["ru_stime.tv_usec"]) / 1000000;

        error_log("DES Decryption user run time : " . $user_time . " second");
        error_log("DES Decryption system run time : " . $system_time . " second");

        $total_time = $user_time + $system_time;
        error_log("DES Decryption total time : " . $total_time . " second");

        if ($is_file == 1) file_put_contents($data, $plaintext);
        else return $plaintext;
    }
}
