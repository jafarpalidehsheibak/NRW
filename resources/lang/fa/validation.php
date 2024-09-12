<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    "accepted"         => ":attribute باید پذیرفته شده باشد.",
    'accepted_if'      => 'هنگامی که :other، :value است باید با :attribute توافق کنید.',
    "active_url"       => "آدرس :attribute معتبر نیست",
    "after"            => ":attribute باید تاریخی بعد از :date باشد.",
    'after_or_equal'   => ':attribute باید بعد از یا برابر تاریخ :date باشد.',
    "alpha"            => ":attribute باید شامل حروف الفبا باشد.",
    "alpha_dash"       => ":attribute باید شامل حروف الفبا و عدد و خظ تیره(-) باشد.",
    "alpha_num"        => ":attribute باید شامل حروف الفبا و عدد باشد.",
    "array"            => ":attribute باید شامل آرایه باشد.",
    "before"           => ":attribute باید تاریخی قبل از :date باشد.",
    'before_or_equal' => ':attribute باید قبل از یا برابر تاریخ :date باشد.',
    "between"          => [
        "numeric" => ":attribute باید بین :min و :max باشد.",
        "file"    => ":attribute باید بین :min و :max کیلوبایت باشد.",
        "string"  => ":attribute باید بین :min و :max کاراکتر باشد.",
        "array"   => ":attribute باید بین :min و :max آیتم باشد.",
    ],
    "boolean"          => "فیلد :attribute فقط میتواند صحیح و یا غلط باشد",
    "confirmed"        => ":attribute با تاییدیه مطابقت ندارد.",
    "date"             => ":attribute یک تاریخ معتبر نیست.",
    'date_equals'      => ':attribute باید برابر تاریخ :date باشد.',
    "date_format"      => ":attribute با الگوی :format مطاقبت ندارد.",
    'declined'         => ':attribute باید پذیرفته نشود.',
    'declined_if'      => 'هنگامی که :other، :value است باید با :attribute نپذیرید.',
    "different"        => ":attribute و :other باید متفاوت باشند.",
    "digits"           => ":attribute باید :digits رقم باشد.",
    "digits_between"   => ":attribute باید بین :min و :max رقم باشد.",
    'dimensions'       => 'dimensions مربوط به فیلد :attribute اشتباه است.',
    'distinct'         => ':attribute مقدار تکراری دارد.',
    "email"            => "فرمت :attribute معتبر نیست.",
    'ends_with'        => ':attribute باید با این مقدار تمام شود: :values.',
    "exists"           => ":attribute انتخاب شده،  در دیتابیس وجود ندارد.",
    'file' 	       => 'فیلد :attribute باید فایل باشد.',
    "filled"           => "فیلد :attribute الزامی است",
    'gt' => [
        'numeric' => ':attribute باید بیشتر از :value باشد.',
        'file'    => ':attribute باید بیشتر از :value کیلوبایت باشد.',
        'string'  => ':attribute باید بیشتر از :value کاراکتر باشد.',
        'array'   => ':attribute باید بیشتر از :value ایتم باشد.',
    ],
    'gte' => [
        'numeric' => ':attribute باید بیشتر یا برابر :value باشد.',
        'file'    => ':attribute باید بیشتر یا برابر :value کیلوبایت باشد.',
        'string'  => ':attribute باید بیشتر یا برابر :value کاراکتر باشد.',
        'array'   => ':attribute باید :value ایتم یا بیشتر را داشته باشد.',
    ],
    "image"            => ":attribute باید تصویر باشد.",
    "in"               => ":attribute انتخاب شده، معتبر نیست.",
    "integer"          => ":attribute باید نوع داده ای عددی (integer) باشد.",
    'in_array'         => 'فیلد :attribute در :other موجود نیست.',
    "ip"               => ":attribute باید IP آدرس معتبر باشد.",
    'ipv4'             => ':attribute باید یک ادرس درست IPv4 باشد.',
    'ipv6'             => ':attribute باید یک ادرس درست IPv6 باشد.',
    'json'             => ':attribute یک مقدار درست JSON باشد.',
    'lt' => [
        'numeric' => ':attribute باید کمتر از :value باشد.',
        'file'    => ':attribute باید کمتر از :value کیلوبایت باشد.',
        'string'  => ':attribute باید کمتر از :value کاراکتر باشد.',
        'array'   => ':attribute باید :value ایتم یا کمتر را داشته باشد.',
    ],
    'lte' => [
        'numeric' => ':attribute باید کمتر یا برابر :value باشد.',
        'file'    => ':attribute باید کمتر یا برابر :value کیلوبایت باشد.',
        'string'  => ':attribute باید کمتر یا برابر :value کاراکتر باشد.',
        'array'   => ':attribute باید :value ایتم یا کمتر را داشته باشد.',
    ],
    "max"              => [
        "numeric" => ":attribute نباید بزرگتر از :max باشد.",
        "file"    => ":attribute نباید بزرگتر از :max کیلوبایت باشد.",
        "string"  => ":attribute نباید بیشتر از :max کاراکتر باشد.",
        "array"   => ":attribute نباید بیشتر از :max آیتم باشد.",
    ],
    "mimes"            => ":attribute باید یکی از فرمت های :values باشد.",
    'mimetypes'        => ':attribute باید تایپ ان از نوع: :values باشد.',
    "min"              => [
        "numeric" => ":attribute نباید کوچکتر از :min باشد.",
        "file"    => ":attribute نباید کوچکتر از :min کیلوبایت باشد.",
        "string"  => ":attribute نباید کمتر از :min کاراکتر باشد.",
        "array"   => ":attribute نباید کمتر از :min آیتم باشد.",
    ],
    'multiple_of'      => ':attribute باید ضریبی از :value باشد.',
    "not_in"           => ":attribute انتخاب شده، معتبر نیست.",
    'not_regex'        => ':attribute فرمت معتبر نیست.',
    "numeric"          => ":attribute باید شامل عدد باشد.",
    'password'         => 'رمز عبور اشتباه است.',
    'present'          => ':attribute باید وجود داشته باشد.',
    'prohibited'       => 'فیلد :attribute ممنوع است.',
    'prohibited_if'    => 'هنگام که :other، :value است فیلد :attribute ممنوع است.',
    'prohibited_unless' => ':attribute ممنوع است مگر اینکه :other برابر با (:values) باشد.',
    'prohibits'        => 'هنگام ورود فیلد :attribute، وارد کردن فیلد :other ممنوع است.',
    "regex"            => ":attribute یک فرمت معتبر نیست",
    "required"         => "فیلد :attribute الزامی است",
    "required_if"      => "فیلد :attribute هنگامی که :other برابر با :value است، الزامیست.",
    'required_unless'  => 'قیلد :attribute الزامیست مگر این فیلد :other مقدارش  :values باشد.',
    "required_with"    => ":attribute الزامی است زمانی که :values موجود است.",
    "required_with_all" => ":attribute الزامی است زمانی که :values موجود است.",
    "required_without" => ":attribute الزامی است زمانی که :values موجود نیست.",
    "required_without_all" => ":attribute الزامی است زمانی که :values موجود نیست.",
    "same"             => ":attribute و :other باید مانند هم باشند.",
    "size"             => [
        "numeric" => ":attribute باید برابر با :size باشد.",
        "file"    => ":attribute باید برابر با :size کیلوبایت باشد.",
        "string"  => ":attribute باید برابر با :size کاراکتر باشد.",
        "array"   => ":attribute باسد شامل :size آیتم باشد.",
    ],
    'starts_with'      => ':attribute باید با یکی از این مقادیر شروع شود: :values.',
    "string"           => ":attribute باید رشته باشد.",
    "timezone"         => "فیلد :attribute باید یک منطقه صحیح باشد.",
    "unique"           => ":attribute قبلا انتخاب شده است.",
    'uploaded'         => 'فیلد :attribute به درستی اپلود نشد.',
    "url"              => "فرمت آدرس :attribute اشتباه است.",
    'uuid'             => ':attribute باید یک فرمت درست UUID باشد.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */
    'attributes' => [
        "name" => "نام",
        "username" => "نام کاربری",
        "email" => "شماره همراه",
        "first_name" => "نام",
        "last_name" => "نام خانوادگی",
        "family" => "نام خانوادگی",
        "passold" => "رمز عبور قبلی",
        "password" => "رمز عبور",
        "password_confirmation" => "تاییدیه ی رمز عبور",
        "city" => "شهر",
        "country" => "کشور",
        "address" => "نشانی",
        "phone" => "تلفن",
        "mobile" => "تلفن همراه",
        "age" => "سن",
        "sex" => "جنسیت",
        "gender" => "جنسیت",
        "day" => "روز",
        "month" => "ماه",
        "year" => "سال",
        "hour" => "ساعت",
        "minute" => "دقیقه",
        "second" => "ثانیه",
        "title" => "عنوان",
        "text" => "متن",
        "content" => "محتوا",
        "description" => "توضیحات",
        "excerpt" => "گلچین کردن",
        "date" => "تاریخ",
        "time" => "زمان",
        "available" => "موجود",
        "size" => "اندازه",
		"file" => "فایل",
		"fullname" => "نام کامل",
		"data.password" => "کلمه عبور",
		"data.email" => "ایمیل",
		"comment_text" => "کامنت",
        "checkRule"=>"قوانین",
        "phone_number"=>"تلفن همراه",
        "code"=>"کد",
        "traking_code"=>"کد رهگیری",
        "pdf_barname"=>"فایل بارنامه",

        "phone_frastande"=>"شماره موبایل ",
        "codemeli_frastande"=>"کد /شناسه ملی",
        "name_frastande"=>"نام و نام خانوادگی / شرکت",
        "display_name"=>"نام",
        "sh_shenash"=>"شماره شناسنامه/ثبت",
        "code_meli"=>"کد/شناسه ملی",
        "postalcode"=>"کد پستی",
        "company_name"=>"نام شرکت",
        "id_city"=>"شهر",
        "amount"=>"مبلغ(تومان)",
        "letter_title"=>"عنوان نامه",
        "letter_image_address"=>"عکس",
        "letter_text"=>"شرح نامه",

        "etebar"=>"اعتبار",
        "rozetebar"=>"روز اعتبار",

        'nameoperator'=>'نام و نام خانوادگی',
        'fathername'=>'نام پدر',
        'selectcity'=>'انتخاب شهر',
        'national_code'=>'کد ملی',
        'main_address'=>'آدرس اصلی',
        'manager_name'=>'نام مدیر عامل',
        'manager_mobile'=>'موبایل مدیر عامل',
        'commercial_manager_name'=>'نام مدیر بازرگانی',
        'commercial_manager_mobile'=>'موبایل مدیر بازرگانی',
        'bachelor_commercial_name'=>'نام کارشناس بازرگانی',
        'bachelor_commercial_mobile'=>'موبایل کارشناس بازرگانی',
        'inventory_manager_name'=>'نام مدیر انبار',
        'inventory_manager_mobile'=>'موبایل مدیر انبار',
        'contracting_company_name'=>'شرکت حمل طرف قرارداد',
        'contracting_company_mobile'=>'موبایل شرکت حمل طرف قرارداد',
        'transport_manager_name'=>'مدیر ترابری',
        'transport_manager_mobile'=>'موبایل مدیر ترابری',
        'financial_manager_name'=>' مدیر مالی',
        'financial_manager_mobile'=>'موبایل مدیر مالی',
        'truck_description'=>'توضیحات',
        'status_track_workshop'=>'آخرین وضعیت',
        'nextdate_at'=>'تاریخ بعدی تماس',
        'message_reply_text'=>'متن پاسخ',
        'importance'=>'درجه اهمیت',
        'unit_code'=>'کد واحد',
        'unit_name'=>'نام واحد',
        'person_code'=>'کد پرسنل',
        'person_name'=>'نام پرسنل',
        'unit_id'=>'واحد پرسنلی',
        'person_family'=>' نام خانوادگی پرسنل',
        'company_id'=>'نام شرکت',
        'expert'=>'تخصص',
        'province'=>'استان',
        'contractor_name'=>'نام پیمانکار',
        'contractor_rank'=>'رتبه پیمانکار',
        'contractor_mobile'=>'موبایل پیمانکار',
        'province_id'=>'استان',
        'city_id'=>'شهر',
        'road_name'=>'نام راه',
        'expert_id'=>'رشته',
        'workshop_location_kilometers'=>'محل کارگاه (کیلومتراژ)',
        'workshop_begin_lat_long'=>'مختصات مبدا',
        'workshop_end_lat_long'=>'مختصات مقصد',
        'workshop_name'=>'نام کارگاه',
        'full_name_connector'=>'نام رابط',
        'mobile_connector'=>'موبایل رابط',
        'email_connector'=>'ایمیل رابط',
        'approximate_start_date'=>'زمان تقریبی شروع',
        'workshop_duration'=>'مدت زمان اجرای کارگاه',
        'checklist_item_detail_id.executive_operation_correctly_contractor.confirm_notconfirm'=>'نوع منطقه عملیات اجرایی',
        'checklist_item_detail_id.executive_operation_correctly_contractor.description'=>'توضیحات نوع منطقه عملیات اجرایی',
        'checklist_item_detail_id.documentation_provided_contractor_consistent_executive.confirm_notconfirm'=>'مستندات ارائه شده توسط پیمانکار',
        'checklist_item_detail_id.documentation_provided_contractor_consistent_executive.description'=>'توضیحات مستندات ارائه شده توسط پیمانکار',
        'checklist_item_detail_id.sufficient_field_view_area.confirm_notconfirm'=>'میدان دید کافی با توجه به هندسه موقعیت مکانی',
        'checklist_item_detail_id.sufficient_field_view_area.description'=>'توضیحات میدان دید کافی با توجه به هندسه موقعیت مکانیی',
        'checklist_item_detail_id.ttcp_scheme_capable_implemented.confirm_notconfirm'=>'طرح TTCP ',
        'checklist_item_detail_id.ttcp_scheme_capable_implemented.description'=>'توضیحات طرح TTCP ',
        'checklist_item_detail_id.border_middle_enough_space.confirm_notconfirm'=>'حاشیه و میانه راه',
        'checklist_item_detail_id.border_middle_enough_space.description'=>'توضیحات حاشیه و میانه راه',
        'checklist_item_detail_id.emergency_vehicles_access_executive.confirm_notconfirm'=>'امکان دسترسی وسایل نقلیه امدادی',
        'checklist_item_detail_id.emergency_vehicles_access_executive.description'=>'توضیحات امکان دسترسی وسایل نقلیه امدادی',
        'checklist_item_detail_id.necessary_measures_tmp_traffic.confirm_notconfirm'=>'حجم جریان ترافیک در محل',
        'checklist_item_detail_id.necessary_measures_tmp_traffic.description'=>'توضیحات حجم جریان ترافیک در محل',
        'checklist_item_detail_id.executive_operation_requirement_block_road.confirm_notconfirm'=>'الزام برای انسداد کامل راه',
        'checklist_item_detail_id.executive_operation_requirement_block_road.description'=>'توضیحات الزام برای انسداد کامل راه',
        'checklist_item_detail_id.pedestrians_motorcyclists_observed_enforcement.confirm_notconfirm'=>'کاربران آسیب پذیر',
        'checklist_item_detail_id.pedestrians_motorcyclists_observed_enforcement.description'=>'توضیحات کاربران آسیب پذیر',
        'checklist_item_detail_id.users_considered_executive_operations.confirm_notconfirm'=>'ایمنی کاربران آسیب پذیر',
        'checklist_item_detail_id.users_considered_executive_operations.description'=>'توضیحات ایمنی کاربران آسیب پذیر',
        'checklist_item_detail_id.intersecting_traffic_flow_vicinity.confirm_notconfirm'=>'جریان ترافیکی تلاقی کننده',
        'checklist_item_detail_id.intersecting_traffic_flow_vicinity.description'=>'توضیحات جریان ترافیکی تلاقی کننده',
        'checklist_item_detail_id.safety_measures_intersecting_traffic_vicinity.confirm_notconfirm'=>'تدابیر ایمنی برای جریان ترافیکی',
        'checklist_item_detail_id.safety_measures_intersecting_traffic_vicinity.description'=>'توضیحات تدابیر ایمنی برای جریان ترافیکی',
        'checklist_item_detail_id.termination_date'=>'تاریخ خاتمه',
        'checklist_item_detail_id.end_time_hours'=>'هنگامه خاتمه (ساعت)',
        'checklist_item_detail_id.end_time_day'=>'هنگامه خاتمه (روز)',
        'checklist_item_detail_id.traffic_situation'=>'وضعیت ترافیک',
        'checklist_item_detail_id.weather_conditions'=>'شرایط آب و هوایی',
        'checklist_item_detail_id.time_complete_cleaning'=>'هنگامه پاکسازی کامل',
        'checklist_item_detail_id.public_information_arrangements.0.newspaper_advertisements_notices.done_notdone'=>'تبلیغات روزنامه، اعلامیه و انتشار در مطبوعات ',
        'checklist_item_detail_id.public_information_arrangements.0.providing_information_through_website.provided_notprovided'=>'ارائه اطلاعات از طریق وب‌سایت',
        'checklist_item_detail_id.public_information_arrangements.0.brochures.provided_notprovided'=>'بروشورها',
        'checklist_item_detail_id.public_information_arrangements.0.variable_message_boards.provided_notprovided'=>'تابلوهای پیام متغیر ',
        'checklist_item_detail_id.public_information_arrangements.0.comprehensive_radio_television_social.done_notdone'=>'رسانه‌های فراگیر',
        'checklist_item_detail_id.public_information_arrangements.0.traffic_radio.done_notdone'=>'رادیو ترافیک',
        'checklist_item_detail_id.public_information_arrangements.0.system_141.done_notdone'=>'سامانه 141',
        'checklist_item_detail_id.public_information_arrangements.0.contact_information_boards.provided_notprovided'=>'تابلوهای اطلاعات تماس در محل پروژه',
        'checklist_item_detail_id.details_notification_messages.0.apology_text_users.done_notdone'=>'متن پوزش خواهی از کاربران',
        'checklist_item_detail_id.details_notification_messages.0.time_start_executive_operation.yes_no'=>'زمان/روز شروع عملیات اجرایی',
        'checklist_item_detail_id.details_notification_messages.0.end_time_executive_operation.yes_no'=>'زمان پایان عملیات اجرایی',
        'checklist_item_detail_id.details_notification_messages.0.type_executive_operation.yes_no'=>'نوع عملیات اجرایی',
        'checklist_item_detail_id.details_notification_messages.0.recommended_transit_speed.done_notdone'=>'سرعت عبور پیشنهادی',
        'checklist_item_detail_id.details_notification_messages.0.special_advice_necessary.done_notdone'=>'توصیه ویژه در صورت لزوم',
        'checklist_item_detail_id.details_notification_messages.0.traffic_changes.yes_no'=>'تغییرات ترافیک',
        'checklist_item_detail_id.details_notification_messages.0.contact_numbers_administrators_trustees_question.0.yes_no'=>'شماره تماس با مدیران و متولیان',
        'checklist_item_detail_id.details_notification_messages.0.contact_numbers_administrators_trustees_question.0.person'=>'شخص مورد نظر',
        'checklist_item_detail_id.details_notification_messages.0.contact_numbers_administrators_trustees_question.0.position'=>'سمت',
        'checklist_item_detail_id.details_notification_messages.0.contact_numbers_administrators_trustees_question.0.phone_number'=>'شماره تلفن',
        'checklist_item_detail_id.details_notification_messages.0.contact_number_emergency_services.yes_no'=>'شماره تماس با خدمات فوریتی',
        'checklist_item_detail_id.provision_parking_arrangements.provided_notprovided'=>'فراهم سازی تمهیدات پارک سوار',
        'checklist_item_detail_id.provision_public_shared_vehicles.provided_notprovided'=>'فراهم سازی وسایل نقلیه همگانی',
        'checklist_item_detail_id.provision_special_lanes_passenger.provided_notprovided'=>'فراهم سازی خطوط ویژه',
        'checklist_item_detail_id.arrangements_convergence_lines.done_notdone'=>'تمهیدات همگرایی خطوط',
        'checklist_item_detail_id.measures_reduce_traffic_congestion.done_notdone'=>'تمهیدات کاهش ازدحام ترافیک',
        'checklist_item_detail_id.using_intelligent_systems.yes_no'=>'بکارگیری سامانه ها هوشمند',
        'checklist_item_detail_id.creating_overtaking_lanes_heavy_vehicles.created_notcreated'=>'ایجاد خطوط سبقت از وسایل نقلیه سنگین',
        'checklist_item_detail_id.creation_special_lanes_heavy_vehicles.created_notcreated'=>'ایجاد خطوط ویژه عبور وسایل نقلیه سنگین',
        'checklist_item_detail_id.coordination_adjacent_operational_areas.coordinate_notcoordinate'=>'هماهنگی با مناطق عملیات اجرایی مجاور',
        'checklist_item_detail_id.stop_limits.provided_notprovided'=>'محدودیت هاي توقف',
        'checklist_item_detail_id.control_railway_crossings.provided_notprovided'=>'کنترل گذرگاه هاي ریلی',
        'checklist_item_detail_id.ramp_control.provided_notprovided'=>'کنترل شیبراهه',
        'checklist_item_detail_id.reducing_permitted_speed_using_signs.used_notused'=>'کاهش سرعت مجاز',
        'checklist_item_detail_id.modification_intersections_passages.done_notdone'=>'اصلاح تقاطع ها و معابر',
        'checklist_item_detail_id.restriction_heavy_vehicles_trucks.done_notdone'=>'محدودیت عبور وسایل نقلیه سنگین',
        'checklist_item_detail_id.circulation_restrictions.provided_notprovided'=>'',
        'checklist_item_detail_id.circulation_restrictions.used_notused'=>'محدودیت هاي گردش',
        'checklist_item_detail_id.coordination_response_emergency_services.provided_notprovided'=>'هماهنگی و پاسخگویی خدمات اضطراری',
        'checklist_item_detail_id.surveillance_cctv_cameras.used_notused'=>'نظارت(دوربین های مداربسته، شناسگرهای حلقه ای)',
        'checklist_item_detail_id.patrol.used_notused'=>'گشت زنی',
        'checklist_item_detail_id.law_enforcement_police.used_notused'=>'اعمال قانون توسط پلیس',
        'checklist_item_detail_id.access_rescue_equipment_emergency.used_notused'=>'دسترسی به تجهیزات امداد',
        'checklist_item_detail_id.presence_someone_emergency_services.used_notused'=>'حضور فرد آشنا با خدمات فوریتی',
        'checklist_item_detail_id.emergency_vehicle_executive_operation.provided_notprovided'=>'دسترسی وسیله نقلیه امدادی',
        'checklist_item_detail_id.ensuring_safety_pedestrians_users.provided_notprovided'=>'تأمین ایمنی عابرین پیاده و کاربران غیرموتوری',
        'checklist_item_detail_id.safe_accesses_workshop.provided_notprovided'=>'دسترسی‌های ایمن ورود به کارگاه',
        'checklist_item_detail_id.incident_management_program_workshop.provided_notprovided'=>'برنامه مدیریت حادثه در کارگاه',
        'checklist_item_detail_id.detour_options.exist_notexist'=>'گزینه‌های مسیر انحرافی',
        'checklist_item_detail_id.necessary_arrangements_maintaining_detour_route.provided_notprovided'=>'تمهیدات لازم برای نگهداری مسیر انحرافی',
        'checklist_item_detail_id.ttcp_configuration_considerations_minimize_traffic.provided_notprovided'=>'ملاحظات تنظیم و آرایش TTCP',
        'checklist_item_detail_id.preparedness_deal_unplanned_events.provided_notprovided'=>'آمادگی مقابله با رویدادهای برنامه‌ریزی نشده',
        'contractor_request_id'=>'کد کارگاه',
        'checklist_id'=>'نام چک لیست',
        'checklist_item_detail_id.start_date'=>'تاریخ شروع',
        'checklist_item_detail_id.start_time_hours'=>'هنگامه شروع (ساعت)',
        'checklist_item_detail_id.start_time_day'=>'هنگامه شروع (روز)',



    ],
];
