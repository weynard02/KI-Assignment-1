<?php

namespace App\Http\Controllers;

use App\Models\UserInbox;
use Mail;
use Auth;
use App\Mail\HelloMail;
use App\Models\User;
use App\Models\AES;
use Illuminate\Http\Request;
use Http\Controllers\DataController;
use App\Http\Controllers\HomeController;
use phpseclib3\Crypt\RSA;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MailController extends Controller
{
    public function accRequest($main_id, $client_id, $type, $symkey, $iv_data, $encrypted_data, $is_file)
    {
        $inbox = UserInbox::where('main_user_id', $main_id)
            ->where('client_user_id', $client_id)->where('type', $type);
        // dd($inbox->first()->client_user_id);
        // dd($symkey, $iv_data, $encrypted_data);
        $inbox->update([
            'is_acc' => true,
            'sym_key' => base64_encode($symkey),
            'iv' => base64_encode($iv_data),
        ]);

        if ($is_file == 1)
            $inbox->update([
                'encrypted_data' => $encrypted_data
            ]);
        else
            $inbox->update([
                'encrypted_data' => base64_encode($encrypted_data)
            ]);
        return;
    }
    public function encrypt_fullname($requested_id, $requesting_id)
    {
        $requesting_user = User::find($requesting_id);
        $public_key = $requesting_user->public_key;

        $requested_user = AES::where('user_id', $requested_id)->first();
        $encrypted = null;

        $HomeControllerInstance = new HomeController();
        $hasil_decrypt_fullname = $HomeControllerInstance->AESdecrypt($requested_user->fullname, $requested_user->fullname_key, $requested_user->fullname_iv, 0);

        $key_aes = openssl_random_pseudo_bytes(32);
        $iv_aes = openssl_random_pseudo_bytes(16);
        $hasil_encrypt_fullname = $HomeControllerInstance->AESencrypt($hasil_decrypt_fullname, $key_aes, $iv_aes, 0);

        $this->accRequest($requested_id, $requesting_id, 'fullname', $key_aes, $iv_aes, $hasil_encrypt_fullname, 0);
        openssl_public_encrypt(base64_encode($key_aes), $encrypted, $public_key, OPENSSL_PKCS1_PADDING);

        $subject = 'Requested Key';

        $body = base64_encode($encrypted);

        Mail::to($requesting_user->email)->send(new HelloMail($subject, $body));

        return redirect()->back();
    }

    public function encrypt_idcard($requested_id, $requesting_id)
    {
        $requesting_user = User::find($requesting_id);
        $public_key = $requesting_user->public_key;

        $requested_user = AES::where('user_id', $requested_id)->first();
        $encrypted = null;
        $id_card = $requested_user->id_card;

        $filePath = storage_path('app/public/id-card/aes/' . $id_card);
        $copyfilePath = storage_path('app/public/id-card/aes/request/request_' . $id_card);

        Storage::makeDirectory('public/id-card/aes/request');

        File::copy($filePath, $copyfilePath);

        $HomeControllerInstance = new HomeController();
        $HomeControllerInstance->AESdecrypt(storage_path('app/public/id-card/aes/request/request_' . $id_card), $requested_user->id_card_key, $requested_user->id_card_iv, 1);
        // $hasil_decrypt_id_card = 

        $key_aes = openssl_random_pseudo_bytes(32);
        $iv_aes = openssl_random_pseudo_bytes(16);
        $HomeControllerInstance->AESencrypt(storage_path('app/public/id-card/aes/request/request_' . $id_card), $key_aes, $iv_aes, 1);
        // $HomeControllerInstance->AESdecrypt(storage_path('app/public/id-card/aes/request/request_' . $id_card), base64_encode($key_aes), base64_encode($iv_aes), 1);

        $this->accRequest($requested_id, $requesting_id, 'idcard', $key_aes, $iv_aes, $id_card, 1);
        openssl_public_encrypt(base64_encode($key_aes), $encrypted, $public_key, OPENSSL_PKCS1_PADDING);

        $subject = 'Requested Key';
        $body = base64_encode($encrypted);

        Mail::to($requesting_user->email)->send(new HelloMail($subject, $body));

        return redirect()->back();
    }

    public function encrypt_document($requested_id, $requesting_id)
    {
        $requesting_user = User::find($requesting_id);
        $public_key = $requesting_user->public_key;

        $requested_user = AES::where('user_id', $requested_id)->first();
        $encrypted = null;
        $document = $requested_user->document;

        $filePath = storage_path('app/public/document/aes/' . $document);
        $copyfilePath = storage_path('app/public/document/aes/request/request_' . $document);

        Storage::makeDirectory('public/document/aes/request');

        File::copy($filePath, $copyfilePath);

        $HomeControllerInstance = new HomeController();
        $HomeControllerInstance->AESdecrypt(storage_path('app/public/document/aes/request/request_' . $document), $requested_user->document_key, $requested_user->document_iv, 1);

        $key_aes = openssl_random_pseudo_bytes(32);
        $iv_aes = openssl_random_pseudo_bytes(16);
        $HomeControllerInstance->AESencrypt(storage_path('app/public/document/aes/request/request_' . $document), $key_aes, $iv_aes, 1);
        // $HomeControllerInstance->AESdecrypt(storage_path('app/public/document/aes/request/request_' . $document), base64_encode($key_aes), base64_encode($iv_aes), 1);

        $this->accRequest($requested_id, $requesting_id, 'document', $key_aes, $iv_aes, $document, 1);
        openssl_public_encrypt(base64_encode($key_aes), $encrypted, $public_key, OPENSSL_PKCS1_PADDING);

        $subject = 'Requested Key';

        $body = base64_encode($encrypted);

        Mail::to($requesting_user->email)->send(new HelloMail($subject, $body));

        return redirect()->back();
    }

    public function encrypt_video($requested_id, $requesting_id)
    {
        $requesting_user = User::find($requesting_id);
        $public_key = $requesting_user->public_key;

        $requested_user = AES::where('user_id', $requested_id)->first();
        $encrypted = null;
        $video = $requested_user->video;

        $filePath = storage_path('app/public/video/aes/' . $video);
        $copyfilePath = storage_path('app/public/video/aes/request/request_' . $video);

        Storage::makeDirectory('public/video/aes/request');

        File::copy($filePath, $copyfilePath);

        $HomeControllerInstance = new HomeController();
        $HomeControllerInstance->AESdecrypt(storage_path('app/public/video/aes/request/request_' . $video), $requested_user->video_key, $requested_user->video_iv, 1);

        $key_aes = openssl_random_pseudo_bytes(32);
        $iv_aes = openssl_random_pseudo_bytes(16);
        $HomeControllerInstance->AESencrypt(storage_path('app/public/video/aes/request/request_' . $video), $key_aes, $iv_aes, 1);
        // $HomeControllerInstance->AESdecrypt(storage_path('app/public/video/aes/request/request_' . $video), base64_encode($key_aes), base64_encode($iv_aes), 1);

        $this->accRequest($requested_id, $requesting_id, 'video', $key_aes, $iv_aes, $video, 1);
        openssl_public_encrypt(base64_encode($key_aes), $encrypted, $public_key, OPENSSL_PKCS1_PADDING);

        $subject = 'Requested Key';

        $body = base64_encode($encrypted);

        Mail::to($requesting_user->email)->send(new HelloMail($subject, $body));

        return redirect()->back();
    }
}
