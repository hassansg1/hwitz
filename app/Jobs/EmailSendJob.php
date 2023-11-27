<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EmailSendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $emailData;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($emailData)
    {
        $this->emailData = $emailData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $emailData = $this->emailData;
        Mail::send('receiptmailscreen', ['body' => $emailData['body']], function ($m) use ($emailData) {
            $m->from($emailData['from'], '');
            $m->to($emailData['to'], '');
            $m->subject($emailData['subject']);
            if ($emailData['attachment']) {
                $m->attach($emailData['attachment']);
            }
        });
    }
}
