<?php

namespace App\Http\Controllers;
use Mail;
use Auth;
use App\Mail\HelloMail;
use App\Models\User;
use App\Models\AES;
use Illuminate\Http\Request;
use Http\Controllers\DataController;

class MailController extends Controller
{
    public function encrypt_fullname($requested_id) {
        $requesting_user = Auth::user();
        $public_key = $requesting_user->public_key;
        
        $requested_user = AES::find($requested_id);
        $fullname_id = $requested_user->fullname_key;
        $encrypted = null;

        $success = openssl_public_encrypt($fullname_id, $encrypted, $public_key, OPENSSL_PKCS1_PADDING);

        $subject = 'Requested Key';

        $body = base64_encode($encrypted);

        Mail::to(Auth::user()->email)->send(new HelloMail($subject, $body));
        
        return redirect()->back();
    }

    public function encrypt_idcard($requested_id) {
        $requesting_user = Auth::user();
        $public_key = $requesting_user->public_key;
        
        $requested_user = AES::find($requested_id);
        $id_card_id = $requested_user->id_card_key;
        $encrypted = null;

        $success = openssl_public_encrypt($id_card_id, $encrypted, $public_key, OPENSSL_PKCS1_PADDING);

        $subject = 'Requested Key';

        $body = base64_encode($encrypted);

        Mail::to(Auth::user()->email)->send(new HelloMail($subject, $body));
        
        return redirect()->back();
    }

    public function encrypt_document($requested_id) {
        $requesting_user = Auth::user();
        $public_key = $requesting_user->public_key;
        
        $requested_user = AES::find($requested_id);
        $document_id = $requested_user->document_key;
        $encrypted = null;

        $success = openssl_public_encrypt($document_id, $encrypted, $public_key, OPENSSL_PKCS1_PADDING);

        $subject = 'Requested Key';

        $body = base64_encode($encrypted);

        Mail::to(Auth::user()->email)->send(new HelloMail($subject, $body));
        
        return redirect()->back();
    }

    public function encrypt_video($requested_id) {
        $requesting_user = Auth::user();
        $public_key = $requesting_user->public_key;
        
        $requested_user = AES::find($requested_id);
        $video_id = $requested_user->video_key;
        $encrypted = null;

        $success = openssl_public_encrypt($video_id, $encrypted, $public_key, OPENSSL_PKCS1_PADDING);

        $subject = 'Requested Key';

        $body = base64_encode($encrypted);

        Mail::to(Auth::user()->email)->send(new HelloMail($subject, $body));
        
        return redirect()->back();
    }
}
