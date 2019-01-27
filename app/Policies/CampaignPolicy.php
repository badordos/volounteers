<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\Step;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Collection;

class CampaignPolicy
{
    use HandlesAuthorization;

    public $draftedCampaign;
    public $canEdit;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        $user = auth()->user();
        $this->draftedCampaign = $user->draftedCampaign();
        //Если редактируем незавершенную кампанию этого юзера или юзер админ
        $this->canEdit = $this->draftedCampaign && $user->id === $this->draftedCampaign->user->id || $user->can('admin');
    }

    //в этом шаге нет голосований и шаг не активен и не ниже активного разрешаем добавить
    public function createVotingInStep(User $user, Step $step, Collection $steps)
    {
        return $step->voting == null && $step->active != true && $step->lowerStep($steps, $step) == false;
    }

    //пользователь - автор кампании
    public function author(User $user, Campaign $campaign)
    {
       return $user->id === $campaign->user->id;
    }

    //пользователь - автор создаваемой(!) кампании
    public function creator(User $user)
    {
        return $this->canEdit;
    }

    public function delete(User $user)
    {
        return $this->canEdit;
    }

    public function update(User $user, Campaign $campaign)
    {
        return $this->canEdit;
    }

    public function create(User $user)
    {
        return $this->canEdit;
    }

    public function view(User $user, Campaign $campaign)
    {
        return $this->canEdit;
    }
}
