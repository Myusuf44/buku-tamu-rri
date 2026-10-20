<?php

namespace App\Mail;

use App\Models\Visitor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VisitorStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $visit; 
    public $viewName;
    public $subjectText;

    public function __construct(Visitor $visitor, string $statusType)
    {
        $this->visit = $visitor;

        // Menyesuaikan dengan file Blade email yang sudah Anda miliki di resources/views/emails/
        switch ($statusType) {
            case 'approved':
                $this->viewName = 'emails.visitor_approved';
                $this->subjectText = 'Konfirmasi Persetujuan Kunjungan Resmi - RRI Bukittinggi';
                break;
            case 'completed':
                $this->viewName = 'emails.visitor_completed';
                $this->subjectText = 'Terima Kasih Kunjungan Anda - RRI Bukittinggi';
                break;
            case 'canceled':
                $this->viewName = 'emails.visitor_cancelled';
                $this->subjectText = 'Pemberitahuan Pembatalan Kunjungan - RRI Bukittinggi';
                break;
            default:
                $this->viewName = 'emails.visitor_registered';
                $this->subjectText = 'Registrasi Kunjungan Diproses - RRI Bukittinggi';
                break;
        }
    }

    public function build()
    {
        return $this->subject($this->subjectText)
                    ->view($this->viewName);
    }
}