<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;

class CommunityMemberController extends Controller
{
    public function index()
    {
        $members = User::where('role', 'member')->get(); 
        return view('admin.community-members.index', compact('members'));
    }



    public function show($id)
    {
        $member = User::with([
            'travelRequests' => fn($q) => $q->latest(),
            'localForms.request',
            'OverseasForms.request',
        ])->findOrFail($id);
    
        $now = Carbon::now();
        $isInTravel = false;
    
        foreach ($member->localForms as $form) {
            if (
                $form->status === 'approved' &&
                $form->request &&
                $form->request->intended_departure_date &&
                $form->request->intended_return_date &&
                $now->between($form->request->intended_departure_date, $form->request->intended_return_date)
            ) {
                $isInTravel = true;
                break;
            }
        }
    
        if (!$isInTravel) {
            foreach ($member->OverseasForms as $form) {
                if (
                    $form->status === 'approved' &&
                    $form->request &&
                    $form->request->intended_departure_date &&
                    $form->request->intended_return_date &&
                    $now->between($form->request->intended_departure_date, $form->request->intended_return_date)
                ) {
                    $isInTravel = true;
                    break;
                }
            }
        }
    
        return view('admin.community-members.show', compact('member', 'isInTravel'));
    }
    

    public function history($id)
    {
        $member = \App\Models\User::findOrFail($id);
    
        $travelRequests = $member->travelRequests()->latest()->get();
        $localForms = $member->localForms()->latest()->get();
        $OverseasForms = $member->OverseasForms()->latest()->get();
    
        return view('admin.community-members.history', compact('member', 'travelRequests', 'localForms', 'OverseasForms'));
    }
    
}

