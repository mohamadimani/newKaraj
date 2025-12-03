<?php

use App\Http\Controllers\Admin\PanelController;
use App\Http\Controllers\Admin\SecretaryController;
use App\Http\Controllers\Auth\AuthLoginController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\PhoneController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ClassRoomController;
use App\Http\Controllers\Admin\ClerkController;
use App\Http\Controllers\Admin\ClueController;
use App\Http\Controllers\Admin\CourseCancelController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CoursePaymentController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\CourseRegisterController;
use App\Http\Controllers\Admin\CourseReserveController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\FamiliarityWayController;
use App\Http\Controllers\Admin\FollowUpController;
use App\Http\Controllers\Admin\GoodsController;
use App\Http\Controllers\Admin\GroupDescriptionController;
use App\Http\Controllers\Admin\MarketingSms\MarketingSmsItemController;
use App\Http\Controllers\Admin\MarketingSms\MarketingSmsTemplateController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\ProfessionController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TechnicalAddressController;
use App\Http\Controllers\Admin\TechnicalController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\OnlineCourseController;
use App\Http\Controllers\Admin\OnlineCourseGroupController;
use App\Http\Controllers\Admin\OnlineCourseBasketController;
use App\Http\Controllers\Admin\OnlineCourseOrderController;
use App\Http\Controllers\Admin\OnlineCoursePaymentController;
use App\Http\Controllers\Admin\OnlineCoursePercentageController;
use App\Http\Controllers\Admin\SendSmsController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SalesTeamController;
use App\Http\Controllers\Admin\SurveyController;
use App\Http\Controllers\HookController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\Users\CourseBasketController as UserCourseBasketController;
use App\Http\Controllers\Users\CourseController as UsersCourseController;
use App\Http\Controllers\Users\GiftController;
use App\Http\Controllers\Users\MyOnlineCoursesController;
use App\Http\Controllers\Users\OnlineCourseController as UsersOnlineCourseController;
use App\Http\Controllers\Users\OnlineCourseBasketController as UsersOnlineCourseBasketController;
use App\Http\Controllers\Users\OnlinePaymentController;
use App\Http\Controllers\Users\OnlinePaymentVerifyController;
use App\Http\Controllers\Users\OrderController as UsersOrderController;
use App\Http\Controllers\Users\CourseOrderController as UsersCourseOrderController;
use App\Http\Controllers\Users\CoursePaymentVerifyController;
use App\Http\Controllers\Users\DocumentController;
use App\Http\Controllers\Users\ExamController as UsersExamController;
use App\Http\Controllers\Users\ResumeController;
use App\Http\Controllers\Users\UserController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckUser;

Route::get('/', function () {
    return redirect(route('dashboard'));
});

Route::prefix('auth')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthLoginController::class, 'loginView'])->name('login');
        Route::get('verify-code', [AuthLoginController::class, 'verifyCodeView'])->name('auth.verify-code');

        Route::middleware('throttle:50')->group(function () {
            Route::post('send-verification-code', [AuthLoginController::class, 'sendVerificationCode'])->name('auth.send-verification-code');
            Route::post('check-verification-code', [AuthLoginController::class, 'checkVerificationCode'])->name('auth.check-verification-code');
        });
    });

    Route::get('logout', [AuthLoginController::class, 'logout'])
        ->middleware('throttle:10')
        ->name('auth.logout');

    Route::get('login-by-user-id/{userId}', [AuthLoginController::class, 'loginByUserId'])->name('auth.login-by-user-id');
});

