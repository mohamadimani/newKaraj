<?php

namespace App\Constants;


class PermissionTitle
{
    // secretary
    public const ADMIN_SECRETARY = 'adminSecretary';
    public const INDEX_SECRETARY = 'indexSecretary';
    public const CREATE_SECRETARY = 'createSecretary';
    public const STORE_SECRETARY = 'storeSecretary';
    public const EDIT_SECRETARY = 'editSecretary';
    public const UPDATE_SECRETARY = 'updateSecretary';
    public const DELETE_SECRETARY = 'deleteSecretary';
    // clerk
    public const ADMIN_CLERK = 'adminClerk';
    public const MANAGER_CLERK = 'managerClerk';
    public const INDEX_CLERK = 'indexClerk';
    public const CREATE_CLERK = 'createClerk';
    public const STORE_CLERK = 'storeClerk';
    public const EDIT_CLERK = 'editClerk';
    public const UPDATE_CLERK = 'updateClerk';
    public const DELETE_CLERK = 'deleteClerk';
    // phone
    public const ADMIN_PHONE = 'adminPhone';
    public const INDEX_PHONE = 'indexPhone';
    // branches
    public const ADMIN_BRANCH = 'adminBranch';
    public const INDEX_BRANCH = 'indexBranch';
    public const CREATE_BRANCH = 'createBranch';
    public const STORE_BRANCH = 'storeBranch';
    public const EDIT_BRANCH = 'editBranch';
    public const UPDATE_BRANCH = 'updateBranch';
    // class rooms
    public const ADMIN_CLASS_ROOM = 'adminClassRoom';
    public const INDEX_CLASS_ROOM = 'indexClassRoom';
    public const CREATE_CLASS_ROOM = 'createClassRoom';
    public const STORE_CLASS_ROOM = 'storeClassRoom';
    public const EDIT_CLASS_ROOM = 'editClassRoom';
    public const UPDATE_CLASS_ROOM = 'updateClassRoom';
    public const DELETE_CLASS_ROOM = 'deleteClassRoom';
    // teachers
    public const ADMIN_TEACHER = 'adminTeacher';
    public const INDEX_TEACHER = 'indexTeacher';
    public const CREATE_TEACHER = 'createTeacher';
    public const STORE_TEACHER = 'storeTeacher';
    public const EDIT_TEACHER = 'editTeacher';
    public const UPDATE_TEACHER = 'updateTeacher';
    // professions
    public const ADMIN_PROFESSION = 'adminProfession';
    public const INDEX_PROFESSION = 'indexProfession';
    public const CREATE_PROFESSION = 'createProfession';
    public const STORE_PROFESSION = 'storeProfession';
    public const EDIT_PROFESSION = 'editProfession';
    public const UPDATE_PROFESSION = 'updateProfession';

    // permissions
    public const INDEX_PERMISSION = 'indexPermission';
    // roles
    public const INDEX_ROLE = 'indexRole';
    // courses
    public const ADMIN_COURSE = 'adminCourse';
    public const INDEX_COURSE = 'indexCourse';
    public const CREATE_COURSE = 'createCourse';
    public const STORE_COURSE = 'storeCourse';
    public const EDIT_COURSE = 'editCourse';
    public const UPDATE_COURSE = 'updateCourse';

    // clues
    public const ADMIN_CLUE = 'adminClue';
    public const INDEX_CLUE = 'indexClue';
    public const CREATE_CLUE = 'createClue';
    public const STORE_CLUE = 'storeClue';
    public const EDIT_CLUE = 'editClue';
    public const UPDATE_CLUE = 'updateClue';
    // familiarity ways
    public const INDEX_FAMILIARITY_WAY = 'indexFamiliarityWay';
    // payments
    public const ADMIN_PAYMENT = 'adminPayment';
    public const INDEX_PAYMENT = 'indexPayment';
    public const STORE_PAYMENT = 'storePayment';
    public const CHANGE_PAYMENT_AMOUNT = 'changePaymentAmount';
    public const VERIFY_PAYMENT = 'verifyPayment';
    public const REDO_VERIFY_PAYMENT = 'redoVerifyPayment';
    public const REJECT_PAYMENT = 'rejectPayment';
    public const REDO_REJECT_PAYMENT = 'redoRejectPayment';
    // refund
    public const REFUND_PAYMENT = 'refundPayment';
    public const REFUND_CONFIRM = 'refundConfirm';
    // course registers
    public const ADMIN_COURSE_REGISTER = 'adminCourseRegister';
    public const INDEX_COURSE_REGISTER = 'indexCourseRegister';
    public const CREATE_COURSE_REGISTER = 'createCourseRegister';
    public const STORE_COURSE_REGISTER = 'storeCourseRegister';
    public const CANCEL_COURSE_REGISTER = 'cancelCourseRegister';
    public const COURSE_REGISTER_WITHOUT_PAYMENT = 'courseRegisterWithoutPayment';
    public const COURSE_REGISTER_CREATE_PRICE_CAN_CHANGE = 'courseRegisterCreatePriceCanChange';
    public const COURSE_REGISTER_CHANGE_FOR_STUDENT = 'courseRegisterChangeForStudent';
    public const COURSE_REGISTER_TO_RESERVE = 'courseRegisterToReserve';

