@extends('master')
@include('navbar')
@section('content')
<div class="container">
    <h1 class="text-center display-5 fw-bold" style="margin-top: 100px">Hi {{ Auth::user()->username }}, this is your profile!</h1>
    <?php
        use Illuminate\Support\Facades\Crypt;
        if (!function_exists('Desdecrypt')) {
            function Desdecrypt($data, $key, $iv, $is_file) {
                if ($is_file == 1) $ciphertext = file_get_contents($data);
                else $ciphertext = $data;
    
                $ciphertext = hex2bin($ciphertext);
                $iv = hex2bin($iv);
                $key = hex2bin($key);
    
                $plaintext = openssl_decrypt($ciphertext, 'des-ede-cfb', $key, 0, $iv);
    
                return $plaintext;
            }
        }
        if (!function_exists('Rc4decrypt')) {
            function Rc4decrypt($data, $key, $is_file) {
                if ($is_file == 1) $ciphertext = file_get_contents($data);
                else $ciphertext = $data;

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

                return $plaintext;
            }
        }
        if (!function_exists('AESdecrypt')) {
            function AESdecrypt($data, $is_file) {
                if ($is_file == 1) $ciphertext = file_get_contents($data);
                else $ciphertext = $data;
    
                $plaintext = Crypt::decryptString($ciphertext);
                
                return $plaintext;
            }
        }
    ?>
    
    <h3>AES</h3>
    @foreach ($aess as $aes)
        <?php
            echo "Full Name: " . AESdecrypt($aes->fullname, 0) . "\n";
            // echo "Id Card: " . AESdecrypt(storage_path('app/public/id-card/aes/' . $aes->id_card), 1) . "\n";
        ?>
    @endforeach

    <h3>Rc4</h3>
    @foreach ($rc4s as $rc4)
        <?php
            echo "Full Name: " . Rc4decrypt($rc4->fullname, $rc4->key, 0) . "\n";
        ?>
    @endforeach

    <h3>Des</h3>
    @foreach ($des as $des)
        <?php
            echo "Full Name: " . Desdecrypt($des->fullname, $des->key, $des->iv, 0) . "\n";
            // echo "Id Card: " . Desdecrypt(storage_path('app/public/id-card/des/' . $item->id_card), 1, $item) . "\n";
        ?>
    @endforeach
</div>
@endsection
