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

    'accepted' => ':attribute को स्वीकृत किया जाना चाहिए।',
    'accepted_if' => ':attribute को स्वीकृत किया जाना चाहिए जब :other :value हो।',
    'active_url' => ':attribute एक मान्य URL नहीं है।',
    'after' => ':attribute को :date के बाद की एक तारीख होनी चाहिए।',
    'after_or_equal' => ':attribute को :date के बाद या उसके समान एक तारीख होनी चाहिए।',
    'alpha' => ':attribute में केवल अक्षर होने चाहिए।',
    'alpha_dash' => ':attribute में केवल अक्षर, संख्या, डैश और अंडरस्कोर होने चाहिए।',
    'alpha_num' => ':attribute में केवल अक्षर और संख्याएं होनी चाहिए।',
    'array' => ':attribute एक एरे होना चाहिए।',
    'ascii' => ':attribute में केवल एक बाइट वाले अल्फान्यूमेरिक वर्ण और प्रतीक होने चाहिए।',
    'before' => ':attribute को :date के पहले की एक तारीख होनी चाहिए।',
    'before_or_equal' => ':attribute को :date के पहले या उसके समान एक तारीख होनी चाहिए।',
    'between' => [
        'array' => ':attribute को :min और :max आइटम के बीच होना चाहिए।',
        'file' => ':attribute को :min और :max किलोबाइट के बीच होना चाहिए।',
        'numeric' => ':attribute को :min और :max के बीच होना चाहिए।',
        'string' => ':attribute को :min और :max वर्णों के बीच होना चाहिए।',
    ],
    'boolean' => ':attribute फ़ील्ड को सत्य या असत्य होना चाहिए।',
    'confirmed' => ':attribute की पुष्टि मेल नहीं खाती।',
    'current_password' => 'पासवर्ड गलत है।',
    'date' => ':attribute एक मान्य तिथि नहीं है।',
    'date_equals' => ':attribute को :date के समान एक तिथि होनी चाहिए।',
    'date_format' => ':attribute का प्रारूप :format से मेल नहीं खाता है।',
    'decimal' => ':attribute को :decimal दशमलव स्थान होने चाहिए।',
    'declined' => ':attribute को इनकार किया जाना चाहिए।',
    'declined_if' => ':attribute को इनकार किया जाना चाहिए जब :other :value हो।',
    'different' => ':attribute और :other अलग होने चाहिए।',
    'digits' => ':attribute में :digits अंक होने चाहिए।',
    'digits_between' => ':attribute की लंबाई :min और :max अंकों के बीच होनी चाहिए।',
    'dimensions' => ':attribute में अमान्य छवि आयाम हैं।',
    'distinct' => ':attribute फ़ील्ड में एक अनन्य मूल्य होना चाहिए।',
    'doesnt_end_with' => ':attribute निम्नलिखित में से किसी भी से समाप्त नहीं हो सकता: :values।',
    'doesnt_start_with' => ':attribute निम्नलिखित में से किसी भी से शुरू नहीं हो सकता: :values।',
    'email' => ':attribute एक मान्य ईमेल पता होना चाहिए।',
    'ends_with' => ':attribute निम्नलिखित में से किसी से समाप्त होना चाहिए: :values।',
    'enum' => 'चयनित :attribute अमान्य है।',
    'exists' => 'चयनित :attribute अमान्य है।',
    'file' => ':attribute एक फ़ाइल होनी चाहिए।',
    'filled' => ':attribute फ़ील्ड में एक मूल्य होना चाहिए।',
    'gt' => [
        'array' => ':attribute में :value से अधिक आइटम होने चाहिए।',
        'file' => ':attribute :value किलोबाइट से अधिक होनी चाहिए।',
        'numeric' => ':attribute :value से अधिक होना चाहिए।',
        'string' => ':attribute :value वर्णों से अधिक होना चाहिए।',
    ],
    'gte' => [
        'array' => ':attribute में :value आइटम या उससे अधिक होने चाहिए।',
        'file' => ':attribute :value किलोबाइट या उससे अधिक होना चाहिए।',
        'numeric' => ':attribute :value या उससे अधिक होना चाहिए।',
        'string' => ':attribute :value वर्ण या उससे अधिक होना चाहिए।',
    ],
    'image' => ':attribute एक छवि होनी चाहिए।',
    'in' => 'चयनित :attribute अमान्य है।',
    'in_array' => ':attribute फ़ील्ड :other में मौजूद नहीं है।',
    'integer' => ':attribute एक पूर्णांक होना चाहिए।',
    'ip' => ':attribute एक मान्य IP पता होना चाहिए।',
    'ipv4' => ':attribute एक मान्य IPv4 पता होना चाहिए।',
    'ipv6' => ':attribute एक मान्य IPv6 पता होना चाहिए।',
    'json' => ':attribute एक मान्य JSON स्ट्रिंग होनी चाहिए।',
    'lowercase' => ':attribute को छोटे अक्षर में होना चाहिए।',
    'lt' => [
        'array' => ':attribute में :value से कम आइटम होने चाहिए।',
        'file' => ':attribute :value किलोबाइट से कम होना चाहिए।',
        'numeric' => ':attribute :value से कम होना चाहिए।',
        'string' => ':attribute :value वर्णों से कम होना चाहिए।',
    ],
    'lte' => [
        'array' => ':attribute में :value से अधिक आइटम नहीं होने चाहिए।',
        'file' => ':attribute :value किलोबाइट या उससे कम होना चाहिए।',
        'numeric' => ':attribute :value या उससे कम होना चाहिए।',
        'string' => ':attribute :value वर्ण या उससे कम होना चाहिए।',
    ],
    'mac_address' => ':attribute एक मान्य MAC पता होना चाहिए।',
    'max' => [
        'array' => ':attribute में :max से अधिक आइटम नहीं होने चाहिए।',
        'file' => ':attribute :max किलोबाइट से अधिक नहीं होना चाहिए।',
        'numeric' => ':attribute :max से अधिक नहीं होना चाहिए।',
        'string' => ':attribute :max वर्णों से अधिक नहीं होना चाहिए।',
    ],
    'max_digits' => ':attribute में :max अंक से अधिक नहीं होने चाहिए।',
    'mimes' => ':attribute :values प्रकार की एक फ़ाइल होनी चाहिए।',
    'mimetypes' => ':attribute :values प्रकार की एक फ़ाइल होनी चाहिए।',
    'min' => [
        'array' => ':attribute में कम से कम :min आइटम होने चाहिए।',
        'file' => ':attribute कम से कम :min किलोबाइट होनी चाहिए।',
        'numeric' => ':attribute कम से कम :min होना चाहिए।',
        'string' => ':attribute कम से कम :min वर्ण होने चाहिए।',
    ],
    'min_digits' => ':attribute में कम से कम :min अंक होने चाहिए।',
    'multiple_of' => ':attribute :value का एक गुणक होना चाहिए।',
    'not_in' => 'चयनित :attribute अमान्य है।',
    'not_regex' => ':attribute प्रारूप अमान्य है।',
    'numeric' => ':attribute एक संख्या होनी चाहिए।',
    'password' => [
        'letters' => ':attribute में कम से कम एक अक्षर होना चाहिए।',
        'mixed' => ':attribute में कम से कम एक अपरकेस और एक लोवरकेस अक्षर होना चाहिए।',
        'numbers' => ':attribute में कम से कम एक संख्या होनी चाहिए।',
        'symbols' => ':attribute में कम से कम एक प्रतीक होना चाहिए।',
        'uncompromised' => 'दिए गए :attribute ने एक डेटा लीक में दिखाई दी है। कृपया एक अलग :attribute चुनें।',
    ],
    'present' => ':attribute फ़ील्ड मौजूद होना चाहिए।',
    'prohibited' => ':attribute फ़ील्ड प्रतिबंधित है।',
    'prohibited_if' => ':other :value होने पर :attribute फ़ील्ड प्रतिबंधित है।',
    'prohibited_unless' => ':other :values में नहीं होने पर :attribute फ़ील्ड प्रतिबंधित है।',
    'prohibits' => ':attribute फ़ील्ड :other को मौजूद होने से रोकता है।',
    'regex' => ':attribute प्रारूप अमान्य है।',
    'required' => ':attribute फ़ील्ड आवश्यक है।',
    'required_array_keys' => ':attribute फ़ील्ड में :values के लिए प्रविष्टियाँ होनी चाहिए।',
    'required_if' => ':other :value होने पर :attribute फ़ील्ड आवश्यक है।',
    'required_if_accepted' => ':other स्वीकृत होने पर :attribute फ़ील्ड आवश्यक है।',
    'required_unless' => ':other :values में होने पर :attribute फ़ील्ड आवश्यक है।',
    'required_with' => ':values मौजूद होने पर :attribute फ़ील्ड आवश्यक है।',
    'required_with_all' => ':values मौजूद होने पर :attribute फ़ील्ड आवश्यक हैं।',
    'required_without' => ':values अभ्यंतर नहीं होने पर :attribute फ़ील्ड आवश्यक है।',
    'required_without_all' => ':values में से कोई भी मौजूद नहीं होने पर :attribute फ़ील्ड आवश',
    'same' => ':attribute और :other मेल खाना चाहिए।',
    'size' => [
        'array' => ':attribute में :size आइटम होनी चाहिए।',
        'file' => ':attribute :size किलोबाइट होना चाहिए।',
        'numeric' => ':attribute :size होना चाहिए।',
        'string' => ':attribute :size वर्ण होना चाहिए।',
    ],
    'starts_with' => ':attribute को निम्नलिखित में से एक के साथ शुरू होना चाहिए: :values।',
    'string' => ':attribute एक स्ट्रिंग होनी चाहिए।',
    'timezone' => ':attribute एक मान्य समय क्षेत्र होना चाहिए।',
    'unique' => ':attribute पहले से ही लिया गया है।',
    'uploaded' => ':attribute अपलोड करने में विफल रहा।',
    'uppercase' => ':attribute को बड़े अक्षर में होना चाहिए।',
    'url' => ':attribute को एक मान्य URL होना चाहिए।',
    'ulid' => ':attribute को एक मान्य ULID होना चाहिए।',
    'uuid' => ':attribute को एक मान्य UUID होना चाहिए।',
    'custom' => [
        'attribute-name' => [
            'rule-name' => 'कस्टम-संदेश',
        ],
    ],
    'attributes' => [
        'mobile' => 'फ़ोन नंबर',
        "country_code"=>"देश कोड",
        "otp"=>"ओ.टी.पी",
        'type'=>'प्रकार',
        "password"=>'पासवर्ड',
        "new_password"=>"नया पासवर्ड",
        "confirm_new_password"=>"नये पासवर्ड की पुष्टि",
        "milk_type"=>"दूध प्रकार",
        "farmer_id"=>'किसान ID',
        "quantity"=>"मात्रा",
        'fat' => 'वसा',
        'snf' => 'एसएनएफ',
        'clr' => 'सीएलआर',
    ],

    'min_length' => ' :attribute कम से कम 8 अक्षर होना चाहिए।',
    'number_required' => ' :attribute में कम से कम एक संख्या होनी चाहिए।',
    'special_char_required' => ' :attribute में कम से कम एक विशेष वर्ण होना चाहिए।',
    'uppercase_required' => ' :attribute में कम से कम एक अपरकेस अक्षर होना चाहिए।',
    'lowercase_required' => ' :attribute में कम से कम एक लोअरकेस अक्षर होना चाहिए।',
    'valid_json' => ' :attribute फ़ील्ड को एक मान्य JSON स्ट्रिंग होना चाहिए।',
];