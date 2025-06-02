<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Customer;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_registers_a_customer_successfully()
    {
        $data = [
            'name' => 'Maria Oliveira',
            'email' => 'maria.oliveira@teste.com',
            'phone' => '11999999999',
            'birth_date' => '1990-05-15',
            'address' => 'Rua das Flores, 123',
            'complement' => '',
            'neighborhood' => 'Centro',
            'zip_code' => '01234567',
        ];

        $response = $this->postJson('/api/customers', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'name' => 'Maria Oliveira',
                     'email' => 'maria.oliveira@teste.com',
                 ]);

        $this->assertDatabaseHas('customers', [
            'email' => 'maria.oliveira@teste.com',
        ]);
    }

    /** @test */
    public function it_fails_to_register_customer_due_to_missing_fields()
    {
        $data = [
            // Campos obrigatórios ausentes
        ];

        $response = $this->postJson('/api/customers', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                     'name',
                     'email',
                     'phone',
                     'birth_date',
                     'address',
                     'neighborhood',
                     'zip_code',
                 ]);
    }

    /** @test */
    public function it_fails_to_register_customer_due_to_duplicate_email()
    {
        Customer::factory()->create([
            'email' => 'eduardo.bonete@teste.com',
        ]);

        $data = [
            'name' => 'Eduardo Bonete',
            'email' => 'eduardo.bonete@teste.com',
            'phone' => '11988888888',
            'birth_date' => '1987-01-17',
            'address' => 'Rua Lauro Freire 5',
            'complement' => '',
            'neighborhood' => 'JD Celeste',
            'zip_code' => '05527170',
        ];

        $response = $this->postJson('/api/customers', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }
}
