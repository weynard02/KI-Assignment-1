<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aes;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use phpseclib3\Crypt\RSA;

class DataController extends Controller
{
    public function encrypt_asym($symkey) {
        $publickey = Auth::user()->public_key;
        $encrypted = null;
        $success = openssl_private_encrypt(base64_decode($symkey), $encrypted, $publickey, OPENSSL_PKCS1_PADDING);
        
        if ($success) {
            $response = ['status' => 'success', 'encrypted' => $encrypted];
        } else {
            $response = ['status' => 'error', 'encrypted' => 'Failed to encrypt the symmetric key'];
        }

        return $encrypted;
    }
    public function fullname($id)
    {
        $aess = Aes::where('user_id', Auth::user()->id)->get();
        $user = User::where('id', $id)->first();
        $aesuser = Aes::where('id', $id)->first();
        return view('data.fullname', compact('aess', 'user', 'aesuser'));
    }

    public function fullname_asym(Request $request)
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

    public function idcardname($id)
    {
        $aess = Aes::where('user_id', Auth::user()->id)->get();
        $user = User::where('id', $id)->first();
        $aesuser = Aes::where('id', $id)->first();
        return view('data.idcard', compact('aess', 'user', 'aesuser'));
    }

    public function id_card_asym(Request $request)
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

    public function documentname($id)
    {
        $aess = Aes::where('user_id', Auth::user()->id)->get();
        $user = User::where('id', $id)->first();
        $aesuser = Aes::where('id', $id)->first();
        return view('data.document', compact('aess', 'user', 'aesuser'));
    }

    public function document_asym(Request $request)
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

    public function videoname($id)
    {
        $aess = Aes::where('user_id', Auth::user()->id)->get();
        $user = User::where('id', $id)->first();
        $aesuser = Aes::where('id', $id)->first();
        return view('data.video', compact('aess', 'user', 'aesuser'));
    }

    public function video_asym(Request $request)
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

    public function id_card()
    {
        return view('data.idcard');
    }
    public function document()
    {
        return view('data.document');
    }
    public function video()
    {
        return view('data.video');
    }
}
