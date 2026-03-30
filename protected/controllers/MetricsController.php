<?php

class MetricsController extends Controller
{
    // Metrics API 開放讀取，不需後台登入
    protected function needLogin(): bool
    {
        return false;
    }

    public function actionIndex()
    {
        $cpuUsage = $this->getCpuUsagePct();
        $memUsage = $this->getMemoryUsagePct();
        $diskUsage = $this->getDiskUsagePct('/');

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => true,
            'data' => [
                'hostname' => gethostname(),
                'cpu_usage_pct' => $cpuUsage,
                'mem_usage_pct' => $memUsage,
                'disk_usage_pct' => $diskUsage,
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
        Yii::app()->end();
    }

    private function getCpuUsagePct()
    {
        // 使用較長且多次取樣，降低瞬時抖動帶來的誤差
        $sampleIntervals = [500000, 500000, 500000]; // 每段 500ms，共約 1.5s
        $last = $this->readCpuStat();
        if ($last === null) {
            return null;
        }

        $usages = [];
        foreach ($sampleIntervals as $interval) {
            usleep($interval);
            $current = $this->readCpuStat();
            if ($current === null) {
                continue;
            }

            $usage = $this->calculateCpuUsagePct($last, $current);
            if ($usage !== null) {
                $usages[] = $usage;
            }
            $last = $current;
        }

        if (empty($usages)) {
            return null;
        }

        return round(array_sum($usages) / count($usages), 2);
    }

    private function calculateCpuUsagePct(array $first, array $second)
    {
        $totalDiff = $second['total'] - $first['total'];
        $idleDiff = $second['idle'] - $first['idle'];
        if ($totalDiff <= 0) {
            return null;
        }

        $usage = (1 - ($idleDiff / $totalDiff)) * 100;
        if ($usage < 0) {
            $usage = 0;
        } elseif ($usage > 100) {
            $usage = 100;
        }

        return $usage;
    }

    private function readCpuStat()
    {
        $line = @file('/proc/stat');
        if ($line === false || empty($line)) {
            return null;
        }

        $parts = preg_split('/\s+/', trim($line[0]));
        if (count($parts) < 5 || $parts[0] !== 'cpu') {
            return null;
        }

        $values = array_slice($parts, 1);
        $total = 0.0;
        foreach ($values as $v) {
            $total += (float)$v;
        }

        // idle + iowait
        $idle = (float)$values[3] + (isset($values[4]) ? (float)$values[4] : 0.0);

        return [
            'total' => $total,
            'idle' => $idle,
        ];
    }

    private function getMemoryUsagePct()
    {
        $lines = @file('/proc/meminfo');
        if ($lines === false) {
            return null;
        }

        $memTotal = null;
        $memAvailable = null;
        foreach ($lines as $line) {
            if (strpos($line, 'MemTotal:') === 0) {
                $memTotal = (float)filter_var($line, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            } elseif (strpos($line, 'MemAvailable:') === 0) {
                $memAvailable = (float)filter_var($line, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            }
        }

        if ($memTotal === null || $memTotal <= 0 || $memAvailable === null) {
            return null;
        }

        return round((($memTotal - $memAvailable) / $memTotal) * 100, 2);
    }

    private function getDiskUsagePct($path = '/')
    {
        $total = @disk_total_space($path);
        $free = @disk_free_space($path);
        if ($total === false || $free === false || $total <= 0) {
            return null;
        }

        return round((($total - $free) / $total) * 100, 2);
    }
}

