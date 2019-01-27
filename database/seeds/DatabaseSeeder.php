<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Flagstudio',
            'email' => config('app.admin_mail'),
            'password' => bcrypt('123123'),
            'date_of_birth' => \Carbon\Carbon::create(1990,9,01),
            'education' => json_encode(['Harvard University', 'SoloLearn courses', 'URFU',]),
            'hobbies' => json_encode(['football', 'coding', 'cinema', 'flowers', 'sky']),
            'email_verified_at' => \Carbon\Carbon::now(),
        ]);

        $admin_role = factory(\App\Models\Role::class)->create(['title' => 'admin']);
        $admin_user = \App\User::find(1);
        $admin_user->roles()->attach($admin_role);

        $cities = factory(\App\Models\City::class, 10)->create();
        $categories = factory(\App\Models\Category::class, 5)->create([
            'image' => function() {
                copy(resource_path() . '/assets/img/photo1.jpg', public_path() . '/storage/photo1.jpg');
                return 'photo1.jpg';
            }
        ]);
        $users = factory(App\User::class, 50)->create([
            'image' => function () {
                copy(resource_path() . '/assets/img/robot1.svg', public_path() . '/storage/robot1.svg');
                return 'robot1.svg';
            }
        ]);
        $campaigns = factory(\App\Models\Campaign::class, 50)->create();
        $campaigns->each(function($campaign){
            $campaign->steps()->saveMany(factory(\App\Models\Step::class, 2)->make(['active' => 0]));
            $campaign->steps()->save(factory(\App\Models\Step::class)->make(['active' => 1]));
            $campaign->steps()->saveMany(factory(\App\Models\Step::class, 3)->make(['active' => 0]));
        });
        $campaigns->each(function ($campaign) {
            $campaign->addMedia(resource_path() . '/assets/img/photo'.random_int(1, 4).'.jpg')
                ->preservingOriginal()
                ->toMediaCollection('images');
            $campaign->addMedia(resource_path() . '/assets/img/photo'.random_int(1, 4).'.jpg')
                ->preservingOriginal()
                ->toMediaCollection('main_images');
            $campaign->addMedia(resource_path() . '/assets/img/photo'.random_int(1, 4).'.jpg')
                ->preservingOriginal()
                ->toMediaCollection('preview_image');
        });
        $campaigns->random(20)->each(function ($campaign) {
            $campaign->addMedia(public_path() . '/video/dreamachine.mp4')
                ->preservingOriginal()
                ->toMediaCollection('main_videos');
        });
        $votings = factory(\App\Models\Voting::class, 250)->create();
        $skills = factory(\App\Models\Skill::class, 5)->create();
        $achievements = factory(\App\Models\Achievement::class, 20)->create([
            'image' => function(){
                copy(resource_path() . '/assets/img/achievement.svg', public_path().'/storage/achievement.svg');
                return 'achievement.svg';
        }
        ]);

        //скиллы пользователя
        \App\User::all()->each(function ($user) use ($skills) {
            $user->skills()->attach(
                $skills->random(rand(1,3))->pluck('id')->toArray()
            );
        });

        //интересные пользователю категории
        \App\User::all()->each(function ($user) use ($categories) {
            $user->categories()->attach(
                $categories->random(rand(1,3))->pluck('id')->toArray()
            );
        });

        //ачивки пользователя
        \App\User::all()->each(function ($user) use ($achievements) {
            $user->achievements()->attach(
                $achievements->random(rand(1,5))->pluck('id')->toArray()
            );
        });

        //присоединения к кампаниям
        $users = \App\User::all();
        \App\Models\Campaign::all()->each(function ($campaign) use ($users) {
            $campaign->joinedUsers()->attach(
                $users->random(rand(5,25))->pluck('id')->toArray()
            );
        });

        //связь пользователей и голосований
        $users->except(1)->each(function ($user) use ($votings) {
            $user->votings()->attach(
                $votings->random(rand(100,200))->pluck('id')->toArray(), ['variant' => rand(0,3)]);
        });

        $this->hardcodeBlocks();
    }


    public function hardcodeBlocks()
    {
        //"как это работает?"
        $howItWorks = factory(\App\Models\Block::class, 1)->create([
            'type' => 'howItWorks',
            'title' => 'How does it work?',
            'img' => function(){
                copy(resource_path() . '/assets/img/how-it-works.png', public_path().'/storage/how-it-works.png');
                return 'how-it-works.png';
            }
        ]);

        //команда
        $teamHeader = $about1 = factory(\App\Models\Block::class, 1)->create([
            'type' => 'teamHeader',
            'content' => '<h1>Our team</h1>'
        ]);
        $member1 = factory(\App\Models\Block::class, 1)->create([
            'title' => 'Ross Zaykov',
            'type' => 'team',
            'content' => '<p>Co-founder, CEO Developer, Financial trader</p>',
            'img' => function(){
                copy(resource_path() . '/assets/img/team1.png', public_path().'/storage/team1.png');
                return 'team1.png';
            }
        ]);
        $member2 = factory(\App\Models\Block::class, 1)->create([
            'title' => 'Daniel Haňka',
            'type' => 'team',
            'content' => '<p>Co-founder, Chief Operating Officer
                            Economist</p>',
            'img' => function(){
                copy(resource_path() . '/assets/img/team2.png', public_path().'/storage/team2.png');
                return 'team2.png';
            }
        ]);
        $member3 = factory(\App\Models\Block::class, 1)->create([
            'title' => 'Nitin Kaushik',
            'type' => 'team',
            'content' => '<p>Developer <br>
                            Ambassador of DreaMachine in India</p>',
            'img' => function(){
                copy(resource_path() . '/assets/img/team3.png', public_path().'/storage/team3.png');
                return 'team3.png';
            }
        ]);
        $member4 = factory(\App\Models\Block::class, 1)->create([
            'title' => 'Nour Eddine El Ghoumari',
            'type' => 'team',
            'content' => '<p>Movie director,
                            professional photographer
                            </p>
                            <p>
                            Ambassador of
                            DreaMachine in Middle East
                            </p>',
            'img' => function(){
                copy(resource_path() . '/assets/img/team4.png', public_path().'/storage/team4.png');
                return 'team4.png';
            }
        ]);
        $member5 = factory(\App\Models\Block::class, 1)->create([
            'title' => 'Camden Gülden',
            'type' => 'team',
            'content' => '<p>Economist</p>
                            <p>
                            Ambassador of DreaMachine in USA
                            </p>',
            'img' => function(){
                copy(resource_path() . '/assets/img/team5.png', public_path().'/storage/team5.png');
                return 'team5.png';
            }
        ]);
        $member6 = factory(\App\Models\Block::class, 1)->create([
            'title' => 'Leonid Fomin',
            'type' => 'team',
            'content' => '<p>Professional hockey player</p>
                            <p>
                            Ambassador of
                            DreaMachine in Global Sports
                            </p>',
            'img' => function(){
                copy(resource_path() . '/assets/img/team6.png', public_path().'/storage/team6.png');
                return 'team6.png';
            }
        ]);

        //блоки
        $aboutHeader = $about1 = factory(\App\Models\Block::class, 1)->create([
            'type' => 'aboutHeader',
            'content' => '<h1>About DREAMACHINE</h1>'
        ]);
        $about1 = factory(\App\Models\Block::class, 1)->create([
            'type' => 'about',
            'content' => '<p>
                        DreaMachine is a decentralized online platform which is connecting volunteers, donors and experts in self–governed process of running volunteering campaigns. DreaMachine is based on democratic values which means that there is no centralized body which decides on steps of the campaign, the way of use of collected funds (tokens) and how the volunteers’ activities are managed. On the contrary volunteers and experts subscribed to participate in the campaign are supposed to build the plan of campaign, discuss the budget and carry out the campaign via debates, polls, and voting
                        </p>
                        <h2>
                        We make revolution in process of donating
                        and organising volunteer activities
                        </h2>
                        <ol>
                        <li>Create your own campaign</li>
                        <li>Open subscription for volunteers</li>
                        <li>Build a plan of campaign realization</li>
                        <li>Kickstart your campaign with Changeum and DreaMining</li>
                        <li>Improve the world</li>
                        </ol>',
            'img' => function(){
                copy(resource_path() . '/assets/img/robot1.svg', public_path().'/storage/robot1.svg');
                return 'robot1.svg';
            }
        ]);
        $about2 = factory(\App\Models\Block::class, 1)->create([
            'type' => 'about',
            'content' => '<h2>DREAMING</h2>
                        <p>
                        DreaMining is a software dedicated to contributing to social issues that you worry about through the use of your CPU or GPU with the possibility to change the percentage of used resources in order to avoid system lagging.
                        </p>
                        <p>
                        The software DreaMining is checking which cryptocurrency (ETH, ETC, ZEC etc.) is more suitable for mining on your PC/MAC in terms of system resources usage and reward for mining. After the mining algorithm has been chosen, members’ machine is connected to a pool. Every three hours the pool automatically, using limit orders, exchanges mined crypto tokens (ETH, ETC, ZEC etc.) to Changeum coin (CNGX) on available exchanges, and directly sends CHX to smart-contracts of campaigns which members decided to mine for. All transactions hashes will be present in members DreaMachine profile in order to keep clear track record and statistics of socially-useful mining nodes. One of our goals is to involve as many corporations in DreaMining as possible in order to make donations collecting faster and more efficient.
                        </p>',
            'img' => function(){
                copy(resource_path() . '/assets/img/robot2.svg', public_path().'/storage/robot2.svg');
                return 'robot2.svg';
            }
        ]);

        $about3 = factory(\App\Models\Block::class, 1)->create([
            'type' => 'about',
            'content' => '<h2>CHANGEUM</h2>
                        <p>
                        Changeum is the coin used in DreaMachine for campaign funding purposes. Changeum coin meets ERC20 compliance and aims to make donation process clear, transparent and decentralized for lowering the administration and fundraising costs.
                        <br>
                        Changeum connects donors directly to actors (P2P) by the use of
                        smart-contracts which eliminates the possibility of creating a fraud
                        charity/volunteering campaign. The key feature of Changeum is that it has a limited token issue what presumes it’s natural price growth
                        </p>',
            'img' => function(){
                copy(resource_path() . '/assets/img/C.svg', public_path().'/storage/C.svg');
                return 'C.svg';
            }
        ]);

        //thank you page
        $thankYou = factory(\App\Models\Block::class, 1)->create([
            'type' => 'thankYou',
            'content' => '<h1>Thank you for your campaign, together we can do more!</h1>'
        ]);
        $thankYouRegister = factory(\App\Models\Block::class, 1)->create([
            'type' => 'thankYouRegister',
            'content' => '<h1>Thank you for your registration! Together we can do more. Please approve your email.</h1>'
        ]);

        //Слайдер кампании
        $campaignsSliderTitle = factory(\App\Models\Block::class, 1)->create([
            'type' => 'campaignsSliderTitle',
            'content' => '<h1>Campaigns now</h1>'
        ]);
        $campaignsSliderDesc = factory(\App\Models\Block::class, 1)->create([
            'type' => 'campaignsSliderDesc',
            'content' => '<p>Check other campaigns that
                            require your help</p>'
        ]);
        //Слайдер истории
        $storiesSliderTitle = factory(\App\Models\Block::class, 1)->create([
            'type' => 'storiesSliderTitle',
            'content' => '<h1>Stories</h1>'
        ]);
        $storiesSliderDesc = factory(\App\Models\Block::class, 1)->create([
            'type' => 'storiesSliderDesc',
            'content' => '<p>Long story short...</p>'
        ]);

        //блоки заголовков
        $votesHeader = factory(\App\Models\Block::class, 1)->create([
            'type' => 'votesHeader',
            'content' => '<h1>Vote</h1>'
        ]);

        $createCampaign = factory(\App\Models\Block::class, 1)->create([
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
    }
}

