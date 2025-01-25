<?php

namespace App\Console\Commands;

use App\Models\Gift;
use App\Models\TgUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class parseG extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse-g';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
            $giftTypes = [
                'skullflower' => 10000,
                'plushpepe' => 10000,
                'SantaHat' => 10000,
                'HexPot' => 10000,
                'EvilEye' => 10000,
                'HomemadeCake' => 10000,
                'SpicedWine' => 10000,
                'SignetRing' => 10000,
                'KissedFrog' => 10000,
                'JellyBunny' => 10000,
                'ScaredCat' => 10000,
                'EternalRose' => 10000,
                'SharpTongue' => 10000,
                'SpyAgaric' => 10000,
                'BerryBox' => 10000,
                'MagicPotion' => 10000,
                'TrappedHeart' => 10000,
                'PreciousPeach' => 10000,
                'PerfumeBottle' => 10000,
                'DurovsCap' => 10000,
                'VintageCigar' => 10000,
            ];

        foreach ($giftTypes as $giftType => $quantity) {
            for ($i = 1; $i <= $quantity; $i++) {
                $url = "https://nft.fragment.com/gift/{$giftType}-{$i}.json";

                $response = Http::get($url);

                if ($response->successful()) {
                    $giftData = $response->json();

                    $name = $giftData['name'];
                    $description = $giftData['description'];
                    $image = $giftData['image'];
                    $attributes = $giftData['attributes'];
                    $lottie = $giftData['lottie'];
                    if (isset($giftData['original_details'])){
                        $originalDetails = $giftData['original_details'];
                        if (isset($originalDetails['sender_telegram_id'])) {
                            $sender = TgUser::firstOrCreate(
                                ['telegram_id' => $originalDetails['sender_telegram_id']],
                                ['name' => $originalDetails['sender_name']]
                            );
                        }
                        if (isset($originalDetails['recipient_telegram_id'])) {
                            $recipient = TgUser::firstOrCreate(
                                ['telegram_id' => $originalDetails['recipient_telegram_id']],
                                ['name' => $originalDetails['recipient_name']]
                            );
                        }

                        Gift::create([
                            'name' => $name,
                            'description' => $description,
                            'image' => $image,
                            'attributes' => $attributes,
                            'lottie' => $lottie,
                            'original_details' => $originalDetails,
                            'sender_user_id' => $sender->id,
                            'recipient_user_id' => $recipient->id
                        ]);
                    }
                    $this->info("Gift {$name} (ID: {$i}) successfully fetched and saved.");
                } else {
                    $this->error("Failed to fetch gift data for {$giftType}-{$i}");
                }
            }
        }

        $this->info('All gift data has been fetched and stored.');
    }
}
