<?php

Route::get('refresh_DB', 'Tools\RefreshDatabase')->name('refresh.db')->middleware('can:admin');
Route::get('info',function(){
    phpinfo();
})->middleware('can:admin');

Auth::routes(['verify' => true]);

//Страницы
Route::get('/', 'PagesController@main')->name('main');
Route::get('/main', 'PagesController@mainGuest')->name('main.guest');
Route::get('/about-us', 'PagesController@about')->name('about-us');
Route::get('/register/success', 'PagesController@registerSuccess')->name('register.success');
Route::post('/testcharity', 'PagesController@testCharity')->name('testCharity');

//Кампании
Route::prefix('campaigns')->group(function () {
    Route::any('/', 'CampaignController@index')->name('campaigns');
    Route::get('/show/{campaign}', 'CampaignController@show')->name('single-campaign');
    Route::get('/join/{campaign}/user/{user?}', 'CampaignController@join')->name('campaign.join');
    Route::post('/hide/{campaign}/user/{user?}', 'CampaignController@hide')->name('campaign.hide');

    //Создание кампании
    Route::get('/create/step/1/{edit?}', 'CampaignController@createStepOne')->name('create-campaign-step-1')->middleware('auth');
    Route::post('/create/step/1', 'CampaignController@storeStepOne')->name('store-campaign-step-1')->middleware('auth');
    Route::post('/create/step/2/deleteMedia/{type?}', 'CampaignController@deleteStepTwo')->name('deleteStepTwo');
    Route::post('/create/step/2/upload_video', 'CampaignController@uploadVideo')->name('uploadVideo')->middleware('auth');
    Route::middleware(['auth', 'can:creator'])->group(function () {
        Route::post('create/step/2/upload', 'CampaignController@uploadStepTwo')->name('uploadStepTwo');
        Route::get('/create/step/2', 'CampaignController@createStepTwo')->name('create-campaign-step-2');
        Route::post('/create/step/2', 'CampaignController@storeStepTwo')->name('store-campaign-step-2');
        Route::get('/create/step/3', 'CampaignController@createStepThree')->name('create-campaign-step-3');
        Route::post('/create/step/3', 'CampaignController@storeStepThree')->name('store-campaign-step-3');
        Route::get('/create/step/4', 'CampaignController@createStepFour')->name('create-campaign-step-4');
        Route::post('/create/step/4', 'CampaignController@storeStepFour')->name('store-campaign-step-4');
        Route::get('/create/step/5', 'CampaignController@createStepFive')->name('create-campaign-step-5');
        Route::post('/create/step/5', 'CampaignController@storeStepFive')->name('store-campaign-step-5');
        Route::get('/create/preview', 'CampaignController@preview')->name('preview');
        Route::get('/create/complete', 'CampaignController@creationComplete')->name('creationComplete');
    });

    //управление голосованиями и шагами
    Route::post('/create/{campaign}/voting/delete/', 'VotingController@deleteVoting')->middleware(['auth'])->name('deleteVoting');
    Route::post('/create/{campaign}/voting/add', 'VotingController@addVoting')->middleware(['auth'])->name('addVoting');
    Route::post('/step/active/{step}', 'StepController@activeStep')->name('activeStep');
});

//Голосования
Route::any('/vote', 'VotingController@index')->name('vote');
Route::post('/vote/{voting_id}/{user_id}', 'VotingController@vote')->middleware('auth')->name('voting');
Route::get('/vote/moreVotes', 'VotingController@moreVotes')->name('moreVotes');

//Публичный профиль
Route::get('/users/{user}', 'UserController@profile')->name('public-profile');

//Профиль пользователя
Route::get('/profile/about-me/', 'UserController@index')->name('profile.about-me')->middleware('auth');
Route::get('/profile/my-campaigns', 'UserController@campaigns')->name('profile.campaigns')->middleware('auth');
Route::get('/profile/achievements', 'UserController@achievements')->name('profile.achievements')->middleware('auth');
//Route::view('/profile/settings', 'settings')->name('profile.settings')->middleware('auth'); //TODO на втором этапе
