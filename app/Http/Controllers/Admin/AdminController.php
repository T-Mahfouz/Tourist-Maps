<?php

namespace App\Http\Controllers\Admin;

use App\About;
use App\Admin;
use App\Ambassador;
use App\Comment;
use App\Contact;
use App\ContactLink;
use App\Continent;
use App\Country;
use App\ExternalLink;
use App\Favourite;
use App\GuideBook;
use App\Http\Controllers\Controller;
use App\Intro;
use App\Notification;
use App\Offer;
use App\OfferBanner;
use App\Place;
use App\PlaceImage;
use App\PlaceRate;
use App\PlaceType;
use App\Post;
use App\Slider;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class AdminController extends Controller
{
    public function __construct()
    {
        \App::setLocale('ar');
        Carbon::setLocale('ar');

        $this->middleware('auth:admin', [
            'except'=>['login','submitLogin']
        ]);
        $this->middleware('verified', [
            'except'=>['login','submitLogin']
        ]);
    }

    public function home(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $users = User::all();
        $admins = Admin::all();


        $countries = Country::all();
        $continents = Continent::all();
        $places = Place::all();


        return view('admin.home')->with([
            'admin' => $admin,
            'admins' => $admins,
            'continents' => $continents,
            'places' => $places,
            'users' => $users,
            'countries' => $countries
        ]);
    }

    ######## Admins
    public function admins(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $admins = new Admin;
        if ($request->has('key') && $request->key != null) {
            $admins = $admins->where('name', 'LIKE', '%'.$request->key.'%')
            ->orWhere('phone', 'LIKE', '%'.$request->key.'%')
            ->orWhere('email', 'LIKE', '%'.$request->key.'%');
        }
        $admins = $admins->paginate(10);

        return view('admin.admins')->with([
            'admins' => $admins,
        ]);
    }
    public function deleteAdmin(Request $request)
    {
        $me = Auth::guard('admin')->user();

        $adminID = $request->admin_id;
        $admin = Admin::find($adminID);

        if (!$admin || $adminID == $me->id) {
            return response()->json('غير موجود');
        }

        $admin->delete();

        return response()->json('تم الحذف بنجاح.');
    }
    public function editAdmin(Request $request)
    {
        $me = Auth::guard('admin')->user();

        $adminID = $request->admin_id;
        $admin = Admin::find($adminID);

        if (!$admin) {
            session()->flash('feedback', 'غير موجود');
            return redirect()->back();
        }
        if ($me->role != 2) {
            session()->flash('feedback', 'غير مصرح لك');
            return redirect()->back();
        }


        $name = $request->has('name')?$request->name:$admin->name;
        $email = $request->has('email')?$request->email:$admin->email;
        $phone = $request->has('phone')?$request->phone:$admin->phone;
        $password = ($request->has('password') && $request->password)?$request->password:$admin->password;

        $flagPassword = $request->password;

        $request->merge([
            'id' => $admin->id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
        ]);



        $notvalid = parent::AdminValidator($request);
        if ($notvalid) {
            session()->flash('feedback', $notvalid['ar']);
            return redirect()->back();
        }

        if ($flagPassword) {
            $password = bcrypt($flagPassword);
        }

        $fileName = $oldImage = $admin->image;
        if ($request->hasfile('image')) {
            $destination = public_path('images/users');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);
            if ($moved) {
                $file = $destination.'/'.$oldImage;
                if (file_exists($file)) {
                    if ($oldImage != 'default.png') {
                        unlink($file);
                    }
                }
            }
        }

        $admin->name = $name;
        $admin->email = $email;
        $admin->phone = $phone;
        $admin->password = $password;
        $admin->image = $fileName;
        $admin->update();

        session()->flash('feedback', 'تم التعديل بنجاح');
        return redirect()->back();
    }
    public function activateAdmin(Request $request)
    {
        $me = Auth::guard('admin')->user();

        if ($me->role != 2) {
            session()->flash('feedback', 'غير مصرح لك');
            return redirect()->back();
        }

        $adminID = $request->admin_id;
        $admin = Admin::find($adminID);
        if (!$admin) {
            session()->flash('feedback', 'المستخدم غير  موجود!!');
            return redirect()->back();
        }

        $status = 1;
        if ($user->status) {
            $status = 0;
        }
        $admin->status = $status;
        $admin->update();

        session()->flash('feedback', 'تم التعديل بنجاح.');

        return redirect()->back();
    }
    public function addAdmin(Request $request)
    {
        $me = Auth::guard('admin')->user();

        if ($me->role != 2) {
            session()->flash('feedback', 'غير موجود');
            return redirect()->back();
        }

        $notValid = parent::adminValidator($request);
        if ($notValid) {
            session()->flash('feedback', $notValid['ar']);
            return redirect()->back();
        }

        $name = $request->name;
        $role = 1;
        $email = $request->email;
        $phone = $request->phone;
        $status = 1;
        $password = bcrypt($request->password);


        $fileName = null;
        if ($request->hasfile('image')) {
            $destination = public_path('images/users');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $request->file('image')->move($destination, $fileName);
        }


        $admin = Admin::create([
            'name' => $name,
            'role' => $role,
            'email' => $email,
            'phone' => $phone,
            'status' => $status,
            'password' => $password,
            'image' => $fileName,
            'code' => null,
            'firebase' => null,
            'last_seen' => Carbon::now(),
        ]);

        session()->flash('feedback', 'تمت الإضافة بنجاح.');
        return redirect()->back();
    }
    public function editMe(Request $request)
    {
        $me = Auth::guard('admin')->user();

        $adminID = $request->admin_id;
        $admin = Admin::find($adminID);

        if (!$admin || $adminID != $me->id) {
            session()->flash('feedback', 'غير موجود');
            return redirect()->back();
        }


        $name = $request->has('name')?$request->name:$admin->name;
        $email = $request->has('email')?$request->email:$admin->email;
        $phone = $request->has('phone')?$request->phone:$admin->phone;
        $password = ($request->has('password') && $request->password)?$request->password:$admin->password;

        $flagPassword = $request->password;

        $request->merge([
            'id' => $admin->id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
        ]);



        $notvalid = parent::AdminValidator($request);
        if ($notvalid) {
            session()->flash('feedback', $notvalid['ar']);
            return redirect()->back();
        }

        if ($flagPassword) {
            $password = bcrypt($flagPassword);
        }

        $fileName = $oldImage = $admin->image;
        if ($request->hasfile('image')) {
            $destination = public_path('images/users');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);
            if ($moved) {
                $file = $destination.'/'.$oldImage;
                if (file_exists($file)) {
                    if ($oldImage != 'default.png') {
                        unlink($file);
                    }
                }
            }
        }

        $admin->name = $name;
        $admin->email = $email;
        $admin->phone = $phone;
        $admin->password = $password;
        $admin->image = $fileName;
        $admin->update();

        session()->flash('feedback', 'تم التعديل بنجاح');
        return redirect()->back();
    }
    ######## Users
    public function users(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $allusers = User::all();
        $users = new User;
        if ($request->has('key') && $request->key != null) {
            $users = $users->where('fullname', 'LIKE', '%'.$request->key.'%')
            ->orWhere('phone', 'LIKE', '%'.$request->key.'%')
            ->orWhere('email', 'LIKE', '%'.$request->key.'%');
        }

        $users = $users->paginate(10);
        foreach ($users as $user) {
            $user['posts'] = Post::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();
        }
        //return $categories;
        return view('admin.users')->with([
            'users' => $users,
            'allusers' => $allusers
        ]);
    }
    public function deleteUser(Request $request)
    {
        $me = Auth::guard('admin')->user();

        $userID = $request->user_id;
        $user = User::find($userID);

        if (!$user) {
            session()->flash('feedback', 'هذا المسنخدم غير موجود!!');
            return redirect()->back();
        }
        //$user->places->delete();
        Notification::where('user_id', $userID)->delete();
        Post::where('user_id', $userID)->delete();
        Favourite::where('user_id', $userID)->delete();
        PlaceRate::where('user_id', $userID)->delete();
        //Place::where('user_id',$userID)->delete();

        $user->delete();

        session()->flash('feedback', 'تم الحذف بنجاح.');
        return redirect()->back();
    }
    public function deleteUserPost(Request $request)
    {
        $postID = $request->post_id;
        $post = Post::find($postID);

        if (!$post) {
            return response()->json('غير موجود', 404);
        }
        $post->delete();

        return response()->json('تم الحذف', 200);
    }
    public function userPosts(Request $request)
    {
        $userID = $request->user_id;
        $user = User::find($userID);

        if (!$user) {
            $message = parent::messages('error_user');
            return parent::jsonResponse(404, $message);
        }
        $posts = Post::where('user_id', $userID)->get();

        $message = parent::messages('success_process');
        return parent::jsonResponse(200, $message, $posts);
    }
    public function activateUser(Request $request)
    {
        $me = Auth::guard('admin')->user();

        $userID = $request->user_id;
        $user = User::find($userID);
        if (!$user) {
            session()->flash('feedback', 'المستخدم غير  موجود!!');
            return redirect()->back();
        }

        $status = 1;
        if ($user->status) {
            $status = 0;
        }
        $user->status = $status;
        $user->update();

        session()->flash('feedback', 'تم التعديل بنجاح.');

        return redirect()->back();
    }
    public function editUser(Request $request)
    {
        $me = Auth::guard('admin')->user();
        $userID = $request->user_id;
        $user = User::find($userID);
        if (!$user) {
            session()->flash('feedback', 'غير موجود');
            return redirect()->back();
        }

        $phone = $request->has('phone')?$request->phone:$user->phone;
        $fullname = $request->has('fullname')?$request->fullname:$user->fullname;
        $username = $request->has('username')?$request->username:$user->username;
        $email = $request->has('email')?$request->email:$user->email;
        $password = ($request->has('password') && $request->password !=null)?$request->password:$user->password;
        $bio = $request->has('bio')?$request->bio:$user->bio;
        $flagPassword = $request->password;

        $request->merge([
            'id' => $user->id,
            'fullname' => $fullname,
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'phone' => $phone,
        ]);

        $notvalid = parent::UserValidator($request);
        if ($notvalid) {
            session()->flash('feedback', $notvalid['ar']);
            return redirect()->back();
        }

        if ($flagPassword) {
            $password = bcrypt($flagPassword);
        }

        $fileName = $oldImage = $user->image;
        if ($request->hasfile('image')) {
            $destination = public_path('images/users');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);
            if ($moved) {
                $file = $destination.'/'.$oldImage;
                if (file_exists($file)) {
                    if ($oldImage != 'default.png') {
                        unlink($file);
                    }
                }
            }
        }

        $user->fullname = $fullname;
        $user->email = $email;
        $user->phone = $phone;
        $user->image = $fileName;
        $user->username = $username;
        $user->password = $password;
        $user->update();

        session()->flash('feedback', 'تم التعديل بنجاح');
        return redirect()->back();
    }
    public function addUser(Request $request)
    {
        $notvalid = parent::UserValidator($request);
        if ($notvalid) {
            session()->flash('feedback', $notvalid['ar']);
            return redirect()->back();
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
            'firebase' => null,
            'status' => 0,
            'code' => $vc,
            'last_seen' => Carbon::now(),
        ]);


        session()->flash('feedback', 'تمت الإضافة بنجاح.');
        return redirect()->back();
    }
    public function sendNotifications(Request $request)
    {
        $title = $request->title;
        $message = $request->message;
        if (!$message) {
            session()->flash('feedback', 'يجب إدخال جميع الحقول');
            return redirect()->back();
        }
        $toUsers = $request->users;
        if ($request->all == 'on') {
            $toUsers = User::pluck('id')->toArray();
        }
        $users = array();
        if (count($toUsers)) {
            foreach ($toUsers as $item) {
                $user = User::find((int)$item);
                if ($user) {
                    $type = 'Realtime Notification';
                    $body = $message;
                    $tokens = $user->firebase;
                    if (!$title) {
                        $title = 'إشعار جديد';
                    }
                    parent::FCMPush($tokens, $title, $body, $type);

                    Notification::create([
                        'user_id' => $user->id,
                        'content' => $message,
                    ]);
                }
            }
        } else {
            session()->flash('feedback', 'يجب اختيار المستخدمين');
            return redirect()->back();
        }

        session()->flash('feedback', 'تم الإرسال بنجاح');
        return redirect()->back();
    }


    ######## Continents
    public function continents(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $continents = new Continent;

        $continents = $continents->paginate(10);

        foreach ($continents as $continent) {
            $places = 0;
            $countries_count = $continent->countries;
            $continent['countries_count'] = count($countries_count);
            if (count($countries_count)) {
                foreach ($countries_count as $country) {
                    $places += count($country->places);
                }
            }
            $continent['places_count'] = $places;
        }

        return view('admin.continents')->with([
            'continents' => $continents
        ]);
    }
    public function deleteContinent(Request $request)
    {
        $me = Auth::guard('admin')->user();

        $continentID = $request->continent_id;
        $continent = Continent::find($continentID);

        if (!$continent) {
            return response()->json('هذه القارة غير موجودة');
        }

        $countries = $continent->countries;
        if (count($countries)) {
            foreach ($countries as $country) {
                $places = $country->places;
                if (count($places)) {
                    foreach ($places as $place) {
                        $images = $place->images;
                        $rates = $place->rates;
                        if (count($images)) {
                            foreach ($images as $image) {
                                $image->delete();
                            }
                        }
                        if (count($rates)) {
                            foreach ($rates as $rate) {
                                $rate->delete();
                            }
                        }
                        $place->delete();
                    }
                }
            }
            $country->delete();
        }
        $continent->delete();

        return response()->json('تم الحذف');
    }
    public function editContinent(Request $request)
    {
        $continent_id = $request->continent_id;
        $continent = Continent::find($continent_id);
        $name = $request->name;
        $name_en = $request->name_en;

        if (!$name || !$continent) {
            session()->flash('feedback', 'يجب التأكد من صحة البيانات.');
            return redirect()->back();
        }

        $fileName = $continent->icon;
        if ($request->hasfile('image')) {
            $destination = public_path('images/continents');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $request->file('image')->move($destination, $fileName);
        }

        $continent->name = $name;
        $continent->name_en = $name_en;
        $continent->icon = $fileName;
        $continent->update();

        session()->flash('feedback', 'تم  التعديل بنجاح.');
        return redirect()->back();
    }
    public function addContinent(Request $request)
    {
        $name = $request->name;
        $name_en = $request->name_en;

        if (!$name) {
            session()->flash('feedback', 'يجب التأكد من صحة البيانات.');
            return redirect()->back();
        }
        $continent = Continent::where('name', $name)->first();
        if ($continent) {
            session()->flash('feedback', 'هذه القارة موجودة بالفعل');
            return redirect()->back();
        }

        $fileName = null;
        if ($request->hasfile('image')) {
            $destination = public_path('images/continents');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $request->file('image')->move($destination, $fileName);
        }

        $continent = Continent::create([
            'name' => $name,
            'name_en' => $name_en,
            'icon' => $fileName,
        ]);

        session()->flash('feedback', 'تمت الإضافة بنجاح.');
        return redirect()->back();
    }

    ######## Countries
    public function countries(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $continents = Continent::all();
        $countries = new Country;

        if ($request->has('continent') && ($request->continent != null && $request->continent != 0)) {
            $countries = $countries->where('continent_id', $request->continent);
        }
        if ($request->has('key') && $request->key != null) {
            $countries = $countries->where('name', 'LIKE', '%'.$request->key.'%');
        }

        $countries = $countries->orderBy('order', 'DESC');
        $countries = $countries->paginate(10);

        if ($request->has('page') && $request->page) {
            if ($request->key || $request->continent) {
                $countries = $countries->appends(['page' => $request->page]);
            }
        }
        if ($request->has('key') && $request->key) {
            $countries = $countries->appends(['key' => $request->key]);
        }
        if ($request->has('continent') && $request->continent) {
            $countries = $countries->appends(['continent' => $request->continent]);
        }

        foreach ($countries as $country) {
            $country['places_count'] = count($country->places);
        }

        return view('admin.countries')->with([
            'request' => $request,
            'continents' => $continents,
            'countries' => $countries,
            'page' => $request->page,
        ]);
    }
    public function deleteCountry(Request $request)
    {
        $me = Auth::guard('admin')->user();

        $countryID = $request->country_id;
        $country = Country::find($countryID);

        if (!$country) {
            return response()->json('هذه البلد غير موجودة.');
        }
        $places = $country->places->pluck('id');

        if (count($places)) {
            foreach ($places as $place) {
                $images = $place->images;
                $rates = $place->rates;
                if (count($images)) {
                    foreach ($images as $image) {
                        $image->delete();
                    }
                }
                if (count($rates)) {
                    foreach ($rates as $rate) {
                        $rate->delete();
                    }
                }
                $place->delete();
            }
        }
        $country->delete();

        return response()->json('تم الحذف');
    }
    public function editCountry(Request $request)
    {
        $country_id = $request->country_id;
        $country = Country::find($country_id);
        $name = $request->name;
        $continent = $request->continent;
        $order = $request->order;
        $description = $request->description;
        if ($order < 1 || $order > 1000) {
            session()->flash('feedback', 'يجب أن يكون الترتيب من 1 إلى 1000');
            return redirect()->back();
        }

        if (!$name || !$country || !$continent) {
            session()->flash('feedback', 'يجب التأكد من صحة البيانات.');
            return redirect()->back();
        }

        $fileName = $oldFlag = $country->flag;
        if ($request->hasfile('flag')) {
            $destination = public_path('images/flags');
            $extension = $request->file('flag')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('flag')->move($destination, $fileName);
            if ($moved) {
                $file = $destination.'/'.$oldFlag;
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }

        $imageName = $oldImage = $country->image;
        if ($request->hasfile('image')) {
            $destination = public_path('images/countries');
            $extension = $request->file('image')->getClientOriginalExtension();
            $imageName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $imageName);
        }

        $country->name = $name;
        $country->continent_id = $continent;
        $country->flag = $fileName;
        $country->image = $imageName;
        $country->order = $order;
        $country->description = $description;
        $country->update();

        session()->flash('feedback', 'تم  التعديل بنجاح.');
        return redirect()->back();
    }
    public function addCountry(Request $request)
    {
        $continentID = $request->continent_id;
        $continent = Continent::find($continentID);
        $name = $request->name;
        $order = $request->order;
        $description = $request->description;

        if ($order < 1 || $order > 1000) {
            session()->flash('feedback', 'يجب أن يكون الترتيب من 1 إلى 1000');
            return redirect()->back();
        }

        if (!$name || !$continent) {
            session()->flash('feedback', 'يجب التأكد من صحة البيانات.');
            return redirect()->back();
        }

        $fileName = 'eg.png';
        if ($request->hasfile('flag')) {
            $destination = public_path('images/flags');
            $extension = $request->file('flag')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('flag')->move($destination, $fileName);
        }

        $imageName = '';
        if ($request->hasfile('image')) {
            $destination = public_path('images/countries');
            $extension = $request->file('image')->getClientOriginalExtension();
            $imageName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $imageName);
        }

        $country = Country::create([
            'name' => $name,
            'continent_id' => $continentID,
            'flag' => $fileName,
            'image' => $imageName,
            'order' => $order,
            'description' => $description,
        ]);

        session()->flash('feedback', 'تمت الإضافة بنجاح');
        return redirect()->back();
    }

    ######## Places
    public function places(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $placesTypes = PlaceType::all();
        $countries = Country::all();
        $places = new Place;


        if ($request->has('key') && $request->key) {
            $places = $places->where('name', 'LIKE', '%'.$request->key.'%');
        }

        if ($request->has('status') && in_array($request->status, [0,1])) {
            $places = $places->where('status', $request->status);
        }

        if ($request->has('type') && $request->type) {
            $places = $places->where('place_type_id', $request->type);
        }

        if ($request->has('country') && $request->country) {
            $places = $places->where('country_id', $request->country);
        }


        $places = $places->paginate(10);

        if ($request->has('key') && $request->key) {
            $places = $places->appends(['key' => $request->key]);
        }
        if ($request->has('page') && $request->page) {
            $places = $places->appends(['page' => $request->page]);
        }

        if ($request->has('country') && $request->country) {
            $places = $places->appends(['country' => $request->country]);
        }

        foreach ($places as $place) {
            $place['images'] = $place->images->pluck('image')->toArray();
            $place['place_images'] = $place->images;
            $place['place_type'] = $place->place_type->name;
        }
        $notActive = Place::where('status', 0)->get();
        if (count($notActive)) {
            session()->flash('notActive', 'يوجد  '.count($notActive).' مكان  بانتظار التفعيل');
        }
        //return $places;
        return view('admin.places')->with([
            'admin' => $admin,
            'places' => $places,
            'page' => $request->page,
            'request' => $request,
            'placesTypes' => $placesTypes,
            'countries' => $countries,
        ]);
    }
    public function deletePlace(Request $request)
    {
        $me = Auth::guard('admin')->user();

        $placeID = $request->place_id;
        $place = Place::find($placeID);

        if (!$place) {
            return response()->json('هذا المكان غير موجود');
        }

        $images = $place->images;
        $rates = $place->rates;
        if (count($images)) {
            foreach ($images as $image) {
                $image->delete();
            }
        }
        if (count($rates)) {
            foreach ($rates as $rate) {
                $rate->delete();
            }
        }
        $place->delete();

        return response()->json('تم الحذف');
    }
    public function changePlaceStatus(Request $request)
    {
        $placeID = $request->place_id;
        $place = Place::find($placeID);
        if (!$place) {
            return response()->json('هذا المكان غير موجود');
        }

        $status = 1;
        if ($place->status) {
            $status = 0;
        }
        $place->status = $status;
        $place->update();

        return response()->json('تم التعديل بنجاح');
    }
    public function editPlace(Request $request)
    {
        $placeID = $request->place_id;
        $place = Place::find($placeID);
        if (!$place) {
            session()->flash('feedback', 'غير موجود');
            return redirect()->back();
        }

        $place_type_id = $request->has('place_type_id')?$request->place_type_id:$place->place_type_id;
        $country_id = $request->has('country_id')?$request->country_id:$place->country_id;
        $name = $request->has('name')?$request->name:$place->name;
        $lat = $request->has('lat')?$request->lat:$place->lat;
        $lon = $request->has('lon')?$request->lon:$place->lon;
        $address = $request->has('address')?$request->address:$place->address;
        $description = $request->has('description')?$request->description:$place->description;
        $booking_link = $request->has('booking_link')?$request->booking_link:$place->booking_link;

        $request->merge([
            'place_type_id' => $place_type_id,
            'country_id' => $country_id,
            'name' => $name,
            'lat' => $lat,
            'lon' => $lon,
            'address' => $address,
            'description' => $description,
            'booking_link' => $booking_link,
        ]);

        $notvalid = parent::PlaceValidator($request);
        if ($notvalid) {
            session()->flash('feedback', $notvalid['ar']);
            return redirect()->back();
        }

        $place_type_id = $request->place_type_id;
        $place_type = PlaceType::find($place_type_id);
        if (!$place_type) {
            $message = parent::messages('error_place_type');
            session()->flash('feedback', $message);
            return redirect()->back();
        }
        $country_id = $request->country_id;
        $country = Country::find($country_id);
        if (!$country) {
            $message = parent::messages('error_country');
            session()->flash('feedback', $message);
            return redirect()->back();
        }
        $continent_id = $country->continent_id;


        $place->place_type_id = $place_type_id;
        $place->country_id = $country_id;
        $place->continent_id = $continent_id;
        $place->name = $name;
        $place->lat = $lat;
        $place->lon = $lon;
        $place->address = $address;
        $place->description = $description;
        $place->booking_link = $booking_link;
        $place->update();

        if ($request->hasfile('images')) {
            $images = $request->file('images');
            $destination = public_path('images/places');
            foreach ($images as $img) {
                $ext = $img->getClientOriginalExtension();
                if (in_array(strtolower($ext), ['jpg','jpeg','png'])) {
                    $imgname = strtolower(rand(99999, 99999999).uniqid().'.'.$ext);
                    $moved = $img->move($destination, $imgname);
                    if ($moved) {
                        PlaceImage::create([
                            'place_id' => $place->id,
                            'image' => $imgname,
                        ]);
                    }
                }
            }
        }

        session()->flash('feedback', 'تم التعديل بنجاح');
        return redirect()->back();
    }
    public function addPlace(Request $request)
    {
        $latitude = $request->lat;
        $longitude = $request->lon;

        $locationError = "يجب إدخال الموقع الجغرافى للمكان مثال   45.85927,2.63360";
        if ($latitude < -360 || $latitude > 360) {
            session()->flash('feedback', $locationError);
            return redirect()->back()->withInput(Input::all());
        }
        if ($longitude < -360 || $longitude > 360) {
            session()->flash('feedback', $locationError);
            return redirect()->back()->withInput(Input::all());
        }

        $notvalid = parent::PlaceValidator($request);
        if ($notvalid) {
            session()->flash('feedback', $notvalid['ar']);
            return redirect()->back()->withInput(Input::all());
        }

        $place_type_id = $request->place_type_id;
        $place_type = PlaceType::find($place_type_id);
        if (!$place_type) {
            $message = parent::messages('error_place_type');
            session()->flash('feedback', $message);
            return redirect()->back()->withInput(Input::all());
        }
        $country_id = $request->country_id;
        $country = Country::find($country_id);
        if (!$country) {
            $message = parent::messages('error_country');
            session()->flash('feedback', $message);
            return redirect()->back()->withInput(Input::all());
        }
        $exist = Place::where([
            'lat' => $request->lat,
            'lon' => $request->lon,
        ])->first();
        if ($exist) {
            $message = parent::messages('error_place_exist');
            session()->flash('feedback', $message);
            return redirect()->back()->withInput(Input::all());
        }

        $place = Place::create([
            'user_id' => 0,
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

        if ($place) {
            if ($request->hasfile('images')) {
                $images = $request->file('images');
                $destination = public_path('images/places');
                foreach ($images as $img) {
                    $ext = $img->getClientOriginalExtension();
                    if (in_array(strtolower($ext), ['jpg','jpeg','png'])) {
                        $imgname = strtolower(rand(99999, 99999999).uniqid().'.'.$ext);
                        $moved = $img->move($destination, $imgname);
                        if ($moved) {
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

        session()->flash('feedback', 'تمت الإضافة بنجاح');
        return redirect()->back();
    }
    public function deletePlaceImage(Request $request)
    {
        $place_id = $request->place_id;
        $image = (String)$request->image;
        $placeImage = PlaceImage::where([
            'place_id' => $place_id,
            'image' => $image
        ])->first();


        if (!$placeImage) {
            return response()->json('غير موجود');
        }

        $placeImage->delete();

        return response()->json('تم الحذف');
    }

    ######## PlacesTypes
    public function placesTypes(Request $request)
    {
        $placestypes = PlaceType::all();

        return view('admin.placestypes')->with([
            'placestypes' => $placestypes
        ]);
    }
    public function editPlaceType(Request $request)
    {
        $placeType_id = $request->placetype_id;
        $placeType = PlaceType::find($placeType_id);

        $name = $request->name;

        if (!$name || !$placeType) {
            session()->flash('feedback', 'يجب التأكد من صحة البيانات.');
            return redirect()->back();
        }

        $fileName = $oldMarker = $placeType->marker;
        if ($request->hasfile('image')) {
            $destination = public_path('images/markers');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);
            if ($moved) {
                $file = $destination.'/'.$oldMarker;
                if (file_exists($file)) {
                    if ($oldMarker != 'defaultplace.png') {
                        unlink($file);
                    }
                }
            }
        }

        $placeType->name = $name;
        $placeType->marker = $fileName;
        $placeType->update();

        session()->flash('feedback', 'تم  التعديل بنجاح.');
        return redirect()->back();
    }
    public function addPlaceType(Request $request)
    {
        $placeType_id = $request->placetype_id;

        $name = $request->name;
        if (!$name) {
            session()->flash('feedback', 'يجب التأكد من صحة البيانات.');
            return redirect()->back();
        }

        $exist = PlaceType::where('name', $name)->first();
        if ($exist) {
            session()->flash('feedback', 'موجود بالفعل');
            return redirect()->back();
        }

        $fileName = 'defaultplace.png';
        if ($request->hasfile('image')) {
            $destination = public_path('images/markers');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);
        }

        PlaceType::create([
            'name' => $name,
            'marker' => $fileName,
        ]);

        session()->flash('feedback', 'تمت الإضافة بنجاح');
        return redirect()->back();
    }
    public function deletePlaceType(Request $request)
    {
        $placeTypeID = $request->placeType_id;
        $placeType = PlaceType::find($placeTypeID);

        if (!$placeType) {
            return response()->json('هغير موجود');
        }

        $placeType->delete();

        return response()->json('تم الحذف');
    }


    ######## About Us
    public function aboutus(Request $request)
    {
        $aboutus = About::first();

        return view('admin.aboutus')->with([
            'aboutus' => $aboutus
        ]);
    }
    public function editAboutus(Request $request)
    {
        $aboutus = About::first();
        $title = $request->title;
        $content = $request->content;
        if (!$title || !$content) {
            session()->flash('feedback', 'يجب إدخال جميع الحقول');
            return redirect()->back();
        }

        if (!$aboutus) {
            About::create([
                'title' => $title,
                'content' => $content,
            ]);
        } else {
            $aboutus->title = $title;
            $aboutus->content = $content;
            $aboutus->update();
        }



        session()->flash('feedback', 'تم التعدل بنجاح.');
        return redirect()->back();
    }

    ######## Intro
    public function intro(Request $request)
    {
        $intro = Intro::first();

        return view('admin.intro')->with([
            'intro' => $intro
        ]);
    }
    public function editIntro(Request $request)
    {
        $intro = Intro::first();
        $title = $request->title;
        $content = $request->content;
        if (!$title || !$content) {
            session()->flash('feedback', 'يجب إدخال جميع الحقول');
            return redirect()->back();
        }

        if (!$intro) {
            Intro::create([
                'title' => $title,
                'content' => $content,
            ]);
        } else {
            $intro->title = $title;
            $intro->content = $content;
            $intro->update();
        }



        session()->flash('feedback', 'تم التعدل بنجاح.');
        return redirect()->back();
    }

    ######## Sliders
    public function sliders(Request $request)
    {
        $sliders = Slider::all();

        return view('admin.sliders')->with([
            'sliders' => $sliders
        ]);
    }
    public function editSlider(Request $request)
    {
        $sliderID = $request->slider_id;
        $slider = Slider::find($sliderID);

        if (!$slider) {
            session()->flash('feedback', 'غير موجود');
            return redirect()->back();
        }

        $link = $request->link;
        if (!$link) {
            session()->flash('feedback', 'يجب إدخال جميع الحقول');
            return redirect()->back();
        }

        $fileName = $slider->image;
        if ($request->hasfile('image')) {
            $destination = public_path('images/sliders');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);
        }

        $slider->image = $fileName;
        $slider->link = $link;
        $slider->update();

        session()->flash('feedback', 'تم التعدل بنجاح.');
        return redirect()->back();
    }
    public function deleteSlider(Request $request)
    {
        $sliderID = $request->slider_id;
        $slider = Slider::find($sliderID);

        if (!$slider) {
            return response()->json('غير موجود');
        }

        $slider->delete();

        return response()->json('تم الحذف');
    }
    public function addSlider(Request $request)
    {
        $link = $request->link;
        if (!$link) {
            session()->flash('feedback', 'يجب إدخال جميع الحقول');
            return redirect()->back();
        }

        $fileName = '';
        if ($request->hasfile('image')) {
            $destination = public_path('images/sliders');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);

            if ($moved) {
                $slider = Slider::create([
                    'image' => $fileName,
                    'link' => $link
                ]);

                session()->flash('feedback', 'تمت الإاضافة بنجاح');
                return redirect()->back();
            }
        } else {
            session()->flash('feedback', 'حدث خطأ  ما أثناء الإضافة');
            return redirect()->back();
        }
    }


    ######## Contacts
    public function contactlinks(Request $request)
    {
        $contactLinks = ContactLink::all();

        return view('admin.contactLinks')->with([
            'contactLinks' => $contactLinks
        ]);
    }
    public function editContactLink(Request $request)
    {
        $contactLink_id = $request->contactLink_id;
        $contactLink = ContactLink::find($contactLink_id);

        $type = $request->type;
        $link = $request->link;

        if (!$contactLink) {
            session()->flash('feedback', 'غير  موجود');
            return redirect()->back();
        }
        if (!$type || !$link) {
            session()->flash('feedback', 'يجب التأكد من صحة البيانات.');
            return redirect()->back();
        }

        $fileName = $oldIcon = $contactLink->icon;
        if ($request->hasfile('image')) {
            $destination = public_path('images/contactlinks');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);
            if ($moved) {
                $file = $destination.'/'.$oldIcon;
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }

        $contactLink->type = $type;
        $contactLink->link = $link;
        $contactLink->icon = $fileName;
        $contactLink->update();

        session()->flash('feedback', 'تم  التعديل بنجاح.');
        return redirect()->back();
    }
    public function deleteContactLink(Request $request)
    {
        $contactLink_id = $request->contactLink_id;
        $contactLink = ContactLink::find($contactLink_id);

        if (!$contactLink) {
            return response()->json('غير  موجود');
        }

        $contactLink->delete();

        return response()->json('تم الحذف');
    }
    public function addContactLink(Request $request)
    {
        $type = $request->type;
        $link = $request->link;

        if (!$type || !$link) {
            session()->flash('feedback', 'يجب التأكد من صحة البيانات.');
            return redirect()->back();
        }

        $exist = ContactLink::where([
            'type' => $type,
            'link' => $link,
        ])->first();

        if ($exist) {
            session()->flash('feedback', 'موجود بالفعل');
            return redirect()->back();
        }

        $fileName = 'default.png';
        if ($request->hasfile('image')) {
            $destination = public_path('images/contactlinks');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);
        }

        $contactLink = ContactLink::create([
            'type' => $type,
            'link' => $link,
            'icon' => $fileName,
        ]);

        session()->flash('feedback', 'تمت الإضافة بنجاح');
        return redirect()->back();
    }


    ######## ExternalLink
    public function externalLinks(Request $request)
    {
        $externalLinks = ExternalLink::all();

        return view('admin.externalLinks')->with([
            'externalLinks' => $externalLinks
        ]);
    }
    public function editExternalLink(Request $request)
    {
        $externalLink_id = $request->externalLink_id;
        $externalLink = ExternalLink::find($externalLink_id);

        $title = $request->title;
        $content = $request->content;
        $link = $request->link;

        if (!$externalLink) {
            session()->flash('feedback', 'غير  موجود');
            return redirect()->back();
        }
        if (!$title || !$content || !$link) {
            session()->flash('feedback', 'يجب التأكد من صحة البيانات.');
            return redirect()->back();
        }

        $fileName = $oldImage = $externalLink->image;
        if ($request->hasfile('image')) {
            $destination = public_path('images/externallinks');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);
            if ($moved) {
                $file = $destination.'/'.$oldImage;
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }

        $externalLink->title = $title;
        $externalLink->content = $content;
        $externalLink->link = $link;
        $externalLink->image = $fileName;
        $externalLink->update();

        session()->flash('feedback', 'تم  التعديل بنجاح.');
        return redirect()->back();
    }
    public function deleteExternalLink(Request $request)
    {
        $externalLink_id = $request->externalLink_id;
        $externalLink = ExternalLink::find($externalLink_id);

        if (!$externalLink) {
            return response()->json('غير  موجود');
        }

        $externalLink->delete();

        return response()->json('تم الحذف');
    }
    public function addExternalLink(Request $request)
    {
        $title = $request->title;
        $content = $request->content;
        $link = $request->link;

        if (!$title || !$link || !$content) {
            session()->flash('feedback', 'يجب التأكد من صحة البيانات.');
            return redirect()->back();
        }

        $exist = ExternalLink::where([
            'title' => $title,
            'content' => $content,
            'link' => $link,
        ])->first();

        if ($exist) {
            session()->flash('feedback', 'موجود بالفعل');
            return redirect()->back();
        }

        $fileName = 'default.png';
        if ($request->hasfile('image')) {
            $destination = public_path('images/externallinks');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);
        } else {
            session()->flash('feedback', 'يجب إدخال صورة');
            return redirect()->back();
        }

        $externalLink = ExternalLink::create([
            'title' => $title,
            'content' => $content,
            'link' => $link,
            'image' => $fileName,
        ]);

        session()->flash('feedback', 'تمت الإضافة بنجاح');
        return redirect()->back();
    }


    ######## Ambassadors
    public function ambassadors(Request $request)
    {
        $ambassadors = Ambassador::all();

        return view('admin.ambassadors')->with([
            'ambassadors' => $ambassadors
        ]);
    }
    public function editAmbassador(Request $request)
    {
        $ambassador_id = $request->ambassador_id;
        $ambassador = Ambassador::find($ambassador_id);

        $title = $request->title;
        $link = $request->link;

        if (!$ambassador) {
            session()->flash('feedback', 'غير  موجود');
            return redirect()->back();
        }
        if (!$title || !$link) {
            session()->flash('feedback', 'يجب التأكد من صحة البيانات.');
            return redirect()->back();
        }

        $fileName = $oldImage = $ambassador->image;
        if ($request->hasfile('image')) {
            $destination = public_path('images/ambassadors');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);
            if ($moved) {
                $file = $destination.'/'.$oldImage;
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }

        $ambassador->title = $title;
        $ambassador->link = $link;
        $ambassador->image = $fileName;
        $ambassador->update();

        session()->flash('feedback', 'تم  التعديل بنجاح.');
        return redirect()->back();
    }
    public function deleteAmbassador(Request $request)
    {
        $ambassador_id = $request->ambassador_id;
        $ambassador = Ambassador::find($ambassador_id);

        if (!$ambassador) {
            return response()->json('غير  موجود');
        }

        $ambassador->delete();

        return response()->json('تم الحذف');
    }
    public function addAmbassador(Request $request)
    {
        $title = $request->title;
        $link = $request->link;

        if (!$title || !$link) {
            session()->flash('feedback', 'يجب التأكد من صحة البيانات.');
            return redirect()->back();
        }

        $exist = Ambassador::where([
            'title' => $title,
            'link' => $link,
        ])->first();

        if ($exist) {
            session()->flash('feedback', 'موجود بالفعل');
            return redirect()->back();
        }

        $fileName = 'default.png';
        if ($request->hasfile('image')) {
            $destination = public_path('images/ambassadors');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);
        } else {
            session()->flash('feedback', 'يجب إدخال صورة');
            return redirect()->back();
        }

        $externalLink = Ambassador::create([
            'title' => $title,
            'link' => $link,
            'image' => $fileName,
        ]);

        session()->flash('feedback', 'تمت الإضافة بنجاح');
        return redirect()->back();
    }


    ######## Offers
    public function offers(Request $request)
    {
        $offers = Offer::all();
        foreach ($offers as $offer) {
            $offer['banners'] = $offer->banners;
        }
        return view('admin.offers')->with([
            'offers' => $offers
        ]);
    }
    public function editOffer(Request $request)
    {
        $offer_id = $request->offer_id;
        $offer = Offer::find($offer_id);

        $title = $request->title;
        $content = $request->content;
        $link = $request->link;

        if (!$offer) {
            session()->flash('feedback', 'غير  موجود');
            return redirect()->back();
        }
        if (!$title || !$content || !$link) {
            session()->flash('feedback', 'يجب التأكد من صحة البيانات.');
            return redirect()->back();
        }

        $fileName = $oldImage = $offer->image;
        if ($request->hasfile('image')) {
            $destination = public_path('images/offers');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);
            if ($moved) {
                $file = $destination.'/'.$oldImage;
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }

        $offer->title = $title;
        $offer->content = $content;
        $offer->link = $link;
        $offer->image = $fileName;
        $offer->update();

        session()->flash('feedback', 'تم  التعديل بنجاح.');
        return redirect()->back();
    }
    public function deleteOffer(Request $request)
    {
        $offer_id = $request->offer_id;
        $offer = Offer::find($offer_id);

        if (!$offer) {
            return response()->json('غير  موجود');
        }

        $offer->delete();

        return response()->json('تم الحذف');
    }
    public function addOffer(Request $request)
    {
        $title = $request->title;
        $content = $request->content;
        $link = $request->link;

        if (!$title || !$link || !$content) {
            session()->flash('feedback', 'يجب التأكد من صحة البيانات.');
            return redirect()->back();
        }

        $exist = Offer::where([
            'title' => $title,
            'content' => $content,
            'link' => $link,
        ])->first();

        if ($exist) {
            session()->flash('feedback', 'موجود بالفعل');
            return redirect()->back();
        }

        $fileName = 'default.png';
        if ($request->hasfile('image')) {
            $destination = public_path('images/offers');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);
        } else {
            session()->flash('feedback', 'يجب إدخال صورة');
            return redirect()->back();
        }

        $offer = Offer::create([
            'title' => $title,
            'content' => $content,
            'link' => $link,
            'image' => $fileName,
        ]);

        session()->flash('feedback', 'تمت الإضافة بنجاح');
        return redirect()->back();
    }
    public function addBanner(Request $request)
    {
        $offerID = $request->offer_id;
        $description = $request->description;

        $offer = Offer::find($offerID);
        if (!$offer) {
            session()->flash('feedback', 'غير  موجود');
            return redirect()->back();
        }

        if (!$description) {
            session()->flash('feedback', 'يجب التأكد من صحة البيانات.');
            return redirect()->back();
        }

        $fileName = '';
        if ($request->hasfile('image')) {
            $destination = public_path('images/banners');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);
        } else {
            session()->flash('feedback', 'يجب إدخال صورة');
            return redirect()->back();
        }

        $banner = OfferBanner::create([
            'offer_id' => $offerID,
            'image' => $fileName,
            'description' => $description,
        ]);

        session()->flash('feedback', 'تمت الإضافة بنجاح');
        return redirect()->back();
    }
    public function deleteBanner(Request $request)
    {
        $offer_banner_id = $request->offer_banner_id;
        $offerBanner = OfferBanner::find($offer_banner_id);

        if (!$offerBanner) {
            return response()->json('غير  موجود');
        }

        $offerBanner->delete();

        return response()->json('تم الحذف');
    }
    public function editBanner(Request $request)
    {
        $banner_id = $request->banner_id;
        $banner = OfferBanner::find($banner_id);
        if (!$banner) {
            session()->flash('feedback', 'غير  موجود');
            return redirect()->back();
        }

        $description = $request->description;
        if (!$description) {
            session()->flash('feedback', 'يجب التأكد من صحة البيانات.');
            return redirect()->back();
        }

        $fileName = $oldImage = $banner->image;
        if ($request->hasfile('image')) {
            $destination = public_path('images/banners');
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $fileName);
        }

        $banner->image = $fileName;
        $banner->description = $description;
        $banner->update();

        session()->flash('feedback', 'تم  التعديل بنجاح.');
        return redirect()->back();
    }

    ######## Contact
    public function contactus(Request $request)
    {
        $contacts = Contact::paginate(10);

        return view('admin.contacts')->with([
            'contacts' => $contacts
        ]);
    }
    public function deleteContactus(Request $request)
    {
        $messages_id = $request->messages_id;
        $contact = Contact::find($messages_id);

        if (!$contact) {
            return response()->json('غير  موجود');
        }

        $contact->delete();

        return response()->json('تم الحذف');
    }



    ######## Guide Books
    public function guidebooks(Request $request)
    {
        $guidebooks = GuideBook::paginate(10);

        return view('admin.guidebooks')->with([
            'guidebooks' => $guidebooks
        ]);
    }
    public function addGuidebook(Request $request)
    {
        $title = $request->title;
        $content = $request->content;

        if (!$title || !$content) {
            session()->flash('feedback', 'يجب التأكد من صحة البيانات.');
            return redirect()->back();
        }

        $exist = GuideBook::where([
            'title' => $title,
            'content' => $content,
        ])->first();

        if ($exist) {
            session()->flash('feedback', 'موجود بالفعل');
            return redirect()->back();
        }

        $bookImage = '';
        if ($request->hasfile('image')) {
            $destination = public_path('images/books');
            $extension = $request->file('image')->getClientOriginalExtension();
            $bookImage = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $bookImage);
        } else {
            session()->flash('feedback', 'يجب إدخال صورة');
            return redirect()->back();
        }


        $fileName = '';
        if ($request->hasfile('book')) {
            $destination = public_path('books');
            $extension = $request->file('book')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('book')->move($destination, $fileName);
        } else {
            session()->flash('feedback', 'يجب التأكد من صحة البيانات.');
            return redirect()->back();
        }

        $guidebook = GuideBook::create([
            'title' => $title,
            'content' => $content,
            'path' => url('/books').'/'.$fileName,
            'image' => $bookImage,
        ]);

        session()->flash('feedback', 'تمت الإضافة بنجاح');
        return redirect()->back();
    }
    public function deleteGuidebook(Request $request)
    {
        $guidebook_id = $request->guidebook_id;
        $guidebook = GuideBook::find($guidebook_id);

        if (!$guidebook) {
            return response()->json('غير  موجود');
        }

        $guidebook->delete();

        return response()->json('تم الحذف');
    }
    public function editGuidebook(Request $request)
    {
        $guidebook_id = $request->guidebook_id;
        $guidebook = GuideBook::find($guidebook_id);

        $title = $request->title;
        $content = $request->content;

        if (!$guidebook) {
            session()->flash('feedback', 'غير  موجود');
            return redirect()->back();
        }
        if (!$title || !$content) {
            session()->flash('feedback', 'يجب التأكد من صحة البيانات.');
            return redirect()->back();
        }

        $bookImage = $oldBookImage = $guidebook->image;
        if ($request->hasfile('image')) {
            $destination = public_path('images/books');
            $extension = $request->file('image')->getClientOriginalExtension();
            $bookImage = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('image')->move($destination, $bookImage);
        }


        $fileName = $oldBook = $guidebook->path;
        if ($request->hasfile('book')) {
            $destination = public_path('books');
            $extension = $request->file('book')->getClientOriginalExtension();
            $fileName = strtolower(rand(99999, 99999999).uniqid().'.'.$extension);

            $moved = $request->file('book')->move($destination, $fileName);
            if ($moved) {
                if (file_exists($oldBook)) {
                    unlink($oldBook);
                }
            }
            $fileName = url('/books').'/'.$fileName;
        }

        $guidebook->title = $title;
        $guidebook->content = $content;
        $guidebook->path = $fileName;
        $guidebook->image = $bookImage;
        $guidebook->update();

        session()->flash('feedback', 'تم  التعديل بنجاح.');
        return redirect()->back();
    }



    public function login(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin-home');
        }

        return view('admin.login');
    }
    public function submitLogin(Request $request)
    {
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], true)) {
            $admin = Auth::guard('admin')->user();

            $admin->last_seen = Carbon::now();
            $admin->update();

            return redirect()->route('admin-home');
        }


        return redirect()->back()->withInput(Input::all());
    }

    public function logout(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $admin->last_seen = Carbon::now();
        $admin->update();

        Auth::guard('admin')->logout();
        return redirect()->route('admin-login');
    }
}
