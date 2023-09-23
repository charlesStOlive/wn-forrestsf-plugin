<?php namespace Waka\SalesForce\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\SettingsManager; 
/**
 * Log Sfs Backend Controller
 */
class LogSfs extends Controller
{
    /**
     * @var array Behaviors that are implemented by this controller.
     */
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
        \Backend\Behaviors\RelationController::class,
        \Waka\Wutils\Behaviors\WakaControllerBehavior::class,
    ];

    public $sfpopupWidget;

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Winter.System', 'system', 'settings');
        SettingsManager::setContext('Waka.SalesForce', 'logSfs');
        $this->sfpopupWidget = $this->createSfPopupWidget();
    }

    public function isSfOauthAuthenticate()
    {
        $forrest = null;
        try {
            $forrest = \Forrest::authenticate();
        } catch (\Exception $e) {
            return false;
        }
        try {
            $identity = \Forrest::identity();
        } catch (\Exception $e) {
            return false;
        }
        if ($identity) {
            return true;
        } else {
            return false;
        }
    }

    public function isSfWuAuthenticate()
    {

        try {
            $forrest = \Forrest::refresh();
        } catch (\Exception $e) {
            //trace_log("erreur");
            return false;
        }
        if ($forrest) {
            return true;
        } else {
            return $this->tryToConnect();
        }
    }

    public function tryToConnect()
    {
        try {
            $forrest = \Forrest::authenticate();
        } catch (\Exception $e) {
            return false;
        }
        if ($forrest) {
            //trace_log(get_class($forrest));
            return true;
        } else {
            return $this->tryToConnect();
        }
    }

    public function onManualImport()
    {
        $imports = Settings::get('sf_active_imports');
        foreach ($imports as $import) {
            SalesForceImport::find($import)->executeQuery();
        }
        return \Redirect::refresh();
    }

    public function onCallSfImportPopup()
    {
        $sf = new \Waka\SalesForce\Classes\SalesForceConfig();
        $this->sfpopupWidget->getField('active_imports')->options = $sf->lists('import');
        $this->vars['sfpopupWidget'] = $this->sfpopupWidget;
        return $this->makePartial('$/waka/salesforce/controllers/logsfs/_popup_config.htm');
    }

    public function onSfImportValidation()
    {
        $options = post('sfBehavior_array');
        $imports = $options['active_imports'];
        foreach ($imports as $import) {
            $datas = [
                'productorId' => $import,
                'options' => $options
            ];
            $job = new \Waka\SalesForce\Jobs\ImportSf($datas);
            $jobManager = \App::make('Waka\Wakajob\Classes\JobManager');
            $jobManager->dispatch($job, "Chargement SalesForce ".$import);
            //SalesForceImport::find($import)->changeMainDate($options['main_date'])->executeQuery();
        }
    }

    public function createSfPopupWidget()
    {
        $config = $this->makeConfig('$/waka/salesforce/controllers/logsfs/config_popup.yaml');
        $config->alias = 'sfBehaviorformWidget';
        $config->arrayName = 'sfBehavior_array';
        $config->model = new \Model();
        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();
        return $widget;
    }

    
    
}
