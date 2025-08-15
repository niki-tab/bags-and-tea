<?php

use App\Mail\TestEmail;
use App\Mail\OrderConfirmation;
use App\Events\NewOrderCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function () {

    Route::middleware(['auth:sanctum'])->group(function (){

        

    });

    Route::post('/users/generate-auth-token/{userId}', [Src\Authorization\Users\Infrastructure\PostGenerateAuthTokenByUserId::class, '__invoke']);

});


Route::group(['prefix' => 'blog'], function () {

    //Route::middleware(['auth:sanctum'])->group(function (){

        Route::get('/articles/all', [Src\Blog\Articles\Infrastructure\GetAllArticlesController::class, '__invoke']);
        Route::get('/articles/{articleId}', [Src\Blog\Articles\Infrastructure\GetArticleController::class, '__invoke']);
        Route::post('/articles/create-or-update', [Src\Blog\Articles\Infrastructure\PostCreateOrUpdateArticleController::class, '__invoke']);        

    //});

    

});

Route::get('/test-email', function (Request $request) {
    try {
        $email = $request->query('email', 'info@bagsandtea.com');
        $message = $request->query('message', 'This is a test email from Bags and Tea using SendGrid!');
        
        Mail::to($email)->send(new TestEmail($message));
        
        return response()->json([
            'success' => true,
            'message' => 'Test email sent successfully!',
            'sent_to' => $email,
            'email_message' => $message
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to send email: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('/test-order-confirmation', function (Request $request) {
    try {
        $email = $request->query('email', 'nicolas.tabares.tech@gmail.com');
        $orderNumber = $request->query('order', 'BT-2025-000006');
        $locale = $request->query('locale'); // Optional locale override
        
        Mail::to($email)->send(new OrderConfirmation($orderNumber, $locale));
        
        return response()->json([
            'success' => true,
            'message' => 'Order confirmation email sent successfully!',
            'sent_to' => $email,
            'order_number' => $orderNumber,
            'locale' => $locale ?: 'auto-detected'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to send order confirmation: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

Route::get('/test-new-order-event', function (Request $request) {
    try {
        $orderNumber = $request->query('order', 'BT-2025-000006');
        
        // Dispatch the NewOrderCreated event
        NewOrderCreated::dispatch($orderNumber);
        
        return response()->json([
            'success' => true,
            'message' => 'NewOrderCreated event dispatched successfully!',
            'order_number' => $orderNumber
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to dispatch NewOrderCreated event: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});
