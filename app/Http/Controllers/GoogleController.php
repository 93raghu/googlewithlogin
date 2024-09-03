<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle(){
		  return Socialite::driver('google')->redirect();
		 
	}
	
	public function handleGoogleCallback(){
		
		try{
		$user = Socialite::driver('google')->user();
		
		$finduser = User::where('google_id',$user->id)->first();
		if($finduser)
		{
			Auth::login($finduser);
			return redirect()->route('manager.home');
			//return redirect()->intended('home');
		}
		else
		{
			 $user = User::updateOrCreate([
				//'google_id' => $googleuser->id,
				'email' => $user->email,
				'type' => "2",
				
			], [
				'name' => $user->name,
				'profile_img' => $user->avatar,
				'google_id' => $user->id,
				'password' => encrypt('12345678'),
				'google_token' => $user->token,
				'expiresIn' => $user->expiresIn,
				'status' => "Active",
				
				//'google_token' => $googleUser->token,
				//'google_refresh_token' => $googleUser->refreshToken,
			]);
		 
			Auth::login($user);
			
		}
		return redirect()->route('manager.home');
		//return redirect()->intended('home');
		
		}
		
		catch(Exception $e)
		{
			dd($e->getMessage());
		}
	
	}
}
