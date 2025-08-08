<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CategoryPoliciyController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SocialNetworkController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\BadWordController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\BadWordCommentsController;
use App\Http\Controllers\MissionController;
use App\Http\Controllers\ValueController;

use App\Http\Controllers\SetUpController;
use App\Http\Controllers\articlController;
use App\Http\Controllers\categoryController;
use App\Http\Controllers\commentaireController;
use App\Http\Controllers\tagController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\tipController;
use App\Http\Controllers\RateQuestionController;
use App\Http\Controllers\rateController;
use App\Http\Controllers\profilController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::middleware('auth')->group(function () {
    Route::get('/user/dashboard', 'UserController@dashboard')->name('user.dashboard');
    // Add other user routes here
});
// Routes for administrators

    Route::get('/homeadmin', function () {
        return view('admin.dashboard');
    })->name('admin.home');
    Route::get('/admin/dashboard', 'AdminController@dashboard')->name('admin.dashboard');
    Route::post('/socialwork', [SocialNetworkController::class, 'addSocialwork'])->name('socialworks.add');
    Route::post('/socialworks/update/{id}', [SocialNetworkController::class, 'updateSocialwork'])->name('socialworks.update');
    Route::delete('/deletesocialworks/{id}', [SocialNetworkController::class, 'deleteSocialwork'])->name('socialworks.delete');




    Route::get('/contact_us', function () {
        return view('user.contactus');
    });
Route::get('/search-results', [SearchController::class, 'search'])->name('search');
    Route::get('editprofile', [App\Http\Controllers\User\HomeController::class, 'editUser'])->name('editprofileUser');
    Route::put('/updateprofile', [App\Http\Controllers\User\HomeController::class, 'update'])->name('updateProfil');
    Route::get('/Add_Question/{id}', [QuestionController::class, 'gotoaddQuestion'])->name('gotoaddQuestion');
    Route::get('/FilterMyQuestions/{id}', [QuestionController::class,'FilterMyQuestions'])->name('FilterMyQuestions');
    Route::get('/AnswerQ/{id}', [QuestionController::class, 'AnswerQuestion'])->name('allAnswersByQ');
    Route::delete('/delete-answer/{id}', [AnswerController::class, 'deleteAnswer']);
    Route::get('/edit-question/{id}', [QuestionController::class, 'editQuestion']);
    Route::put('/update-question/{id}', [QuestionController::class, 'updateQuestion']);
    Route::post('/addcomments', [CommentsController::class, 'add_Comments'])->name('add_Comments');
    Route::post('/add-answer/{question}/{id}', [QuestionController::class, 'saveAnswer']);
    Route::get('/question', [QuestionController::class, 'create']);
    Route::post('/add_Question/{id}', [QuestionController::class, 'add_Question']);
    Route::delete('/delete-question/{id}', [QuestionController::class, 'delete_Question']);
    Route::delete('/removeFriend/{userId}/{friendId}', [profilController::class, 'removeFriend'])->name('removeFriend');



Route::get('/', function () {
    return view('user.homeuser');
})->name('user.homeuser');



//Routes for regular users

    Route::get('about', [App\Http\Controllers\MissionController::class, 'showAbout']);

    Route::get('/allQuestions', [QuestionController::class, 'allQuestions'])->name('allQuestions');
    Route::get('/AllAnswersByQ/{id}', [QuestionController::class, 'showAnswersByQuestion']);
    Route::get('/filterQByCreatedAt', [QuestionController::class,'filterByCreatedAt'])->name('filterByCreatedAt');
    Route::get('/filterQByCount', [QuestionController::class,'filterByCount'])->name('filterByCount');
    Route::get('/filterQByunanswered', [QuestionController::class,'FilterQunanswered'])->name('FilterQunanswered');


    Route::get('/filterAnswersByCreatedAt/{id}', [AnswerController::class,'filterByCreatedAt'])->name('filterAnswersByCreatedAt');
    Route::get('/get-details-question/{id}', [QuestionController::class, 'getDetails']);
     Route::get('/getfaqs', [FaqController::class, 'listfeq'])->name('getfaqs');

   Route::get('/show_all_Answers/{question}', [QuestionController::class, 'show']);


    Route::get('/download-file/{id}', [QuestionController::class,'downloadFile']);
    Route::get('/download-filee/{id}', [AnswerController::class,'downloadFile']);




