<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inbox;

class InboxController extends Controller
{

    public function index()
    {
        $data['inboxes'] = Inbox::orderBy('id', 'desc')
                                -> paginate(10)
                                -> onEachSide(1)
                                -> withQueryString();
        $data['title'] = 'Inbox';

        return \View::make('admin.inbox.index', $data);
    }

    public function show($id)
    {
        $data['inbox'] = Inbox::findOrFail($id);
        $data['title'] = 'Inbox';

        return \View::make('admin.inbox.show', $data);
    }

    public function searchInbox(Request $request){

        $data['title'] = 'Search inbox';
        $q = $request->s;
        $data['inboxes'] = Inbox::where('name', 'like', "%".$q."%")
                            -> orWhere('email', 'like', "%".$q."%")
                            -> latest()
                            -> paginate(10)
                            -> withQueryString();

        return \View::make('admin.inbox.index', $data);
    }

    public function destroy($id)
    {
        $inbox = Inbox::find($id);
        $inbox->delete();

        return redirect()->route('inbox.index')->with('success', 'Inbox are deleted');
    }

    public function deleteInbox(){
        $inbox = Inbox::find($_POST['inbox_id']);
        $inbox->delete();
    }

    function bulkAction(Request $request){
        if( $request->input('bulks') ){
            Inbox::whereIn('id', $request->input('bulks'))->delete();
            return redirect()->route('inbox.index')->with('success', 'All selected Inbox deleted !');
        }else{
            return redirect()->route('inbox.index');
        }
    }

}
