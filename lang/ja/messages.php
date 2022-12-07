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

    'alert'=>[
      'employee-register'=>'担当者登録が完了しました。'
    ],
    'validation' => [
        'email' => [
            'required' => 'メールアドレスは必須項目です。',
            'invalid' => '有効なメールアドレスを入力してください。',
            'unique' => 'ユーザーは既に存在します。',
        ],
        'name' => [
            'required' => '名前は必須項目です。'
        ],
        'furigana' => [
            'required' => 'ふりがなは必須項目です。'
        ],
        'nick_name' => [
            'required' => 'ニックネームは必須項目です。'
        ],
        'password' => [
            'required' => 'パスワードは必須項目です。',
            'confirm' => 'パスワード再入力は必須項目です。',
            'regex' => 'パスワードに正しい形式を入力してください。'
        ],
        'password_confirmation' => [
            'required' => 'パスワード再入力は必須項目です。',
            'confirmed' => 'パスワードとパスワード再入力が異なります。',
        ],
        'company_name' => [
            'required' => '会社名は必須項目です。',
            'exist' => '会社名は必須項目です。'
        ],
        'industry' => [
            'required' => '業種は必須項目です。',
            'exist' => '業種は必須項目です。'
        ], 'occupation' => [
            'required' => '職種は必須項目です。',
            'exist' => '職種は必須項目です。'
        ], 'position' => [
            'required' => '役職は必須項目です。',
            'exist' => '役職は必須項目です。'
        ],
        'birth_year' => [
            'required' => '生まれ年は必須項目です。',
            'date' => '生まれ年は必須項目です。'
        ],
        'post_code' => [
            'invalid' => '入力形式は数字のみです。',
            'max' => '郵便番号は正しくありません。'
        ],
        'is_agree' => [
            'in' => 'Not agree to terms and conditions'
        ],
        'licence_key' => [
            'required' => 'ライセンスキーは必須項目です。',
            'exist' => 'ユーザーライセンスキーが無効です。'
        ],
        'invalid_token' => [
            'expired' => 'トークンは無効です。'
        ],
        'common_error' => ['エラーが発生しました。'],
        'email_list_empty' => ['メールリストを空にすることはできません。'],
        'telephone_no' => [
            'size' => '電話番号は正しい形式ではありません。',
            'min' => '電話番号は正しい形式ではありません。',
            'regex' => '電話番号は正しい形式ではありません。'
        ],
        'password_reset' => [
            'user_not_found' => 'メールアドレスは存在しません。'
        ],
        'image' => [
            'type' => 'イメージの形式はjpeg,jpg,pngのみです。',
            'size_10' => 'イメージサイズを10MB以下にしてください。'
        ],
        'course' => [
            'name.required' => '有効なメールアドレスを入力してください。',
            'thumbnail.required' => 'サムネイルが必修です。',
            'categories.required' => 'この項目が必修です。',
            'keywords.required' => 'キーワードが必修です。',
            'summery.required' => 'メールアドレスは必須項目です。',
            'reports.required' => 'メールアドレスは必須項目です。',
        ],
        'alert'=>[
            'login.page.message'=>'担当者登録が完了しました。'
        ]
    ]
];
