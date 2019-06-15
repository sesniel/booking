<?php

namespace App\Http\Controllers;

use Chat;
use App\User;
use App\Couple;
use App\Vendor;
use App\JobPost;
use App\JobQuote;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessagesController extends Controller
{
    public function index()
    {
        if ((int) request('recipient_user_id') === Auth::user()->id
            || (int) request('recipient_user_id') === 1
        ) {
            abort(404);
        }

        $conversation = null;
        $recipientAvatar = null;
        $title = null;
        $messages = [];

        if (request('recipient_user_id')) {
            $recipientUser = User::whereId(request('recipient_user_id'))->firstOrFail(['id', 'account']);
            $conversation = Chat::getConversationBetween(Auth::user()->id, $recipientUser->id);

            if (!$conversation) {
                $participants = [Auth::user()->id, $recipientUser->id];
                $conversation = Chat::createConversation($participants);
            }

            Chat::conversations($conversation)->for(Auth::user())->readAll();

            $messages = DB::table('mc_messages')->where('conversation_id', $conversation->id)->get()->toArray();

            if ($recipientUser->account === 'couple') {
                $couple = Couple::where('userA_id', $recipientUser->id)
                    ->orWhere('userB_id', $recipientUser->id)
                    ->firstOrFail(['id', 'title', 'profile_avatar']);

                $recipientAvatar = $couple->profile_avatar;
                $title = $couple->title;
            } else {
                $vendor = $recipientUser->vendorProfile;
                $recipientAvatar = $vendor->profile_avatar;
                $title = $vendor->business_name;
            }
        }

        $contactHistory = $this->getContactHistory();
        $contactList = $this->getContactList();

        return view('messages.index', compact(
            'contactHistory',
            'recipientAvatar',
            'conversation',
            'contactList',
            'messages',
            'title'
        ));
    }

    public function getConversationMessages($conversationId)
    {
        return DB::table('mc_messages')->where('conversation_id', $conversationId)
            ->orderBy('id', 'DESC')->get();
    }

    public function getContactHistory()
    {
        $userConversationIds = DB::table('mc_conversation_user')->where('user_id', Auth::user()->id)
            ->select('conversation_id')->get()->pluck('conversation_id')->toArray();

        $userIds = DB::table('mc_conversation_user')->where('user_id', '<>', Auth::user()->id)
            ->whereIn('conversation_id', $userConversationIds)
            ->orderBy('created_at', 'DESC')->get()->pluck('user_id')->toArray();

        if (request('recipient_user_id')) {
            array_unshift($userIds, request('recipient_user_id'));
        }

        return User::whereIn('id', $userIds)->with([
            'vendorProfile' => function ($q) {
                $q->addSelect(['id', 'user_id', 'business_name', 'profile_avatar', 'location_id'])
                ->with(['location' => function ($q) {
                    $q->addSelect(['id', 'name']);
                }]);
            },
            'coupleA' => function ($q) {
                $q->addSelect(['id', 'userA_id', 'title', 'profile_avatar', 'booked_venue_id'])
                ->with(['bookedVenue' => function ($q) {
                    $q->addSelect(['id', 'name']);
                }]);
            },
            'coupleB' => function ($q) {
                $q->addSelect(['id', 'userB_id', 'title', 'profile_avatar', 'booked_venue_id'])
                ->with(['bookedVenue' => function ($q) {
                    $q->addSelect(['id', 'name']);
                }]);
            }
        ])->get(['id']);
    }

    public function getContactList()
    {
        if (Auth::user()->account === 'couple') {
            $jobPosts = JobPost::where('user_id', Auth::user()->id)
                ->get(['id', 'user_id']);
            $users = JobQuote::whereIn('job_post_id', $jobPosts->pluck('id')->toArray())
                ->get(['id', 'user_id'])->pluck('user_id')->unique()->toArray();
            $contactLists = Vendor::whereIn('user_id', $users)
                ->get(['id', 'user_id', 'business_name', 'profile_avatar']);
        } else {
            $jobQuotes = JobQuote::where('user_id', Auth::user()->id)
                ->get(['id', 'job_post_id']);
            $users = JobPost::whereIn('id', $jobQuotes->pluck('job_post_id')->unique()->toArray())
                ->get(['id', 'user_id'])->pluck('user_id')->unique()->toArray();
            $contactLists = Couple::whereIn('userA_id', $users)
                ->orWhereIn('userB_id', $users)
                ->get(['id', 'userA_id AS user_id', 'title', 'profile_avatar']);
        }

        return $contactLists;
    }

    public function retreiveOrCreateConversation($recipientUserId)
    {
        $recipientUser = User::whereId($recipientUserId)->firstOrFail();
        $conversation = Chat::getConversationBetween(Auth::user()->id, $recipientUser->id);

        if ($conversation) {
            return $conversation;
        }

        $participants = [Auth::user()->id, $recipientUser->id];

        return Chat::createConversation($participants);
    }

    public function store(Request $request)
    {
        $conversation = Chat::conversation($request->conversationId);
        $message = $request->message;

        if ($request->messageType === 'image') {
            $message = Storage::url($request->message->store('user-uploads'));
        }

        $message = Chat::message($message)
            ->type($request->messageType ?? 'text')
            ->from(Auth::user())
            ->to($conversation)
            ->send();

        broadcast(new MessageSent(Auth::user(), $message))->toOthers();

        return response()->json($message);
    }
}
