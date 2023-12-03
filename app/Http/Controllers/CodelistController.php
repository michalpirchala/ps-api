<?php

namespace App\Http\Controllers;

use App\Http\Resources\CodelistCollection;
use App\Http\Resources\CodelistResource;
use App\Http\Resources\GenderCollection;
use App\Http\Resources\MaritalStatusCollection;
use App\Http\Resources\TitleCollection;
use App\Models\Gender;
use App\Models\MaritalStatus;
use App\Models\Title;
use Illuminate\Http\Request;

class CodelistController extends Controller
{
    public function show()
    {
        return new CodelistResource([
            'marital_statuses' => new MaritalStatusCollection(MaritalStatus::all()),
            'genders' => new GenderCollection(Gender::all()),
            'titles_before' => New TitleCollection(Title::where('type', Title::TYPE_BEFORE)->orderBy('id')->get()),
            'titles_after' => New TitleCollection(Title::where('type', Title::TYPE_AFTER)->orderBy('id')->get()),
        ]);
    }
}
