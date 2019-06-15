<?php

namespace App\Http\Controllers;

use Chat;
use App\User;
use App\JobPost;
use App\JobQuote;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use App\Notifications\JobQuoteResponse;

class JobQuoteResponseController extends Controller
{
    public function update($jobQuoteId)
    {
        $jobQuote = JobQuote::whereId($jobQuoteId)->firstOrFail([
            'id', 'job_post_id', 'user_id', 'status',
        ]);

        if (Auth::user()->cannot('respond', $jobQuote)
            || $jobQuote->user_id === Auth::user()->id) {
            abort(403);
        }

        if (request('message')) {
            $this->sendMessage($jobQuote);
        }

        $this->updateStatus($jobQuote);

        $this->notifyVendor($jobQuote);

        return redirect()->back()->with('success_message', 'Response sent!');
    }

    public function sendMessage($jobQuote)
    {
        $conversation = Chat::getConversationBetween(Auth::user()->id, $jobQuote->user_id);

        if (!$conversation) {
            $participants = [Auth::user()->id, $jobQuote->user_id];
            $conversation = Chat::createConversation($participants);
        }

        Chat::conversations($conversation)->for(Auth::user())->readAll();

        $message = Chat::message(request('message'))
            ->from(Auth::user())
            ->to($conversation)
            ->send();

        return broadcast(new MessageSent(Auth::user(), $message))->toOthers();
    }

    public function updateStatus($jobQuote)
    {
        if (request('job_quote_response') === 'accepted') {
            $dataUpdates = [
                'status' => 4,
                'locked' => 1,
            ];
            $this->closeJobPost($jobQuote);
        }

        if (request('job_quote_response') === 'declined') {
            $dataUpdates = ['status' => 8];
        }

        if (request('job_quote_response') === 'request changes') {
            $dataUpdates = ['status' => 3];
        }

        return $jobQuote->update($dataUpdates);
    }

    public function notifyVendor($jobQuote)
    {
        $jobQuoteOwner = User::whereId($jobQuote->user_id)->firstOrFail(['id']);

        return $jobQuoteOwner->notify(new JobQuoteResponse($jobQuote));
    }

    public function closeJobPost($jobQuote)
    {
        $jobPost = JobPost::whereId($jobQuote->job_post_id)->firstOrFail();
        $jobPost->status = 'closed';

        return $jobPost->update();
    }
}
