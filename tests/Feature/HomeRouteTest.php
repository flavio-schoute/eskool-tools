<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HomeRouteTest extends TestCase
{
    #[Test]
    public function home_route_redirects(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }
}