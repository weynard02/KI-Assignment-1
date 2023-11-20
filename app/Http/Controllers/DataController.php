<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aes;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use phpseclib3\Crypt\RSA;

class DataController extends Controller
{
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

    public function id_card()
    {
        return view('data.id_card');
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
