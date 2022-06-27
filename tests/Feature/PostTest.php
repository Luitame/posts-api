<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    const URI = '/api/post';

    public function test_getting_the_post_list()
    {
        $response = $this->getJson(self::URI);

        $response
            ->assertStatus(200);
    }
}
