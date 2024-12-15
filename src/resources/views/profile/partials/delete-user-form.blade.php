<section class="section-container">
    <header>
        <h2 class="section-title">アカウントを削除する</h2>
        <p class="account-deletion-description">アカウントを削除すると、すべてのリソースとデータが完全に削除されます。アカウント削除前に保存したいデータをダウンロードしてください。</p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">アカウントを削除する</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="deletion-confirmation-form">
            @csrf
            @method('delete')

            <h2 class="confirmation-title">本当にアカウントを削除しますか？</h2>

            <p class="confirmation-description">
                アカウントを削除すると、すべてのデータが完全に削除されます。削除を確認するには、パスワードを入力してください。
            </p>

            <div class="form-group">
                <x-input-label for="password" value="パスワード" class="sr-only" />
                <x-text-input id="password" name="password" type="password" class="form-input" placeholder="パスワード" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="error-message" />
            </div>

            <div class="form-actions">
                <x-secondary-button x-on:click="$dispatch('close')">キャンセル</x-secondary-button>
                <x-danger-button class="confirm-deletion-button">アカウントを削除する</x-danger-button>
            </div>
        </form>
    </x-modal>
</section>