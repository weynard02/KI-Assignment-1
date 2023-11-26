<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aes;
use App\Models\User;
use App\Models\UserInbox;
use Illuminate\Support\Facades\Auth;
use phpseclib3\Crypt\RSA;

class DataController extends Controller
{
    // public function encrypt_asym($symkey)
    // {
    //     $publickey = Auth::user()->public_key;
    //     $encrypted = null;
    //     $success = openssl_private_encrypt(base64_decode($symkey), $encrypted, $publickey, OPENSSL_PKCS1_PADDING);

    //     if ($success) {
    //         $response = ['status' => 'success', 'encrypted' => $encrypted];
    //     } else {
    //         $response = ['status' => 'error', 'encrypted' => 'Failed to encrypt the symmetric key'];
    //     }

    //     return $encrypted;
    // }

    public function decrypt_asym(Request $request)
    {
        $private = Auth::user()->private_key;
        $decrypted = null;
        $success = openssl_private_decrypt(base64_decode($request->encsymkey), $decrypted, $private, OPENSSL_PKCS1_PADDING);

        if ($success) {
            $response = ['status' => 'success', 'decrypted' => $decrypted];
        } else {
            $response = ['status' => 'error', 'decrypted' => 'Failed to decrypt the symmetric key'];
        }

        return response()->json($response);
    }

    public function fullname($id)
    {
        $aess = Aes::where('user_id', Auth::user()->id)->get();
        $user = User::where('id', $id)->first();
        $aesuser = Aes::where('user_id', $id)->first();
        $inbox = UserInbox::where('main_user_id', $id)
            ->where('client_user_id', Auth::user()->id)->where('is_acc', 1)->where('type', 'fullname')->first();
        return view('data.fullname', compact('aess', 'user', 'aesuser', 'inbox'));
    }

    public function idcard($id)
    {
        $aess = Aes::where('user_id', Auth::user()->id)->get();
        $user = User::where('id', $id)->first();
        $aesuser = Aes::where('user_id', $id)->first();
        $inbox = UserInbox::where('main_user_id', $id)
            ->where('client_user_id', Auth::user()->id)->where('is_acc', 1)->where('type', 'idcard')->first();
        return view('data.idcard', compact('aess', 'user', 'aesuser', 'inbox'));
    }

    public function document($id)
    {
        $aess = Aes::where('user_id', Auth::user()->id)->get();
        $user = User::where('id', $id)->first();
        $aesuser = Aes::where('user_id', $id)->first();
        $inbox = UserInbox::where('main_user_id', $id)
            ->where('client_user_id', Auth::user()->id)->where('is_acc', 1)->where('type', 'document')->first();
        return view('data.document', compact('aess', 'user', 'aesuser', 'inbox'));
    }

    public function video($id)
    {
        $aess = Aes::where('user_id', Auth::user()->id)->get();
        $user = User::where('id', $id)->first();
        $aesuser = Aes::where('user_id', $id)->first();
        $inbox = UserInbox::where('main_user_id', $id)
            ->where('client_user_id', Auth::user()->id)->where('is_acc', 1)->where('type', 'video')->first();
        return view('data.video', compact('aess', 'user', 'aesuser', 'inbox'));
    }
}