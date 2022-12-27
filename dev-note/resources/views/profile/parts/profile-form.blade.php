<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('プロフィールを登録しましょう。') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("register your account's profile information.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="icon-img" :value="__('アイコン')" />
            <div id="img_previewer" class="mt-4 rounded-full border drop-shadow-md" style="width: 100px; height: 100px; overflow: hidden; "></div>
            <input type="file" class="mt-4" name="icon-img" data-target-id="img_previewer" data-classes="icon-img" onchange="setImgPreview(event);">
            <x-input-error class="mt-2" :messages="$errors->get('img')" />
        </div>

        <div>
            <x-input-label for="description" :value="__('プロフィール文')" />
            <textarea id="description" name="description" rows="15" cols="63" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" autofocus></textarea>
        </div>

        <div>
            <input type="hidden" name="email" value="{{ $user->email }}">
            <input type="hidden" name="name" value="{{ $user->name }}">
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </div>
    </form>
</section>

<script>
    function setImgPreview(e)
    {
        const fr = new FileReader();
        const previewElm = document.getElementById(e.target.dataset.targetId);

        fr.readAsDataURL(e.target.files[0]);

        fr.onload = function () {
            if (previewElm.firstChild) {
                // 子要素がある場合は全削除
                while (previewElm.firstChild) {
                    previewElm.removeChild(previewElm.firstChild);
                }
            }

            const imgElm = document.createElement('img');
            imgElm.src = this.result;

            // classが与えられているか確認して、与えられている場合は
            // クラスを付与
            const classes = e.target.dataset.classes;
            if (classes) {
                const classArray = classes.split(' ');
                for (let i = 0; i < classArray.length; i++) {
                    imgElm.classList.add(classArray[i]);
                }
            }

            // imgタグを挿入
            previewElm.appendChild(imgElm);
        }
    }
</script>