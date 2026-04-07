<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Vistoo</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
    </style>
</head>

<body class="bg-slate-50 font-montserrat">
    <div class="bg-white h-[320px] rounded-b-[60px] shadow-sm">
        <div class="h-32 md:h-80 w-[100%] md:w-full md:rounded-b-[80px] flex items-center justify-center">

            @if ($bannerStore != null)
                <div class="object-cover h-full w-full rounded-b-[100px] border-4 border-t-0 border-white">
                    <img src="{{ $bannerStore }}" alt=""
                        class="object-cover object-center h-full w-full rounded-b-[100px]" width="80%"
                        height="100%">
                </div>
            @else
                <div class="object-cover bg-blue-800 h-full w-full rounded-b-[100px] border-4 border-t-0 border-white">
                </div>
            @endif

            @if ($logoStore != null)
                <div class=" bg-slate-200 md:ml-[12%] mt-32 w-32 h-32 rounded-full flex justify-center items-center absolute"
                    style="border: 4px solid white; overflow: hidden;">
                    <img src="{{ $logoStore }}" class="object-cover object-center w-full h-full" alt="Logo">
                </div>
            @else
                <div class=" bg-slate-200 md:ml-[12%] mt-32 min-[414px]:h-[20%] w-[35%] min-[390px]:h-[16%] min-[414px]:w-[43%] rounded-full flex justify-center items-center absolute"
                    style="border: 4px solid white; overflow: hidden;">
                    <img src="https://s3-sa-east-1.amazonaws.com/projetos-artes/fullsize%2F2020%2F12%2F12%2F09%2FLogo-272799_34049_091615787_599428780.jpg"
                        class="object-cover" alt="">
                </div>
            @endif
        </div>

        <div
            class="min-[414px]:-mt-1 min-[412px]:-mt-2 min-[414px]:h-52 mx-2 flex justify-center flex-col rounded-bg-lg">
            <div class=" h-28 mt-16 text-center min-[414px]:mt-24 min-[412px]:mt-20 w-full mb-0">
                <h5 class="mt-2 text-black font-bold text-xl">{{ $store->store_name }}</h5>

                <p class="text-black text-xs">{{ $store->store_email }}</p>
                <p class="text-black text-sm mask-phone">{{ $store->store_phone }}</p>

                @if ($itsOpen)
                    <div class="text-green-400 text-sm font-bold flex items-center gap-1 justify-center">
                        <span class="bg-green-400 h-2 w-2 rounded-full mt-[1px]"></span>Aberto agora
                    </div>
                @else
                    <div class="text-red-400 text-sm font-bold flex items-center gap-1 justify-center mt-1">
                        Fechado agora
                    </div>
                @endif
            </div>
        </div>

    </div>


    <div>
        <div class="px-3 mt-4 flex justify-between h-12">
            <div class="text-sm py-1 w-1/6 items-center flex">
                <ion-icon name="filter-outline" class="text-2xl font-medium"></ion-icon>
            </div>

            <div class="w-5/6 space-x-2 flex justify-end items-center relative">
                <input type="text" class="w-full h-11 rounded-full border border-gray-400 pl-7"
                    placeholder="Pesquise aqui...">
                <ion-icon name="search-outline"
                    class="search-icon text-2xl absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></ion-icon>
            </div>
        </div>
        <div id="div-categorias" class="mt-4 py-2 px-3 flex space-x-3 w-full overflow-x-auto">
            <div class="flex space-x-3">
                @if (count($categories) > 1)
                    <div data-categoryId="todos" style="letter-spacing: 1px"
                        class="font-bold w-24 justify-center text-white rounded-full p-1 flex div-categoria bg-blue-600 cursor-pointer">
                        Todos
                    </div>
                @endif
                @foreach ($categories as $category)
                    <div style="letter-spacing: 1px"
                        class="font-bold text-white w-24 justify-center rounded-full p-1 flex div-categoria bg-blue-600 cursor-pointer"
                        data-categoryId="{{ $category['id'] }}">
                        {{ $category['name'] }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>


    <div id="list-products-by-category" class="grid grid-cols-2 px-3 gap-2 justify-between flex-wrap mt-3 mb-10">

    </div>

</body>

<script>
    let itemCount = 1;
    let cardProducts = document.getElementsByClassName('class_card_product');
    let categoriesElements = document.getElementsByClassName('div-categoria');
    let initialCategoryId = categoriesElements[0].getAttribute('data-categoryId');
    let listOfProductsByCategory = document.getElementById('list-products-by-category');


    let url = window.location.href;
    let partnerLink = url.split('/')[4];
    console.log(partnerLink);


    function formatPrice(price) {
        let formattedPrice = price.toLocaleString('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        });
        formattedPrice = formattedPrice.replace('R$', '').trim();
        return formattedPrice;
    }

    function getAllProducts() {
        $.ajax({
            type: "GET",
            url: "/get-products-by-partner",
            success: function(data) {
                console.log(data);

                let cards = '';
                data.forEach(product => {

                    product.price = parseFloat(product.price);
                    product.old_price = parseFloat(product.old_price);

                    console.log(product, 'product');

                    cards += `
                            <div id="car-product" class="class_card_product card p-1 w-full shadow-sm pb-4 bg-white mt-3 ">
                                <a href="/catalog/${partnerLink}/product/${product.id}">
                                    <img class="rounded-t-lg w-full h-32 object-cover object-center" src="/storage/${product.image_main.replace('', 'public/')}" alt="" >
                                    <div class="h-1/2 flex justify-between flex-col">
                                        <div class="mt-2 mx-1">
                                            <div class="h-6 flex">
                                                <div class="flex gap-1 items-end">
                                                    <div class="text-sm font-medium leading-none flex items-end mb-[1px]">R$</div>
                                                    <div class="flex text-lg font-semibold text-blue-700 leading-none items-end">${formatPrice(product.price)}</div>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="mt-2 leading-4 font-semibold text-xs uppercase text-gray-500">${product.name}</p>

                                                <div class="italic text-xs mt-1">
                                                    ${product.stock} Km
                                                </div>

                                                <div class="leading-4 break-words flex mt-2 gap-2 flex-wrap">
                                                    <div class="avista text-[10px] bg-sky-500 px-1 rounded text-white hidden">
                                                        À vista    
                                                    </div>
                                                    <div class="financiamento text-[10px] bg-blue-500 px-1 rounded text-white hidden">
                                                        Financiamento
                                                    </div>
                                                    <div class="consorcio text-[10px] bg-blue-900 px-1 rounded text-white hidden">
                                                        Consórcio
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        `;

                });

                listOfProductsByCategory.innerHTML = cards;

                data.forEach(product => {
                    let divOldPrice = document.getElementsByClassName('div-old-price');

                    if (product.price < product.old_price) {
                        divOldPrice[0].classList.remove('hidden');
                    } else {
                        divOldPrice[0].classList.add('hidden');
                    }

                    if (product.in_sight == 1) {
                        let avista = document.getElementsByClassName('avista');
                        avista[0].classList.remove('hidden');
                    }

                    if (product.financing == 1) {
                        let financiamento = document.getElementsByClassName('financiamento');
                        financiamento[0].classList.remove('hidden');
                    }

                    if (product.consortium == 1) {
                        let consorcio = document.getElementsByClassName('consorcio');
                        consorcio[0].classList.remove('hidden');
                    }
                });


            }
        });
    }


    function getProductByCategory(dataElement) {
        $.ajax({
            type: "GET",
            url: "/catalog/get-products-by-category/" + dataElement,
            success: function(data) {

                if (data.length == 0) {
                    listOfProductsByCategory.innerHTML = `
                            <div class="w-full flex justify-center items-center">
                                <p class="text-center text-gray-400">Nenhum produto encontrado</p>
                            </div>
                        `;
                } else {
                    let cards = '';
                    data.forEach(product => {

                        product.price = parseFloat(product.price);
                        product.old_price = parseFloat(product.old_price);

                        cards += `
                                <div id="car-product" class="class_card_product card p-1 w-[50%] pb-4 bg-white mt-3">
                                    <a href="/catalog/${partnerLink}/product/${product.id}">
                                        <div>teste</div>
                                        <img class="rounded-lg w-full h-32 object-cover" src="/storage/${product.image_main.replace('/public/', '')}" alt="" >
                                        <div class="h-1/2 flex justify-between flex-col">
                                            <div class="mt-2 mx-1">
                                                <div>
                                                    <p class="mt-2 leading-4 font-bold text-blue-950">${formatPrice(product.price)}</p>
                                                    <div class="hidden div-old-price mt-0 leading-4 font-semibold text-gray-500 text-[12px] line-through">${formatPrice(product.old_price)}</div>
                                                </div>
                                                <div>
                                                    <p class="mt-2 leading-4 font-bold text-sm">${product.name}</p>

                                                    <div class="leading-4 break-words flex mt-2 gap-2 flex-wrap">
                                                        <div class="avista text-[10px] bg-sky-500 px-1 rounded text-white hidden">
                                                            À vista    
                                                        </div>
                                                        <div class="financiamento text-[10px] bg-blue-500 px-1 rounded text-white hidden">
                                                            Financiamento
                                                        </div>
                                                        <div class="consorcio text-[10px] bg-blue-900 px-1 rounded text-white hidden">
                                                            Consórcio
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            `;

                    });
                    listOfProductsByCategory.innerHTML = cards;

                    data.forEach(product => {
                        let divOldPrice = document.getElementsByClassName('div-old-price');

                        if (product.price < product.old_price) {
                            divOldPrice[0].classList.remove('hidden');
                        } else {
                            divOldPrice[0].classList.add('hidden');
                        }

                        if (product.in_sight == 1) {
                            let avista = document.getElementsByClassName('avista');
                            avista[0].classList.remove('hidden');
                        }

                        if (product.financing == 1) {
                            let financiamento = document.getElementsByClassName('financiamento');
                            financiamento[0].classList.remove('hidden');
                        }

                        if (product.consortium == 1) {
                            let consorcio = document.getElementsByClassName('consorcio');
                            consorcio[0].classList.remove('hidden');
                        }
                    });
                }

            }
        })
    }


    $(document).ready(function() {
        categoriesElements[0].classList.add('active-category')
        getAllProducts();
    });


    // MUDANDO CATEGORIA DE ACORDO COM O CLIQUE
    $(".div-categoria").click(function(e) {
        e.preventDefault();
        const element = e.target

        for (let i = 0; i < categoriesElements.length; i++) {
            categoriesElements[i].classList.remove('active-category');
        }

        element.classList.add('active-category');

        if (element.getAttribute('data-categoryId') == 'todos') {
            getAllProducts();
            return;
        } else {
            getProductByCategory(element.getAttribute('data-categoryId'));
        }

    });


    for (let i = 0; i < cardProducts.length; i++) {
        cardProducts[i].addEventListener('click', function(event) {
            console.log(event)
        });
    }





    // CONTROLE DE ABAS NO PERFIL DO PARCEIRO
    // $("#btn-atendimento").click(function(e){
    //     e.preventDefault();
    //     $("#btn-atendimento").addClass('active');
    //     $("#btn-endereco").removeClass('active');
    //     $("#btn-horarios").removeClass('active');

    //     $("#div-atendimento").removeClass('hidden');
    //     $("#div-endereco").addClass('hidden');
    //     $("#div-horarios").addClass('hidden');
    // });

    // $("#btn-endereco").click(function(e){
    //     e.preventDefault();
    //     $("#btn-endereco").addClass('active');
    //     $("#btn-atendimento").removeClass('active');
    //     $("#btn-horarios").removeClass('active');

    //     $("#div-atendimento").addClass('hidden');
    //     $("#div-endereco").removeClass('hidden');
    //     $("#div-horarios").addClass('hidden');
    // });

    // $("#btn-horarios").click(function(e){
    //     e.preventDefault();
    //     $("#btn-horarios").addClass('active');
    //     $("#btn-atendimento").removeClass('active');
    //     $("#btn-endereco").removeClass('active');

    //     $("#div-atendimento").addClass('hidden');
    //     $("#div-endereco").addClass('hidden');
    //     $("#div-horarios").removeClass('hidden');
    // });


    // function updateTotal(item) {
    //     const totalValue = document.getElementById('totalValue');
    //     const total = itemCount * item;
    //     totalValue.textContent = `Valor total: ${total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL'})}`;
    // }

    // function incrementItem(item){
    //     itemCount++;
    //     console.log(itemCount)
    //     document.getElementById('itemCount').textContent = itemCount;
    //     updateTotal(item);
    // }

    // function removeItem(item){
    //     if(itemCount > 1) {
    //         itemCount--;
    //         document.getElementById('itemCount').textContent = itemCount;
    //         updateTotal(item);
    //     }
    // }



    // function addItem(e, item){
    //     e.preventDefault();
    //     console.log(item)
    //     console.log('cheguei até aqui')

    //     // Aqui você pode manipular o conteúdo do modal com as informações do item clicado
    //     const modalContent = document.getElementById('modalContent');
    //     modalContent.innerHTML = `
    //     <div class="bg-white rounded-md px-1 py-4">
    //         <div>
    //             <img src="/storage/${item.images[0]['url']}" height=290 width=290 class="rounded-md">
    //         </div>
    //         <div class="text-center mt-2">
    //             <h2 class="uppercase font-bold">${item.name}</h2>
    //             <p>${item.description}</p>
    //             <div class="flex justify-center gap-4 mt-3">
    //                 <button onclick="removeItem(${item.price})" class="bg-gray-300 px-2 rounded-full font-bold">-</button>
    //                 <span id="itemCount">1</span>
    //                 <button onclick="incrementItem(${item.price})" class="bg-gray-300 px-2 rounded-full font-bold">+</button>
    //             </div>
    //             <p id="totalValue" class="mt-2">Valor total: R$ ${item.price}</p>
    //         </div>
    //     </div>
    //     `;

    //     document.getElementById('modal').classList.remove('hidden');
    // }

    // Função para fechar o modal
    // function closeModal() {
    //     // Ocultar o modal
    //     document.getElementById('modal').classList.add('hidden');
    // }

    // document.getElementById('closeModal').addEventListener('click', closeModal);
</script>


<style>
    .search-icon {
        position: absolute;
        right: ;
        : 10px;
        /* Ajuste a posição horizontal */
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        /* Para evitar que o ícone interfira na interação com o input */
    }

    .active {
        border-bottom: 1px solid black;
    }

    .active-category {
        box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
    }
</style>

</html>
