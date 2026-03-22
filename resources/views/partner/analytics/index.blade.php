<x-app-layout>

    @section('content')
    <div class="p-2 flex md:justify-center">
        <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">
            <div class="w-full md:min-w-[900px] max-w-[1400px]">

                <div class="flex flex-col md:justify-between mt-4">

                    <div class="flex items-center justify-between">
                        {{-- <div class="text-gray-900 font-semibold text-3xl md:text-3xl ml-1">
                            {{ __('Analytics') }}
                        </div> --}}
                        <h2 class="font-semibold text-2xl mb-3 mt-3 text-gray-800">
                            {{ __('Analytics') }}
                        </h2>
                    </div>

                </div>

                <div class="overflow-auto md:overflow-hidden rounded-lg mt-10 mb-4">

                    <div class="grid md:grid-cols-3 gap-3">
                        <div class="border px-4 py-2 border-gray-600 rounded-xl bg-white">   
                            <div class="flex justify-center font-semibold">
                                Estoque por categoria de produto
                            </div> 
                            <canvas id="estoqueChart"></canvas>
                        </div>

                        <div class="border px-4 py-2 border-gray-600 rounded-xl bg-white">   
                            <div class="flex justify-center font-semibold">
                                Acessos ao Catálogo (Últimos 7 dias)
                            </div> 
                            <canvas id="acessosChart"></canvas>
                        </div>

                        <div class="border px-4 py-2 border-gray-600 rounded-xl bg-white">   
                            <div class="flex justify-center font-semibold">
                                Produtos mais visualizados
                            </div> 
                            <canvas id="visualizacoesChart"></canvas>
                        </div>

                        <div class="border px-4 py-2 border-gray-600 rounded-xl bg-white">   
                            <div class="flex justify-center font-semibold">
                                Leads (Contatos) por Veículo
                            </div> 
                            <div class="md:h-[90%] flex items-center">
                                <canvas id="leadsChart"></canvas>
                            </div>
                        </div>

                        <div class="border px-4 py-2 border-gray-600 rounded-xl bg-white">   
                            <div class="flex justify-center font-semibold">
                                Tempo médio de venda por categoria
                            </div> 
                            <canvas id="tempoVendaChart" class=""></canvas>
                        </div>

                        <div class="border px-4 py-2 border-gray-600 rounded-xl bg-white">   
                            <div class="flex justify-center font-semibold">
                                Cores mais vendidas
                            </div> 
                            <canvas id="coresMaisVendidasChart" class="h-[300px] w-full"></canvas>
                        </div>

                    </div>

                    
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    $(function () {
        const ctx = document.getElementById('estoqueChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Acessórios', 'Eletrônicos', 'Roupas'],
                datasets: [
                    {
                        // label: "Estoque por categoria de produto",
                        data: [12, 19, 5],
                        backgroundColor: ['#4dc9f6','#190eeb','#537bc4'],
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                        position: 'bottom',
                    },
                    title: {
                        display: false,
                        text: 'Estoque por categoria de produto'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });


    const acessosCtx = document.getElementById('acessosChart').getContext('2d');
    new Chart(acessosCtx, {
        type: 'line',
        data: {
            labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
            datasets: [{
                label: 'Acessos diários',
                data: [110, 135, 98, 150, 180, 200, 170],
                borderColor: '#4dc9f6',
                backgroundColor: 'rgba(77,201,246,0.2)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            plugins: {
                title: {
                    display: false,
                    text: 'Acessos ao Catálogo (Últimos 7 dias)'
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });


    const visCtx = document.getElementById('visualizacoesChart').getContext('2d');
    new Chart(visCtx, {
        type: 'bar',
        data: {
            labels: ['Civic 2021', 'Hilux 2020', 'Onix 2022', 'HB20 2019'],
            datasets: [{
                label: 'Visualizações',
                data: [350, 290, 410, 275],
                backgroundColor: '#f67019'
            }]
        },
        options: {
            indexAxis: 'y',
            plugins: {
                title: {
                    display: false,
                    text: 'Produtos mais visualizados'
                }
            },
            scales: {
                x: { beginAtZero: true }
            }
        }
    });

    const leadsCtx = document.getElementById('leadsChart').getContext('2d');
    new Chart(leadsCtx, {
        type: 'bar',
        data: {
            labels: ['Corolla', 'Ranger', 'T-Cross', 'Fiesta'],
            datasets: [{
                label: 'Contatos por veículo',
                data: [23, 17, 29, 12],
                backgroundColor: '#537bc4'
            }]
        },
        options: {
            plugins: {
                title: {
                    display: false,
                    text: 'Leads (Contatos) por Veículo'
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    const tempoCtx = document.getElementById('tempoVendaChart').getContext('2d');
    new Chart(tempoCtx, {
        type: 'polarArea',
        data: {
            labels: ['Acessórios', 'Eletrônicos', 'Roupas'],
            datasets: [{
                label: 'Dias médios até venda',
                data: [22, 15, 30],
                backgroundColor: 'rgba(83,123,196,0.2)',
                borderColor: '#537bc4',
                pointBackgroundColor: '#537bc4'
            }]
        },
        options: {
            plugins: {
                title: {
                    display: false,
                    text: 'Tempo médio de venda por categoria'
                }
            }
        }
    });

    const coresCtx = document.getElementById('coresMaisVendidasChart').getContext('2d');

    new Chart(coresCtx, {
        type: 'doughnut',
        data: {
            labels: ['Preto', 'Branco', 'Prata', 'Vermelho', 'Azul'],
            datasets: [{
                label: 'Cores mais vendidas',
                data: [35, 25, 20, 10, 10], // Exemplo de quantidades de vendas
                backgroundColor: [
                    '#000000', // Preto
                    '#ffffff', // Branco
                    '#c0c0c0', // Prata
                    '#ff0000', // Vermelho
                    '#0000ff'  // Azul
                ],
                borderColor: ['#eeeeee'],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                title: {
                    display: false,
                    text: 'Cores mais vendidas'
                },
                legend: {
                    position: 'bottom'
                }
            }
        }
    }); 
    </script>

    <style>
        #leadsChart {
            height: 50% !important;
        }
    </style>
    @endsection
</x-app-layout>
