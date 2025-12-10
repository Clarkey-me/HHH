<?php
include 'admin_protect.php';
include '../connect.php';

// Dashboard stats
$t_users    = $conn->query("SELECT COUNT(*) AS c FROM `user`")->fetch_assoc()['c'];
$t_products = $conn->query("SELECT COUNT(*) AS c FROM products")->fetch_assoc()['c'];
$t_sales    = $conn->query("SELECT IFNULL(SUM(total_amount),0) AS s FROM sales")->fetch_assoc()['s'];

// Weekly data (last 8 weeks)
$weeks = $week_revenues = [];
for ($i = 7; $i >= 0; $i--) {
    $start = date('Y-m-d', strtotime("-$i weeks", strtotime('monday this week')));
    $end   = date('Y-m-d', strtotime($start . ' +6 days'));
    $label = date('M d', strtotime($start)) . '–' . date('d', strtotime($end));
    $res   = $conn->query("SELECT IFNULL(SUM(total_amount),0) AS rev FROM sales WHERE order_date BETWEEN '$start' AND '$end 23:59:59'")->fetch_assoc();
    $weeks[] = $label;
    $week_revenues[] = (float)$res['rev'];
}

// Monthly data (last 12 months)
$months = $month_revenues = [];
for ($i = 11; $i >= 0; $i--) {
    $date = date('Y-m-01', strtotime("-$i months"));
    $label = date('M Y', strtotime($date));
    $res = $conn->query("SELECT IFNULL(SUM(total_amount),0) AS rev FROM sales WHERE DATE_FORMAT(order_date, '%Y-%m') = DATE_FORMAT('$date', '%Y-%m')")->fetch_assoc();
    $months[] = $label;
    $month_revenues[] = (float)$res['rev'];
}

// Yearly data (last 6 years)
$years = $year_revenues = [];
for ($i = 5; $i >= 0; $i--) {
    $year = date('Y') - $i;
    $res = $conn->query("SELECT IFNULL(SUM(total_amount),0) AS rev FROM sales WHERE YEAR(order_date) = $year")->fetch_assoc();
    $years[] = $year;
    $year_revenues[] = (float)$res['rev'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="adminCSS/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin-page">

    <?php include 'includes/sidebar.php'; ?>
    <?php include 'includes/header.php'; ?>

    <main class="admin-container">
        <div class="cards">
            <div class="card">
                <i class="fas fa-users"></i>
                <h3>Total Users</h3>
                <p><?= number_format($t_users) ?></p>
            </div>
            <div class="card">
                <i class="fas fa-box"></i>
                <h3>Total Products</h3>
                <p><?= number_format($t_products) ?></p>
            </div>
            <div class="card">
                <i class="fas fa-peso-sign"></i>
                <h3>Total Sales</h3>
                <p>₱<?= number_format($t_sales, 2) ?></p>
            </div>
        </div>

        <div class="chart-section">
            <div class="tab-buttons">
                <button class="tab-btn active" data-view="week">Weekly</button>
                <button class="tab-btn" data-view="month">Monthly</button>
                <button class="tab-btn" data-view="year">Yearly</button>
            </div>
            <h2 class="chart-title" id="chartTitle">Weekly Sales Revenue</h2>
            <div class="chart-wrapper">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </main>

    <?php include 'includes/scripts.php'; ?>

    <script>
        // Chart data
        const weekly  = { labels: <?= json_encode($weeks) ?>, data: <?= json_encode($week_revenues) ?> };
        const monthly = { labels: <?= json_encode($months) ?>, data: <?= json_encode($month_revenues) ?> };
        const yearly  = { labels: <?= json_encode($years) ?>, data: <?= json_encode($year_revenues) ?> };
        let chart;

        function getChartTheme() {
            const isLight = document.body.classList.contains('light-mode');
            return {
                line: isLight ? '#000000' : '#FFFFFF',
                fill: isLight ? 'rgba(0, 0, 0, 0.05)' : 'rgba(255, 255, 255, 0.08)',
                point: isLight ? '#000000' : '#FFFFFF',
                pointHover: '#C9A227',
                grid: isLight ? 'rgba(0, 0, 0, 0.1)' : 'rgba(255, 255, 255, 0.1)',
                text: isLight ? '#555' : '#aaa'
            };
        }

        function renderChart(view) {
            const data = view === 'week' ? weekly : view === 'month' ? monthly : yearly;
            const theme = getChartTheme();

            document.getElementById('chartTitle').textContent = 
                view === 'week' ? 'Weekly Sales Revenue' :
                view === 'month' ? 'Monthly Sales Revenue' : 'Yearly Sales Revenue';

            if (chart) chart.destroy();

            chart = new Chart(document.getElementById('salesChart'), {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Revenue',
                        data: data.data,
                        borderColor: theme.line,
                        backgroundColor: theme.fill,
                        pointBackgroundColor: theme.point,
                        pointHoverBackgroundColor: theme.pointHover,
                        pointHoverBorderColor: theme.pointHover,
                        pointBorderWidth: 3,
                        pointRadius: 7,
                        pointHoverRadius: 10,
                        borderWidth: 5,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: document.body.classList.contains('light-mode') 
                                ? 'rgba(0,0,0,0.85)' : 'rgba(255,255,255,0.95)',
                            titleColor: document.body.classList.contains('light-mode') ? '#fff' : '#000',
                            bodyColor: document.body.classList.contains('light-mode') ? '#fff' : '#000',
                            cornerRadius: 12,
                            displayColors: false,
                            callbacks: {
                                label: ctx => ' ₱' + ctx.parsed.y.toLocaleString()
                            }
                        }
                    },
                    scales: {
                        x: { 
                            grid: { color: theme.grid },
                            ticks: { color: theme.text }
                        },
                        y: { 
                            grid: { color: theme.grid },
                            ticks: { 
                                color: theme.text,
                                callback: value => '₱' + value.toLocaleString()
                            }
                        }
                    },
                    animation: {
                        duration: 1500,
                        easing: 'easeOutQuart'
                    }
                }
            });
        }

        // Initial render
        renderChart('week');

        // Tab switching
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                renderChart(btn.dataset.view);
            });
        });

        // Auto re-render on theme change
        const observer = new MutationObserver(() => {
            const activeView = document.querySelector('.tab-btn.active')?.dataset.view || 'week';
            renderChart(activeView);
        });
        observer.observe(document.body, { attributes: true, attributeFilter: ['class'] });
    </script>
</body>
</html>