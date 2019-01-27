<?php

namespace Tests\Feature;

use App\Events\CampaignCreationCompleted;
use App\Models\Block;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\City;
use App\Models\Step;
use App\Models\Voting;
use App\Notifications\CampaignCreationCompletedToAdmin;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCampaignTest extends TestCase
{
    use DatabaseMigrations;

    public $user;

    public function setup()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    public function testAuthUserSeeCreateCampaignButtonOnMainPage()
    {
        $block = factory(Block::class, 1)->create([
            'type' => 'createCampaign',
            'title' => 'Create campaign',
            'content' => '<h2>Create your campaign in 5 easy steps:</h2>

            <ol>
                <li>Create your own campaign</li>
                <li>Open subscription for volunteers</li>
                <li>Build a plan of campaign realization</li>
                <li>Kickstart your campaign with Changeum and DreaMining</li>
                <li>Improve the world</li>
            </ol>',
            'img' => function () {
                copy(resource_path() . '/assets/img/robot1.svg', public_path() . '/storage/robot1.svg');
                return 'robot1.svg';
            },
        ]);

        $response = $this->actingAs($this->user)->get(route('main'));
        $response->assertSee('Create campaign');
    }

    public function testGuestNoSeeCreateCampaignButtonOnMainPage()
    {
        $this->get(route('main'))
            ->assertDontSee('Create campaign');
    }


    public function testGuestNotCanCreateCampaign()
    {
        $this->get(route('create-campaign-step-1'))
            ->assertRedirect(route('login'));
    }

    public function testUserCanCreateCampaignStepOneAndReturnEdit()
    {
        $this->actingAs($this->user)->get(route('create-campaign-step-1'))
            ->assertOk();

        $response = $this->post(route('store-campaign-step-1'), [
            'title' => 'TestTitle',
            'description' => 'TestDescription',
            'volunteers_needed' => 666,
            'category_id' => 3,
            'city_id' => 3,
        ]);

        $campaign = Campaign::find(1);

        $response->assertRedirect(route('create-campaign-step-2'));

        $this->get(route('create-campaign-step-1', ['edit' => 'edit']))
            ->assertStatus(200)
            ->assertSee($campaign->title)
            ->assertSee($campaign->description)
            ->assertSee($campaign->category_id)
            ->assertSee($campaign->city_id)
            ->assertSee($campaign->volunteers_needed);

    }

    public function testUserCanUploadFileInOwnedCampaign()
    {
        $campaign = factory(Campaign::class)->create([
            'user_id' => $this->user->id,
            'readiness' => '2'
        ]);

        $this->actingAs($this->user)->get(route('create-campaign-step-2'))
            ->assertDontSee('test.jpg');

        $local_file = __DIR__ . '/Files/test.jpg';

        $this->post(route('uploadStepTwo'), [
            'next_step_2' => 'true',
            'files' => ['file' => new \Illuminate\Http\UploadedFile($local_file, 'test.jpg', null, null, null, true)],
        ])->assertRedirect(route('create-campaign-step-3'));

        $media = $campaign->getMedia('images');

        $this->get(route('create-campaign-step-2'))
            ->assertSee($media[0]->getUrl());

    }

    public function testUserCanCreateStepsAndVotingsAndCanSeeIt()
    {
        factory(Campaign::class)->create([
            'user_id' => $this->user->id,
            'readiness' => '3'
        ]);

        $this->actingAs($this->user)->post(route('store-campaign-step-3'), [
            'next_step_3' => 'true',
            'steps' => [
                'step1' => [
                    'title' => 'Step 1',
                    'description' => 'Description 1',
                    'vote' => [
                        'title' => 'Voting 1',
                        'description' => 'Desc about Voting 1',
                        'variants' => ['variant a', 'variant b', 'variant c', 'variant d'],
                    ]
                ]
            ]

        ])->assertRedirect(route('create-campaign-step-4'));

        $this->get(route('create-campaign-step-3'))
            ->assertSee('Step 1')
            ->assertSee('Description 1')
            ->assertSee('Voting 1')
            ->assertSee('Desc about Voting 1')
            ->assertSee('variant a')
            ->assertSee('variant b')
            ->assertSee('variant c')
            ->assertSee('variant d');
    }


    public function testUserCanStoreStepFourAndSeeItWhenReturn()
    {
        factory(Campaign::class)->create([
            'user_id' => $this->user->id,
            'readiness' => '4',
        ]);

        $about_desc = 'Campaign description';

        $this->actingAs($this->user)->post(route('store-campaign-step-4'), ['about_desc' => $about_desc, 'next_step_4' => 'true'])
            ->assertRedirect(route('create-campaign-step-5'));

        $this->get(route('create-campaign-step-4'))
            ->assertSee($about_desc);

    }

    public function testUserCanStoreStepFiveAndSeeResult()
    {
        $campaign = factory(Campaign::class)->create([
            'user_id' => $this->user->id,
            'readiness' => '5',
        ]);

        $local_file = __DIR__ . '/Files/test.jpg';

        $this->actingAs($this->user)->post(route('store-campaign-step-5'), [
            'back_step_5' => 'true',
            'preview_image' => new \Illuminate\Http\UploadedFile($local_file, 'test.jpg', null, null, null, true),
        ])->assertRedirect(route('create-campaign-step-4'));

        $media = $campaign->getMedia('preview_image');

        $this->get(route('create-campaign-step-5'))
            ->assertSee($media[0]->getUrl());
    }

    public function testUserCanSeePreviewWithCampaignInfo()
    {
        factory(City::class)->create();
        factory(Category::class)->create();

        $campaign = factory(Campaign::class)->create([
            'user_id' => $this->user->id,
            'readiness' => '5',
            'category_id' => 1,
            'city_id' => 1,
        ]);

        $step = factory(Step::class)->create([
            'campaign_id' => $campaign->id,
        ]);
        factory(Voting::class)->create([
            'step_id' => $step->id,
        ]);

        $this->actingAs($this->user)->get(route('preview'))
            ->assertSee($campaign->title)
            ->assertSee($campaign->description)
            ->assertSee($campaign->about_desc)
            ->assertSee($campaign->category_id)
            ->assertSee($campaign->city_id)
            ->assertSee($campaign->image)
            ->assertSee($campaign->volunteers_needed)
            ->assertSee($step->title)
            ->assertSee($step->description)
            ->assertSee($step->voting->title)
            ->assertSee($step->voting->description);
    }

    public function testUserCanCompleteCreateCampaign()
    {
        Notification::fake();

        DB::table('blocks')->insert([
            'type' => 'thankYou',
            'content' => '<h1>Thank you for your campaign, together we can do more!</h1>'
        ]);

        $role = factory(\App\Models\Role::class)->create(['title' => 'admin']);
        $admin = factory(User::class)->create();
        $admin->roles()->attach($role);

        $campaign = factory(Campaign::class)->create([
            'user_id' => $this->user->id,
            'readiness' => '5',
        ]);

        $this->actingAs($this->user)->get(route('creationComplete'))
            ->assertOk()
            ->assertSee(route('single-campaign', $campaign));

        Notification::assertSentTo($admin, CampaignCreationCompletedToAdmin::class);
    }

    public function testIfInputsAtStepThreeIsEmptyRedirectWithoutValidation()
    {
        $campaign = factory(Campaign::class)->create([
            'user_id' => $this->user->id,
            'readiness' => '2',
        ]);

        $this->actingAs($this->user)->get(route('create-campaign-step-3'))
            ->assertOk();
        $this->post(route('store-campaign-step-3'), [
            'back_step_3' => 'true',
            'steps' => ['step1' => ['id' => null, 'title' => null, 'description' => null]],
        ])->assertRedirect(route('create-campaign-step-2'));
    }

    public function testIfInputAtStepFourIsEmptyRedirectWithoutValidation()
    {
        $campaign = factory(Campaign::class)->create([
            'user_id' => $this->user->id,
            'readiness' => '3',
        ]);

        $this->actingAs($this->user)->get(route('create-campaign-step-4'))
            ->assertOk();
        $this->post(route('store-campaign-step-4'), [
            'back_step_4' => 'true',
            'about_desc' => null,
        ])->assertRedirect(route('create-campaign-step-3'));
    }

    public function testStepOneValidation()
    {
        $this->actingAs($this->user)->get(route('create-campaign-step-1'))
            ->assertOk();

        $response = $this->post(route('store-campaign-step-1'), [
            'title' => 'TestTitle1TestTitle1TestTitle1TestTitle1TestTitle1***', //max 50
            'description' => 'TestDescription', //max 1000
            'volunteers_needed' => 666, //numeric volunteers
            'category_id' => 3, //required category
        ]);

        $response->assertRedirect(route('create-campaign-step-1'))
            ->assertSessionHasErrors([
                'title' => 'The title cannot exceed 50 characters.'
            ]);

        $response = $this->post(route('store-campaign-step-1'), [
            'title' => 'TestTitle1', //max 50
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam vulputate, ipsum in sollicitudin efficitur, eros velit viverra mi, vitae lobortis magna ligula non mauris. Sed euismod nisl vitae lectus tristique, vel hendrerit velit placerat. Sed eleifend venenatis risus. Fusce accumsan purus non eleifend tempor. Nulla ullamcorper elit at neque lacinia dapibus. In feugiat ante ac lobortis ullamcorper. Aliquam vestibulum, urna sodales ultricies suscipit, risus lorem tempor lacus, non convallis metus lorem quis enim.
            Ut semper leo non nibh venenatis viverra. Vivamus cursus ut augue ac accumsan. Duis arcu erat, semper nec ante volutpat, accumsan euismod lacus. Aenean porttitor arcu vitae tellus laoreet lacinia. Aliquam eget aliquet ex. Donec at sapien eu arcu tincidunt dignissim non at elit. Duis feugiat sit amet leo quis sollicitudin. Donec eleifend lorem ac tellus hendrerit consequat. Aliquam blandit vitae mi eget suscipit. Phasellus bibendum ex non tortor ultrices, sed dignissim enim porta. Sed id mi id neque sagittis aliquam.
            Pellentesque nec sapien lacus. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Mauris finibus volutpat sem, id mollis justo sollicitudin vel. Nullam tempus facilisis dolor in sodales. Nam at mattis eros. Nulla dapibus sapien a leo iaculis congue non quis quam. Pellentesque cursus vulputate pharetra. Sed non interdum augue. Duis erat erat, faucibus quis eros vel, sollicitudin consequat dolor.
            Integer dignissim fringilla mauris id dapibus. In tincidunt molestie eros in tempor. Praesent tristique id sapien eu pellentesque. Sed hendrerit lectus nec libero dictum ultrices. Aliquam pellentesque vitae tellus ut scelerisque. Integer placerat erat id nulla vehicula, in dignissim orci consequat. Proin dui sem, egestas quis magna vel, sagittis auctor diam. Sed id mi sem. Morbi consectetur nibh orci. Sed vel dapibus quam. Donec ornare vitae eros ut congue. Etiam eu ante mauris.
            Donec ac metus viverra lectus efficitur laoreet. Pellentesque eget maximus ex, a lacinia lorem. Sed augue elit, laoreet quis magna a, maximus dignissim nulla. Donec molestie, nunc eget pellentesque interdum, ex metus placerat nisl, sed cursus lorem sem nec lacus. Quisque iaculis velit nec lectus vestibulum ornare. Nam ac est vestibulum tellus auctor cursus vel vitae ex. Aenean rhoncus erat at nunc feugiat elementum. Nunc eget posuere orci. Nam dignissim lorem id pharetra hendrerit. Vivamus est ipsum, porta id dolor sed, volutpat porttitor nulla.
            Duis in congue felis. Proin accumsan iaculis ante dapibus pellentesque. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent at gravida arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Fusce sodales consectetur ante nec semper. Ut eget nibh tincidunt, condimentum felis id, ornare diam. Sed sed dignissim ex, eget dictum mi. Praesent facilisis felis in tortor facilisis semper. Donec eleifend augue quam, porttitor mollis eros rhoncus at.
            Sed rhoncus suscipit lorem vel venenatis. Cras mattis quam pretium ligula tristique, ac venenatis velit egestas. In ante felis, rhoncus non dolor nec, luctus tempor mi. Praesent eleifend est nisl, sed pulvinar ante rutrum vitae. Nullam dapibus aliquam diam, at aliquam augue luctus vitae. Quisque bibendum convallis elit, quis ornare velit egestas ut. Phasellus ut malesuada urna, ac volutpat risus. Cras ante eros, maximus vel velit eget, fringilla vehicula massa. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;
            Mauris quis ornare diam. Duis convallis viverra tempus. Proin eu elementum ex. Maecenas iaculis semper tincidunt. Sed in mauris nisl. Nunc ultrices pulvinar interdum. Nullam et justo eros. Nunc nisl elit, egestas sit amet dui non, semper feugiat sapien.
            Proin aliquet lectus et odio rutrum, vel imperdiet justo porta. Integer fermentum sodales placerat. Donec aliquet rhoncus condimentum. Suspendisse potenti. Nullam nec magna lorem. Integer dictum ante non pharetra suscipit. Nam finibus nibh erat, ut faucibus eros laoreet sit amet. Donec non vehicula ligula. Quisque molestie dui vel laoreet suscipit. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aliquam imperdiet neque interdum velit sodales, non mollis massa varius. Phasellus ut neque nulla. Proin dictum bibendum orci in hendrerit. Sed ac arcu eget est viverra condimentum.
            Nulla aliquet egestas mi sit amet euismod. Maecenas dui arcu, fringilla vel dapibus in, ultrices vitae erat. Aliquam eu justo ligula. Pellentesque vitae arcu eget odio elementum fringilla. Integer quis rhoncus lectus. Maecenas eu enim eget mauris laoreet porttitor eu eget.', //max 1000
            'volunteers_needed' => 666, //numeric volunteers
            'category_id' => 3, //required category
        ]);

        $response->assertRedirect(route('create-campaign-step-1'))
            ->assertSessionHasErrors([
                'description' => 'The description cannot exceed 700 characters.'
            ]);

        $response = $this->post(route('store-campaign-step-1'), [
            'title' => 'TestTitle1', //max 50
            'description' => 'TestDescription', //max 1000
            'category_id' => 3
        ]);
        $response->assertRedirect(route('create-campaign-step-1'))
            ->assertSessionHasErrors([
                'volunteers_needed' => 'The volunteers needed field is required.'
            ]);

        $response = $this->post(route('store-campaign-step-1'), [
            'title' => 'TestTitle1', //max 50
            'description' => 'TestDescription', //max 1000
            'volunteers_needed' => '123asdasd123',
            'category_id' => 3
        ]);
        $response->assertRedirect(route('create-campaign-step-1'))
            ->assertSessionHasErrors([
                'volunteers_needed' => 'The volunteers needed must be a number.'
            ]);

        $response = $this->post(route('store-campaign-step-1'), [
            'title' => 'TestTitle1', //max 50
            'description' => 'TestDescription', //max 1000
            'volunteers_needed' => 666, //numeric volunteers
        ]);
        $response->assertRedirect(route('create-campaign-step-1'))
            ->assertSessionHasErrors([
                'category_id' => 'The category id field is required.'
            ]);
    }

    public function testStepThreeValidation()
    {
        $campaign = factory(Campaign::class)->create([
            'user_id' => $this->user->id,
            'readiness' => '3',
        ]);

        $this->actingAs($this->user)->get(route('create-campaign-step-3'))
            ->assertOk();

        $this->post(route('store-campaign-step-3'), [
            'steps' => [
                'step1' => [ //min
                    'title' => 'ab',
                    'description' => '12',
                    'vote' => [
                        'title' => 'qw',
                        'description' => '34',
                        'variants' => [
                            0 => '',
                            1 => '',
                        ]
                    ]
                ],
                'step2' => [ //max
                    'title' => 'tensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbols',
                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam vulputate, ipsum in sollicitudin efficitur, eros velit viverra mi, vitae lobortis magna ligula non mauris. Sed euismod nisl vitae lectus tristique, vel hendrerit velit placerat. Sed eleifend venenatis risus. Fusce accumsan purus non eleifend tempor. Nulla ullamcorper elit at neque lacinia dapibus. In feugiat ante ac lobortis ullamcorper. Aliquam vestibulum, urna sodales ultricies suscipit, risus lorem tempor lacus, non convallis metus lorem quis enim.
            Ut semper leo non nibh venenatis viverra. Vivamus cursus ut augue ac accumsan. Duis arcu erat, semper nec ante volutpat, accumsan euismod lacus. Aenean porttitor arcu vitae tellus laoreet lacinia. Aliquam eget aliquet ex. Donec at sapien eu arcu tincidunt dignissim non at elit. Duis feugiat sit amet leo quis sollicitudin. Donec eleifend lorem ac tellus hendrerit consequat. Aliquam blandit vitae mi eget suscipit. Phasellus bibendum ex non tortor ultrices, sed dignissim enim porta. Sed id mi id neque sagittis aliquam.
            Pellentesque nec sapien lacus. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Mauris finibus volutpat sem, id mollis justo sollicitudin vel. Nullam tempus facilisis dolor in sodales. Nam at mattis eros. Nulla dapibus sapien a leo iaculis congue non quis quam. Pellentesque cursus vulputate pharetra. Sed non interdum augue. Duis erat erat, faucibus quis eros vel, sollicitudin consequat dolor.
            Integer dignissim fringilla mauris id dapibus. In tincidunt molestie eros in tempor. Praesent tristique id sapien eu pellentesque. Sed hendrerit lectus nec libero dictum ultrices. Aliquam pellentesque vitae tellus ut scelerisque. Integer placerat erat id nulla vehicula, in dignissim orci consequat. Proin dui sem, egestas quis magna vel, sagittis auctor diam. Sed id mi sem. Morbi consectetur nibh orci. Sed vel dapibus quam. Donec ornare vitae eros ut congue. Etiam eu ante mauris.
            Donec ac metus viverra lectus efficitur laoreet. Pellentesque eget maximus ex, a lacinia lorem. Sed augue elit, laoreet quis magna a, maximus dignissim nulla. Donec molestie, nunc eget pellentesque interdum, ex metus placerat nisl, sed cursus lorem sem nec lacus. Quisque iaculis velit nec lectus vestibulum ornare. Nam ac est vestibulum tellus auctor cursus vel vitae ex. Aenean rhoncus erat at nunc feugiat elementum. Nunc eget posuere orci. Nam dignissim lorem id pharetra hendrerit. Vivamus est ipsum, porta id dolor sed, volutpat porttitor nulla.
            Duis in congue felis. Proin accumsan iaculis ante dapibus pellentesque. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent at gravida arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Fusce sodales consectetur ante nec semper. Ut eget nibh tincidunt, condimentum felis id, ornare diam. Sed sed dignissim ex, eget dictum mi. Praesent facilisis felis in tortor facilisis semper. Donec eleifend augue quam, porttitor mollis eros rhoncus at.
            Sed rhoncus suscipit lorem vel venenatis. Cras mattis quam pretium ligula tristique, ac venenatis velit egestas. In ante felis, rhoncus non dolor nec, luctus tempor mi. Praesent eleifend est nisl, sed pulvinar ante rutrum vitae. Nullam dapibus aliquam diam, at aliquam augue luctus vitae. Quisque bibendum convallis elit, quis ornare velit egestas ut. Phasellus ut malesuada urna, ac volutpat risus. Cras ante eros, maximus vel velit eget, fringilla vehicula massa. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;
            Mauris quis ornare diam. Duis convallis viverra tempus. Proin eu elementum ex. Maecenas iaculis semper tincidunt. Sed in mauris nisl. Nunc ultrices pulvinar interdum. Nullam et justo eros. Nunc nisl elit, egestas sit amet dui non, semper feugiat sapien.
            Proin aliquet lectus et odio rutrum, vel imperdiet justo porta. Integer fermentum sodales placerat. Donec aliquet rhoncus condimentum. Suspendisse potenti. Nullam nec magna lorem. Integer dictum ante non pharetra suscipit. Nam finibus nibh erat, ut faucibus eros laoreet sit amet. Donec non vehicula ligula. Quisque molestie dui vel laoreet suscipit. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aliquam imperdiet neque interdum velit sodales, non mollis massa varius. Phasellus ut neque nulla. Proin dictum bibendum orci in hendrerit. Sed ac arcu eget est viverra condimentum.
            Nulla aliquet egestas mi sit amet euismod. Maecenas dui arcu, fringilla vel dapibus in, ultrices vitae erat. Aliquam eu justo ligula. Pellentesque vitae arcu eget odio elementum fringilla. Integer quis rhoncus lectus. Maecenas eu enim eget mauris laoreet porttitor eu eget.',
                    'vote' => [
                        'title' => 'tensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbols',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam vulputate, ipsum in sollicitudin efficitur, eros velit viverra mi, vitae lobortis magna ligula non mauris. Sed euismod nisl vitae lectus tristique, vel hendrerit velit placerat. Sed eleifend venenatis risus. Fusce accumsan purus non eleifend tempor. Nulla ullamcorper elit at neque lacinia dapibus. In feugiat ante ac lobortis ullamcorper. Aliquam vestibulum, urna sodales ultricies suscipit, risus lorem tempor lacus, non convallis metus lorem quis enim.
            Ut semper leo non nibh venenatis viverra. Vivamus cursus ut augue ac accumsan. Duis arcu erat, semper nec ante volutpat, accumsan euismod lacus. Aenean porttitor arcu vitae tellus laoreet lacinia. Aliquam eget aliquet ex. Donec at sapien eu arcu tincidunt dignissim non at elit. Duis feugiat sit amet leo quis sollicitudin. Donec eleifend lorem ac tellus hendrerit consequat. Aliquam blandit vitae mi eget suscipit. Phasellus bibendum ex non tortor ultrices, sed dignissim enim porta. Sed id mi id neque sagittis aliquam.
            Pellentesque nec sapien lacus. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Mauris finibus volutpat sem, id mollis justo sollicitudin vel. Nullam tempus facilisis dolor in sodales. Nam at mattis eros. Nulla dapibus sapien a leo iaculis congue non quis quam. Pellentesque cursus vulputate pharetra. Sed non interdum augue. Duis erat erat, faucibus quis eros vel, sollicitudin consequat dolor.
            Integer dignissim fringilla mauris id dapibus. In tincidunt molestie eros in tempor. Praesent tristique id sapien eu pellentesque. Sed hendrerit lectus nec libero dictum ultrices. Aliquam pellentesque vitae tellus ut scelerisque. Integer placerat erat id nulla vehicula, in dignissim orci consequat. Proin dui sem, egestas quis magna vel, sagittis auctor diam. Sed id mi sem. Morbi consectetur nibh orci. Sed vel dapibus quam. Donec ornare vitae eros ut congue. Etiam eu ante mauris.
            Donec ac metus viverra lectus efficitur laoreet. Pellentesque eget maximus ex, a lacinia lorem. Sed augue elit, laoreet quis magna a, maximus dignissim nulla. Donec molestie, nunc eget pellentesque interdum, ex metus placerat nisl, sed cursus lorem sem nec lacus. Quisque iaculis velit nec lectus vestibulum ornare. Nam ac est vestibulum tellus auctor cursus vel vitae ex. Aenean rhoncus erat at nunc feugiat elementum. Nunc eget posuere orci. Nam dignissim lorem id pharetra hendrerit. Vivamus est ipsum, porta id dolor sed, volutpat porttitor nulla.
            Duis in congue felis. Proin accumsan iaculis ante dapibus pellentesque. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent at gravida arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Fusce sodales consectetur ante nec semper. Ut eget nibh tincidunt, condimentum felis id, ornare diam. Sed sed dignissim ex, eget dictum mi. Praesent facilisis felis in tortor facilisis semper. Donec eleifend augue quam, porttitor mollis eros rhoncus at.
            Sed rhoncus suscipit lorem vel venenatis. Cras mattis quam pretium ligula tristique, ac venenatis velit egestas. In ante felis, rhoncus non dolor nec, luctus tempor mi. Praesent eleifend est nisl, sed pulvinar ante rutrum vitae. Nullam dapibus aliquam diam, at aliquam augue luctus vitae. Quisque bibendum convallis elit, quis ornare velit egestas ut. Phasellus ut malesuada urna, ac volutpat risus. Cras ante eros, maximus vel velit eget, fringilla vehicula massa. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;
            Mauris quis ornare diam. Duis convallis viverra tempus. Proin eu elementum ex. Maecenas iaculis semper tincidunt. Sed in mauris nisl. Nunc ultrices pulvinar interdum. Nullam et justo eros. Nunc nisl elit, egestas sit amet dui non, semper feugiat sapien.
            Proin aliquet lectus et odio rutrum, vel imperdiet justo porta. Integer fermentum sodales placerat. Donec aliquet rhoncus condimentum. Suspendisse potenti. Nullam nec magna lorem. Integer dictum ante non pharetra suscipit. Nam finibus nibh erat, ut faucibus eros laoreet sit amet. Donec non vehicula ligula. Quisque molestie dui vel laoreet suscipit. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aliquam imperdiet neque interdum velit sodales, non mollis massa varius. Phasellus ut neque nulla. Proin dictum bibendum orci in hendrerit. Sed ac arcu eget est viverra condimentum.
            Nulla aliquet egestas mi sit amet euismod. Maecenas dui arcu, fringilla vel dapibus in, ultrices vitae erat. Aliquam eu justo ligula. Pellentesque vitae arcu eget odio elementum fringilla. Integer quis rhoncus lectus. Maecenas eu enim eget mauris laoreet porttitor eu eget.',
                        'variants' => [
                            0 => 'tensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbols',
                            1 => 'tensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbolstensymbols',
                        ]
                    ]
                ],
                'step3' => [ //numeric
                    'title' => 12,
                    'description' => 34,
                    'vote' => [
                        'title' => 56,
                        'description' => 78,
                        'variants' => [
                            0 => 9,
                            1 => 1,
                        ]
                    ]
                ],
            ]
        ])->assertSessionHasErrors([
            'steps.step1.title' => "The Step's name must be at least 3 characters.",
            'steps.step1.description' => "The Step's description must be at least 3 characters.",
            'steps.step1.vote.title' => "The Votes's name must be at least 3 characters.",
            'steps.step1.vote.description' => "The Votes's description must be at least 3 characters.",
            'steps.step1.vote.variants.0' => "The Votes's variant must be at least 1 characters.",
            'steps.step1.vote.variants.1' => "The Votes's variant must be at least 1 characters.",

            'steps.step2.title' => "The Step's name cannot exceed 120 characters.",
            'steps.step2.description' => "The Step's description cannot exceed 1000 characters.",
            'steps.step2.vote.title' => "The Votes's name cannot exceed 120 characters.",
            'steps.step2.vote.description' => "The Votes's description cannot exceed 1000 characters.",
            'steps.step2.vote.variants.0' => "The Votes's variant cannot exceed 60 characters.",
            'steps.step2.vote.variants.1' => "The Votes's variant cannot exceed 60 characters.",

            'steps.step3.title' => "The Step's name must be a string.",
            'steps.step3.description' => "The Step's description must be a string.",
            'steps.step3.vote.title' => "The Votes's name must be a string.",
            'steps.step3.vote.description' => "The Votes's description must be a string.",
            'steps.step3.vote.variants.0' => "The Votes's variant must be a string.",
            'steps.step3.vote.variants.1' => "The Votes's variant must be a string.",
        ]);
    }

    public function testUserCanUploadVideoAndSeePreviewImageAndVideoName()
    {
        $campaign = factory(Campaign::class)->create([
            'user_id' => $this->user->id,
            'readiness' => '2'
        ]);

        $this->actingAs($this->user)->get(route('create-campaign-step-2'))
            ->assertOk();

        $local_file = public_path() . '/video/dreamachine.mp4';

        $this->post(route('uploadVideo'), [
            'file' => new \Illuminate\Http\UploadedFile($local_file, 'dreamachine.mp4', null, null, null, true),
        ])
        ->assertOk();

        $media = $campaign->getFirstMedia('main_video_preview');

        $this->get(route('create-campaign-step-2'))
            ->assertSee($media->getUrl()) //превью
            ->assertSee('dreamachine.mp4'); //название файла
    }

    public function testUserCanNotVoteInCreatingCampaign()
    {
        $campaign = factory(Campaign::class)->create([
            'user_id' => $this->user->id,
            'readiness' => '4'
        ]);

        $step = factory(Step::class)->create([
            'campaign_id' => $campaign->id,
            'active' => 1,
        ]);
        $voting = factory(Voting::class)->create([
            'step_id' => $step->id,
        ]);

        $this->actingAs($this->user)->get(route('preview'))
            ->assertOk()
            ->assertSee(':disabled="true"')
            ->assertDontSee(':disabled="false"');

        $this->post(route('voting', ['voting_id' => $voting->id, 'user_id' => $this->user->id]))
            ->assertStatus(403);

    }

    


}
