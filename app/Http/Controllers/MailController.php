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

class MailController extends Controller
{
    public function accRequest($main_id, $client_id, $type) {
        $inbox = UserInbox::where('main_user_id', $main_id)
        ->where('client_user_id', $client_id)->where('type', $type);
        $inbox->update([
            'is_acc' => true
        ]);
        return;
    }
    public function encrypt_fullname($requested_id, $requesting_id) {
        $requesting_user = User::find($requesting_id);
        $public_key = $requesting_user->public_key;
        
        $requested_user = AES::find($requested_id);
        $fullname_id = $requested_user->fullname_key;
        $encrypted = null;
        $this->accRequest($requested_id, $requesting_id, 'fullname');
        $success = openssl_public_encrypt($fullname_id, $encrypted, $public_key, OPENSSL_PKCS1_PADDING);

        $subject = 'Requested Key';

        $body = base64_encode($encrypted);

        Mail::to($requesting_user->email)->send(new HelloMail($subject, $body));
        
        return redirect()->back();
    }

    public function encrypt_idcard($requested_id,  $requesting_id) {
        $requesting_user = User::find($requesting_id);
        $public_key = $requesting_user->public_key;
        
        $requested_user = AES::find($requested_id);
        $id_card_id = $requested_user->id_card_key;
        $encrypted = null;

        $this->accRequest($requested_id, $requesting_id, 'idcard');
        $success = openssl_public_encrypt($id_card_id, $encrypted, $public_key, OPENSSL_PKCS1_PADDING);

        $subject = 'Requested Key';

        $body = base64_encode($encrypted);

        Mail::to($requesting_user->email)->send(new HelloMail($subject, $body));
        
        return redirect()->back();
    }

    public function encrypt_document($requested_id,  $requesting_id) {
        $requesting_user = User::find($requesting_id);
        $public_key = $requesting_user->public_key;
        
        $requested_user = AES::find($requested_id);
        $document_id = $requested_user->document_key;
        $encrypted = null;

        $this->accRequest($requested_id, $requesting_id, 'document');
        $success = openssl_public_encrypt($document_id, $encrypted, $public_key, OPENSSL_PKCS1_PADDING);

        $subject = 'Requested Key';

        $body = base64_encode($encrypted);

        Mail::to($requesting_user->email)->send(new HelloMail($subject, $body));
        
        return redirect()->back();
    }

    public function encrypt_video($requested_id,  $requesting_id) {
        $requesting_user = User::find($requesting_id);
        $public_key = $requesting_user->public_key;
        
        $requested_user = AES::find($requested_id);
        $video_id = $requested_user->video_key;
        $encrypted = null;

        $this->accRequest($requested_id, $requesting_id, 'video');
        $success = openssl_public_encrypt($video_id, $encrypted, $public_key, OPENSSL_PKCS1_PADDING);

        $subject = 'Requested Key';

        $body = base64_encode($encrypted);

        Mail::to($requesting_user->email)->send(new HelloMail($subject, $body));
        
        return redirect()->back();
    }
}
