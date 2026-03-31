<?php

class MetricsController extends Controller
{
    protected function needLogin(): bool
    {
        return false;
    }

    public function actionIndex()
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
                    'ram' => true,
                    'mounts' => true,
                ],
                'cpu_usage' => true,
            ]);
            $linfo->scan();
            $info = $linfo->getInfo();

            if (isset($info['cpuUsage']) && is_numeric($info['cpuUsage'])) {
                $metrics['cpu_usage_pct'] = round(
                    min(max((float)$info['cpuUsage'], 0), 100), 2
                );
            }

            if (isset($info['RAM']['total'], $info['RAM']['free']) && (float)$info['RAM']['total'] > 0) {
                $total = (float)$info['RAM']['total'];
                $free = (float)$info['RAM']['free'];
                $metrics['mem_usage_pct'] = round(
                    min(max(($total - $free) / $total * 100, 0), 100), 2
                );
            }

            if (isset($info['Mounts']) && is_array($info['Mounts'])) {
                foreach ($info['Mounts'] as $mount) {
                    if (!isset($mount['mount']) || $mount['mount'] !== '/') {
                        continue;
                    }
                    $size = isset($mount['size']) ? (float)$mount['size'] : 0;
                    $free = isset($mount['free']) ? (float)$mount['free'] : 0;
                    if ($size > 0) {
                        $metrics['disk_usage_pct'] = round(($size - $free) / $size * 100, 2);
                    }
                    break;
                }
            }
        } catch (\Throwable $e) {
            // 維持 null，避免因套件失敗中斷 API
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => true, 'data' => $metrics]);
        Yii::app()->end();
    }
}
