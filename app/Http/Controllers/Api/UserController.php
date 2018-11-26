<?php

namespace App\Http\Controllers\Api;

use App\Country;
use App\Favourite;
use App\Http\Controllers\Controller;
use App\Place;
use App\PlaceImage;
use App\PlaceRate;
use App\PlaceType;
use App\Post;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api','jwt.auth','activated']);
    }

    public function refresh(Request $request)
    {
    	return Auth::refresh();
    	return $this->respondWithToken(Auth::refresh());
    }
    public function me()
    {
        return response()->json(Auth::user());
    }

    public function editProfile(Request $request)
    {
        $user = Auth::user();

        $phone = $request->has('phone')?$request->phone:$user->phone;
        $fullname = $request->has('fullname')?$request->fullname:$user->fullname;
        $username = $request->has('username')?$request->username:$user->username;
        $email = $request->has('email')?$request->email:$user->email;
        $password = $request->has('password')?$request->password:$user->password;
        $bio = $request->has('bio')?$request->bio:$user->bio;

        $request->merge([
            'id' => $user->id,
            'fullname' => $fullname,
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'phone' => $phone,
        ]);

        $notvalid = parent::UserValidator($request);
        if($notvalid){
            return parent::jsonResponse(400,$notvalid['ar']);
        }

        $fileName = $oldImage = $user->image;
        if($request->hasfile('image'))
        {
            $destination = public_path('images/users');
            $extension = $request->file('image')->getClientOriginalExtension();
            if(!in_array(strtolower($extension), ['jpg','jpeg','png'])){
                return parent::jsonResponse(400,parent::messages('error_image'));
            }
            $fileName = strtolower(rand(99999,99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);
            if($moved && ($oldImage != 'default.png')){
                if(file_exists($destination.'/'.$oldImage))
                    unlink($destination.'/'.$oldImage);
            }
        }

        $user->fullname = $fullname;
        $user->username = $username;
        $user->phone = $phone;
        $user->email = $email;
        $user->image = $fileName;
        $user->bio = $bio;
        $user->password = bcrypt($password);
        $user->last_seen = Carbon::now();

        $user->update();

        $message = parent::messages('success_edit_profile');
        return $this->jsonResponse(200,$message,$user);
    }
    
    public function placesTypes(Request $request)
    {
        $placesTypes = PlaceType::all();

        $message = parent::messages('success_process');
        return parent::jsonResponse(200,$message,$placesTypes);
    }

    public function addPlace(Request $request)
    {
        $user = Auth::user();
        
        $notvalid = parent::PlaceValidator($request);
        if($notvalid){
            return parent::jsonResponse(400,$notvalid['ar']);
        }

        $place_type_id = $request->place_type_id;
        $place_type = PlaceType::find($place_type_id);
        if(!$place_type){
            $message = parent::messages('error_place_type');
            return parent::jsonResponse(400,$message);
        }
        $country_id = $request->country_id;
        $country = Country::find($country_id);
        if(!$country){
            $message = parent::messages('error_country');
            return parent::jsonResponse(400,$message);
        }
        $exist = Place::where([
            'lat' => $request->lat,
            'lon' => $request->lon,
        ])->first();
        if($exist){
            $message = parent::messages('error_place_exist');
            return parent::jsonResponse(400,$message,$exist);
        }

        $place = Place::create([
            'user_id' => $user->id,
            'place_type_id' => $request->place_type_id,
            'continent_id' => $country->continent->id,
            'country_id' => $country_id,
            'name' => $request->name,
            'name_en' => '',
            'lat' => $request->lat,
            'lon' => $request->lon,
            'address' => $request->address,
            'description' => $request->description,
            'status' => 0,
            'booking_link' => $request->booking_link,
        ]);

        if($place){
            if($request->hasfile('images'))
            {
                $images = $request->file('images');
                $destination = public_path('images/places');
                foreach($images as $img ){
                    $ext = $img->getClientOriginalExtension();
                    if(in_array(strtolower($ext),['jpg','jpeg','png'])){
                        $imgname = strtolower(rand(99999,99999999).uniqid().'.'.$ext);
                        $moved = $img->move($destination,$imgname);
                        if($moved){
                            PlaceImage::create([
                                'place_id' => $place->id, 
                                'image' => $imgname,
                            ]);
                        }
                    }
                }
            }
        }

        $place['continent'] = $place->continent;
        $place['country'] = $place->country;
        $place['user'] = $place->user;
        $place['images'] = $place->images->pluck('image');
        $place['place_type'] = $place->place_type;
        $place['avg_rate'] = $place->avg_rates();

        $users_interactions = array();
        $allRates = PlaceRate::where('place_id',$place->id)->get();
        if(count($allRates)){
            foreach($allRates as $item){
                $info = [];
                $info['user'] = $item->user;
                $info['rate'] = $item->rate;
                $info['rate_at'] = $item->created_at;
                $info['posts'] = Post::where([
                    'user_id' => $item->user->id,
                    'place_id' => $place->id,
                ])->get();
                $users_interactions[] = $info;
            }
        }
        $place['users_interactions'] = $users_interactions;

        $message = parent::messages('success_add');
        return $this->jsonResponse(200,$message,$place);
    }

    public function addToFavourites(Request $request)
    {
        $user = Auth::user();
        $placeID = $request->place_id;
        $place = Place::find($placeID);
        if(!$place)
            return parent::jsonResponse(404,parent::messages('error_place'));

        $isFavourite = 0;

        $favourite = Favourite::where([
            'user_id' => $user->id,
            'place_id' => $placeID
        ])->first();

        if($favourite)
        {
            $favourite->delete();
        }
        else{
            Favourite::create([
                'user_id' => $user->id,
                'place_id' => $placeID,
            ]);
            $isFavourite = 1;
        }

        $users_interactions = array();
        $allRates = PlaceRate::where('place_id',$place->id)->get();
        if(count($allRates)){
            foreach($allRates as $item){
                $info = [];
                $info['user'] = $item->user;
                $info['rate'] = $item->rate;
                $info['rate_at'] = $item->created_at;
                $info['posts'] = Post::where([
                    'user_id' => $item->user->id,
                    'place_id' => $place->id,
                ])->get();
                $users_interactions[] = $info;
            }
        }
        $place['continent'] = $place->continent;
        $place['country'] = $place->country;
        $place['user'] = $place->user;
        $place['images'] = $place->images->pluck('image');
        $place['place_type'] = $place->place_type;
        $place['avg_rate'] = $place->avg_rates();
        $place['users_interactions'] = $users_interactions;
        $place['is_favourite'] = $isFavourite;
        
        $message = parent::messages('success_process');
        return parent::jsonResponse(200,$message,$place);
    }
    public function myFavouriteList(Request $request)
    {

        $user = Auth::user();
        
        $favourites = Favourite::where('user_id',$user->id)->get();
        $places = array();
        if(count($favourites)){
            foreach($favourites as $item){
                $place = Place::find($item->place_id);
                if($place)
                    $places[] = $place;
            } 
        }
        
        if(count($places)){
            foreach($places as $place){

                $users_interactions = array();
                $allRates = PlaceRate::where('place_id',$place->id)->get();
                if(count($allRates)){
                    foreach($allRates as $item){
                        $info = [];
                        $info['user'] = $item->user;
                        $info['rate'] = $item->rate;
                        $info['rate_at'] = $item->created_at;
                        $info['posts'] = Post::where([
                            'user_id' => $item->user->id,
                            'place_id' => $place->id,
                        ])->get();
                        $users_interactions[] = $info;
                    }
                }
                $place['continent'] = $place->continent;
                $place['country'] = $place->country;
                $place['user'] = $place->user;
                $place['images'] = $place->images->pluck('image');
                $place['place_type'] = $place->place_type;
                $place['avg_rate'] = $place->avg_rates();
                $place['users_interactions'] = $users_interactions;
                $place['is_favourite'] = $place->isFavourite();
            }
        }
        return parent::jsonResponse(200,parent::messages('success_process'),$places);
    }
    public function addPost(Request $request)
    {
        $user = Auth::user();

        $placeID = $request->place_id;
        $place = Place::find($placeID);
        if(!$place)
            return parent::jsonResponse(404,parent::messages('error_place'));
        if(!$request->has('content')){
            return parent::jsonResponse(400,parent::messages('error_post_content'));
        }
        if(strlen($request->content) < 10){
            return parent::jsonResponse(400,parent::messages('error_post_content_length'));
        }
        $post = Post::create([
            'user_id' => $user->id,
            'place_id'=> $placeID,
            'content'=> $request->content,
        ]);
        $post['user'] = $post->user;
        $post['place'] = $post->place;

        $post['place']['continent'] = $place->continent;
        $post['place']['country'] = $place->country;
        $post['place']['user'] = $place->user;
        $post['place']['images'] = $place->images->pluck('image');
        $post['place']['place_type'] = $place->place_type;
        $post['place']['avg_rate'] = $place->avg_rates();

        $users_interactions = array();
        $allRates = PlaceRate::where('place_id',$place->id)->get();
        if(count($allRates)){
            foreach($allRates as $item){
                $info = [];
                $info['user'] = $item->user;
                $info['rate'] = $item->rate;
                $info['rate_at'] = $item->created_at;
                $info['posts'] = Post::where([
                    'user_id' => $item->user->id,
                    'place_id' => $place->id,
                ])->get();
                $users_interactions[] = $info;
            }
        }
        $post['place']['users_interactions'] = $users_interactions;
        
        $post['place'] = $post->place;

        return parent::jsonResponse(200,parent::messages('success_add'),$post);

    }
    public function rate(Request $request)
    {
        $user = Auth::user();

        $placeID = $request->place_id;
        $place = Place::find($placeID);
        if(!$place)
            return parent::jsonResponse(404,parent::messages('error_place'));
        
        $rate = (float)$request->rate;

        if(!$rate || $rate > 5 || $rate < 0)
            return parent::jsonResponse(400,parent::messages('error_rate_value'));

        $exist = PlaceRate::where([
            'user_id' => $user->id,
            'place_id' => $placeID
        ])->first();
        if($exist){
            return parent::jsonResponse(400,parent::messages('error_rate_exist'));
        }
        PlaceRate::create([
            'user_id' => $user->id,
            'place_id' => $placeID,
            'rate' => $rate,
        ]);

        $placeRate = $place->avg_rates();
        $place['avg_rate'] = $placeRate;

        $users_interactions = array();
        $allRates = PlaceRate::where('place_id',$place->id)->get();
        if(count($allRates)){
            foreach($allRates as $item){
                $info = [];
                $info['user'] = $item->user;
                $info['rate'] = $item->rate;
                $info['rate_at'] = $item->created_at;
                $info['posts'] = Post::where([
                    'user_id' => $item->user->id,
                    'place_id' => $place->id,
                ])->get();
                $users_interactions[] = $info;
            }
        }
        $place['users_interactions'] = $users_interactions;

        return parent::jsonResponse(200,parent::messages('success_process'),$place);
    }
    public function notifications(Request $request)
    {
        $user = Auth::user();
        $notifications = $user->notifications;

        $message = parent::messages('success_process');
        return parent::jsonResponse(200,$message,$notifications);
    }
    ###########################################
    protected function respondWithToken($token)
    {
        return [
        	'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 3600
        ];
    }
    public function logout()
    {
        $user = Auth::user();
        $user->last_seen = Carbon::now();
        $user->update();

        Auth::logout();

        $message = 'تم تسجيل الخروج بنجاح';
        return $this->jsonResponse(200,$message);
    }
}