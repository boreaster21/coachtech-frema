<div class="profile-picture-container" x-data="picturePreview()">
    <div class="profile-picture">
        <img
            id="preview"
            src="{{ Auth::user()->profile_photo_path 
                    ? Storage::url(Auth::user()->profile_photo_path) 
                    : asset('images/default_avatar.png') }}"
            alt="プロフィール画像"
            class="profile-image">
    </div>

    <div class="profile-picture-button">
        <button
            x-on:click="document.getElementById('picture').click()"
            type="button"
            class="image-upload-button">
            画像を選択する
        </button>
        <input
            @change="showPreview(event)"
            type="file"
            name="picture"
            id="picture"
            class="hidden"
            accept="image/*">
    </div>

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