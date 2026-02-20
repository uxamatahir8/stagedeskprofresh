<?php

namespace App\Services;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class MailDispatchService
{
    public function send(string $to, Mailable $mailable, array $context = []): bool
    {
        try {
            Mail::to($to)->send($mailable);

            ActivityLogger::success(
                'email.dispatch.service_success',
                'Email dispatched through MailDispatchService',
                [
                    'category' => 'email',
                    'action' => 'send',
                    'metadata' => array_merge($context, [
                        'to' => $to,
                        'mailable' => get_class($mailable),
                    ]),
                ]
            );

            return true;
        } catch (\Throwable $e) {
            ActivityLogger::error(
                'email.dispatch.service_failed',
                'Email dispatch failed through MailDispatchService',
                [
                    'category' => 'email',
                    'action' => 'send',
                    'metadata' => array_merge($context, [
                        'to' => $to,
                        'mailable' => get_class($mailable),
                    ]),
                    'exception' => ['message' => $e->getMessage()],
                ]
            );

            return false;
        }
    }
}
