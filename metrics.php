<?php

date_default_timezone_set('Asia/Taipei');

require_once __DIR__ . '/vendor/autoload.php';

if (!function_exists('lw_collect_metrics')) {
    function lw_collect_metrics()
    {
        $metrics = [
            'hostname' => gethostname(),
            'cpu_usage_pct' => null,
            'mem_usage_pct' => null,
            'disk_usage_pct' => null,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        try {
            $linfo = new \Linfo\Linfo([
                'show' => [
                    'cpu' => true,
                    'ram' => true,
                    'mounts' => true,
                ],
                'cpu_usage' => true,
            ]);
            $linfo->scan();
            $info = $linfo->getInfo();

            if (isset($info['CPU']) && is_array($info['CPU'])) {
                $total = 0;
                $count = 0;
                foreach ($info['CPU'] as $cpu) {
                    if (isset($cpu['usage_percentage']) && is_numeric($cpu['usage_percentage'])) {
                        $total += (float) $cpu['usage_percentage'];
                        $count++;
                    }
                }
                if ($count > 0) {
                    $metrics['cpu_usage_pct'] = round($total / $count, 2);
                }
            }

            if (isset($info['RAM']['total'], $info['RAM']['free']) && (float) $info['RAM']['total'] > 0) {
                $total = (float) $info['RAM']['total'];
                $free = (float) $info['RAM']['free'];
                $metrics['mem_usage_pct'] = round(
                    min(max(($total - $free) / $total * 100, 0), 100),
                    2
                );
            }

            if (isset($info['Mounts']) && is_array($info['Mounts'])) {
                $rootMount = null;
                foreach ($info['Mounts'] as $mount) {
                    if (isset($mount['mount']) && $mount['mount'] === '/') {
                        $rootMount = $mount;
                        break;
                    }
                }

                if ($rootMount !== null) {
                    $size = isset($rootMount['size']) ? (float) $rootMount['size'] : 0;
                    $free = isset($rootMount['free']) ? (float) $rootMount['free'] : 0;
                    if ($size > 0) {
                        $metrics['disk_usage_pct'] = round(($size - $free) / $size * 100, 2);
                    }
                } else {
                    // 某些環境可能沒有 "/" 掛載點，退回總量估算
                    $totalSize = 0;
                    $totalFree = 0;
                    foreach ($info['Mounts'] as $mount) {
                        $totalSize += isset($mount['size']) ? (float) $mount['size'] : 0;
                        $totalFree += isset($mount['free']) ? (float) $mount['free'] : 0;
                    }
                    if ($totalSize > 0) {
                        $metrics['disk_usage_pct'] = round(($totalSize - $totalFree) / $totalSize * 100, 2);
                    }
                }
            }
        } catch (\Throwable $e) {
            // 維持 null，避免因套件失敗中斷 API
        }

        return $metrics;
    }
}

if (realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(lw_collect_metrics());
}