Route::middleware(['auth', CheckAdmin::class])->prefix('admin')->group(function () {

    Route::prefix('hook')->group(function () {
        Route::get('/addBranchIdInCourseSession', [HookController::class, 'addBranchIdInCourseSession'])->name('hook.addBranchIdInCourseSession');
        Route::get('/addFidarAiIdInOnlinePaymentCreateByUser', [HookController::class, 'addFidarAiIdInOnlinePaymentCreateByUser'])->name('hook.addFidarAiIdInOnlinePaymentCreateByUser');
        Route::get('/addFidarAiIdInCoursePaymentCreateByUser', [HookController::class, 'addFidarAiIdInCoursePaymentCreateByUser'])->name('hook.addFidarAiIdInCoursePaymentCreateByUser');
        Route::get('/addStudentAccountForOnlineCourseUsers', [HookController::class, 'addStudentAccountForOnlineCourseUsers'])->name('hook.addStudentAccountForOnlineCourseUsers');
    });

    Route::get('/', [PanelController::class, 'index'])->name('dashboard');

    Route::get('phones', [PhoneController::class, 'index'])->name('admin.phones.index');

    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('admin.permissions.index');
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('admin.roles.index');
    });

    Route::prefix('secretaries')->group(function () {
        Route::get('/', [SecretaryController::class, 'index'])->name('secretaries.index');
        Route::get('create', [SecretaryController::class, 'create'])->name('secretaries.create');
        Route::post('store', [SecretaryController::class, 'store'])->name('secretaries.store');
        Route::delete('{secretary}', [SecretaryController::class, 'destroy'])->name('secretaries.destroy');
        Route::get('{secretary}/edit', [SecretaryController::class, 'edit'])->name('secretaries.edit');
        Route::put('{secretary}', [SecretaryController::class, 'update'])->name('secretaries.update');
    });

    Route::prefix('branches')->group(function () {
        Route::get('/', [BranchController::class, 'index'])->name('admin.branches.index');
        Route::get('create', [BranchController::class, 'create'])->name('admin.branches.create');
        Route::get('{branch}/edit', [BranchController::class, 'edit'])->name('admin.branches.edit');
        Route::put('{branch}', [BranchController::class, 'update'])->name('admin.branches.update');
        Route::post('/', [BranchController::class, 'store'])->name('admin.branches.store');
    });

    Route::prefix('class-rooms')->group(function () {
        Route::get('/', [ClassRoomController::class, 'index'])->name('class-rooms.index');
        Route::get('create', [ClassRoomController::class, 'create'])->name('class-rooms.create');
        Route::get('{classRoom}/edit', [ClassRoomController::class, 'edit'])->name('class-rooms.edit');
        Route::put('{classRoom}', [ClassRoomController::class, 'update'])->name('class-rooms.update');
        Route::post('/', [ClassRoomController::class, 'store'])->name('class-rooms.store');
    });

    Route::prefix('teachers')->group(function () {
        Route::get('/', [TeacherController::class, 'index'])->name('teachers.index');
        Route::get('create', [TeacherController::class, 'create'])->name('teachers.create');
        Route::post('/', [TeacherController::class, 'store'])->name('teachers.store');
        Route::get('{teacher}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
        Route::put('{teacher}', [TeacherController::class, 'update'])->name('teachers.update');
        Route::delete('{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');
    });

    Route::prefix('professions')->group(function () {
        Route::get('/', [ProfessionController::class, 'index'])->name('professions.index');
        Route::get('create', [ProfessionController::class, 'create'])->name('professions.create');
        Route::post('/', [ProfessionController::class, 'store'])->name('professions.store');
        Route::get('{profession}/edit', [ProfessionController::class, 'edit'])->name('professions.edit');
        Route::put('{profession}', [ProfessionController::class, 'update'])->name('professions.update');
    });

    Route::prefix('courses')->group(function () {
        Route::get('/', [CourseController::class, 'index'])->name('courses.index');
        Route::get('create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/', [CourseController::class, 'store'])->name('courses.store');
        Route::get('{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('{course}', [CourseController::class, 'update'])->name('courses.update');
        Route::get('{course}/students', [CourseController::class, 'courseStudents'])->name('courses.course-students');
    });

    Route::prefix('clues')->group(function () {
        Route::get('/', [ClueController::class, 'index'])->name('clues.index');
        Route::get('create', [ClueController::class, 'create'])->name('clues.create');
        Route::post('/', [ClueController::class, 'store'])->name('clues.store');
        Route::get('{clue}/edit', [ClueController::class, 'edit'])->name('clues.edit');
        Route::put('{clue}', [ClueController::class, 'update'])->name('clues.update');
    });

    Route::prefix('familiarity-ways')->group(function () {
        Route::get('/', [FamiliarityWayController::class, 'index'])->name('familiarity-ways.index');
    });

    Route::prefix('payments')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('payments.index');
        Route::post('/', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('/refund', [PaymentController::class, 'refund'])->name('payments.refund');
    });

    Route::prefix('refunds')->group(function () {
        Route::get('/', [RefundController::class, 'index'])->name('refund.index');
    });

    Route::prefix('follow-ups')->group(function () {
        Route::get('/', [FollowUpController::class, 'index'])->name('follow-ups.index');
        Route::get('create', [FollowUpController::class, 'create'])->name('follow-ups.create');
        Route::post('/', [FollowUpController::class, 'store'])->name('follow-ups.store');
    });

    Route::prefix('course-registers')->group(function () {
        Route::get('/', [CourseRegisterController::class, 'index'])->name('course-registers.index');
        Route::get('create', [CourseRegisterController::class, 'create'])->name('course-registers.create');
        Route::post('/', [CourseRegisterController::class, 'store'])->name('course-registers.store');
        Route::get('{courseRegister}/edit', [CourseRegisterController::class, 'edit'])->name('course-registers.edit');
        Route::post('{courseRegister}', [CourseRegisterController::class, 'update'])->name('course-registers.update');
    });

    Route::prefix('course-cancels')->group(function () {
        Route::get('/', [CourseCancelController::class, 'index'])->name('course-cancels.index');
    });

    Route::prefix('payment-methods')->group(function () {
        Route::get('/', [PaymentMethodController::class, 'index'])->name('payment-methods.index');
    });

    Route::prefix('technicals')->group(function () {
        Route::get('/', [TechnicalController::class, 'index'])->name('technicals.index');
        Route::get('/introduced', [TechnicalController::class, 'introduced'])->name('technicals.introduced');
        Route::get('/addresses', [TechnicalAddressController::class, 'index'])->name('technicals.addresses.index');
    });

    Route::prefix('students')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('students.index');
        Route::get('{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('{student}', [StudentController::class, 'update'])->name('students.update');
        Route::post('{student}/upload-images', [StudentController::class, 'uploadImages'])->name('students.upload-images');
    });

    Route::prefix('clerks')->group(function () {
        Route::get('/', [ClerkController::class, 'index'])->name('clerks.index');
        Route::get('create', [ClerkController::class, 'create'])->name('clerks.create');
        Route::post('store', [ClerkController::class, 'store'])->name('clerks.store');
        Route::delete('{clerk}', [ClerkController::class, 'destroy'])->name('clerks.destroy');
        Route::get('{clerk}/edit', [ClerkController::class, 'edit'])->name('clerks.edit');
        Route::put('{clerk}', [ClerkController::class, 'update'])->name('clerks.update');
    });

    Route::prefix('discounts')->group(function () {
        Route::get('/', [DiscountController::class, 'index'])->name('discounts.index');
        Route::get('create', [DiscountController::class, 'create'])->name('discounts.create');
        Route::post('/', [DiscountController::class, 'store'])->name('discounts.store');
        Route::get('{discount}/edit', [DiscountController::class, 'edit'])->name('discounts.edit');
        Route::put('{discount}', [DiscountController::class, 'update'])->name('discounts.update');
    });

    Route::prefix('course-reserves')->group(function () {
        Route::get('/', [CourseReserveController::class, 'index'])->name('course-reserves.index');
        Route::get('create', [CourseReserveController::class, 'create'])->name('course-reserves.create');
        Route::post('/', [CourseReserveController::class, 'store'])->name('course-reserves.store');
        Route::post('convert-to-course', [CourseReserveController::class, 'convertToCourse'])->name('course-reserves.convert-to-course');
        Route::get('{courseReserve}/convert-to-course', [CourseReserveController::class, 'convertToCourseView'])->name('course-reserves.convert-to-course-view');
        Route::get('{courseReserve}/edit', [CourseReserveController::class, 'edit'])->name('course-reserves.edit');
        Route::put('{courseReserve}', [CourseReserveController::class, 'update'])->name('course-reserves.update');
    });

    Route::prefix('group-descriptions')->group(function () {
        Route::get('/', [GroupDescriptionController::class, 'index'])->name('group-descriptions.index');
        Route::get('create', [GroupDescriptionController::class, 'create'])->name('group-descriptions.create');
        Route::post('/', [GroupDescriptionController::class, 'store'])->name('group-descriptions.store');
        Route::get('{groupDescription}/edit', [GroupDescriptionController::class, 'edit'])->name('group-descriptions.edit');
        Route::put('{groupDescription}', [GroupDescriptionController::class, 'update'])->name('group-descriptions.update');
        Route::get('{groupDescription}/delete', [GroupDescriptionController::class, 'destroy'])->name('group-descriptions.destroy');
    });

    Route::prefix('marketing-sms-templates')->group(function () {
        Route::get('/', [MarketingSmsTemplateController::class, 'index'])->name('marketing-sms-templates.index');
        Route::get('create', [MarketingSmsTemplateController::class, 'create'])->name('marketing-sms-templates.create');
        Route::post('/', [MarketingSmsTemplateController::class, 'store'])->name('marketing-sms-templates.store');
        Route::put('{marketingSmsTemplate}', [MarketingSmsTemplateController::class, 'update'])->name('marketing-sms-templates.update');
        Route::get('{marketingSmsTemplate}/edit', [MarketingSmsTemplateController::class, 'edit'])->name('marketing-sms-templates.edit');
        Route::get('{marketingSmsTemplate}/settings', [MarketingSmsTemplateController::class, 'settings'])->name('marketing-sms-templates.settings');
    });

    Route::prefix('marketing-sms-items')->group(function () {
        Route::get('create/{marketingSmsTemplate}', [MarketingSmsItemController::class, 'create'])->name('marketing-sms-items.create');
        Route::post('/', [MarketingSmsItemController::class, 'store'])->name('marketing-sms-items.store');
        Route::get('{marketingSmsItem}/edit', [MarketingSmsItemController::class, 'edit'])->name('marketing-sms-items.edit');
        Route::put('{marketingSmsItem}', [MarketingSmsItemController::class, 'update'])->name('marketing-sms-items.update');
    });

    // Route::prefix('send-sms')->group(function () {
    //     Route::post('group', [SendSmsController::class, 'sendGroupSms'])->name('send-sms.group');
    //     Route::post('single', [SendSmsController::class, 'sendSingleSms'])->name('send-sms.single');
    // });

    Route::prefix('online-courses')->group(function () {
        Route::get('/', [OnlineCourseController::class, 'index'])->name('online-courses.index');
        Route::get('create', [OnlineCourseController::class, 'create'])->name('online-courses.create');
        Route::delete('{onlineCourse}/delete', [OnlineCourseController::class, 'destroy'])->name('online-courses.destroy');
        Route::post('/', [OnlineCourseController::class, 'store'])->name('online-courses.store');
        Route::put('{onlineCourse}', [OnlineCourseController::class, 'update'])->name('online-courses.update');
        Route::get('{onlineCourse}/edit', [OnlineCourseController::class, 'edit'])->name('online-courses.edit');

        Route::get('sms_marketing', [OnlineCourseController::class, 'smsMarketing'])->name('online-courses.sms_marketing');
    });

    Route::prefix('online-course-groups')->group(function () {
        Route::get('/', [OnlineCourseGroupController::class, 'index'])->name('online-course-groups.index');
        Route::get('create', [OnlineCourseGroupController::class, 'create'])->name('online-course-groups.create');
        Route::post('/', [OnlineCourseGroupController::class, 'store'])->name('online-course-groups.store');
        Route::delete('{onlineCourseGroup}', [OnlineCourseGroupController::class, 'destroy'])->name('online-course-groups.destroy');
    });

    Route::prefix('online-course-baskets')->group(function () {
        Route::get('/', [OnlineCourseBasketController::class, 'index'])->name('online-course-baskets.index');
        Route::get('{user}', [OnlineCourseBasketController::class, 'show'])->name('online-course-baskets.show');
        Route::post('{user}', [OnlineCourseBasketController::class, 'store'])->name('online-course-baskets.store');
        Route::delete('{onlineCourseBasket}', [OnlineCourseBasketController::class, 'destroy'])->name('online-course-baskets.destroy');
    });

    Route::prefix('online-course-orders')->group(function () {
        Route::get('/', [OnlineCourseOrderController::class, 'index'])->name('online-course-orders.index');
        Route::get('registers', [OnlineCourseOrderController::class, 'registers'])->name('online-course-orders.registers');
        Route::get('{user}', [OnlineCourseOrderController::class, 'store'])->name('online-course-orders.store');
        Route::get('{order}/show', [OnlineCourseOrderController::class, 'show'])->name('online-course-orders.show');
        Route::get('{order}/delete-item/{orderItem}', [OnlineCourseOrderController::class, 'deleteItem'])->name('online-course-orders.delete-item');
        Route::post('{order}/pay', [OnlineCourseOrderController::class, 'pay'])->name('online-course-orders.pay');
        Route::patch('{orderItem}/update-amount', [OnlineCourseOrderController::class, 'updateAmount'])->name('online-course-orders.update-amount');
        Route::get('{order}/checkout', [OnlineCourseOrderController::class, 'checkout'])->name('online-course-orders.checkout');
    });

    Route::prefix('online-course-payments')->group(function () {
        Route::get('/', [OnlineCoursePaymentController::class, 'index'])->name('online-course-payments.index');
    });

    Route::prefix('online-course-percentages')->group(function () {
        Route::get('/', [OnlineCoursePercentageController::class, 'index'])->name('online-course-percentages.index');
    });

    Route::prefix('whatsapp')->group(function () {
        // Route::get('/', [WhatsAppController::class, 'index'])->name('whatsapp.index');
    });

    Route::prefix('goods')->group(function () {
        Route::get('/', [GoodsController::class, 'index'])->name('goods.index');
        Route::get('create', [GoodsController::class, 'create'])->name('goods.create');
        Route::post('/', [GoodsController::class, 'store'])->name('goods.store');
        Route::post('{goods}/reports', [GoodsController::class, 'reportsStore'])->name('goods.reports.store');
    });
    // policy done
    Route::prefix('sales-team')->group(function () {
        Route::get('/', [SalesTeamController::class, 'index'])->name('sales-team.index');
        Route::get('/create', [SalesTeamController::class, 'create'])->name('sales-team.create');
        Route::post('/', [SalesTeamController::class, 'store'])->name('sales-team.store');
        Route::get('{salesTeam}/edit', [SalesTeamController::class, 'edit'])->name('sales-team.edit');
        Route::patch('{salesTeam}', [SalesTeamController::class, 'update'])->name('sales-team.update');
    });

    Route::prefix('reports')->group(function () {
        // Route::get('/', [ReportController::class, 'index'])->name('reports.index');
        Route::get('payment-change-log', [ReportController::class, 'paymentChangeLog'])->name('reports.payment-change-log');
        Route::get('verification-code', [ReportController::class, 'verificationCode'])->name('reports.verification-code');
        Route::get('course-register-change-log', [ReportController::class, 'courseRegisterChangeLog'])->name('reports.course-register-change-log');
        Route::get('order-item-change-log', [ReportController::class, 'orderItemChangeLog'])->name('reports.order-item-change-log');
        Route::get('secretary-sales', [ReportController::class, 'secretarySales'])->name('reports.secretary-sales');
        Route::get('secretary-follows', [ReportController::class, 'secretaryFollows'])->name('reports.secretary-follows');
        Route::any('secretary-sales-data', [ReportController::class, 'secretarySalesChartData'])->name('reports.secretary-sales-data');
        Route::get('financial', [ReportController::class, 'financial'])->name('reports.financial');
        Route::get('send-sms-log', [ReportController::class, 'sendSmsLog'])->name('reports.send-sms-log');
    });

    Route::prefix('course-payments')->group(function () {
        Route::get('/', [CoursePaymentController::class, 'index'])->name('course-payments.index');
    });

    Route::prefix('survey')->group(function () {
        Route::get('/', [SurveyController::class, 'index'])->name('survey.index');
    });

    Route::prefix('exams')->group(function () {
        Route::get('/', [ExamController::class, 'index'])->name('exams.index');
        Route::get('/create', [ExamController::class, 'create'])->name('exams.create');
        Route::post('/', [ExamController::class, 'store'])->name('exams.store');
        Route::get('/{exam}/edit', [ExamController::class, 'edit'])->name('exams.edit');
        Route::put('/{exam}', [ExamController::class, 'update'])->name('exams.update');
        Route::get('/{exam}/question', [ExamController::class, 'question'])->name('exams.question');
    });
});


