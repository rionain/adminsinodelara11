<?php

namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class MaintenanceController extends Controller
{
    public function index()
    {
        $data['title'] = 'Maintenance';
        $config = config('maintenance');

        // Tech Stack Information
        $data['tech_stack'] = [
            $this->formatComponent(
                'Web Server',
                'HTTP Server handling requests',
                $this->getNginxVersion(),
                $config['recommended']['nginx'],
                'mdi mdi-web',
                'text-primary',
                null // Obsolete threshold not defined for Nginx here
            ),
            $this->formatComponent(
                'PHP Engine',
                'Scripting language interpreter',
                phpversion(),
                $config['recommended']['php'],
                'mdi mdi-language-php',
                'text-purple',
                $config['obsolete']['php']
            ),
            $this->formatComponent(
                'Laravel Framework',
                'Core application framework',
                \Illuminate\Foundation\Application::VERSION,
                $config['recommended']['laravel'],
                'mdi mdi-laravel',
                'text-danger',
                $config['obsolete']['laravel']
            ),
            $this->formatComponent(
                'Database Server',
                'Relational Database Management',
                $this->getMariaDBVersion(),
                $config['recommended']['mariadb'],
                'mdi mdi-database',
                'text-info',
                $config['obsolete']['mariadb']
            ),
            $this->formatComponent(
                'Operating System',
                'Base environment',
                $this->getOSVersion(),
                $config['recommended']['alpine'],
                'mdi mdi-linux',
                'text-dark'
            )
        ];

        // PHP Extensions
        $data['extensions'] = [
            ['name' => 'imagick', 'category' => 'Image Processing', 'version' => phpversion('imagick') ?: 'Not installed', 'purpose' => 'QR Code generation and image manipulation'],
            ['name' => 'pdo_mysql', 'category' => 'Database Connector', 'version' => phpversion('pdo_mysql') ?: 'Not installed', 'purpose' => 'MySQL / MariaDB connectivity'],
            ['name' => 'bcmath', 'category' => 'Mathematics', 'version' => phpversion('bcmath') ?: 'Not installed', 'purpose' => 'High precision math operations'],
            ['name' => 'mbstring', 'category' => 'String Handling', 'version' => phpversion('mbstring') ?: 'Not installed', 'purpose' => 'Multi-byte string support'],
            ['name' => 'zip', 'category' => 'Compression', 'version' => phpversion('zip') ?: 'Not installed', 'purpose' => 'File archive extraction and creation']
        ];

        // Server Resources
        $data['resources'] = $this->getServerResources();

        // PHP Configuration
        $data['php_config'] = [
            ['setting' => 'upload_max_filesize', 'value' => ini_get('upload_max_filesize'), 'purpose' => 'Max size for uploaded files'],
            ['setting' => 'post_max_size', 'value' => ini_get('post_max_size'), 'purpose' => 'Max size for POST data (must be >= upload_max)'],
            ['setting' => 'memory_limit', 'value' => ini_get('memory_limit'), 'purpose' => 'Max memory a script can consume'],
            ['setting' => 'max_execution_time', 'value' => ini_get('max_execution_time') . 's', 'purpose' => 'Max time a script can run'],
        ];

        // Security Alerts
        $data['alerts'] = $this->getSecurityAlerts($data['tech_stack']);

        return view('pengaturan.maintenance', $data);
    }

    private function formatComponent($service, $desc, $current, $recommended, $icon, $color, $obsolete = null)
    {
        $current_clean = preg_replace('/[^0-9.]/', '', $current);
        $recommended_clean = preg_replace('/[^0-9.]/', '', $recommended);
        
        $status = 'Up-to-date';
        if ($obsolete && version_compare($current_clean, $obsolete, '<')) {
            $status = 'Obsolete / Insecure';
        } elseif (version_compare($current_clean, $recommended_clean, '<')) {
            $status = 'Update Available';
        }

        return [
            'service' => $service,
            'description' => $desc,
            'current_version' => $current,
            'status' => $status,
            'recommended' => $recommended,
            'icon' => $icon,
            'color' => $color
        ];
    }

    private function getServerResources()
    {
        $path = base_path();
        $free = disk_free_space($path);
        $total = disk_total_space($path);
        $used = $total - $free;
        $usage_percent = round(($used / $total) * 100, 2);

        return [
            'disk' => [
                'total' => $this->formatBytes($total),
                'free' => $this->formatBytes($free),
                'used' => $usage_percent . '%',
                'status' => $usage_percent > 90 ? 'Critical' : ($usage_percent > 75 ? 'Warning' : 'Healthy')
            ],
            'environment' => config('app.env'),
            'debug_mode' => config('app.debug') ? 'Enabled (Risk in Production)' : 'Disabled'
        ];
    }

    private function getSecurityAlerts($stack)
    {
        $alerts = [];
        foreach ($stack as $comp) {
            if ($comp['status'] == 'Obsolete / Insecure') {
                $alerts[] = "Critical security risk: {$comp['service']} version is obsolete. Update to {$comp['recommended']} immediately.";
            }
        }

        if (config('app.debug') && config('app.env') === 'production') {
            $alerts[] = "Security warning: APP_DEBUG is enabled in a production environment. This can leak sensitive configuration data.";
        }

        return $alerts;
    }

    private function getNginxVersion()
    {
        $server_software = $_SERVER['SERVER_SOFTWARE'] ?? '';
        if (preg_match('/nginx\/([0-9.]+)/', $server_software, $matches)) {
            return $matches[1];
        }
        return 'nginx';
    }

    private function getMariaDBVersion()
    {
        try {
            return DB::connection()->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION);
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    private function getOSVersion()
    {
        if (file_exists('/etc/os-release')) {
            $os_info = parse_ini_file('/etc/os-release');
            return $os_info['PRETTY_NAME'] ?? $os_info['NAME'] ?? 'Linux';
        }
        if (file_exists('/etc/alpine-release')) {
            return 'Alpine Linux ' . trim(file_get_contents('/etc/alpine-release'));
        }
        return PHP_OS;
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
