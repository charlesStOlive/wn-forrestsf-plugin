<?php

namespace Waka\SalesForce;

use App;
use Backend;
use Carbon\Carbon;
use Config;
use Event;
use Illuminate\Foundation\AliasLoader;
use Lang;
use System\Classes\PluginBase;
use Waka\SalesForce\Classes\SalesForceConfig;
use Waka\SalesForce\Classes\SalesForceImport;
use Waka\SalesForce\Models\Settings;

/**
 * SalesForce Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * @var array Plugin dependencies
     */
    public $require = [
        'Waka.Wutils',
        // 'Wcli.Wconfig',
    ];
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'SalesForce',
            'description' => 'Branchement salesforce nécessite Wcli.Wconfig pour fonctionner',
            'author' => 'Waka',
            'icon' => 'icon-leaf',
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
            App::bind(\Omniphx\Forrest\Providers\Laravel\LaravelSession::class, \Waka\SalesForce\Classes\Replacements\LaravelSession::class);
            // Ajoutez autant de liens de substitution que nécessaire
    }

    public function registerSchedule($schedule)
    {
        $sfCronTime = Settings::get('sf_cron_time');
        //trace_log($sfCronTime);

        $schedule->call(function () {
            //trace_log('lancement cron sf');
            $sfCronTime = Settings::get('sf_cron_time');
            //trace_log($sfCronTime);
            if (!$sfCronTime) {
                \Log::error('sfCronTime est vide le cron SalesForce est annulé');
                return;
            }
            $usersIds = Settings::get('sf_responsable');
            $forrest = false;
            try {
                \Forrest::authenticate();
                $forrest = true;
            } catch (\Exception $e) {
                //trace_log($e);
                foreach ($usersIds as $userId) {
                    $user = \Backend\Models\User::find($userId);
                    if ($user) {
                        \Mail::sendTo($user, 'waka.salesforce::mail.error_auth_sf');
                    }
                }
            }
            if ($forrest) {
                $importsAuthorized = Settings::get('imports_authorized');
                if (!$importsAuthorized) {
                    \Log::info('Les imports sont bloqués dans les Settings de Salesforce');
                    return;
                }
                $imports = Settings::get('sf_active_imports');
                foreach ($imports as $import) {
                    $datas = [
                        'productorId' => $import,
                        'options' => []
                    ];
                    $job = new \Waka\SalesForce\Jobs\ImportSf($datas);
                    $jobManager = \App::make('Waka\Wakajob\Classes\JobManager');
                    $jobManager->dispatch($job, "Chargement SalesForce " . $import);
                }
            }
        })->dailyAt(Carbon::parse(Settings::get('sf_cron_time'))->format('H:i'));

        $schedule->call(function () {
            $sfCronTime = Settings::get('sf_cron_time');
            if (!$sfCronTime) {
                \Log::error('sfCronTime est vide le cron SalesForce est annulé');
                return;
            }
            $usersIds = Settings::get('sf_responsable');
            foreach ($usersIds as $userId) {
                $user = \Backend\Models\User::find($userId);
                if ($user) {
                    $sfLogs = \Waka\SalesForce\Models\Logsf::with('logsfErrors')->where('updated_at', '>=', $fromDate = \Carbon\Carbon::today())->get();
                    $vars = compact('sfLogs', 'user');
                    \Mail::sendTo($user, 'waka.salesforce::mail.import_result', $vars);
                } else {
                    /**/
                    //trace_log('impossible de trouver le user ligne 96 waka.salesforce plugin');
                }
            }
        })->dailyAt(Carbon::parse(Settings::get('sf_cron_time'))->addMinutes(10)->format('H:i'));
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        $this->bootPackages();
    }

    public function bootPackages()
    {
        // Get the namespace of the current plugin to use in accessing the Config of the plugin
        $pluginNamespace = str_replace('\\', '.', strtolower(__NAMESPACE__));

        // Instantiate the AliasLoader for any aliases that will be loaded
        $aliasLoader = AliasLoader::getInstance();

        // Get the packages to boot
        $packages = Config::get($pluginNamespace . '::packages');

        // Boot each package
        foreach ($packages as $name => $options) {
            // Setup the configuration for the package, pulling from this plugin's config
            if (!empty($options['config']) && !empty($options['config_namespace'])) {
                Config::set($options['config_namespace'], $options['config']);
            }
            // Register any Service Providers for the package
            if (!empty($options['providers'])) {
                foreach ($options['providers'] as $provider) {
                    App::register($provider);
                }
            }
            // Register any Aliases for the package
            if (!empty($options['aliases'])) {
                foreach ($options['aliases'] as $alias => $path) {
                    $aliasLoader->alias($alias, $path);
                }
            }
        }
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'waka.salesforce.admin.base' => [
                'tab' => 'Waka - Sales Force',
                'label' => 'Administrateur de Sales Force',
            ],
            'waka.salesforce.admin.super' => [
                'tab' => 'Waka - Sales Force',
                'label' => 'Super administrateur de Sales Force',
            ],
        ];
    }

    public function registerSettings()
    {

        return [
            'sales_force' => [
                'label' => Lang::get('waka.salesforce::lang.menu.settings'),
                'description' => Lang::get('waka.salesforce::lang.menu.settings_description'),
                'category' => Lang::get('waka.salesforce::lang.menu.category'),
                'icon' => 'icon-cog',
                'class' => 'Waka\SalesForce\Models\Settings',
                'order' => 101,
                'permissions' => ['waka.salesforce.admin.*'],
            ],
            'logsfs' => [
                'label' => Lang::get('waka.salesforce::lang.menu.logsf'),
                'description' => Lang::get('waka.salesforce::lang.menu.logsf_description'),
                'category' => Lang::get('waka.salesforce::lang.menu.category'),
                'icon' => 'icon-salesforce',
                'url' => Backend::url('waka/salesforce/logsfs'),
                'order' => 130,
                'permissions' => ['waka.salesforce.admin.*'],
            ],
        ];
    }
}
