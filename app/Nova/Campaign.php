<?php

namespace App\Nova;

use App\Nova\Actions\ApproveCampaign;
use App\Nova\Actions\DeclineCampaign;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class Campaign extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Campaign';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make('Preview', function (){
                return '<a class="no-underline dim text-info font-bold" target="_blank" href="' . route('single-campaign', $this) . '">Preview</a>';
            })->asHtml(),
            ID::make()->sortable(),
            Select::make('readiness')->options([
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                'moderation' => 'moderation',
                'decline' => 'decline',
                'success' => 'success',
            ])->sortable(),
            Text::make('title')->sortable(),
            BelongsTo::make('User')->searchable(),
            BelongsTo::make('Category'),
            BelongsTo::make('City')->nullable(),
            Boolean::make('worldwide'),
            Images::make('Images', 'images')->multiple(),
            Images::make('Main Image', 'main_images'),
            Images::make('Preview Image', 'preview_image'),
            Images::make('Preview Video', 'main_video_preview'),

            Text::make('Video', function (){
                return '<video src="' . optional($this->getFirstMedia('main_videos'))->getUrl() . '" width="300" controls="controls">';
            })->asHtml()->onlyOnDetail(),

            Textarea::make('description'),
            Textarea::make('about_desc'),
            Number::make('Volunteers Needed','volunteers_needed')->sortable(),

//
//            Issue https://github.com/laravel/nova-issues/issues/425
//
//            BelongsToMany::make('User', 'hidUsers')->fields(function(){
//                return [
//                    Text::make('Text Reason'),
//                ];
//            }),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new ApproveCampaign(),
            new DeclineCampaign()
        ];
    }
}
