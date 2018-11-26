<?php

namespace App\Http\Controllers\Api;

use App\About;
use App\Ambassador;
use App\Contact;
use App\ContactLink;
use App\Continent;
use App\Country;
use App\ExternalLink;
use App\GuideBook;
use App\Http\Controllers\Controller;
use App\Intro;
use App\Offer;
use App\Place;
use App\PlaceRate;
use App\Post;
use App\Slider;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PublicController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (! $token = Auth::guard('api')->attempt($credentials)) {
            $message = parent::messages('credential_error');
            return $this->jsonResponse(401, $message);
        }

        $user = Auth::guard('api')->user();

        $user->last_seen = Carbon::now();
        $user->firebase = $request->firebase;
        $user->update();

        $auth = $this->respondWithToken($token);

        $data = array_merge($user->toArray(), $auth);

        $message = parent::messages('success_process');
        return $this->jsonResponse(200, $message, $data);
    }
    public function signup(Request $request)
    {
        $notvalid = parent::UserValidator($request);
        if ($notvalid) {
            return parent::jsonResponse(400, $notvalid['ar']);
        }

        $fileName = 'default.png';
        if ($request->hasfile('image')) {
            $destination = public_path('images/users');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);
            $request->file('image')->move($destination, $fileName);
        }

        $vc = $this->generateCode(4);

        $user = User::create([
            'fullname' => $request->fullname,
            'username' => $request->username,
            'email' => $request->email,
            'image' => $fileName,
            'phone' => $request->phone,
            'bio' => 'test',
            'password' => bcrypt($request->password),
            'firebase' => $request->firebase,
            'status' => 0,
            'code' => $vc,
            'last_seen' => Carbon::now(),
        ]);

        //$credentials = request([$user->username, $user->password]);
        //Auth::guard('api')->attempt($credentials)

        $token = Auth::guard('api')->login($user);

        $data = array_merge($user->toArray(), $this->respondWithToken($token));

        $message = parent::messages('success_signup');
        return $this->jsonResponse(200, $message, $data);
    }
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 3600
        ];
    }

    public function intro(Request $request)
    {
        $message = parent::messages('success_process');
        return $this->jsonResponse(200, $message, Intro::first());
    }
    public function aboutus(Request $request)
    {
        $message = parent::messages('success_process');
        return $this->jsonResponse(200, $message, About::first());
    }
    public function sliders(Request $request)
    {
        $message = parent::messages('success_process');
        $sliders = Slider::all()->take(3)->pluck('image');
        return $this->jsonResponse(200, $message, $sliders);
    }
    public function continents(Request $request)
    {
        $continents = Continent::all();
        foreach ($continents as $continent) {
            $continent['countries'] = Country::where('continent_id', $continent->id)->orderBy('order', 'ASC')->get();
        }
        $message = parent::messages('success_process');
        return $this->jsonResponse(200, $message, $continents);
    }
    public function countries(Request $request)
    {
        $continent_id = $request->continent_id;
        $continent = Continent::find($continent_id);
        if (!$continent) {
            $message = parent::messages('error_continent');
            return parent::jsonResponse(404, $message);
        }
        $firstFour = Country::where('continent_id', $continent_id)->take(4)->orderBy('order', 'ASC')->get();
        foreach ($firstFour as $item) {
            $item['places'] = $item->places;
            if (count($item['places'])) {
                foreach ($item['places'] as $place) {
                    $place['continent'] = $place->continent;
                    $place['country'] = $place->country;
                    $place['user'] = $place->user;
                    $place['images'] = $place->images->pluck('image');
                    $place['place_type'] = $place->place_type;
                    $place['rate'] = $place->avg_rates();

                    $users_interactions = array();
                    $allRates = PlaceRate::where('place_id', $place->id)->get();
                    if (count($allRates)) {
                        foreach ($allRates as $item) {
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
                }
            }
        }
        $countries = Country::where('continent_id', $continent_id)->whereNotIn('id', $firstFour->pluck('id')->toArray())->orderBy('order', 'ASC')->get();

        foreach ($countries as $item) {
            $item['places'] = $item->places;
            if (count($item['places'])) {
                foreach ($item['places'] as $place) {
                    $place['continent'] = $place->continent;
                    $place['country'] = $place->country;
                    $place['user'] = $place->user;
                    $place['images'] = $place->images->pluck('image');
                    $place['place_type'] = $place->place_type;
                    $place['rate'] = $place->avg_rates();

                    $users_interactions = array();
                    $allRates = PlaceRate::where('place_id', $place->id)->get();
                    if (count($allRates)) {
                        foreach ($allRates as $item) {
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
                }
            }
        }

        $data = [];
        $data['first_four'] = $firstFour;
        $data['countries'] = $countries;

        $message = parent::messages('success_process');
        return parent::jsonResponse(200, $message, $data);
    }
    public function searchCountry(Request $request)
    {
        $countryID = $request->country_id;

        $place = [];
        $country = Country::find($countryID);

        if ($country) {
            $country['continent'] = $country->continent;
            $country['places'] = $country->places;
            if (count($country['places'])) {
                foreach ($country['places'] as $place) {
                    //$place['user'] = $place->user;
                    $place['images'] = $place->images->pluck('image');
                    $place['place_type'] = $place->place_type;
                    $place['rate'] = $place->avg_rates();

                    $users_interactions = array();
                    $allRates = PlaceRate::where('place_id', $place->id)->get();
                    if (count($allRates)) {
                        foreach ($allRates as $item) {
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
                }
            }
        }

        $message = parent::messages('success_process');
        return parent::jsonResponse(200, $message, $country);
    }
    public function places(Request $request)
    {
        $country_id = $request->country_id;
        $country = Country::find($country_id);
        if (!$country) {
            $message = parent::messages('error_country');
            return parent::jsonResponse(404, $message);
        }
        $places = Place::where([
            'status' => 1,
            'country_id' => $country_id,
        ])->get();

        foreach ($places as $place) {
            unset($place['continent_id']);
            unset($place['country_id']);
            unset($place['address']);
            unset($place['description']);
            unset($place['status']);
            unset($place['booking_link']);
            unset($place['created_at']);
        }

        /*foreach($places as $place){
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
        }*/

        $message = parent::messages('success_process');
        return parent::jsonResponse(200, $message, $places);
    }
    public function searchPlace(Request $request)
    {
        $placeID = $request->place_id;
        $lat = $request->lat;
        $lon = $request->lon;
        $name = $request->name;

        $place = [];
        if ($placeID) {
            $place = Place::find($placeID);
        } elseif ($lat && $lon) {
            $place = Place::where([
                'lat' => $lat,
                'lon' => $lon,
            ])->first();
        } elseif ($name) {
            $place = Place::where('name', 'LIKE', '%'.$name.'%')->first();
        }

        if ($place) {
            //$place['continent'] = $place->continent;
            //$place['country'] = $place->country;
            $place['user'] = $place->user;
            $place['images'] = $place->images->pluck('image');
            $place['place_type'] = $place->place_type;
            $place['avg_rate'] = $place->avg_rates();

            $users_interactions = array();
            $allRates = PlaceRate::where('place_id', $place->id)->get();
            if (count($allRates)) {
                foreach ($allRates as $item) {
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
        }

        $message = parent::messages('success_process');
        return parent::jsonResponse(200, $message, $place);
    }
    public function sendContactUs(Request $request)
    {
        $notvalid = parent::ContactUsValidator($request);
        if ($notvalid) {
            return parent::jsonResponse(400, $notvalid['ar']);
        }

        $contactus = Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        $message = parent::messages('success_message_sent');
        return parent::jsonResponse(201, $message, $contactus);
    }
    public function contactLinks(Request $request)
    {
        $contactLinks = ContactLink::all();
        $message = parent::messages('success_process');
        return parent::jsonResponse(200, $message, $contactLinks);
    }
    public function guideBooks(Request $request)
    {
        $guideBooks = GuideBook::all();
        $message = parent::messages('success_process');
        return parent::jsonResponse(200, $message, $guideBooks);
    }
    public function offers(Request $request)
    {
        $offers = Offer::all();
        if (count($offers)) {
            foreach ($offers as $offer) {
                $offer['banners'] = $offer->banners;
            }
        }
        $message = parent::messages('success_process');
        return parent::jsonResponse(200, $message, $offers);
    }
    public function externalLinks(Request $request)
    {
        $externalLinks = ExternalLink::all();
        $message = parent::messages('success_process');
        return parent::jsonResponse(200, $message, $externalLinks);
    }
    public function ambassadors(Request $request)
    {
        $ambassadors = Ambassador::all();
        $message = parent::messages('success_process');
        return parent::jsonResponse(200, $message, $ambassadors);
    }
		public function testFCM(Request $request)
		{
				$uid = $request->user_id;
				$user = User::find($uid);
				if($user){
						$token = $user->firebase;
				}else{
						$token = $request->token;
				}
				$title = "FCM PUSH TITLE";
				$body = "How are you Hassan Ramdan Ali Radwan?";
				$type = "FCM";
				$sent = parent::FCMPush($token, $title, $body, $type);
				return $sent;
		}
}
