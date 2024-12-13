<?php
 
use App\Mail\SendConfirmationRequest;
use Illuminate\Support\Facades\Mail;
 
test('Was the email sent', function () {
    Mail::fake();
 
    // Perform order shipping...
 
    // Assert that no mailables were sent...
    Mail::assertNothingSent();
 
    // Assert that a mailable was sent...
    Mail::assertSent(SendConfirmationRequest::class);
 
    // Assert a mailable was sent twice...
    Mail::assertSent(SendConfirmationRequest::class, 2);
 
    // Assert a mailable was sent to an email address...
    Mail::assertSent(SendConfirmationRequest::class, 'bernandre07@gmail.com');
 
    // Assert a mailable was sent to multiple email addresses...
    Mail::assertSent(SendConfirmationRequest::class, ['example@laravel.com', '...']);
 
    // Assert a mailable was not sent...
    Mail::assertNotSent(AnotherMailable::class);
 
    // Assert 3 total mailables were sent...
    Mail::assertSentCount(3);
});