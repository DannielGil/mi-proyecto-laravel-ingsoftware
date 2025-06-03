@extends('layouts.app') {{-- O el layout principal que uses --}}

@section('page_title', 'Métricas de Proyectos')

{{-- Incluye Chart.js desde un CDN --}}
@section('scripts_head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
    <div class="container">
        <h1>Métricas y Estadísticas de Proyectos</h1>

        <div class="row mb-4">
            <div class="col-md-6">
                <h2>Total de Proyectos: {{ $totalProyectos }}</h2>
            </div>
            <div class="col-md-6">
                <h2>Presupuesto Total Acumulado: ${{ number_format($presupuestoTotalAcumulado, 2, ',', '.') }}</h2>
            </div>
        </div>

        <hr>

        {{-- Contenedores para las Gráficas --}}
        <div class="row mt-4">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Proyectos por Estado</h4>
                    </div>
                    <div class="card-body">
                        {{-- Añadimos un estilo para controlar la altura si es necesario, pero Chart.js es responsive --}}
                        <div style="max-height: 250px;"><canvas id="proyectosPorEstadoChart"></canvas></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">Proyectos por Departamento</h4>
                    </div>
                    <div class="card-body">
                        <div style="max-height: 250px;"><canvas id="proyectosPorDepartamentoChart"></canvas></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6 mb-4"> {{-- Changed to col-md-6 to accommodate new budget chart --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0">Top 5 Creadores de Proyectos</h4>
                    </div>
                    <div class="card-body">
                        <div style="max-height: 300px;"><canvas id="topCreadoresChart"></canvas></div>
                    </div>
                </div>
            </div>
            {{-- New Contenedor para la Gráfica de Presupuesto --}}
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-white">
                        <h4 class="mb-0">Distribución de Presupuestos por Proyecto</h4>
                    </div>
                    <div class="card-body">
                        <div style="max-height: 300px;"><canvas id="presupuestoPorProyectoChart"></canvas></div>
                    </div>
                </div>
            </div>
        </div>
        <hr>

    </div>

    {{-- Script para las gráficas de Chart.js --}}
    @section('scripts_body')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Función para generar colores aleatorios
            function getRandomColors(num) {
                const colors = [];
                for (let i = 0; i < num; i++) {
                    const r = Math.floor(Math.random() * 200);
                    const g = Math.floor(Math.random() * 200);
                    const b = Math.floor(Math.random() * 200);
                    colors.push(`rgba(${r}, ${g}, ${b}, 0.7)`); // 0.7 alpha para relleno
                }
                return colors;
            }

            // Datos pasados desde Laravel
            const chartEstadosLabels = @json($chartEstadosLabels);
            const chartEstadosData = @json($chartEstadosData);

            const chartDepartamentosLabels = @json($chartDepartamentosLabels);
            const chartDepartamentosData = @json($chartDepartamentosData);

            const chartCreadoresLabels = @json($chartCreadoresLabels);
            const chartCreadoresData = @json($chartCreadoresData);

            // Make sure these variables are passed from your controller
            const chartPresupuestoLabels = @json($chartPresupuestoLabels);
            const chartPresupuestoData = @json($chartPresupuestoData);

            // --- Gráfica de Proyectos por Estado (Dona) ---
            if (chartEstadosLabels.length > 0) {
                const ctxEstados = document.getElementById('proyectosPorEstadoChart').getContext('2d');
                new Chart(ctxEstados, {
                    type: 'doughnut',
                    data: {
                        labels: chartEstadosLabels,
                        datasets: [{
                            data: chartEstadosData,
                            backgroundColor: getRandomColors(chartEstadosLabels.length),
                            borderColor: '#fff',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, // Permite controlar el tamaño con CSS/contenedor padre
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: false,
                                text: 'Proyectos por Estado'
                            }
                        }
                    },
                });
            }

            // --- Gráfica de Proyectos por Departamento (Barras) ---
            if (chartDepartamentosLabels.length > 0) {
                const ctxDepartamentos = document.getElementById('proyectosPorDepartamentoChart').getContext('2d');
                new Chart(ctxDepartamentos, {
                    type: 'bar',
                    data: {
                        labels: chartDepartamentosLabels,
                        datasets: [{
                            label: 'Número de Proyectos',
                            data: chartDepartamentosData,
                            backgroundColor: getRandomColors(chartDepartamentosLabels.length),
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, // Permite controlar el tamaño con CSS/contenedor padre
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1 // Aquí se establece que el eje Y vaya de 1 en 1
                                },
                                title: {
                                    display: true,
                                    text: 'Cantidad de Proyectos'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: false,
                                text: 'Proyectos por Departamento'
                            }
                        }
                    }
                });
            }

            // --- Gráfica de Top 5 Creadores (Barras) ---
            if (chartCreadoresLabels.length > 0) {
                const ctxCreadores = document.getElementById('topCreadoresChart').getContext('2d'); // Corrected ID
                new Chart(ctxCreadores, { // Corrected context variable
                    type: 'bar',
                    data: {
                        labels: chartCreadoresLabels,
                        datasets: [{
                            label: 'Proyectos Creados',
                            data: chartCreadoresData,
                            backgroundColor: getRandomColors(chartCreadoresLabels.length),
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, // Permite controlar el tamaño con CSS/contenedor padre
                        indexAxis: 'y', // Hace el gráfico de barras horizontal
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1 // Aquí se establece que el eje X vaya de 1 en 1
                                },
                                title: {
                                    display: true,
                                    text: 'Cantidad de Proyectos'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: false,
                                text: 'Top 5 Creadores de Proyectos'
                            }
                        }
                    }
                });
            }

            // --- Nueva Gráfica de Presupuesto por Proyecto (Torta) ---
            // This was the problematic section in your original code
            if (chartPresupuestoLabels.length > 0) {
                const ctxPresupuesto = document.getElementById('presupuestoPorProyectoChart').getContext('2d');
                new Chart(ctxPresupuesto, {
                    type: 'pie', // Or 'doughnut' if you prefer
                    data: {
                        labels: chartPresupuestoLabels,
                        datasets: [{
                            data: chartPresupuestoData,
                            backgroundColor: getRandomColors(chartPresupuestoLabels.length),
                            borderColor: '#fff',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, // Allows control over size with CSS/parent container
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: { // Improve tooltips to show value as currency
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed !== null) {
                                            label += new Intl.NumberFormat('es-CO', { // Format for Colombia
                                                style: 'currency',
                                                currency: 'COP',
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            }).format(context.parsed);
                                        }
                                        return label;
                                    }
                                }
                            },
                            title: {
                                display: false,
                                text: 'Distribución de Presupuestos por Proyecto'
                            }
                        }
                    },
                });
            }
        });
    </script>
    @endsection
@endsection