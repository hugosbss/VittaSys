<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produto;

class ProdutoSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            'Analgésicos' => [
                'Dipirona', 'Paracetamol', 'Ibuprofeno', 'Aspirina', 'Naproxeno',
                'Cetoprofeno', 'Diclofenaco', 'Meloxicam', 'Codeína', 'Tramadol'
            ],
            'Antibióticos' => [
                'Amoxicilina', 'Azitromicina', 'Ciprofloxacino', 'Claritromicina', 'Doxiciclina',
                'Cefalexina', 'Eritromicina', 'Levofloxacino', 'Metronidazol', 'Sulfametoxazol + Trimetoprim'
            ],
            'Anti-inflamatórios' => [
                'Prednisona', 'Betametasona', 'Dexametasona', 'Hidrocortisona', 'Fluocinolona',
                'Mometasona', 'Budesonida', 'Triancinolona', 'Corticotropina', 'Fludrocortisona'
            ],
            'Antialérgicos' => [
                'Loratadina', 'Cetirizina', 'Fexofenadina', 'Dexclorfeniramina', 'Ranitidina',
                'Hidroxizina', 'Ebastina', 'Bilastina', 'Levocetirizina', 'Clorfeniramina'
            ],
            'Antifúngicos' => [
                'Fluconazol', 'Itraconazol', 'Cetoconazol', 'Terbinafina', 'Clotrimazol',
                'Miconazol', 'Nistatina', 'Voriconazol', 'Posaconazol', 'Griseofulvina'
            ],
            'Vitaminas e Suplementos' => [
                'Vitamina C', 'Vitamina D', 'Vitamina B12', 'Ácido Fólico', 'Ômega 3',
                'Cálcio', 'Magnésio', 'Zinco', 'Multivitamínico A-Z', 'Colágeno'
            ],
            'Antidepressivos' => [
                'Fluoxetina', 'Sertralina', 'Escitalopram', 'Paroxetina', 'Venlafaxina',
                'Duloxetina', 'Amitriptilina', 'Nortriptilina', 'Bupropiona', 'Mirtazapina'
            ],
            'Ansiolíticos' => [
                'Diazepam', 'Lorazepam', 'Alprazolam', 'Clonazepam', 'Bromazepam',
                'Midazolam', 'Buspirona', 'Etizolam', 'Zolpidem', 'Triazolam'
            ],
            'Antivirais' => [
                'Oseltamivir', 'Aciclovir', 'Valaciclovir', 'Ganciclovir', 'Ribavirina',
                'Remdesivir', 'Lamivudina', 'Tenofovir', 'Efavirenz', 'Darunavir'
            ],
            'Hipertensão e Coração' => [
                'Losartana', 'Enalapril', 'Captopril', 'Atenolol', 'Propranolol',
                'Anlodipino', 'Clortalidona', 'Furosemida', 'Espironolactona', 'Hidroclorotiazida'
            ]
        ];

        foreach ($categorias as $categoria => $produtos) {
            foreach ($produtos as $produto) {
                Produto::create([
                    'nome' => $produto,
                    'descricao' => 'Medicamento da categoria ' . $categoria,
                    'preco' => fake()->randomFloat(2, 5, 200),
                    'quantidade_estoque' => fake()->numberBetween(10, 500),
                    'categoria' => $categoria,
                ]);
            }
        }
    }
}
