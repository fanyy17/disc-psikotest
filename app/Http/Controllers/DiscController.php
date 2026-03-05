<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiscTest;
use App\Models\DiscAnswer;
use App\Mail\DiscResultMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class DiscController extends Controller
{
    public function form()
    {
        return view('DISC.form');
    }

    public function startTest(Request $request)
    {
        $request->validate([
            'email'            => 'required|email',
            'nama'             => 'required',
            'pendidikan'       => 'required',
            'pendidikanTerakhir' => 'required',
            'gender'           => 'required',
        ]);

        session([
            'disc_user' => $request->only([
                'email',
                'nama',
                'pendidikan',
                'organisasi',
                'posisi',
                'pendidikanTerakhir',
                'gender'
            ])
        ]);

        return redirect()->route('disc.test');
    }

    public function test()
    {
        $user = session('disc_user');

        if (!$user) {
            return redirect()->route('disc.form');
        }

        $questions = [
            [ 'number'=>1, 'options'=>[
                ['text'=>'Mengelola waktu dengan efisien','score'=>'C'],
                ['text'=>'Sering terburu-buru','score'=>'D'],
                ['text'=>'Mementingkan masalah sosial','score'=>'I'],
                ['text'=>'Suka menyelesaikan masalah yang sudah dimulai','score'=>'S'],
            ]],
            [ 'number'=>2, 'options'=>[
                ['text'=>'Penyemangat / Pendukung yang baik','score'=>'I'],
                ['text'=>'Pendengar yang baik','score'=>'S'],
                ['text'=>'Penganalisa yang baik','score'=>'C'],
                ['text'=>'Pandai membagi tugas','score'=>'D'],
            ]],
            [ 'number'=>3, 'options'=>[
                ['text'=>'Non konfrontasi / mengalah','score'=>'*'],
                ['text'=>'Penuh detail','score'=>'C'],
                ['text'=>'Berubah pada menit terakhir','score'=>'I'],
                ['text'=>'Mendesak / memaksa','score'=>'D'],
            ]],
            [ 'number'=>4, 'options'=>[
                ['text'=>'Ramah, mudah berteman','score'=>'S'],
                ['text'=>'Unik, bosan dengan rutinitas','score'=>'*'],
                ['text'=>'Aktif membuat perubahan','score'=>'D'],
                ['text'=>'Ingin segala sesuatu akurat dan pasti','score'=>'C'],
            ]],
            [ 'number'=>5, 'options'=>[
                ['text'=>'Menolak perubahan mendesak','score'=>'S'],
                ['text'=>'Cenderung terlalu banyak berjanji','score'=>'I'],
                ['text'=>'Mundur saat dibawah tekanan','score'=>'*'],
                ['text'=>'Tidak takut berdebat','score'=>'*'],
            ]],
            [ 'number'=>6, 'options'=>[
                ['text'=>'Menahan diri, bisa hidup sederhana','score'=>'*'],
                ['text'=>'Membeli karena dorongan hasrat','score'=>'D'],
                ['text'=>'Menunggu dan tidak tertekan','score'=>'S'],
                ['text'=>'Membeli apa yang diinginkan','score'=>'I'],
            ]],
            [ 'number'=>7, 'options'=>[
                ['text'=>'Menjadi frustasi','score'=>'C'],
                ['text'=>'Memendam perasaan','score'=>'S'],
                ['text'=>'Menyampaikan sudut pandang pribadi','score'=>'*'],
                ['text'=>'Berani menghadapi oposisi','score'=>'D'],
            ]],
            [ 'number'=>8, 'options'=>[
                ['text'=>'Menyemangati orang lain','score'=>'I'],
                ['text'=>'Berusaha mencapai kesempurnaan','score'=>'*'],
                ['text'=>'Menjadi bagian dari tim','score'=>'*'],
                ['text'=>'Ingin menetapkan tujuan','score'=>'D'],
            ]],
            [ 'number'=>9, 'options'=>[
                ['text'=>'Mudah bergaul dan setuju','score'=>'S'],
                ['text'=>'Mempercayai orang lain','score'=>'I'],
                ['text'=>'Petualang, suka risiko','score'=>'*'],
                ['text'=>'Penuh toleransi','score'=>'C'],
            ]],
            [ 'number'=>10, 'options'=>[
                ['text'=>'Peraturan perlu diuji','score'=>'*'],
                ['text'=>'Peraturan membuat adil','score'=>'C'],
                ['text'=>'Peraturan membosankan','score'=>'I'],
                ['text'=>'Peraturan membuat aman','score'=>'S'],
            ]],
            [ 'number'=>11, 'options'=>[
                ['text'=>'Lincah, banyak bicara','score'=>'I'],
                ['text'=>'Cepat dan penuh keyakinan','score'=>'D'],
                ['text'=>'Menjaga keseimbangan','score'=>'S'],
                ['text'=>'Patuh pada peraturan','score'=>'*'],
            ]],
            [ 'number'=>12, 'options'=>[
                ['text'=>'Mementingkan hasil','score'=>'D'],
                ['text'=>'Mengerjakan dengan akurat','score'=>'C'],
                ['text'=>'Membuat pekerjaan menyenangkan','score'=>'*'],
                ['text'=>'Mari kerjakan bersama','score'=>'*'],
            ]],
            [ 'number'=>13, 'options'=>[
                ['text'=>'Pendidikan dan kebudayaan','score'=>'*'],
                ['text'=>'Prestasi dan kebudayaan','score'=>'D'],
                ['text'=>'Keselamatan dan keamanan','score'=>'S'],
                ['text'=>'Sosial dan pertemuan kelompok','score'=>'I'],
            ]],
            [ 'number'=>14, 'options'=>[
                ['text'=>'Menginginkan kekuasaan lebih','score'=>'*'],
                ['text'=>'Menginginkan kesempatan baru','score'=>'I'],
                ['text'=>'Menghindari konflik','score'=>'S'],
                ['text'=>'Menginginkan arahan jelas','score'=>'*'],
            ]],
            [ 'number'=>15, 'options'=>[
                ['text'=>'Tenang, pendiam','score'=>'C'],
                ['text'=>'Gembira dan riang','score'=>'I'],
                ['text'=>'Menyenangkan dan baik','score'=>'S'],
                ['text'=>'Tegas dan berani','score'=>'D'],
            ]],
            [ 'number'=>16, 'options'=>[
                ['text'=>'Menyenangkan orang lain','score'=>'S'],
                ['text'=>'Tertawa lepas','score'=>'*'],
                ['text'=>'Pemberani dan tegas','score'=>'D'],
                ['text'=>'Pendiam dan tenang','score'=>'C'],
            ]],
            [ 'number'=>17, 'options'=>[
                ['text'=>'Mengutamakan kemajuan','score'=>'D'],
                ['text'=>'Mudah puas','score'=>'S'],
                ['text'=>'Menunjukkan perasaan terbuka','score'=>'I'],
                ['text'=>'Rendah hati','score'=>'*'],
            ]],
            [ 'number'=>18, 'options'=>[
                ['text'=>'Memikirkan orang lain dahulu','score'=>'S'],
                ['text'=>'Menyukai tantangan','score'=>'D'],
                ['text'=>'Optimis dan positif','score'=>'I'],
                ['text'=>'Berpikir logis dan sistematis','score'=>'*'],
            ]],
            [ 'number'=>19, 'options'=>[
                ['text'=>'Lembut dan pendiam','score'=>'C'],
                ['text'=>'Optimis dan visioner','score'=>'D'],
                ['text'=>'Pusat perhatian','score'=>'*'],
                ['text'=>'Pendamai','score'=>'S'],
            ]],
            [ 'number'=>20, 'options'=>[
                ['text'=>'Menyediakan waktu untuk orang lain','score'=>'S'],
                ['text'=>'Perencanaan dan persiapan','score'=>'C'],
                ['text'=>'Petualangan','score'=>'I'],
                ['text'=>'Menerima penghargaan target','score'=>'D'],
            ]],
            [ 'number'=>21, 'options'=>[
                ['text'=>'Saya akan pimpin mereka','score'=>'D'],
                ['text'=>'Saya mengikuti','score'=>'S'],
                ['text'=>'Saya akan bujuk mereka','score'=>'I'],
                ['text'=>'Saya akan dapatkan faktanya','score'=>'C'],
            ]],
            [ 'number'=>22, 'options'=>[
                ['text'=>'Tidak mudah menyerah','score'=>'D'],
                ['text'=>'Melakukan sesuai perintah','score'=>'S'],
                ['text'=>'Bersemangat dan ceria','score'=>'I'],
                ['text'=>'Ingin keteraturan','score'=>'*'],
            ]],
            [ 'number'=>23, 'options'=>[
                ['text'=>'Dapat dipercaya','score'=>'*'],
                ['text'=>'Kreatif dan unik','score'=>'I'],
                ['text'=>'Berorientasi hasil','score'=>'D'],
                ['text'=>'Standar tinggi','score'=>'C'],
            ]],
            [ 'number'=>24, 'options'=>[
                ['text'=>'Pendekatan langsung dan tegas','score'=>'D'],
                ['text'=>'Suka bergaul dan antusias','score'=>'*'],
                ['text'=>'Konsisten dan mudah ditebak','score'=>'*'],
                ['text'=>'Waspada dan berhati-hati','score'=>'C'],
            ]],
        ];

        return view('DISC.test', compact('questions', 'user'));
    }

    public function store(Request $request)
    {
        if (!$request->has('most') || !$request->has('least')) {
            return redirect()->route('disc.test');
        }

        // ── Hitung skor ──────────────────────────────────────────
        $most  = ['D'=>0,'I'=>0,'S'=>0,'C'=>0,'*'=>0];
        $least = ['D'=>0,'I'=>0,'S'=>0,'C'=>0,'*'=>0];

        foreach ($request->most as $value) {
            if (isset($most[$value])) $most[$value]++;
        }
        foreach ($request->least as $value) {
            if (isset($least[$value])) $least[$value]++;
        }

        $self = [
            'D' => $most['D'] - $least['D'],
            'I' => $most['I'] - $least['I'],
            'S' => $most['S'] - $least['S'],
            'C' => $most['C'] - $least['C'],
            '*' => $most['*'] + $least['*'],
        ];

        // ── Ambil data user dari session ──────────────────────────
        $user = session('disc_user');

        // ── Simpan ke database ────────────────────────────────────
        DiscTest::create([
            'name'       => $user['nama']  ?? '-',
            'email'      => $user['email'] ?? null,
            'most_d'     => $most['D'],
            'most_i'     => $most['I'],
            'most_s'     => $most['S'],
            'most_c'     => $most['C'],
            'most_star'  => $most['*'],
            'least_d'    => $least['D'],
            'least_i'    => $least['I'],
            'least_s'    => $least['S'],
            'least_c'    => $least['C'],
            'least_star' => $least['*'],
            'self_d'     => $self['D'],
            'self_i'     => $self['I'],
            'self_s'     => $self['S'],
            'self_c'     => $self['C'],
            'self_star'  => $self['*'],
        ]);

        // ── Kirim email ke HRD otomatis ───────────────────────────
        try {
            Mail::to(config('disc.hrd_email'))
                ->send(new DiscResultMail($user, $most, $least, $self));
        } catch (\Exception $e) {
            Log::error('DISC Mail gagal terkirim: ' . $e->getMessage());
        }

        return view('DISC.result', compact('most', 'least', 'self', 'user'));
    }
}