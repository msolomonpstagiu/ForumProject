<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThreadStoreRequest;
use App\Models\Channel;
use App\Models\Tag;
use App\Models\Thread;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class ThreadController extends Controller
{

    public function __construct(){
        return $this->middleware(['auth', 'verified'])->except(['index','show']);
    }

    public function index()
    {
        // order by to show the newest threads
        /** @noinspection PhpUndefinedMethodInspection */
        return view('threads.index', ['threads' => Thread::orderBy('created_at','desc')->paginate(5),]);
    }

    public function create()
    {
        return view('threads.create', ['channels' => Channel::all(), 'tags' => Tag::all()]);
    }

    public function store(ThreadStoreRequest $request): RedirectResponse
    {
        $thread = new Thread;
        $thread->title = $request->title;
        $thread->body = Purifier::clean($request->body);
        $thread->slug = Str::slug($request->title);
        $thread->channel_id = $request->channel;
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $thread->author_id = Auth::user()->id();
        $thread->save();
        $thread->syncTags($request->tags);

        return redirect()->route('threads.index')->with('success', 'Thread Created');
    }

    public function show(Channel $channel, Thread $thread)
    {
        return view('threads.show', compact('thread','channel'));
    }


    public function edit(Thread $thread)
    {
        //
    }

    public function update(Request $request, Thread $thread)
    {
        //
    }

    public function destroy(Thread $thread)
    {
        //
    }
}
