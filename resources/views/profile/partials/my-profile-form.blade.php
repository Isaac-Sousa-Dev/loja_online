<section class="bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100 mt-3 md:mt-0">
    <header class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                <i class="fa-solid fa-user text-sm"></i>
            </div>
            <h2 class="font-semibold text-xl text-gray-800">Meu Usuário</h2>
        </div>

        <div class="bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-xl px-4 py-2 text-sm font-semibold cursor-pointer transition">
            Editar
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form id="form-profile" method="post" action="{{ route('profile.update') }}" class="mt-4" enctype="multipart/form-data">
        @csrf
        @method('put')

        <div class="flex flex-col md:flex-row items-center md:items-start gap-6 bg-gray-50 rounded-2xl p-6 border border-gray-100 hover:border-blue-100 transition">
            
            {{-- IMAGE PROFILE --}}
            <label for="file-profile" class="relative group cursor-pointer flex-shrink-0">
                <div class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-white shadow-md relative overflow-hidden bg-blue-100 flex items-center justify-center transition group-hover:border-blue-50">
                    
                    @if($imageProfile != null && $imageProfile != '/storage/')
                        <img id="profileImagePreview" accept="image/*" src="{{ $imageProfile }}" class="w-full h-full object-cover"> 
                    @else
                        <img id="profileImagePreview" accept="image/*" src="{{ $imageProfile }}" class="w-full h-full object-cover hidden"> 
                        <i id="default-profile-icon" class="fa-solid fa-user text-4xl text-blue-300"></i>
                    @endif

                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                        <span class="text-white font-medium text-xs flex gap-1 items-center">
                            <i class="fa-solid fa-camera"></i> Alterar
                        </span>
                    </div>
                </div>
            </label>
            <input type="file" id="file-profile" class="hidden" name="image-profile" accept="image/*" onchange="previewImage(event, 'profileImagePreview');">

            {{-- USER INFO --}}
            <div class="flex flex-col items-center md:items-start text-center md:text-left w-full mt-2 md:mt-0">
                <div class="text-2xl font-bold text-gray-800 mb-2">
                    {{$user->name}}
                </div>
                
                <div class="mb-4">
                    <span class="bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
                        {{$user->role == 'partner' ? 'Administrador' : 'Vendedor'}}
                    </span>
                </div>

                <div class="space-y-3 mt-2 w-full">
                    <div class="flex items-center gap-3 text-gray-600 bg-white p-3 rounded-xl border border-gray-100 justify-center md:justify-start hover:shadow-sm transition">
                        <i class="fa-solid fa-envelope text-blue-400 w-5 text-center"></i>
                        <span class="font-medium text-sm">{{$user->email}}</span>
                    </div>
                    
                    <div class="flex items-center gap-3 text-gray-600 bg-white p-3 rounded-xl border border-gray-100 justify-center md:justify-start hover:shadow-sm transition">
                        <i class="fa-brands fa-whatsapp text-blue-400 w-5 text-center"></i>
                        <span class="font-medium text-sm">{{$partner->phone ?? 'Não informado'}}</span>
                    </div>
                </div>
            </div>

        </div>

    </form>

    <script>
        function previewImage(event, previewId) {
            var formProfile = document.getElementById('form-profile');
            
            var reader = new FileReader();
            var imageProfile = event.target.files[0];

            reader.onload = function () {
                var output = document.getElementById(previewId);
                output.src = reader.result;
                output.classList.remove('hidden');

                if(document.getElementById('default-profile-icon')) {
                    document.getElementById('default-profile-icon').classList.add('hidden');
                }
                
                formProfile.submit();
            };
            reader.readAsDataURL(imageProfile);
        }
    </script>
</section>