// Routes for administrators


    Route::get('/socialworks', [SocialNetworkController::class, 'getAllSocialworks'])->name('socialworks.getAll');

    Route::get('liste_user', [App\Http\Controllers\User\HomeController::class, 'show'])->name('liste_user');
    Route::get('liste_user2', [App\Http\Controllers\User\HomeController::class, 'show2'])->name('user_accounts');
    Route::get('liste_admins', [App\Http\Controllers\User\HomeController::class, 'show3'])->name('admin_accounts');

    Route::get('editprofileAdmin/{id}', [App\Http\Controllers\Auth\HomeController::class, 'editAdmin'])->name('editprofileAdmin');
    Route::put('/updateProfileAdmin/{id}', [\App\Http\Controllers\Auth\HomeController::class, 'updateProfileAdmin'])->name('admin.update');
    Route::get('/newsletter', [NewsletterController::class, 'CreateNewsletter'])->name('admin.CreateNewsletter');
    Route::post('admin/sendNewsletter', [NewsletterController::class, 'sendNotification'])->name('admin.sendNewsletter');

    Route::get('/showAllpolicies', [PolicyController::class, 'showCategoryWithPolicies'])->name('showAllpolicies');
    Route::post('/rate-answer', [RateQuestionController::class, 'rateAnswer'])->name('rateAnswer');

    Route::get('/all_QuestionsA', [QuestionController::class, 'all_QuestionsA'])->name('all_QuestionsA');
    Route::get('/filterQByCreatedAtA', [QuestionController::class,'filterByCreatedAtA'])->name('filterByCreatedAtA');
    Route::get('/filterQByCountA', [QuestionController::class,'filterByCountA'])->name('filterByCountA');
    Route::get('/filterQByunansweredA', [QuestionController::class,'filterQunansweredA'])->name('filterQunansweredA');
    Route::get('/comments', [CommentsController::class, 'all_Comments'])->name('comments');

    Route::get('/faqs', [FaqController::class, 'index'])->name('faqs');
    Route::get('/faqs/edit/{id}', [FaqController::class, 'edit']);
    Route::patch('/faqs/update/{id}', [FaqController::class, 'update']);
    Route::get('/faqs/add', [FaqController::class, 'add']);
    Route::post('/faqs/add', [FaqController::class, 'store']);
    Route::get('/faqs/delete/{id}', [FaqController::class, 'delete']);


    Route::get('/add-bad-wordcomment', [BadWordCommentsController::class, 'showAddFormComment']);
    Route::post('/add-bad-wordcomment', [BadWordCommentsController::class, 'addBadWordComment']);
    Route::delete('/delete-bad-wordcomment/{id}', [BadWordCommentsController::class, 'deleteBadWordComment']);
    Route::get('/comment', [CommentsController::class, 'create']);
    Route::post('/add_comment/{id}', [CommentsController::class, 'add_comment']);
    Route::delete('/delete-comment/{id}', [CommentsController::class, 'delete_comment']);

    Route::get('/allMission', [MissionController::class, 'showAddForm'])->name('missions');
    Route::post('/addMission', [MissionController::class, 'addMission']);
    Route::delete('/deleteMission/{id}', [MissionController::class, 'deleteMission']);
    Route::post('/updateMission/{id}', [MissionController::class, 'updateMission']);
    Route::get('/allValue', [ValueController::class, 'showAddForm'])->name('values');
    Route::post('/addValue', [ValueController::class, 'addValues']);
    Route::delete('/deleteValue/{id}', [ValueController::class, 'deleteValue']);
    Route::post('/updateValue/{id}', [ValueController::class, 'updateValue']);
    Route::get('/allSetUp', [SetUpController::class, 'showAddForm'])->name('setups');
    Route::post('/addSetUp', [SetUpController::class, 'addSetUp']);
    Route::delete('/deleteSetUp/{id}', [SetUpController::class, 'deleteSetUp']);
    Route::post('/updateSetUp/{id}', [SetUpController::class, 'updateSetUp']);

    Route::get('/add-bad-word', [BadWordController::class, 'showAddForm']);
    Route::post('/add-bad-word', [BadWordController::class, 'addBadWord']);
    Route::delete('/delete-bad-word/{id}', [BadWordController::class, 'deleteBadWord']);
    Route::get('/categories/create', [\App\Http\Controllers\CategoryPoliciyController::class, 'create']);
    Route::post('/categories/add', [CategoryPoliciyController::class, 'save']);
    Route::get('/categoriesPolicy', [CategoryPoliciyController::class, 'showAll'])->name('categoriesPolicy');
    Route::get('/categories/edit/{category}', [CategoryPoliciyController::class, 'edit']);
    Route::put('/categories/update/{category}', [CategoryPoliciyController::class, 'update']);
    Route::delete('/categories/delete/{category}', [CategoryPoliciyController::class, 'destroy']);
    Route::get('/categorie/details/{category}', [CategoryPoliciyController::class, 'details']);
    Route::get('/policies/create', [PolicyController::class, 'create']);
    Route::post('/policies/addByCat/{id}', [PolicyController::class, 'addByCat']);
    Route::get('/AllPoliciesByCat/{id}', [PolicyController::class, 'showByCategory']);
    Route::get('/policies/edit/{id}', [PolicyController::class, 'edit']);
    Route::put('/policies/update/{id}', [PolicyController::class, 'update']);
    Route::delete('/policies/delete/{id}', [PolicyController::class, 'destroy']);




    Route::post('/{userId}/dislike/{comId}', [CommentsController::class, 'dislike'])->name('dislike');
    Route::post('/{userId}/like/{comId}', [CommentsController::class, 'like'])->name('like');

