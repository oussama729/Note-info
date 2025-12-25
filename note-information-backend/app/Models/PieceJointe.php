<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PieceJointe extends Model
{
    protected $table = 'pieces_jointes';

    protected $fillable = [
        'reference_note_informatique',
        'name',
        'extension',
        'mime_type',
        'size',
        'md5',
        'path',
    ];

    public function note()
    {
        return $this->belongsTo(
            NoteInformation::class,
            'reference_note_informatique',
            'reference'
        );
    }
}
