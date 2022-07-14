<?php







namespace App\Http\Controllers;







use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



use Illuminate\Foundation\Bus\DispatchesJobs;



use Illuminate\Foundation\Validation\ValidatesRequests;



use Illuminate\Routing\Controller as BaseController;

use Mail;
use App\Models\PageMeta;
use App\Models\User;
use App\Models\Models;



use Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function getPageMeta($id, $key="")
    {
        if (empty($key)) {

            $PageMeta = PageMeta::where('page_id', $id)->select('key', 'value')
                ->pluck('value', 'key')
                ->toArray();
            return $PageMeta;
        }
        else {

            if ($status) {
                // 
                $PageMeta = PageMeta::where('page_id', $id)->where('key', $key)->first();
                if (!empty($PageMeta))
                    return $PageMeta->value;
                else
                    return "";
            }
            else {
                $PageMeta = PageMeta::where('page_id', $id)->where('key', $key)->select('key', 'value')
                    ->pluck('value', 'key')
                    ->toArray();
                return $PageMeta;
            }
        }
    }
    public function onlinemodel(Request $request){
        $data=Models::join('users','users.id','=','models.user_id')->get();
        dd($data);
    }

}