Route::middleware(['auth', CheckUser::class])->prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('user.profile.index');
    Route::post('/update-names', [UserController::class, 'updateNames'])->name('user.update-names');

    Route::prefix('wallet')->group(function () {
        Route::get('/', [UserController::class, 'wallet'])->name('user.wallet');
    });

    Route::prefix('reference')->group(function () {
        Route::get('/', [UserController::class, 'reference'])->name('user.reference');
    });

    Route::prefix('online-courses')->group(function () {
        Route::get('', [UsersOnlineCourseController::class, 'index'])->name('user.online-courses.index');
        Route::get('{onlineCourse}', [UsersOnlineCourseController::class, 'show'])->name('user.online-courses.show');
        Route::get('{onlineCourse}/register', [UsersOnlineCourseController::class, 'register'])->name('user.online-courses.register');
        Route::get('{onlineCourse}/add-to-cart', [UsersOnlineCourseController::class, 'addToCart'])->name('user.online-courses.add-to-cart');
    });

    Route::prefix('online-course-baskets')->group(function () {
        Route::get('/', [UsersOnlineCourseBasketController::class, 'index'])->name('user.online-course-baskets.index');
        Route::get('checkout', [UsersOnlineCourseBasketController::class, 'checkout'])->name('user.online-course-baskets.checkout');
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [UsersOrderController::class, 'index'])->name('user.orders.index');
        Route::get('{order}', [UsersOrderController::class, 'show'])->name('user.orders.show');
        Route::get('{order}/pay', [UsersOrderController::class, 'pay'])->name('user.orders.pay');
    });

    Route::prefix('courses')->group(function () {
        Route::get('/', [UsersCourseController::class, 'index'])->name('user.courses.index');
        Route::get('{course}', [UsersCourseController::class, 'show'])->name('user.courses.show');
    });

    Route::prefix('course-baskets')->group(function () {
        Route::get('/', [UserCourseBasketController::class, 'index'])->name('user.course-baskets.index');
        Route::get('checkout', [UserCourseBasketController::class, 'checkout'])->name('user.course-baskets.checkout');
    });

    Route::prefix('course-orders')->group(function () {
        Route::get('{order}', [UsersCourseOrderController::class, 'show'])->name('user.course-orders.show');
        Route::get('{order}/pay', [UsersCourseOrderController::class, 'pay'])->name('user.course-orders.pay');
    });

    Route::prefix('payments')->group(function () {
        Route::get('/', [OnlinePaymentController::class, 'index'])->name('user.payments.index');
    });

    Route::prefix('my-online-courses')->group(function () {
        Route::get('/', [MyOnlineCoursesController::class, 'index'])->name('user.my-online-courses.index');
    });

    Route::prefix('gifts')->group(function () {
        Route::get('/', [GiftController::class, 'index'])->name('user.gifts.index');
    });

    Route::prefix('documents')->group(function () {
        Route::get('/course-license', [DocumentController::class, 'courseLicense'])->name('user.documents.course-license');
        Route::get('/course-license/{courseRegister}/show', [DocumentController::class, 'courseLicenseShow'])->name('user.documents.course-license-show');
        Route::get('/online-course-license/{orderItem}/show', [DocumentController::class, 'onlineCourseLicenseShow'])->name('user.documents.online-course-license-show');
        Route::get('/identity-upload', [DocumentController::class, 'identityUpload'])->name('user.documents.identity-upload');
        Route::post('/identity-store', [DocumentController::class, 'identityStore'])->name('user.documents.identity-store');
        Route::post('/storeSurvey/{courseRegister}', [DocumentController::class, 'storeSurvey'])->name('user.documents.storeSurvey');
    });

    Route::prefix('exams')->group(function () {
        Route::get('/', [UsersExamController::class, 'index'])->name('user.exams.index');
        Route::get('/{exam}/show/{courseRegister}', [UsersExamController::class, 'show'])->name('user.exams.show');
    });

    Route::prefix('resume')->group(function () {
        Route::get('/', [ResumeController::class, 'index'])->name('user.resume.index');
        Route::post('/upload_image', [ResumeController::class, 'upload_image'])->name('user.resume.upload_image');
        Route::get('/template', [ResumeController::class, 'template'])->name('user.resume.template');
    });
});

Route::any('users/orders/pay-verify', [OnlinePaymentVerifyController::class, 'index'])->name('user.orders.pay-verify');
Route::any('users/course-orders/pay-verify', [CoursePaymentVerifyController::class, 'index'])->name('user.course-orders.pay-verify');

Route::any('/', function () {
    if (isset($_GET['online_course_id'])) {
        session()->put('online_course_id', $_GET['online_course_id']);
    }
    if (isset($_GET['branch_id'])) {
        session()->put('branch_id', $_GET['branch_id']);
    }
    if (isset($_GET['course_id'])) {
        session()->put('course_id', $_GET['course_id']);
    }
    if (isset($_GET['license'])) {
        session()->put('license', $_GET['license']);
    }
    return redirect()->route('login');
});
