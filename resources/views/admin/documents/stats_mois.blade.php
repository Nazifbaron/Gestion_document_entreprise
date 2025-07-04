<x-app-layout>
    <x-slot name="heading">📊 Documents ajoutés par mois</x-slot>

    <div class="bg-white shadow rounded-xl p-6 max-w-4xl mx-auto">
        <canvas id="barChart" height="100"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('barChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Documents ajoutés',
                    data: {!! json_encode($data) !!},
                    backgroundColor: '#3b82f6',
                    borderRadius: 4,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Nombre de documents ajoutés chaque mois'
                    }
                }
            }
        });
    </script>
</x-app-layout>
