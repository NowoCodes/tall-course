<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Subscriber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail;

class LandingPage extends Component
{
    public $email;

    protected array $rules = [
        'email' => 'required|email:filter|unique:subscribers,email',
    ];

    public function subscribe()
    {
        $this->validate();

        DB::transaction(function () {
            $subscriber = Subscriber::create([
                'email' => $this->email
            ]);

            $notification = new VerifyEmail;
            $notification::createUrlUsing(static function ($notifiable) {
                return URL::temporarySignedRoute(
                    'subscribers.verify',
                    now()->addMinutes(30),
                    [
                        'subscriber' => $notifiable->getKey(),
                    ]
                );
            });

            $subscriber->notify($notification);
        }, 5);

        $this->reset();
    }

    public function render()
    {
        return view('livewire.landing-page');
    }
}
