<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class AuthenticationTest extends TestCase
{
    /**
     * A test example to make sure authentication working.
     * when client try make action without token, system will reterive error
     *
     * @return void
     */
    public function test_add_product_without_authentication()
    {
        $this->json('post', 'api/products', [
            'name'     => 'Product # 1',
            'sku'      => '123456',
            'price'    => 120,
            'quantity' => 15,
        ])
            ->assertJson([
                'status' => 'Authentication Token not found',
            ]);
    }

    /**
     * A test example to make sure authentication working.
     * when client try make action without token, system will reterive error
     *
     * @return void
     */
    public function test_add_product_without_authorization()
    {
        $user = User::factory()->create([
            'password'    => bcrypt('123456'),
            'permissions' => '', // no permission so no authorized.
        ]);

        $token = $this->json('post', 'api/login', [
            'email'    => $user->email,
            'password' => '123456',
        ])->decodeResponseJson()['token'];

        $product = Product::factory()->create([
            'user_id' => auth()->id(),
        ]);

        $this->withToken($token)->json('post', 'api/products', [
            $product->getAttributes(),
        ])
            ->assertJson([
                'success' => false,
                'message' => 'not_autherized_to_products-store',
            ]);
    }

    /**
     * A test example to make sure authentication working.
     * when client try make action with invalid or expire token, system will reterive error
     *
     * @return void
     */
    public function test_add_product_expired_token()
    {
        $user = User::factory()->create([
            'password' => bcrypt('123456'),
        ]);

        $token = $this->json('post', 'api/login', [
            'email'    => $user->email,
            'password' => '123456',
        ])->decodeResponseJson()['token'];

        $this->withToken($token)->json('post', 'api/logout', ['token' => $token]);

        $product = Product::factory()->create([
            'user_id' => auth()->id(),
        ]);

        $this->withToken($token)->json('post', 'api/products',
            $product->getAttributes(), )
            ->assertJson([
                'status' => 'Token is Invalid',
            ]);
    }

    /**
     * A test example to make sure add product web service working.
     * when client try make new products, it's give sucess.
     *
     * @return void
     */
    public function test_add_product_successfully()
    {
        $user = User::factory()->create([
            'password' => bcrypt('123456'),
        ]);

        $token = $this->json('post', 'api/login', [
            'email'    => $user->email,
            'password' => '123456',
        ])->decodeResponseJson()['token'];

        $product = Product::factory()->create([
            'user_id' => auth()->id(),
        ]);

        $this->withToken($token)->json('post', 'api/products',
            $product->getAttributes(), )
            ->assertJson([
                'success' => true,
                'message' => 'Product created successfully',
            ]);
    }
}