// Password Reset Routes
Route::get('/forgot-password', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('/forgot-password', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('/reset-password/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('/reset-password', 'Auth\ResetPasswordController@reset')->name('password.update');
Route::resource('message', \App\Http\Controllers\ContactController::class);
Route::resource('user', \App\Http\Controllers\User\HomeController::class);





Auth::routes();

Route::get('auth/registerAdmin', [\App\Http\Controllers\User\HomeController::class, 'goregister'])->name('registerAdmin');
Route::post('auth/saveAdmin', [\App\Http\Controllers\User\HomeController::class, 'saveAdmin'])->name('SaveAdmin');

Route::get('auth/home',[AdminDashboardController::class, 'index'])->name('admin.homeadmin')->middleware('isAdmin');
Route::get('/', [App\Http\Controllers\User\HomeController::class, 'index'])->name('user.homeuser');


//moulouk


//







Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

//chaima

Route::resource('articls', articlController::class);
Route::resource('tips', tipController::class);
Route::resource('rates', rateController::class);
Route::resource('tags', tagController::class);
Route::resource('categories', categoryController::class);
Route::resource('likes', LikeController::class);
Route::resource('profiles', profilController::class);
Route::get('profiles/profile/{user_id}', [profilController::class,'show'])->name('user.profile');

Route::get('category/{category}/articls', [articlController::class,'index'])->name('articls.category');
Route::get('tags/{tag}', [articlController::class,'index'])->name('articls.tag');
Route::get('/Blog/Articles', [articlController::class, 'articles'])->name('blog.articls');
Route::get('/Blog/articles/{articl}', [articlController::class, 'show_user'])->name('blog.article');
Route::get('/Blog', [categoryController::class, 'nav'])->name('blog');
Route::get('/Blog/categories/{category}', [categoryController::class, 'show_user'])->name('blog.categories');
Route::get('/Blog/tips', [tipController::class, 'show_user'])->name('blog.tips');
Route::get('/Blog/tips/{category}', [tipController::class,'show_user'])->name('tips.category');
Route::post('/like/add/{ida}/{idu}', 'LikeController@addLike')->name('like.add');


Route::get('/Blog/articls/{category}', [articlController::class,'articles'])->name('art.category');
Route::get('/Blog/tips/discover/{tip}', [tipController::class, 'show'])->name('blog.tip');

Route::get('/Go_add_QuestionCat/{id}', [QuestionController::class, 'gotoaddQuestionCat'])->name('gotoaddQuestionCat');
Route::get('/add_QuestionCat/{id}', [QuestionController::class, 'add_QuestionCat'])->name('add_QuestionCat');
Route::get('/categories/questions/{id}', [QuestionController::class, 'allQuestionsCat'])->name('allQuestionsCat');
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

Route::get('/pdfarticle/{id}', [articlController::class, 'pdfarticle'])->name('pdfarticle');
Route::get('/save/{articl}', [articlController::class,'artprof'])->name('art.prof');
Route::get('/articles/tag/{tag}', [articlController::class, 'tags'])->name('art.tag');
