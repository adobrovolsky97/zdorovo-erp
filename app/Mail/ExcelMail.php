<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ExcelMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    private string $filePath;

    /**
     * Create a new message instance.
     *
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this
            ->view('mail.excel')
            ->subject('Фасовка за '.Carbon::today()->format('d.m.Y'))
            ->attach(Storage::path($this->filePath));
    }
}
