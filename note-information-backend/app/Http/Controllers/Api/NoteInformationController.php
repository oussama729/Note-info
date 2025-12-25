<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\NoteInformation;

class NoteInformationController extends Controller
{

   public function index()
{
    $notes = DB::table('note_information')
        ->leftJoin(
            'pieces_jointes',
            'note_information.reference',
            '=',
            'pieces_jointes.reference_note_informatique'
        )
        ->select(
            'note_information.reference',
            'note_information.objet_note',
            'note_information.date_note',
            'pieces_jointes.path'
        )
         ->orderBy('note_information.date_note', 'desc') // ✅ ICI
        ->get()
        ->groupBy('reference')
        ->map(function ($items) {

            $note = $items->first();

            return [
                'reference' => $note->reference,
                'objet_note' => $note->objet_note,
                'date_note' => $note->date_note,
                'pieces_jointes' => $items
                    ->whereNotNull('path')
                    ->map(function ($i) {

                        // 🧹 Sécurité : on enlève "storage/" s’il existe déjà
                        $cleanPath = str_replace('storage/', '', $i->path);

                        return [
                            'path' => url('storage/' . $cleanPath)
                        ];
                    })
                    ->values()
            ];
        })
        ->values();

    return response()->json($notes);
}

  public function store(Request $request)
    {
        $request->validate([
        'reference' => 'required',
        'objet_note' => 'required',
        'date_note' => 'required|date',
        'pdf' => 'required|mimes:pdf|max:10240',
    ]);
             $path = $request->file('pdf')->store('pdfs', 'public');


    $note = NoteInformation::create([
        'reference' => $request->reference,
        'objet_note' => $request->objet_note,
        'date_note' => $request->date_note,
    ]);

    $note->piecesJointes()->create([
        'reference_note_informatique' => $note->reference,
        'name' => basename($path),
        'extension' => 'pdf',
        'mime_type' => 'application/pdf',
        'size' => $request->file('pdf')->getSize(),
        'path' => $path,
    ]);

    return response()->json($note, 201);
    }


}
