<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Document extends Model
{
    use LogsActivity;

    protected static $logName = 'document';
    protected static $logFillable = true; // ou liste des champs

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('document')
            ->logOnly(['title', 'type','folder_id', 'file_path'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $guarded = [];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class);
    }

    public function sharedWith()
    {
        return $this->belongsToMany(User::class, 'document_user')->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


}