    // payment methods
    public const INDEX_PAYMENT_METHOD = 'indexPaymentMethod';
    // technicals
    public const ADMIN_TECHNICAL = 'adminTechnical';
    public const INDEX_TECHNICAL = 'indexTechnical';
    public const REGISTER_TECHNICAL = 'registerTechnical';
    // technical addresses
    public const INDEX_TECHNICAL_ADDRESS = 'indexTechnicalAddress';
    // students
    public const ADMIN_STUDENT = 'adminStudent';
    public const INDEX_STUDENT = 'indexStudent';
    public const EDIT_STUDENT = 'editStudent';
    public const UPDATE_STUDENT = 'updateStudent';
    public const UPLOAD_STUDENT_DOCUMENTS = 'uploadStudentDocuments';

    // discounts
    public const INDEX_DISCOUNT = 'indexDiscount';
    public const CREATE_DISCOUNT = 'createDiscount';
    public const STORE_DISCOUNT = 'storeDiscount';
    public const EDIT_DISCOUNT = 'editDiscount';
    public const UPDATE_DISCOUNT = 'updateDiscount';
    // follow ups
    public const ADMIN_FOLLOW_UP = 'adminFollowUp';
    public const INDEX_FOLLOW_UP = 'indexFollowUp';
    public const CREATE_FOLLOW_UP = 'createFollowUp';
    public const STORE_FOLLOW_UP = 'storeFollowUp';
    public const EDIT_FOLLOW_UP = 'editFollowUp';
    public const UPDATE_FOLLOW_UP = 'updateFollowUp';

    // marketing sms templates
    public const ADMIN_MARKETING_SMS_TEMPLATE = 'adminMarketingSmsTemplate';
    public const INDEX_MARKETING_SMS_TEMPLATE = 'indexMarketingSmsTemplate';
    public const CREATE_MARKETING_SMS_TEMPLATE = 'createMarketingSmsTemplate';
    public const STORE_MARKETING_SMS_TEMPLATE = 'storeMarketingSmsTemplate';
    public const EDIT_MARKETING_SMS_TEMPLATE = 'editMarketingSmsTemplate';
    public const UPDATE_MARKETING_SMS_TEMPLATE = 'updateMarketingSmsTemplate';
    public const SETTINGS_MARKETING_SMS_TEMPLATE = 'settingsMarketingSmsTemplate';

    // marketing sms items
    public const ADMIN_MARKETING_SMS_ITEM = 'adminMarketingSmsItem';
    public const INDEX_MARKETING_SMS_ITEM = 'indexMarketingSmsItem';
    public const CREATE_MARKETING_SMS_ITEM = 'createMarketingSmsItem';
    public const STORE_MARKETING_SMS_ITEM = 'storeMarketingSmsItem';
    public const EDIT_MARKETING_SMS_ITEM = 'editMarketingSmsItem';
    public const UPDATE_MARKETING_SMS_ITEM = 'updateMarketingSmsItem';

