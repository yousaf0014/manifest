<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http;
use App\User;
use App\Meditation;
use App\Category;
use Auth;

class CommentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(array('auth','admin'));
    }
    
    public function index(Request $request,Meditation $meditation)
    {
        $keyword = '';
        $commentObj = $meditation->commentUser();
        if(isset($request->keyword)){
            $keyword = $request->keyword;
            $commentObj = $commentObj->where('comments','like', '%'.$keyword.'%');
        }        
        $comments = $commentObj->paginate(20);

        $categorires = Category::get();
        $selected = Category::where('id',$meditation->category_id)->first();
        $user = User::where('id',$meditation->user_id)->first();

        return view('Admin.Comment.index',compact('comments','keyword','meditation','categorires','selected','user'));
    }
    
    public function delete(Meditation $meditation,$pivotID){
        //
        //$visits = Visit::find($visit_id);
        //$visits->products()->wherePivot('product_id','=',$product_id)->detach();
       // $meditation
        $meditation->commentUser()->wherePivot('id','=',$pivotID)->detach();
        flash('Successfully deleted the Content!','success');
        return redirect('comments/'.$meditation->id);
    }


    public function viewratings(Request $request,Meditation $meditation)
    {
        $keyword = '';
        $rateObj = $meditation->rateUser();
        if(isset($request->keyword)){
            $keyword = $request->keyword;
            $rateObj = $rateObj->where('comments','like', '%'.$keyword.'%');
        }        
        $ratings = $rateObj->paginate(20);

        $categorires = Category::get();
        $selected = Category::where('id',$meditation->category_id)->first();
        $user = User::where('id',$meditation->user_id)->first();

        return view('Admin.Comment.rating',compact('ratings','keyword','meditation','categorires','selected','user'));
    }
    
    public function delete_rating(Meditation $meditation,$pivotID){
        //
        //$visits = Visit::find($visit_id);
        //$visits->products()->wherePivot('product_id','=',$product_id)->detach();
       // $meditation
        $meditation->rateUser()->wherePivot('id','=',$pivotID)->detach();;
        flash('Successfully deleted the Content!','success');
        return redirect('ratings/'.$meditation->id);
    }



}