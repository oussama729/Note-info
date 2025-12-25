<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoteInformation extends Model
{
    protected $table = 'note_information';

    protected $primaryKey = 'reference';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'reference',
        'date_note',
        'objet_note',
    ];

    public function piecesJointes()
{
   return $this->hasMany(
        PieceJointe::class,
        'reference_note_informatique',
        'reference'
    );
}
}
