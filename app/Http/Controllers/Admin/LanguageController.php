<?php
 
namespace App\Http\Controllers\Admin;
 
use App\Models\User;
use App\Models\LanguageString;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller as Controller;
use App\Services\ApiClientService;

use Hash;
use Session;
use Config;
 
class LanguageController extends Controller
{
    public function index(): View
    {
        return view('admin.language.language');
    }

    public function languageList(Request $request)
    {
        $input = $request->all();

        $search = $input['search'];

        $languageList = LanguageString::when(isset($search) && $search != '', function ($query) use ($search) {
                            $query->where('lang_value', 'like', '%' . $search . '%');
                        })
                        ->orderBy('id', 'desc')
                        ->paginate(15);

        return view('admin.language.list', compact('languageList'));
    }

    public function saveString(Request $request)
    {
        try {
            $input = $request->all();

            $countString = isset($input['lang_value']) ? count($input['lang_value']) : 0;

            for ($i=0; $i < $countString; $i++) 
            {
                if($input['lang_value'][$i])
                {
                    $stringArr = [];
                    $stringArr['lang_key'] = strtolower(str_replace(' ', '_', trim(preg_replace('/[^A-Za-z0-9]/', ' ', $input['lang_value'][$i]))));
                    $stringArr['lang_value'] = $input['lang_value'][$i];

                    $checkExists = LanguageString::where('id', $input['lang_id'][$i])->first();
                    if($checkExists)
                    {
                        LanguageString::where('id', $checkExists->id)->update(['lang_value' => $input['lang_value'][$i]]);
                    }
                    else
                    {
                        LanguageString::create($stringArr);
                    }
                }
            }

            return redirect()->back()->withSuccess('String updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function languageDelete(Request $request)
    {
        $input = $request->all();

        $Id = Crypt::decryptString($input['id']);

        LanguageString::where('id', $Id)->delete();

        return response()->json(['success' => true, 'message' => 'String deleted successfully.']);
    }
}