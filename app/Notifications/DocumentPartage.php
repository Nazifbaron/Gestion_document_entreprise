<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DocumentPartage extends Notification
{
    use Queueable;

    public $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function via($notifiable)
    {
        return ['database']; // Stockée dans la base de données
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Document partagé avec vous',
            'message' => 'Le document "' . $this->document->title . '" vous a été partagé.',
            'document_id' => $this->document->id,
        ];
    }
}
