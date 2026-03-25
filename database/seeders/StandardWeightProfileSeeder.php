<?php

namespace Database\Seeders;

use App\Models\StandardWeightProfile;
use Illuminate\Database\Seeder;

class StandardWeightProfileSeeder extends Seeder
{
    public function run(): void
    {
        if (StandardWeightProfile::where('name', 'Likert 1-5 (Nunca a Sempre)')->exists()) {
            return;
        }

        $profile = StandardWeightProfile::create(['name' => 'Likert 1-5 (Nunca a Sempre)']);

        $options = [
            ['option_text' => 'Nunca', 'weight' => 1],
            ['option_text' => 'Raramente', 'weight' => 2],
            ['option_text' => 'Às vezes', 'weight' => 3],
            ['option_text' => 'Frequentemente', 'weight' => 4],
            ['option_text' => 'Sempre', 'weight' => 5],
        ];

        foreach ($options as $order => $opt) {
            $profile->options()->create([
                'option_text' => $opt['option_text'],
                'weight' => $opt['weight'],
                'order' => $order,
            ]);
        }
    }
}
