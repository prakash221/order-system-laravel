<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        // test user registration
        $response = $this->post('/api/register', [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertStatus(200);
        // test user login
        $response = $this->post('/api/login', [
            'email' => 'test@test.com',
            'password' => 'password',
        ]);
        $response->assertStatus(200);
        // test user forgot password
        $response = $this->post('/api/forgotpassword', [
            'email' => 'test@test.com',
        ]);
        $response->assertStatus(200);

        // test add customer
        $response = $this->post('/api/customer', [
            'full_name' => 'Test User',
            'email' => 'test@test.com',
            'phone' => '1234567890',
            'address' => 'Test Address',
        ]);
        $response->assertStatus(200);
        // test update customer
        $response = $this->patch('/api/customer', [
            'id' => 1,
            'full_name' => 'Test User',
            'email' => 'test@test.com',
            'phone' => '1234567890',
            'address' => 'Test Address',
        ]);
        $response->assertStatus(200);
        // test add product
        $response = $this->post('/api/product', [
            'product_name' => 'Test Product',
            'product_image' => 'Test Product',
            'description' => 'Test Description',
            'price' => 100,
        ]);
        $response->assertStatus(200);
        // test update product
        $response = $this->patch('/api/product', [
            'id' => 1,
            'product_name' => 'Test Product',
            'product_image' => 'Test Product',
            'description' => 'Test Description',
            'price' => 100,
        ]);
        $response->assertStatus(200);
        // test add order
        $response = $this->post('/api/order', [
            'customer_id' => 1,
            'order_date' => '2021-01-01',
            'order_status' => 'pending',
            'total_amount' => 100,
            'order_items' => [
                [
                    'product_id' => 1,
                    'quantity' => 1,
                    'price' => 100,
                ],
            ],
        ]);
        $response->assertStatus(200);
        // test update order
        $response = $this->patch('/api/order', [
            'id' => 1,
            'customer_id' => 1,
            'order_date' => '2021-01-01',
            'order_status' => 'pending',
            'total_amount' => 100,
            'order_items' => [
                [
                    'id' => 1,
                    'product_id' => 1,
                    'quantity' => 1,
                    'price' => 100,
                ],
            ],
        ]);
        $response->assertStatus(200);



        // test get all customers
        $response = $this->get('/api/customer');
        $response->assertStatus(200);
        // test get customer by id
        $response = $this->get('/api/customer/1');
        $response->assertStatus(200);
        // test get all orders
        $response = $this->get('/api/order');
        $response->assertStatus(200);
        // test get order by id
        $response = $this->get('/api/order/1');
        $response->assertStatus(200);
        // test get orders by customer id
        $response = $this->get('/api/order/customer/1');
        $response->assertStatus(200);
        // test get all products
        $response = $this->get('/api/product');
        $response->assertStatus(200);
        // test get product by id
        $response = $this->get('/api/product/1');
        $response->assertStatus(200);
        // test get product names
        $response = $this->get('/api/product/name');
        $response->assertStatus(200);
        // test get customer names
        $response = $this->get('/api/customer/names');
        $response->assertStatus(200);
        // test search customer by all
        $response = $this->get('/api/customers/test');
        $response->assertStatus(200);

        // test delete customer
        $response = $this->delete('/api/customer/1');
        $response->assertStatus(200);

        // test delete order
        $response = $this->delete('/api/order/1');
        $response->assertStatus(200);

        // test delete product
        $response = $this->delete('/api/product/1');
        $response->assertStatus(200);
    }
}
