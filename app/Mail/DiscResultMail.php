<?php
// Lokasi: app/Mail/DiscResultMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use App\Services\DiscChartGenerator;

class DiscResultMail extends Mailable
{
    use Queueable, SerializesModels;

    private array $chartPaths = [];

    public function __construct(
        public array $user,
        public array $most,
        public array $least,
        public array $self,
    ) {}

    public function envelope(): Envelope
    {
        $nama = $this->user['nama'] ?? 'Peserta';
        return new Envelope(
            subject: "[DISC] Hasil Tes — {$nama} — " . now()->format('d M Y H:i'),
        );
    }

    public function content(): Content
    {
        $generator        = new DiscChartGenerator();
        $this->chartPaths = $generator->generate($this->most, $this->least, $this->self);

        return new Content(
            view: 'emails.disc-result',
            with: [
                'chartMost'  => $this->chartPaths['most'],
                'chartLeast' => $this->chartPaths['least'],
                'chartSelf'  => $this->chartPaths['self'],
            ],
        );
    }

    public function attachments(): array
    {
        $labels = [
            'most'  => 'Grafik_Mask_MOST.png',
            'least' => 'Grafik_Pressure_LEAST.png',
            'self'  => 'Grafik_Self_Change.png',
        ];

        $attachments = [];
        foreach ($this->chartPaths as $dim => $path) {
            if (file_exists($path)) {
                $attachments[] = Attachment::fromPath($path)
                    ->as($labels[$dim])
                    ->withMime('image/png');
            }
        }
        return $attachments;
    }

    public function __destruct()
    {
        if (!empty($this->chartPaths)) {
            (new DiscChartGenerator())->cleanup($this->chartPaths);
        }
    }
}