<?php

namespace Database\Factories;
use App\Models\Customer; // Ensure this line is correct
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'business_name' => $this->faker->company(), // Add business name here
            'business_tax_id' => $this->faker->boolean(), 
            'contact_person' => $this->faker->name(), 
            'contact_person_contact_number' => $this->faker->phoneNumber(), 
            'contact_person_email' => $this->faker->safeEmail(), 
            'billing_address' => $this->faker->address(), 
            'shipping_address' => $this->faker->address(), 
            'wholesale_account_number' => $this->faker->randomDigit(), 
        ];
    }
}
