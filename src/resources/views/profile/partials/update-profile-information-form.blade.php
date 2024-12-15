<section class="section-container">
    <header>
        <h2 class="section-title">プロフィール設定</h2>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="profile-form" enctype="multipart/form-data">

        @csrf
        @method('patch')

        <div>
            <x-picture-input />
            <x-input-error class="error-message" :messages="$errors->get('picture')" />
        </div>

        <div class="form-group">
            <x-input-label for="name" :value="'ユーザー名'" />
            <x-text-input id="name" name="name" type="text" class="form-input" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="error-message" :messages="$errors->get('name')" />
        </div>

        <div class="form-group">
            <x-input-label for="email" :value="__('メールアドレス')" />
            <x-text-input id="email" name="email" type="email" class="form-input" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div>
                <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                    {{ __('Your email address is unverified.') }}

                    <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                        {{ __('認証メールを再送する') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                    {{ __('新しい認証リンクが指定のアドレスへ送信できませんでした') }}
                </p>
                @endif
            </div>
            @endif
        </div>

        <div class="form-group">
            <x-input-label for="postcode" :value="__('郵便番号')" />
            <x-text-input id="postcode" name="postcode" type="text" class="form-input" :value="old('postcode', $user->postcode)" autofocus autocomplete="postcode" />
            <x-input-error class="mt-2" :messages="$errors->get('postcode')" />
        </div>
        <div class="form-group">
            <x-input-label for="address" :value="__('住所')" />
            <x-text-input id="address" name="address" type="text" class="form-input" :value="old('address', $user->address)" autofocus autocomplete="address" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>
        <div class="form-group">
            <x-input-label for="building" :value="__('建物名')" />
            <x-text-input id="building" name="building" type="text" class="form-input" :value="old('building', $user->building)" autofocus autocomplete="building" />
            <x-input-error class="mt-2" :messages="$errors->get('building')" />
        </div>

        <div class="form-actions">
            <button type="submit" class="form-button">更新する</button>

            @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600 dark:text-gray-400">{{ __('プロフィールが更新されました') }}</p>
            @endif
        </div>
    </form>
</section>