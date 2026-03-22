<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#036">
        <title>Motiv App</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7537204439684763"
     crossorigin="anonymous"></script>

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
    <body class="font-montserrat">

        <div class="block md:hidden">
            @include('orders.catalog.mobile')
        </div>

        <div class="hidden md:block">
            @include('orders.catalog.desktop')
        </div>

    </body>

</html>

<script>

    let cardProducts = document.getElementsByClassName('class_card_product');
    let categoriesElements = document.getElementsByClassName('div-categoria');
    // let initialCategoryId = categoriesElements[0].getAttribute('data-categoryId');

    let url = window.location.href;
    let partnerLink = url.split('/')[4];

    function formatPrice(price) {
        let formattedPrice = price.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        // Remove o "R$" da string formatada
        formattedPrice = formattedPrice.replace('R$', '').trim();
        return formattedPrice;
    }

    function getAllProducts() {
        $.ajax({
            type: "GET",
            url: "/get-products-by-partner",
            success: function (data) {

                // Processar os dados
                data.forEach(product => {
                    product.price = parseFloat(product.price);
                    product.old_price = parseFloat(product.old_price);
                });

                // Renderizar os dados nos componentes
                renderMobileCatalog(data);
                renderDesktopCatalog(data);
            }
        });
    }

    function renderMobileCatalog(data) {

        if(data.length == 0) {
            document.getElementById('list-products-by-category').innerHTML = `
                <div class="w-full flex justify-center items-center">
                    <p class="text-gray-500">Não há produtos no momento.</p>
                </div>
            `;
            return;
        } else {
            console.log(data, 'meus dados')
            let cards = '';
            data.forEach(product => { 
                cards += `
                    <div id="car-product" class="class_card_product card p-1 w-full rounded-xl shadow-sm pb-4 bg-white mt-3">
                        <a href="/orders/${partnerLink}/product/${product.id}">
                            <img class="rounded-t-lg w-full h-32 md:h-44 object-cover object-center" 
                            src="${(product.images && product.images.length > 0) 
                                    ? `/storage/${product.images[0].url}` 
                                    : '/img/image-not-found.png'}" 
                            alt="${product.name}">
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
                                            ${product.properties.miliage} Km
                                        </div>
                                        <div class="leading-4 break-words flex mt-2 gap-2 flex-wrap">
                                            <div class="avista text-[10px] bg-sky-500 px-1 rounded text-white ${product.in_sight == 1 ? '' : 'hidden'}">
                                                À vista    
                                            </div>
                                            <div class="financiamento text-[10px] bg-blue-500 px-1 rounded text-white ${product.financing == 1 ? '' : 'hidden'}">
                                                Financiamento
                                            </div>
                                            <div class="consorcio text-[10px] bg-blue-900 px-1 rounded text-white ${product.consortium == 1 ? '' : 'hidden'}">
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
            // Inserir os cards no componente mobile
            document.getElementById('list-products-by-category').innerHTML = cards;
        }


    }

    function renderDesktopCatalog(data) {

        if(data.length == 0) {
            document.getElementById('list-products-by-category-desktop').innerHTML = `
                <div class="w-full flex justify-center items-center">
                    <p class="text-gray-500">Não há produtos no momento.</p>
                </div>
            `;
            return;
        } else {
            let cards = '';
            data.forEach(product => {
                cards += `
                    <div id="car-product" class="class_card_product rounded-xl card p-1 w-full shadow-sm pb-4 bg-white">
                        <a href="/orders/${partnerLink}/product/${product.id}">
                            <img class="rounded-t-lg w-full h-32 md:h-44 object-cover object-center" 
                            src="${(product.images && product.images.length > 0) 
                                    ? `/storage/${product.images[0].url}` 
                                    : '/img/image-not-found.png'}" 
                            alt="${product.name}">
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
                                            ${product.properties.miliage != null ? product.properties.miliage+' KM' : ''}
                                        </div>
                                        <div class="leading-4 break-words flex mt-2 gap-2 flex-wrap">
                                            <div class="avista text-[10px] bg-sky-500 px-1 rounded text-white ${product.in_sight == 1 ? '' : 'hidden'}">
                                                À vista    
                                            </div>
                                            <div class="financiamento text-[10px] bg-blue-500 px-1 rounded text-white ${product.financing == 1 ? '' : 'hidden'}">
                                                Financiamento
                                            </div>
                                            <div class="consorcio text-[10px] bg-blue-900 px-1 rounded text-white ${product.consortium == 1 ? '' : 'hidden'}">
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
            // Inserir os cards no componente desktop
            document.getElementById('list-products-by-category-desktop').innerHTML = cards;
        }


    }

    $(document).ready(function () {
        // categoriesElements[0].classList.add('active-category')
        getAllProducts();
    });


    // MUDANDO CATEGORIA DE ACORDO COM O CLIQUE
    $(".div-categoria").click(function(e){
        e.preventDefault();            
        const element = e.target
        console.log(element, 'element');

        for (let i = 0; i < categoriesElements.length; i++) {
            categoriesElements[i].classList.remove('active-category');
        }

        element.classList.add('active-category');

        if(element.getAttribute('data-categoryId') == 'todos') {
            getAllProducts();
            return;
        } else {
            getProductByCategory(element.getAttribute('data-categoryId'));
        }

    });

    function getProductByCategory(dataElement){
            $.ajax({
                type: "GET",
                url: "/orders/get-products-by-category/" + dataElement,
                success: function (data) {
                    console.log(data, 'data by category');

                    // // Processar os dados
                    // data.forEach(product => {
                    //     product.price = parseFloat(product.price);
                    //     product.old_price = parseFloat(product.old_price);
                    // });

                    // // Renderizar os dados nos componentes
                    // renderMobileCatalog(data);
                    // renderDesktopCatalog(data);
                }
            })
        }


    // BUSCANDO PRODUTOS COM BASE NA SUBCATEGORIA OU NOME
    $('.inputSearchCatalog').on('input', function() {
        // console.log(cardProducts, 'Card products');
        let searchValue = $(this).val().toLowerCase();

        $.ajax({
            type: "GET",
            url: "/catalog/search?search=" + searchValue,
            success: function (data) {
                // console.log(data, 'data search');

                //Processar os dados
                data.data.forEach(product => {
                    product.price = parseFloat(product.price);
                    product.old_price = parseFloat(product.old_price);
                });

                // Renderizar os dados nos componentes
                renderMobileCatalog(data.data);
                // renderDesktopCatalog(data.data);
            }
        })
    });
</script>
