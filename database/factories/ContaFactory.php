<?php

namespace Database\Factories;

use App\Domain\Conta\Conta;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContaFactory extends Factory
{
    protected $model = Conta::class;

    public function definition()
    {
        return [
            'saldo' => $this->faker->randomFloat(2, 0, 999999.99),
            'numero_conta' => $this->faker->numerify('######'),
        ];
    }
}
