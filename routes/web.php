<?php


use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportProduct;
use App\Http\Controllers\UserController;
use App\Http\Controllers\admin\UsersController;
use App\Http\Controllers\admin\FanController;
use App\Http\Controllers\admin\RolesController;
use App\Http\Controllers\admin\PermissionsController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\PagesController;
use App\Http\Controllers\admin\MailController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\AttributeController;
use App\Http\Controllers\admin\AttributeValueController;
use App\Http\Controllers\admin\GiftCardController;
use App\Http\Controllers\admin\CouponController;
use App\Http\Controllers\admin\HomepageController;
use App\Http\Controllers\admin\TaxController;
use App\Http\Controllers\admin\CurrencyController;
use App\Http\Controllers\admin\ReviewController;
use App\Http\Controllers\admin\SettingsController;
use App\Http\Controllers\admin\MenuController;
use App\Http\Controllers\admin\ModelsController;
use App\Http\Controllers\admin\ModelOrientationController;
use App\Http\Controllers\admin\ModelCategoryController;
use App\Http\Controllers\admin\ModelEthnicityController;
use App\Http\Controllers\admin\ModelLanguageController;
use App\Http\Controllers\admin\ModelHairController;
use App\Http\Controllers\admin\ModelFetishesController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\VendorSettingController;
use App\Http\Controllers\admin\GeneralSettingController;
use App\Http\Controllers\admin\SupportCategoryController;
use App\Http\Controllers\admin\WithdrowController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\TestimonialsController;
use App\Http\Controllers\admin\BidController;
use App\Http\Controllers\admin\BlogCategoryController;
use App\Http\Controllers\admin\BlogController;
use App\Http\Controllers\admin\FaqController;
use App\Http\Controllers\admin\BlogTagsController;
use App\Http\Controllers\admin\NotificationsController;
use App\Http\Controllers\admin\PackageController;
use App\Http\Controllers\admin\SupportTicketsController;


Route::get('/',[App\Http\Controllers\frontend\FrontendController::class, 'index'])->name('main');


//login

Route::get('user-login',[App\Http\Controllers\HomeController::class, 'logs'])->name('user-login');
Route::get('sign-up',[App\Http\Controllers\HomeController::class, 'registeruser'])->name('sign-up');
Route::post('storeuser',[App\Http\Controllers\HomeController::class, 'storeuser'])->name('storeuser');
Route::get('my-account',[App\Http\Controllers\HomeController::class, 'userlogin'])->name('my-account');
Route::post('mainlogin',[App\Http\Controllers\HomeController::class, 'postlogin'])->name('mainlogin');

Auth::routes(['register' => true]);
// Auth::routes();

Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.', 'middleware' => ['auth']], function () {

  Route::get('/', 'HomeController@index')->name('home');

  Route::get('/', function () {
    return view('index');
  });

  
  Route::resource('users', UsersController::class);
  Route::resource('fans', FanController::class);

  //Roles
  Route::resource('roles', RolesController::class);

  //Permission
  Route::resource('permissions', PermissionsController::class);

  Route::get('country', [VendorSettingController::class, 'countrylist'])->name('country');

  Route::post('fetch-states', [VendorSettingController::class, 'fetchState']);

  Route::post('fetch-cities', [VendorSettingController::class, 'fetchCity']);

  Route::get('vendor-add', [VendorSettingController::class, 'vendoradd']);

  Route::post('vendor-added', [VendorSettingController::class, 'vendoradded']);

  //Product

  Route::resource('product', ProductController::class);

  Route::post('get-attr-value', [ProductController::class, 'getAtrValue'])->name('get-attr-value');

  Route::post('get-attr-value-single', [ProductController::class, 'getAtrValueSingleSelect'])->name('get-attr-value-single');



  //create variants

  Route::post('create-varient', [ProductController::class, 'createVarient'])->name('create-varient');

  Route::post('product-search', [CouponController::class, 'productSearch'])->name('product-search');

  Route::post('user-search', [CouponController::class, 'usersSearch'])->name('user-search');



  //Order

  Route::resource('order', OrderController::class);



  //Pages

  Route::resource('pages', PagesController::class);

  //Mail

  Route::resource('mail', MailController::class);

  //Models

  Route::resource('models', ModelsController::class);

  //Model-Orientation

  Route::resource('model-orientation', ModelOrientationController::class);

  //Model-Category

  Route::resource('model-category', ModelCategoryController::class);

  //Model-Ethnicity

  Route::resource('model-ethnicity', ModelEthnicityController::class);

  //Model-Language

  Route::resource('model-language', ModelLanguageController::class);

  //Model-Hair

  Route::resource('model-hair', ModelHairController::class);

  //Model-fetishes

  Route::resource('model-fetishes', ModelFetishesController::class);

  //Dashboard

  Route::resource('dashboard', DashboardController::class);



  //FAQ

  Route::resource('faq', FaqController::class);




  //Category

  Route::resource('category', CategoryController::class);

  Route::post('category-pagination', [App\Http\Controllers\admin\CategoryController::class, 'pagination'])->name('category-pagination');


  Route::resource('general-setting', GeneralSettingController::class)->name('*', 'general-setting');




  Route::resource('settings', SettingsController::class);


  //blogs category

  Route::resource('blog-category', BlogCategoryController::class);



  //blogs

  Route::resource('blogs', BlogController::class);


  //blog tags
  Route::resource('blog-tags', BlogTagsController::class);


  //Notifications
  Route::resource('notifications', NotificationsController::class);



  //Menus

  Route::resource('menus', MenuController::class);

  // Logs

  Route::get('add-to-log', [App\Http\Controllers\HomeController::class, 'myTestAddToLog'])->name('add-to-log');

  Route::get('logActivity', [App\Http\Controllers\HomeController::class, 'logActivity'])->name('logActivity');

  Route::delete('logsdelete/{id}', [App\Http\Controllers\HomeController::class, 'logsdelete'])->name('logsdelete');

  Route::get('user', [App\Http\Controllers\admin\UsersController::class, 'index2'])->name('user-index');

  Route::get('user-block/{id}', [App\Http\Controllers\admin\UsersController::class, 'blockUser'])->name('user-block');



  Route::get('get-category/{id}', [ProductController::class, 'getCategory'])->name('get-category');

  // User Packages
  Route::resource('packages', PackageController::class);
  // Route::get('packages/', [PackageController::class, 'index'])->name('packages.index');
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::get('/clear-cache', function () {
  Artisan::call('cache:clear');
  Artisan::call('optimize');
  Artisan::call('config:clear');
  Artisan::call('route:clear');
  Artisan::call('view:clear');
  Artisan::call('view:cache');
  Artisan::call('route:cache');
  return "Cache is cleared";
});
