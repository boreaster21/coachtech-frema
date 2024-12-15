<div class="profile-picture-container" x-data="picturePreview()">
    <!-- プロフィール画像 -->
    <div class="profile-picture">
        <img
            id="preview"
            src="{{ isset(Auth::user()->profile_photo_path) ? asset('storage/' . Auth::user()->profile_photo_path) : asset('images/user_icon.png') }}"
            alt="プロフィール画像"
            class="profile-image">
    </div>

    <!-- 画像選択ボタン -->
    <div class="profile-picture-button">
        <button
            x-on:click="document.getElementById('picture').click()"
            type="button"
            class="image-upload-button">
            画像を選択する
        </button>
        <!-- ファイル選択インプット（非表示） -->
        <input
            @change="showPreview(event)"
            type="file"
            name="picture"
            id="picture"
            class="hidden"
            accept="image/*">
    </div>

    <!-- プレビュー用のスクリプト -->
    <script>
        function picturePreview() {
            return {
                showPreview: (event) => {
                    if (event.target.files.length > 0) {
                        var src = URL.createObjectURL(event.target.files[0]);
                        document.getElementById('preview').src = src;
                    }
                }
            }
        }
    </script>
</div>