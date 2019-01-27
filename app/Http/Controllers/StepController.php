<?php

namespace App\Http\Controllers;

use App\Models\Step;
use Illuminate\Http\Request;

class StepController extends Controller
{
    public function activeStep(Step $step)
    {
        if((integer)$step->campaign->user_id === auth()->user()->id){
            $anotherSteps = Step::where('campaign_id', $step->campaign_id)->get();

            foreach($anotherSteps as $anotherStep){
                if($anotherStep->active == true){
                    $anotherStep->active = false;
                    $anotherStep->update();
                }
            }

            $step->active = true;
            $step->update();
            return back();
        }
        else{
            abort(403, 'Access denied');
        }


    }
}
