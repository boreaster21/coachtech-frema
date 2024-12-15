<section class="section-container">
    <header>
        <h2 class="section-title">パスワードを変更する</h2>
        <p class="password-update-description">安全な長いランダムなパスワードを使用して、アカウントを保護してください。</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="password-form">
        @csrf
        @method('put')

        <div class="form-group">
            <x-input-label for="current_password" :value="'現在のパスワード'" />
            <x-text-input id="current_password" name="current_password" type="password" class="form-input" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="error-message" />
        </div>

        <div class="form-group">
            <x-input-label for="new_password" :value="'新しいパスワード'" />
            <x-text-input id="new_password" name="password" type="password" class="form-input" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="error-message" />
        </div>

        <div class="form-group">
            <x-input-label for="password_confirmation" :value="'新しいパスワード（確認）'" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="form-input" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="error-message" />
        </div>

        <div class="form-actions">
            <button type="submit" class="form-button">保存する</button>

            @if (session('status') === 'password-updated')
            <p class="update-success-message">パスワードが更新されました。</p>
            @endif
        </div>
    </form>
</section>