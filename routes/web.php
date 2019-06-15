<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/sign-up', 'Auth\RegisterController@showRegistrationForm');
Route::get('/suppliers-and-venues', 'VendorsController@index');
Route::get('/vendors/{vendor}', 'VendorsController@show');
Route::get('/couples/{couple}', 'CouplesController@show');

Route::get('/social-auth/{provider}/callback', 'Auth\SocialAuthController@handleProviderCallback');
Route::get('/social-auth/{provider}/{action}', 'Auth\SocialAuthController@redirectToProvider');

Route::patch('/users/{user}/account-update', 'UsersController@updateAccountType')->middleware('auth');

Route::middleware(['web', 'auth', 'activeAccount', 'verifiedAccountType'])->group(function () {
    Route::prefix('/dashboard')->group(function () {
        Route::get('/', 'DashboardController@index');
        Route::get('/job-posts/{jobPostId}/quotes', 'DashboardQuotesPerJobPostController@index');
        Route::get('/job-posts/{status?}', 'DashboardJobPostsController@index');
        Route::get('/sent-quotes', 'DashboardSentQuotesController@index');
        Route::get('/draft-quotes', 'DashboardDraftQuotesController@index');
        Route::get('/saved-jobs', 'SavedJobsController@index');
        Route::get('/calendar', 'CalendarController@index');
        Route::get('/todo-list', 'ToDoListController@index');
        Route::get('/payments', 'PaymentsController@index');
        Route::get('/reviews', 'DashboardReviewsController@index');
        Route::get('/favorite-vendors', 'FavoriteVendorsController@index');
        Route::get('/confirmed-bookings', 'ConfirmedBookingsController@index');
        Route::get('/budget-and-payments', 'DashboardBudgetAndPaymentsController@index');
        Route::get('/received-quotes', 'ReceivedQuotesController@index');
        Route::get('/notifications', 'NotificationsController@index');
        Route::resource('messages', 'MessagesController');
    });

    Route::resource('invoices', 'InvoicesController');

    Route::resource('users', 'UsersController');
    Route::resource('media', 'MediaController');
    Route::resource('payments', 'PaymentsController');
    Route::resource('job-posts', 'JobPostsController');
    Route::resource('job-quotes', 'JobQuotesController');
    Route::resource('saved-jobs', 'SavedJobsController');
    Route::resource('app-settings', 'AppSettingsController');
    Route::resource('favorite-vendors', 'FavoriteVendorsController');

    Route::patch('/vendors/{vendor}', 'VendorsController@update');
    Route::get('/vendors/{vendor}/edit', 'VendorsController@edit');

    Route::get('/invoice/{invoice}/pdf', 'PdfInvoiceGeneratorController@create');
    Route::post('onboarding', 'OnboardingController@store');

    Route::patch('/couples/{couple}', 'CouplesController@update');
    Route::get('/couples/{couple}/edit', 'CouplesController@edit');

    Route::post('credit-card-auth-token', 'CreditCardAuthTokenController@store');
    Route::patch('/job-quotes/{jobQuote}/response', 'JobQuoteResponseController@update');

    Route::patch('conversation/{id}/mark-as-read', 'MarkConversationAsReadController@update');

    Route::get('/news-letter-subscriptions/unsubscribe', 'NewsLetterSubscriptionsController@unsubscribe');

    Route::get('/settings', 'SettingsController@index');
    Route::get('/settings/couple/card-account', 'SettingsController@card_account_couple');
    Route::get('/settings/couple/notification', 'SettingsController@notification_couple');
    Route::get('/settings/couple/profile', 'SettingsController@profile_couple');
    Route::get('/settings/vendor/card-account', 'SettingsController@card_account_vendor');
    Route::get('/settings/vendor/notification', 'SettingsController@notification_vendor');
    Route::get('/settings/vendor/profile', 'SettingsController@profile_vendor');
    Route::get('/settings/vendor/terms-conditions', 'SettingsController@terms_vendor');
});

Route::get('faqs', 'FaqsController@index');
Route::get('blogs', 'BlogsController@index');
Route::get('blogs-alt', 'BlogsController@index2');
Route::get('blogs/single', 'BlogsController@single');
Route::get('about-us', 'AboutUsController@index');
Route::get('contact-us', 'ContactUsController@index');
Route::post('contact-us/send', 'ContactUsController@store');
Route::get('how-it-works', 'HowItWorksController@index');
Route::get('privacy-policy', 'PrivacyPolicyController@index');
Route::get('terms-and-conditions', 'TermsAndConditionsController@index');
Route::get('community-guidelines', 'CommunityGuidelinesController@index');
