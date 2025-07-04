<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Document;

class NouveauDocumentAjoute extends Notification
{
    use Queueable;

    public $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function via($notifiable)
    {
        return ['database']; // On enregistre uniquement en base
    }

    public function toDatabase($notifiable)
    {
        return new DatabaseMessage([
            'title' => 'Nouveau document ajouté',
            'message' => "Un nouveau document a été ajouté : {$this->document->title}",
            'document_id' => $this->document->id,
            'user_id' => $this->document->user_id,
        ]);
    }
}
