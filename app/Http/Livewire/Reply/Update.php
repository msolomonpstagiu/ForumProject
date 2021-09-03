<?php

namespace App\Http\Livewire\Reply;

use App\Models\Reply;
use Livewire\Component;

class Update extends Component
{
    public $replyId;
    public $replyOldBody;
    public $replyNewBody;

    public function update(){
        $reply = Reply::findOrFail($this->replyId);
        $reply->body = $this->replyNewBody;
        $reply->save();
        session()->flash('success', 'Reply Updated');
        $this->initialize($reply);

    }

    public function initialize(Reply $reply){
        $this->replyOldBody = $reply->body();
        $this->replyNewBody = $this->replyOldBody;
    }

    public function mount(Reply $reply){
        $this->replyId = $reply->id();
        $this->replyOldBody = $reply->body();
        // as soon as we update the old body too
        $this->initialize($reply);
    }
    public function render()
    {
        return view('livewire.reply.update');
    }
}
