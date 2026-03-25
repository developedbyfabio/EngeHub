<?php

namespace Database\Seeders;

use App\Models\Dvr;
use Illuminate\Database\Seeder;

class DvrSeeder extends Seeder
{
    public function run(): void
    {
        $nomes = [
            'Almox - 01',
            'Almox - 02',
            'Almox - 03',
            'Ananindeua',
            'BH',
            'BH-2',
            'Cascavel',
            'Cascavel-2',
            'Chapeco novo',
            'Cuiaba',
            'Eng - 01',
            'Eng - 02',
            'Eng - 03',
            'Goiania-Vendas',
            'Goiania-Almox',
            'Itajai-1',
            'Itajai-2',
            'JCB-02',
            'JCB-Ext.',
            'JCB-Int.',
            'Maringa',
            'Porto Alegre',
            'Sinop',
        ];

        foreach ($nomes as $nome) {
            Dvr::firstOrCreate(
                ['nome' => $nome],
                ['status' => 'ativo']
            );
        }
    }
}
