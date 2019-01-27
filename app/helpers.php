<?php

use App\Models\Block;

function showBlock(Block $block, $view){

    $view = (view($view, ['block' => $block]));
    if(auth()->user() !== null && (auth()->user()->can('admin'))){
        $button = view('blocks.buttons.__edit', ['block' => $block]);
        $view = $view . $button;
    }
    return $view;
}

?>