<?php

namespace Tests\Feature;

use App\Services\WhatsAppService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WhatsAppServiceTest extends TestCase
{
    public function test_fonnte_message_is_sent_with_the_configured_token(): void
    {
        config()->set('services.otp.driver', 'fonnte');
        config()->set('services.fonnte.token', 'test-fonnte-token');

        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => true], 200),
        ]);

        $error = (new WhatsAppService)->send('628123456789', 'Test message');

        $this->assertNull($error);
        Http::assertSent(fn (Request $request): bool => $request->url() === 'https://api.fonnte.com/send'
            && $request->hasHeader('Authorization', 'test-fonnte-token')
            && $request['target'] === '628123456789'
            && $request['message'] === 'Test message'
        );
    }
}
