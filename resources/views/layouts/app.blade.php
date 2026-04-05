<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#6A2BBA">

    <title>{{ config('app.name', 'Vistuu') }}</title>

    <!-- FONTS (alinhado à landing welcome) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>

    {{-- Toasts: canto superior direito, fora do fluxo do Toastr CDN (id dedicado) --}}
    <style>
        #wg-toast-container {
            position: fixed;
            top: max(1rem, env(safe-area-inset-top, 0px));
            right: max(1rem, env(safe-area-inset-right, 0px));
            left: auto;
            bottom: auto;
            z-index: 100050;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.5rem;
            pointer-events: none;
            max-width: min(24rem, calc(100vw - 1.5rem));
            width: min(24rem, calc(100vw - 1.5rem));
        }

        .toast-item {
            pointer-events: auto;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.875rem 1rem 0.75rem;
            border-radius: 0.875rem;
            background: #fff;
            box-shadow:
                0 1px 2px rgba(15, 23, 42, 0.06),
                0 12px 40px rgba(15, 23, 42, 0.12);
            border: 1px solid rgba(15, 23, 42, 0.08);
            animation: wgToastIn 0.38s cubic-bezier(0.22, 1, 0.36, 1) both;
            position: relative;
            overflow: hidden;
            width: 100%;
        }

        .toast-item.toast-hiding {
            animation: wgToastOut 0.28s cubic-bezier(0.4, 0, 1, 1) both;
        }

        .toast-item--compact .toast-message {
            font-size: 0.9375rem;
            font-weight: 600;
            line-height: 1.45;
        }

        @keyframes wgToastIn {
            from {
                opacity: 0;
                transform: translate3d(1.25rem, -0.35rem, 0) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translate3d(0, 0, 0) scale(1);
            }
        }

        @keyframes wgToastOut {
            from {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }

            to {
                opacity: 0;
                transform: translate3d(0.75rem, 0, 0);
            }
        }

        .toast-icon {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.625rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .toast-icon svg {
            width: 1.125rem;
            height: 1.125rem;
        }

        .toast-success .toast-icon {
            background: #ecfdf5;
            color: #059669;
        }

        .toast-error .toast-icon {
            background: #fef2f2;
            color: #dc2626;
        }

        .toast-warning .toast-icon {
            background: #fffbeb;
            color: #d97706;
        }

        .toast-info .toast-icon {
            background: #eff6ff;
            color: #2563eb;
        }

        .toast-body {
            flex: 1;
            min-width: 0;
            padding-top: 0.125rem;
        }

        .toast-title {
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.25rem;
        }

        .toast-success .toast-title {
            color: #047857;
        }

        .toast-error .toast-title {
            color: #fff;
        }

        .toast-warning .toast-title {
            color: #b45309;
        }

        .toast-info .toast-title {
            color: #1d4ed8;
        }

        .toast-message {
            font-size: 0.8125rem;
            line-height: 1.45;
            word-break: break-word;
            color: #334155;
        }

        .toast-success .toast-message {
            color: #fff;
        }

        .toast-error .toast-message {
            color: #fff;
        }

        .toast-warning .toast-message {
            color: #422006;
        }

        .toast-info .toast-message {
            color: #1e293b;
        }

        .toast-close {
            background: transparent;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            font-size: 1.125rem;
            line-height: 1;
            padding: 0.125rem;
            flex-shrink: 0;
            border-radius: 0.375rem;
            transition: color 0.15s, background 0.15s;
            margin: -0.125rem -0.25rem 0 0;
        }

        .toast-close:hover {
            color: #475569;
            background: rgba(15, 23, 42, 0.06);
        }

        .toast-close:focus-visible {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }

        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            border-radius: 0 0 0.875rem 0.875rem;
            animation: wgToastProgress var(--toast-duration, 5000ms) linear forwards;
        }

        .toast-success .toast-progress {
            background: linear-gradient(90deg, #34d399, #10b981);
        }

        .toast-error .toast-progress {
            background: linear-gradient(90deg, #f87171, #ef4444);
        }

        .toast-warning .toast-progress {
            background: linear-gradient(90deg, #fbbf24, #f59e0b);
        }

        .toast-info .toast-progress {
            background: linear-gradient(90deg, #60a5fa, #3b82f6);
        }

        @keyframes wgToastProgress {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }
    </style>

    {{-- BOOTSTRAP --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>


    {{-- SELECT2 --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>      --}}

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.price-mask').mask('000.000.000.000.000,00', {
                reverse: true
            });
            $('.year-manufacturer-mask').mask('0000/0000');
            $('.miliage-mask').mask('00000000');
            $('.renavam-mask').mask('00000000000');
            $('.phone-mask').mask('(00) 00000-0000');
            $('.cep-mask').mask('00000-000');
            $('.cpf-cnpj-mask').mask('00.000.000/0000-00', {
                onKeyPress: function(value, e, field, options) {
                    var length = value.replace(/\D/g, '').length;
                    var mask = (length > 11) ? '00.000.000/0000-00' : '000.000.000-000';
                    field.mask(mask, options);
                }
            });
            $('.license-plate-mask').mask('AAAAAAA', {
                translation: {
                    'A': {
                        pattern: /[A-Za-z0-9]/
                    }
                }
            }).on('blur', function() {
                const val = $(this).val().replace(/[^A-Za-z0-9]/g, '');
            }).on('input', function() {
                this.value = this.value.toUpperCase();
            });

            $('.chassi-mask').mask('SSSSSSSSSSSSSSSSS', {
                translation: {
                    'S': {
                        pattern: /[A-Za-z0-9]/
                    }
                }
            }).on('input', function() {
                this.value = this.value.toUpperCase();
            });

            $('.engine-power-mask').mask('00,0', {
                reverse: true
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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-jakarta h-full overflow-hidden bg-[#F8F9FC] text-[#33363B] antialiased">
    <div id="global-loader" class=" flex justify-center items-center"
        style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; display: none; background: rgba(255, 255, 255, 0.8); justify-content: center; align-items: center; z-index: 9999;">
        <svg class="size-10 animate-spin text-[#6A2BBA]" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
            <path d="M32 64a32 32 0 1 1 32-32h-4a28 28 0 1 0-28 28z" fill="currentColor" />
            <path d="M32 0a32 32 0 0 1 32 32h-4a28 28 0 0 0-28-28z" fill="currentColor" />
        </svg>
    </div>
    <script>
        function showLoader() {
            $("#global-loader").fadeIn();
        }

        function hideLoader() {
            $("#global-loader").fadeOut();
        }

        $(document).on('submit', 'form', function (e) {
            if (e.isDefaultPrevented()) {
                return;
            }
            showLoader();
        });

        $(document).ready(function() {
            hideLoader();
        });
    </script>

    <div class="flex h-full">

        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main content area -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Navbar -->
            <div
                class="bg-white/95 backdrop-blur-sm h-16 flex items-center justify-between px-2 md:px-4 border-b border-[#6A2BBA]/10 shadow-sm fixed top-0 z-30 w-full md:left-64 md:w-[calc(100%-16rem)]">
                @include('components.navbar')
            </div>

            <!-- Content area with scroll -->
            <main class="mt-16 overflow-auto flex-1 p-0 md:pl-64">
                @yield('content')
            </main>

        </div>
    </div>

    <!-- Mobile Sidebar Toggle -->
    <script>
        $(document).ready(function() {
            $('#toggleSidebar').on('click', function() {
                $('#sidebar').toggleClass('-translate-x-full');
            });

            // (Opcional) Clica fora para fechar
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#sidebar, #toggleSidebar').length && $('#sidebar').hasClass(
                        '-translate-x-full') === false && $(window).width() < 768) {
                    $('#sidebar').addClass('-translate-x-full');
                }
            });
        });
    </script>

    <script>
        // ── Toasts (canto superior direito; id wg-* evita colisão com #toast-container do Toastr) ──
        window.toast = (function() {
            let container;

            const icons = {
                success:
                    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
                error:
                    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
                warning:
                    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
                info:
                    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>',
            };

            const titles = {
                success: 'Sucesso',
                error: 'Erro',
                warning: 'Atenção',
                info: 'Informação',
            };

            function escapeHtml(text) {
                if (text == null) {
                    return '';
                }
                const d = document.createElement('div');
                d.textContent = String(text);
                return d.innerHTML;
            }

            function getContainer() {
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'wg-toast-container';
                    container.setAttribute('role', 'region');
                    container.setAttribute('aria-label', 'Notificações');
                    document.body.appendChild(container);
                }
                return container;
            }

            function show(type, message, title, duration) {
                const dur = typeof duration === 'number' && duration > 0 ? duration : 5000;
                const c = getContainer();
                const showTitle = title !== false;
                const titleText = showTitle ? (title || titles[type]) : '';
                const safeMessage = escapeHtml(message);
                const safeTitle = escapeHtml(titleText);

                const el = document.createElement('div');
                el.className = 'toast-item toast-' + type + (showTitle ? '' : ' toast-item--compact');
                el.setAttribute('role', 'status');
                el.setAttribute('aria-live', 'polite');
                el.style.setProperty('--toast-duration', dur + 'ms');
                el.innerHTML =
                    '<div class="toast-icon">' +
                    (icons[type] || icons.info) +
                    '</div>' +
                    '<div class="toast-body">' +
                    (showTitle ? '<div class="toast-title">' + safeTitle + '</div>' : '') +
                    '<div class="toast-message">' +
                    safeMessage +
                    '</div>' +
                    '</div>' +
                    '<button type="button" class="toast-close" title="Fechar" aria-label="Fechar notificação">&times;</button>' +
                    '<div class="toast-progress" aria-hidden="true"></div>';

                el.querySelector('.toast-close').addEventListener('click', () => dismiss(el));
                c.appendChild(el);

                const timer = setTimeout(() => dismiss(el), dur);
                el._timer = timer;

                return el;
            }

            function dismiss(el) {
                if (!el || el.classList.contains('toast-hiding')) return;
                clearTimeout(el._timer);
                el.classList.add('toast-hiding');
                el.addEventListener(
                    'animationend',
                    () => el.remove(),
                    { once: true }
                );
            }

            return {
                success: (msg, title, dur) => show('success', msg, title, dur),
                error: (msg, title, dur) => show('error', msg, title, dur),
                warning: (msg, title, dur) => show('warning', msg, title, dur),
                info: (msg, title, dur) => show('info', msg, title, dur),
            };
        })();

        // ── Toastr compatibility shim (evita depender do CSS/ DOM do pacote Toastr nesta tela) ──────────
        var toastr = {
            success: (msg, title) => window.toast.success(msg, title),
            error: (msg, title) => window.toast.error(msg, title),
            warning: (msg, title) => window.toast.warning(msg, title),
            info: (msg, title) => window.toast.info(msg, title),
            options: {},
        };

        // ── Session flashes ───────────────────────────────────────────────
        @if (session('success'))
            window.toast.success("{{ addslashes(session('success')) }}");
        @endif
        @if (session('error'))
            window.toast.error("{{ addslashes(session('error')) }}");
        @endif
        @if (session('warning'))
            window.toast.warning("{{ addslashes(session('warning')) }}");
        @endif
        @if (session('info'))
            window.toast.info("{{ addslashes(session('info')) }}");
        @endif
    </script>

</body>

<script>
    // Validar campos obrigatórios
    function validateFields() {
        console.log('chegamos aqui')
        console.log($(this))
        $(this).find('.required').each(function() {
            let valor = $(this).val().trim();

            // Remove mensagens de erro antigas
            $(this).next('.error-message').remove();

            if (valor === '') {
                isValid = false;
                $(this).after(
                    '<span class="error-message" style="color: red; font-size: 12px;">Este campo é obrigatório</span>'
                    );
            }
        });
    }


    // Modal de confirmação genérico
    function showModal(modalId, itemId = null, deleteUrl = null) {
        console.log('teste delete modal', modalId, itemId, deleteUrl);
        const modal = document.getElementById(modalId);
        console.log(modal, 'MODAL');
        if (itemId) modal.dataset.itemId = itemId;
        if (deleteUrl) modal.dataset.deleteUrl = deleteUrl;
        modal.classList.remove('hidden');
    }

    function hideModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    function confirmDelete(modalId) {
        const modal = document.getElementById(modalId);
        const itemId = modal.dataset.itemId;
        const deleteUrl = modal.dataset.deleteUrl;

        $.ajax({
            url: deleteUrl.replace('?', '/'),
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log(response, 'RESPONSE');
                hideModal(modalId);
                toastr.success('Membro deletado com sucesso!');

                setTimeout(function() {
                    window.location.reload();
                }, 3000);
            },
            error: function(xhr) {
                toastr.error('Erro ao deletar o membro.');
                console.error(xhr);
            }
        });

        if (!deleteUrl) {
            console.error('Delete URL não definida para o modal.');
            return;
        }
    }
</script>

<style>
    .error-message {
        color: red;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }

    .license-plate-mask {
        text-transform: uppercase;
    }
</style>

@auth
    @if(Auth::user()->role === 'partner')
        <script>
            (function () {
                var badges = document.querySelectorAll('.js-order-notify-badge');
                if (!badges.length) return;
                var sseEnabled = @json((bool) config('orders.sse_enabled'));
                var sseUrl = @json(route('orders.sse.stream'));
                var pendingOrdersUrl = @json(route('orders.index', ['ack' => 1, 'status' => ['pending']]));
                if (sseEnabled && typeof EventSource !== 'undefined') {
                    try {
                        var source = new EventSource(sseUrl);
                        source.onmessage = function (e) {
                            try {
                                var data = JSON.parse(e.data);
                                if (data.count === undefined) return;
                                var n = parseInt(data.count, 10);
                                if (isNaN(n) || n < 0) return;
                                var label = n > 99 ? '99+' : String(n);
                                badges.forEach(function (el) {
                                    el.textContent = label;
                                    if (n > 0) {
                                        el.classList.remove('hidden');
                                        el.classList.add('inline-flex');
                                    } else {
                                        el.classList.add('hidden');
                                        el.classList.remove('inline-flex');
                                    }
                                });
                            } catch (err) { /* ignore */ }
                        };
                        source.onerror = function () { /* EventSource reconecta */ };
                    } catch (e) { /* ignore */ }
                }
                badges.forEach(function (el) {
                    el.setAttribute('title', 'Ver pedidos pendentes');
                    if (el.closest('a.js-order-pending-orders-link')) {
                        return;
                    }
                    el.addEventListener('click', function (ev) {
                        ev.preventDefault();
                        ev.stopPropagation();
                        window.location.href = pendingOrdersUrl;
                    });
                });
            })();
        </script>
    @endif
@endauth

@stack('scripts')

</html>
