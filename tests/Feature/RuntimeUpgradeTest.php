<?php

namespace Tests\Feature;

use App\Http\Controllers\Store\Location\QrCodeController;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class RuntimeUpgradeTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_tokens_keep_their_owner_and_expiration(): void
    {
        $user = User::factory()->create();
        $expires = now()->addHour()->startOfSecond();
        $token = $user->createToken('upgrade-check', ['read'], $expires);
        $stored = PersonalAccessToken::findToken($token->plainTextToken);

        $this->assertTrue($stored->tokenable->is($user));
        $this->assertTrue($stored->can('read'));
        $this->assertTrue($stored->expires_at->equalTo($expires));
    }

    public function test_location_qr_code_remains_a_200_pixel_svg(): void
    {
        $svg = app(QrCodeController::class)->qrCode('fixture-location');
        $document = new \DOMDocument();
        $this->assertTrue($document->loadXML($svg));
        $this->assertSame('svg', $document->documentElement->localName);
        $this->assertSame('200', $document->documentElement->getAttribute('width'));
        $this->assertSame('200', $document->documentElement->getAttribute('height'));
    }
}
