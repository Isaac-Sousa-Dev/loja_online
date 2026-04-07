<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Motiv</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        {{-- <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet"> --}}
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

       

        <script>
            $(document).ready(function() {
                $('.price-mask').mask('000.000.000.000.000,00', {reverse: true});
                $('.year-manufacturer-mask').mask('0000/0000');
                $('.phone-mask').mask('(00) 00000-0000');
                $('.cep-mask').mask('00000-000');
                $('.cpf-cnpj-mask').mask('00.000.000/0000-00', { // Máscara inicial genérica para CPF/CNPJ
                    onKeyPress: function(value, e, field, options) {
                        // Remove todos os caracteres não numéricos
                        var length = value.replace(/\D/g, '').length;

                        
                        // Escolhe a máscara com base na quantidade de dígitos
                        var mask = (length > 11) ? '00.000.000/0000-00' : '000.000.000-000';
                        
                        // Aplica a máscara dinamicamente
                        field.mask(mask, options);
                    }
                });
            });

        </script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            html, body {
              overflow-x: hidden;
            }

            body {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="bg-white">
            <header class="bg-white shadow-md z-10 fixed justify-between top-0 w-full pt-3">

              <div class="flex justify-between w-full px-3 md:px-32">
                <div class="flex gap-6 items-center">
                    <img src="/img/logos/logo.png" width="100" alt="">                   
                </div>
  
                <a href="{{ route('login') }}" class="text-white font-medium flex items-center gap-2 rounded-xl bg-blue-700 px-5 py-2">
                    <div>Cancelar</div>
                </a>
              </div>
                

              <div class="text-white py-2 mt-2 font-semibold flex justify-center bg-[#000051]">
                PAGAMENTO ONLINE
              </div>
            </header>

            <div class="pt-24 pb-10">
            
              <div class="flex px-2">

                <div class="bg-white pt-6 w-full md:px-10 flex flex-col">

                  <section class="w-full mb-6">
                    <div class="font-semibold text-gray-700 text-xl">
                      Resumo do pedido
                    </div>

                    <div class="bg-white border border-gray-400 w-full rounded-xl mt-2 py-2 px-3">
                      <label for="plan">Assinatura/Plano</label>
                      <select id="plan_slug" class="w-full rounded-xl py-2 px-3 mt-1 font-semibold text-blue-700">
                          <option value="test" class="font-semibold">PLANO TEST</option>
                          <option value="start-plus" class="font-semibold">START PLUS</option>
                          <option value="advanced" class="font-semibold">ADVANCED</option>
                      </select>

                      <div class="mt-3 flex justify-between">
                        <div class="px-2 rounded-md text-gray-800 font-semibold">
                          Teste 
                          <span class="text-green-600">Grátis</span> 
                          por 30 dias
                        </div>

                        <div>
                          <span class="text-xs font-semibold">Valor total</span>
                          <div>
                            R$ 89,99
                          </div>
                        </div>
                      </div>
                    </div>
                  </section>

                  <section class="md:w-1/2">
                    <div class="flex flex-col justify-between md:items-center">
                      <div class="font-semibold text-xl">
                        Método de pagamento
                      </div>
                      <div class="text-xs text-gray-800 flex items-center gap-1">
                        Seguro e criptografado
                        <svg class="w-5 h-5 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                          <path fill-rule="evenodd" d="M11.644 3.066a1 1 0 0 1 .712 0l7 2.666A1 1 0 0 1 20 6.68a17.694 17.694 0 0 1-2.023 7.98 17.406 17.406 0 0 1-5.402 6.158 1 1 0 0 1-1.15 0 17.405 17.405 0 0 1-5.403-6.157A17.695 17.695 0 0 1 4 6.68a1 1 0 0 1 .644-.949l7-2.666Zm4.014 7.187a1 1 0 0 0-1.316-1.506l-3.296 2.884-.839-.838a1 1 0 0 0-1.414 1.414l1.5 1.5a1 1 0 0 0 1.366.046l4-3.5Z" clip-rule="evenodd"/>
                        </svg>                        
                      </div>
                    </div>

                    <div>
                      <div class="accordion-container">
                        <div class="set">
                          <a href="#">
                            Cartões 
                            <i class="fa fa-plus"></i>
                          </a>
                          <div class="content">
                            <form id="form-checkout" class="bg-white mt-4 mb-2 flex flex-col gap-2">
                              <div id="form-checkout__cardNumber" class="container"></div>

                              <div class="flex gap-2">
                                <div id="form-checkout__expirationDate" class="container"></div>
                                <div id="form-checkout__securityCode" class="container"></div>
                              </div>
                              <input class="rounded-md" type="text" id="form-checkout__cardholderName" />
                              <select class="rounded-md" id="form-checkout__issuer"></select>
                              <select class="rounded-md" id="form-checkout__installments"></select>
                              <select class="rounded-md" id="form-checkout__identificationType"></select>
                              <input class="rounded-md" type="text" id="form-checkout__identificationNumber" />
                              <input class="rounded-md" type="email" id="form-checkout__cardholderEmail" />
                          
                              <button class="px-2 py-1 bg-blue-600" type="submit" id="form-checkout__submit">Pagar</button>
                              {{-- <progress value="0" class="progress-bar">Carregando...</progress> --}}
                            </form>
                          </div>
                        </div>
                        {{-- <div class="set">
                          <a href="#">
                            Pix 
                            <i class="fa fa-plus"></i>
                          </a>
                          <div class="content">
                            <p> Aliquam cursus vitae nulla non rhoncus. Nunc condimentum erat nec dictum tempus. Suspendisse aliquam erat hendrerit vehicula vestibulum.</p>
                          </div>
                        </div> --}}
                      </div>
                    </div>
                    
                  </section>
  
                  
                </div>

                
              </div>
              <div class="bg-white fixed bottom-0 w-full p-3 flex justify-center z-10">
                <button id="" class="bg-blue-700 px-4 py-3 w-full rounded-lg font-bold text-white">Pagar</button>
            </div>
            </div>

            <script>
            
                $(document).ready(function() {
                  const mp = new MercadoPago('{{$publicKey}}');
                  let amount = '89.99';

                  let formCheckout = $('#form-checkout');
                  console.log(formCheckout, 'My form')

                  const cardForm = mp.cardForm({
                    amount: amount,
                    iframe: true,
                    form: {
                      id: "form-checkout",
                      cardNumber: {
                        id: "form-checkout__cardNumber",
                        placeholder: "Número do cartão",
                      },
                      expirationDate: {
                        id: "form-checkout__expirationDate",
                        placeholder: "MM/YY",
                      },
                      securityCode: {
                        id: "form-checkout__securityCode",
                        placeholder: "CVC/CVV",
                      },
                      cardholderName: {
                        id: "form-checkout__cardholderName",
                        placeholder: "Titular do cartão",
                      },
                      issuer: {
                        id: "form-checkout__issuer",
                        placeholder: "Banco emissor",
                      },
                      installments: {
                        id: "form-checkout__installments",
                        placeholder: "Parcelas",
                      },        
                      identificationType: {
                        id: "form-checkout__identificationType",
                        placeholder: "Tipo de documento",
                      },
                      identificationNumber: {
                        id: "form-checkout__identificationNumber",
                        placeholder: "Número do documento",
                      },
                      cardholderEmail: {
                        id: "form-checkout__cardholderEmail",
                        placeholder: "E-mail",
                      },
                    },
                    callbacks: {
                      onFormMounted: error => {
                        if (error) return console.warn("Form Mounted handling error: ", error);
                        console.log("Form mounted");
                      },
                      onSubmit: event => {
                        event.preventDefault();

                        const {
                          paymentMethodId: payment_method_id,
                          issuerId: issuer_id,
                          cardholderEmail: email,
                          amount,
                          token,
                          installments,
                          identificationNumber,
                          identificationType,
                        } = cardForm.getCardFormData();

                        fetch("/process_payment", {
                          method: "POST",
                          headers: {
                            "Content-Type": "application/json",
                          },
                          body: JSON.stringify({
                            token,
                            issuer_id,
                            payment_method_id,
                            transaction_amount: Number(amount),
                            installments: Number(installments),
                            description: "Descrição do produto",
                            payer: {
                              email,
                              identification: {
                                type: identificationType,
                                number: identificationNumber,
                              },
                            },
                          }),
                        });
                      },
                      onFetching: (resource) => {
                        console.log("Fetching resource: ", resource);

                        // Animate progress bar
                        const progressBar = document.querySelector(".progress-bar");
                        progressBar.removeAttribute("value");

                        return () => {
                          progressBar.setAttribute("value", "0");
                        };
                      }
                    },
                  });


                  $(".set > a").on("click", function() {
                    if ($(this).hasClass("active")) {
                      $(this).removeClass("active");
                      $(this)
                        .siblings(".content")
                        .slideUp(200);
                      $(".set > a i")
                        .removeClass("fa-minus")
                        .addClass("fa-plus");
                    } else {
                      $(".set > a i")
                        .removeClass("fa-minus")
                        .addClass("fa-plus");
                      $(this)
                        .find("i")
                        .removeClass("fa-plus")
                        .addClass("fa-minus");
                      $(".set > a").removeClass("active");
                      $(this).addClass("active");
                      $(".content").slideUp(200);
                      $(this)
                        .siblings(".content")
                        .slideDown(200);
                    }
                  });
                });
            </script>

        </div>

    <script src="https://sdk.mercadopago.com/js/v2"></script>
    </body>
    <style>
      /* Form Checkout Styles */
      #form-checkout {
        display: flex;
        flex-direction: column;
        max-width: 600px;
        padding: 0px 10px;
      }

      .container {
        height: 40px;
        display: inline-block;
        border: 1px solid rgb(118, 118, 118);
        border-radius: 5px;
        padding: 1px 15px;
      }

      /* Accordions Styles */
      .accordion-container{
        position: relative;
        max-width: 600px;
        height: auto;
        margin: 30px auto;
      }
      .accordion-container > h2{
        text-align: center;
        color: #fff;
        padding-bottom: 5px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #ddd;
      }
      .set{
        position: relative;
        width: 100%;
        height: auto;
        background-color: #f5f5f5;
      }
      .set > a{
        display: block;
        padding: 10px 15px;
        text-decoration: none;
        color: #555;
        font-weight: 600;
        border-bottom: 1px solid #ddd;
        -webkit-transition:all 0.2s linear;
        -moz-transition:all 0.2s linear;
        transition:all 0.2s linear;
      }
      .set > a i{
        float: right;
        margin-top: 2px;
      }
      .set > a.active{
        background-color: #000051;
        color: #fff;
      }
      .content{
        background-color: #fff;
        border-bottom: 1px solid #ddd;
        display:none;
      }
      .content p{
        padding: 10px 15px;
        margin: 0;
        color: #333;
      }
    </style>

    
</html>
