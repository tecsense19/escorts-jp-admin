<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Privacy;
use App\Models\Terms;
use App\Http\Controllers\Controller as Controller;

class SettingsController extends Controller
{
    function index(){
        $datas = Privacy::first();
        return view('admin.settings.privacy-policy',compact('datas'));
    }

    function saveprivacy(Request $request){
        try {
            $input = $request->all();
            if($input['id']){
                $input = $request->all();
                $privacy_data = [];
                $privacy_data['title'] = $input['title'];
                $privacy_data['descirption'] = $input['descirption'];
                Privacy::where('Id',$input['id'])->update($privacy_data);
                return redirect()->route('admin.settings.privacypolicy')->withSuccess('Update Succesfully');
            }else{
                $input = $request->all();
                $privacy_data = [];
                $privacy_data['title'] = $input['title'];
                $privacy_data['descirption'] = $input['descirption'];
                Privacy::Create($privacy_data);
                return redirect()->route('admin.settings.privacypolicy');
            }
        } catch (\Throwable $e) {
            return redirect()->back()->withError($e->getMessage());
        }  
    }


    function termindex(){
        $datas = Terms::first();
        return view('admin.settings.terms-condition',compact('datas'));
    }

    function saveterm(Request $request){
        try {
            $input = $request->all();
            if($input['id']){
                $input = $request->all();
                $privacy_data = [];
                $privacy_data['title'] = $input['title'];
                $privacy_data['description'] = $input['description'];
                Terms::where('Id',$input['id'])->update($privacy_data);
                return redirect()->route('admin.settings.termcondition')->withSuccess('Update Succesfully');
            }else{
                $input = $request->all();
                $privacy_data = [];
                $privacy_data['title'] = $input['title'];
                $privacy_data['description'] = $input['description'];
                Terms::Create($privacy_data);
                return redirect()->route('admin.settings.termcondition');
            }
        } catch (\Throwable $e) {
            return redirect()->back()->withError($e->getMessage());
        }  
    }

    function termscondition(){
        $datas = Terms::first();
        return view('admin.settings.terms-conditions-list',compact('datas'));
    }

    function privacypolicy(){
        $datas = Privacy::first();
        return view('admin.settings.privacy-policy-list',compact('datas'));
    }
    
}

