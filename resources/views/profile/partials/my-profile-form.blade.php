<section class="bg-white p-3 rounded-xl mt-3">
    <header class="flex items-center justify-between">
        <h2 class="text-md font-semibold text-gray-900">
            {{ __('Meu Usuário') }}
        </h2>

        <div class="bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg px-2 py-1 text-md font-semibold cursor-pointer">
            Editar
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form id="form-profile" method="post" action="{{ route('profile.update') }}" class="mt-2 space-y-2" enctype="multipart/form-data">
        @csrf
        @method('put')

        <div class="flex">

            <div class="items-center flex gap-4 mt-2">

                {{-- IMAGE PROFILE --}}
                <label for="file-profile" class="flex justify-center items-center cursor-pointer">
                    <div id="div-image-profile" class="bg-gray-300 flex items-center justify-center border-3 border-gray-200 relative w-20 h-20 md:w-24 rounded-full md:h-24">
                        
                        @if($imageProfile != null && $imageProfile != '/storage/')
                            <img id="profileImagePreview" accept="image/*" src="{{ $imageProfile }}" class="rounded-full w-full h-full cursor-pointer bg-gray-300 "> 
                            <div id="div-text-edit-img-profile" style="display: none" class="flex items-center gap-1 font-bold justify-center text-xs absolute pointer-events-none z-10">
                                <div class="flex gap-1">
                                    Editar Foto
                                    <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M497.9 142.1l-46.1 46.1c-4.7 4.7-12.3 4.7-17 0l-111-111c-4.7-4.7-4.7-12.3 0-17l46.1-46.1c18.7-18.7 49.1-18.7 67.9 0l60.1 60.1c18.8 18.7 18.8 49.1 0 67.9zM284.2 99.8L21.6 362.4 .4 483.9c-2.9 16.4 11.4 30.6 27.8 27.8l121.5-21.3 262.6-262.6c4.7-4.7 4.7-12.3 0-17l-111-111c-4.8-4.7-12.4-4.7-17.1 0zM124.1 339.9c-5.5-5.5-5.5-14.3 0-19.8l154-154c5.5-5.5 14.3-5.5 19.8 0s5.5 14.3 0 19.8l-154 154c-5.5 5.5-14.3 5.5-19.8 0zM88 424h48v36.3l-64.5 11.3-31.1-31.1L51.7 376H88v48z"/></svg>
                                </div>
                            </div>  
                        @else
                            <img id="profileImagePreview" accept="image/*" src="{{ $imageProfile }}" style="display: none" class="rounded-full w-full h-full cursor-pointer bg-gray-300 "> 
                            <div class="flex items-center gap-1 text-xs font-bold justify-center absolute pointer-events-none z-10">
                                Logo
                                <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M400 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zm-32 252c0 6.6-5.4 12-12 12h-92v92c0 6.6-5.4 12-12 12h-56c-6.6 0-12-5.4-12-12v-92H92c-6.6 0-12-5.4-12-12v-56c0-6.6 5.4-12 12-12h92v-92c0-6.6 5.4-12 12-12h56c6.6 0 12 5.4 12 12v92h92c6.6 0 12 5.4 12 12v56z"/></svg> 
                            </div>
                        @endif
                    </div>
                </label>
                <input type="file" id="file-profile" class="hidden" name="image-profile" onchange="previewImage(event, 'profileImagePreview');">



                <div class=" w-full">
                    <div class="text-md md:text-xl font-bold">
                        <span>{{$user->name}}</span>
                    </div>

                    <div class="text-sm font-semibold">
                        <span class="bg-primary text-white rounded-full px-2">{{$user->role == 'partner' ? 'Administrador' : 'Vendedor'}}</span>
                    </div>

                    <div class="text-sm font-medium">
                        <span>{{$user->email}}</span>
                    </div>

                    
                    <div>
                        <span>{{$partner->phone}}</span>
                    </div>
                </div>

            </div>

        </div>

    </form>

    <script>

        function previewImage(event, previewId) {

            var formProfile = document.getElementById('form-profile');
            formProfile.submit();

            var reader = new FileReader();
            var imageProfile = event.target.files[0];

            reader.onload = function () {
                var output = document.getElementById(previewId);
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(imageProfile);
        }

        $('#div-image-profile').mouseover(function (event) { 
            $('#div-text-edit-img-profile').css('display', 'block');
        });

        $('#div-image-profile').mouseout(function (event) { 
            $('#div-text-edit-img-profile').css('display', 'none');
        });

    </script>
</section>

<style>
    /* LOGO */
    #logo-profile-add:hover {
        opacity: 0.4;
        transition: 0.2s ease-in-out;
        cursor: pointer;
    }
    #profileImagePreview:hover {
        opacity: 0.4;
        transition: 0.2s ease-in-out;
        cursor: pointer;
    }
    #profileImagePreview:hover + .flex #div-text-edit-logo {
        display: block;
    }
    
    #div-text-edit-logo,
    #div-text-banner-add,
    #div-text-edit-logo,
    #div-text-edit {
        pointer-events: none;
    }


    /* BANNER */
    #img-banner-catalog:hover + .flex #div-text-edit {
        display: block;
    }
    #img-banner-catalog-add:hover {
        opacity: 0.4;
        transition: 0s ease-in-out;
        cursor: pointer;
    }
    #img-banner-catalog:hover {
        opacity: 0.4;
        transition: 0s ease-in-out;
        cursor: pointer;
    }
    #img-banner-catalog:hover + .flex #div-text-edit {
        display: block;
    }




    .form-input {
    width:160px;
    padding:5px 0px;
    background:#fff;
    border: none
    }

    .form-input img {
        width:100%;
        display:none;
        margin-bottom:30px;
    }

    .form-input input {
        display:none;
    }

    .form-input label {
        display:block;
        width:100%;
        height:45px;
        margin-left: 0px;
        line-height:50px;
        text-align:center;
        background:#1172c2;
        color:#fff;
        font-size:15px;
        font-family:"Open Sans",sans-serif;
        text-transform:Uppercase;
        font-weight:600;
        border-radius:5px;
        cursor:pointer;
    }
</style>
