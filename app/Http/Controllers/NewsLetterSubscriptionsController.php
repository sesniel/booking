<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NewsLetterSubscription;

class NewsLetterSubscriptionsController extends Controller
{
    public function unsubscribe($email)
    {
        NewsLetterSubscription::whereEmail($email)->delete();

        return 'Successfully unsubscribed to booking newsletter!';
    }
}