    // course reserves
    public const ADMIN_COURSE_RESERVE = 'adminCourseReserve';
    public const INDEX_COURSE_RESERVE = 'indexCourseReserve';
    public const CREATE_COURSE_RESERVE = 'createCourseReserve';
    public const STORE_COURSE_RESERVE = 'storeCourseReserve';
    public const EDIT_COURSE_RESERVE = 'editCourseReserve';
    public const CONVERT_TO_COURSE_VIEW = 'convertToCourseView';
    public const CONVERT_TO_COURSE = 'convertToCourse';
    // group descriptions
    public const ADMIN_GROUP_DESCRIPTION = 'adminGroupDescription';
    public const INDEX_GROUP_DESCRIPTION = 'indexGroupDescription';
    public const CREATE_GROUP_DESCRIPTION = 'createGroupDescription';
    public const STORE_GROUP_DESCRIPTION = 'storeGroupDescription';
    public const EDIT_GROUP_DESCRIPTION = 'editGroupDescription';
    public const UPDATE_GROUP_DESCRIPTION = 'updateGroupDescription';
    //online course groups
    public const INDEX_ONLINE_COURSE_GROUP = 'indexOnlineCourseGroup';
    public const CREATE_ONLINE_COURSE_GROUP = 'createOnlineCourseGroup';
    public const STORE_ONLINE_COURSE_GROUP = 'storeOnlineCourseGroup';
    public const DESTROY_ONLINE_COURSE_GROUP = 'destroyOnlineCourseGroup';
    //online course baskets
    public const INDEX_ONLINE_COURSE_BASKET = 'indexOnlineCourseBasket';
    public const SHOW_ONLINE_COURSE_BASKET = 'showOnlineCourseBasket';
    public const STORE_ONLINE_COURSE_BASKET = 'storeOnlineCourseBasket';
    public const DESTROY_ONLINE_COURSE_BASKET = 'destroyOnlineCourseBasket';
    //online course orders
    public const INDEX_ONLINE_COURSE_ORDER = 'indexOnlineCourseOrder';
    public const SHOW_ONLINE_COURSE_ORDER = 'showOnlineCourseOrder';
    //online course payments
    public const INDEX_ONLINE_COURSE_PAYMENT = 'indexOnlineCoursePayment';
    //course payments
    public const INDEX_COURSE_PAYMENT = 'indexCoursePayment';
    //online course
    public const INDEX_ONLINE_COURSE = 'indexOnlineCourse';
    public const CREATE_ONLINE_COURSE = 'createOnlineCourse';
    public const STORE_ONLINE_COURSE = 'storeOnlineCourse';
    public const EDIT_ONLINE_COURSE = 'editOnlineCourse';
    public const UPDATE_ONLINE_COURSE = 'updateOnlineCourse';
    public const DESTROY_ONLINE_COURSE = 'destroyOnlineCourse';
    //online course percentages
    public const INDEX_ONLINE_COURSE_PERCENTAGE = 'indexOnlineCoursePercentage';
    //course withdraw
    public const INDEX_COURSE_WITHDRAW = 'indexCourseWithdraw';
    public const INDEX_COURSE_WITHDRAW_REDO = 'indexCourseWithdrawRedo';
    //goods
    public const ADMIN_GOODS = 'adminGoods';
    public const INDEX_GOODS = 'indexGoods';
    public const CREATE_GOODS = 'createGoods';
    public const STORE_GOODS = 'storeGoods';
    // goods report
    public const REPORTS_STORE_GOODS = 'reportsStoreGoods';
    // add permission
    public const ADD_PERMISSION_FOR_CLERK = 'addPermissionForClerk';
    public const ADD_PERMISSION_FOR_SECRETARY = 'addPermissionForsecretary';
    // Sales permission
    public const ADMIN_SALES_TEAM = 'adminSalesTeam';
    public const INDEX_SALES_TEAM = 'indexSalesTeam';
    public const CREATE_SALES_TEAM = 'createSalesTeam';
    public const STORE_SALES_TEAM = 'storeSalesTeam';
    public const EDIT_SALES_TEAM = 'editSalesTeam';
    public const UPDATE_SALES_TEAM = 'updateSalesTeam';

    //  Reports
    public const REPORTS = 'reports';
    public const REPORTS_VERIFICATION_CODE = 'reportsVerificationCode';
    public const REPORTS_PAYMENT_CHANGE_LOG = 'reportsPaymentChangeLog';
    public const REPORTS_COURSE_REGISTER_CHANGE_LOG = 'reportsCourseRegisterChangeLog';
    public const REPORTS_ORDER_ITEM_CHANGE_LOG = 'reportsOrderItemChangeLog';
    public const REPORTS_SECRETARY_SALES = 'reportsSecretarySales';
    public const REPORTS_FINANCIAL = 'reportsFinancial';
    public const REPORTS_SEND_SMS_LOG = 'reportsSendSmsLog';

    // online course orders
    public const UPDATE_ORDER_ITEM_AMOUNT = 'updateOrderItemAmount';
    // survey
    public const INDEX_SURVEY = 'indexSurvey';
    // exam
    public const INDEX_EXAM = 'indexExam';
}
