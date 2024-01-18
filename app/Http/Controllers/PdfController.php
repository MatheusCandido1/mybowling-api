<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function generateGame(Game $game) {

        $game->load('frames', 'location', 'ball', 'user', 'group');



        $pdf = PDF::loadView('pdfs.games', compact('game'));
        return $pdf->stream();
    }

}
