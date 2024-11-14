<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Interfaces\PartyRepositoryInterface;
use App\Classes\ApiResponseClass;
use App\Http\Resources\PartyResource;

class PartyController
{
    private PartyRepositoryInterface $partyRepositoryInterface;

    public function __construct(PartyRepositoryInterface $partyRepositoryInterface){
        $this->partyRepositoryInterface = $partyRepositoryInterface;
    }

    public function show($id) {
        $party = $this->partyRepositoryInterface->getById($id);
        return view('party.party', compact('party'));
    }
}
