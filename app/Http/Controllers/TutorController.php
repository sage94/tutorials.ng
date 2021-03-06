<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Input;
//use \Input as Input;

use Illuminate\Http\Request;
//tell the controllers the tables to use
use App\Auth;
class TutorController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
     public function __construct()
     {
         $this->middleware('auth');
         $this->middleware('tutorguard');
     }
 
     /**
      * Show the application dashboard.
      *
      * @return \Illuminate\Http\Response
      */
     public function profile()
     {
        $comments=DB::table('comments')->where('commented_for',\Auth::User()->email)->get();
        return view('tutors.profile',compact('comments'));
       
     }

     public function edit( Request $details, $id)
     {
         if($id != null)
         {
           
            // validate($details, [
            //     'name' => 'required|string|max:70',
            //     'email' => 'required|string|email|max:100|unique:users',
            //     'password' => 'required|string|min:6|confirmed',
            //     'phone' => 'required|string|max:15',
            //     'typeOfUser' => 'required|string',
            //     'school'=>'string|max:150',
            //     'skills'=>'string|max:200',
            //     'gig'=>'string|max:100',
            //     'education'=>'string|max:250',
            //     'about'=>'string|max:250',
            //     'address'=>'string|max:200',
            //     'state'=>'string|max:100',
            //     'country'=>'string|max:100'

            // ]);

                    
            //$update = users::findOrFail($id);
            $user=\Auth::user();
           
            $user->name= $details->name;
           
            $user->phone= $details->phone;
            $user->school= $details->school;
            $user->skills= $details->skills;
            $user->gig= $details->gig;
            $user->education= $details->education;
            $user->address= $details->address;
            $user->state= $details->state;
            $user->country= $details->country;
            $user->about= $details->about;

            $user->save();

            return redirect('/tutors');
            //dd($user);
         }
        
       
     }


     public function upload(Request $request){

       
       //dd($request);
       //return $request->file('image');
       if($request->setas=="coverpics")
        {


               

                if($request->hasFile('image'))
                {
                //I saved this guy and then used storage to grab his email 
                $request->file('image');
                $request->image->storeAs('public',\Auth::User()->email.'coverpics.'.$request->image->extension());
                $imageurl= Storage::url(\Auth::User()->email.'coverpics.'.$request->image->extension());

                     $user=\Auth::user();
                     $user->coverpicture=$imageurl;               
                     $user->save();

               

                }
         }
      elseif($request->setas=="profilepics")
      {

        if($request->hasFile('image'))
        {
        //I saved this guy and then used storage to grab his email 
        $request->file('image');
        $request->image->storeAs('public',\Auth::User()->email.'profilepics.'.$request->image->extension());
        $imageurl= Storage::url(\Auth::User()->email.'profilepics.'.$request->image->extension());

             $user=\Auth::user();
             $user->passport=$imageurl;               
             $user->save();

       

        }

      }
         
        // return $request->image->extension();
         //return $request->image->path()
         //return $request->image->store("image");

         //aliter

         //return Storage::putfile('public',$request->file('image'));
         //to use the above add use illuminate\support\Facades\Storage
      

    }

    public function details($id)
        {
            //dd($id);
            $tutor=DB::table('users')->where('email',$id)->first();
            $comments=DB::table('comments')->where('commented_for',$id)->get();
        //dd($tutor);
//dd($comments);
            return view('Tutors.details',compact('tutor','comments'));
        }
    

}
