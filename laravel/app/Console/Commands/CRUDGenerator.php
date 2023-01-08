<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CRUDGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->line('CRUD Generator');

        $this->line('[ * ] Membuat controller');
        $this->generateController();

        $this->line('[ * ] Membuat halaman');
        $this->generatePages();
    }

    private function getStub($stubName = '')
    {
        $path = resource_path('stubs/crud/' . $stubName . '.stub');
        $content = file_get_contents($path);
        return $content;
    }

    private function generateController()
    {
        $controllerStub = $this->getStub('Controller');
        
        $stubText = [
            '{{ controllerNamespace }}', 
            '{{ modelName }}',
            '{{ modelInstance }}',
            '{{ modelInstancePlural }}',
            '{{ resourceName }}',
            '{{ resourceNameSingular }}',
            '{{ viewPath }}',
            '{{ route }}',
            '{{ JSONData }}',
            '{{ validationCreate }}',
            '{{ validationEdit }}',
        ];
        $replacementText = [
            config('crud.controllerNamespace'),
            config('crud.modelName'),
            config('crud.modelInstance'),
            config('crud.modelInstancePlural'),
            config('crud.resourceName'),
            config('crud.resourceNameSingular'),
            str_replace('/', '.', config('crud.viewPath')),
            config('crud.route'),
            $this->generateJSONData(),
            $this->generateValidation(),
            $this->generateValidation(true),
        ];

        $controllerStub = str_replace($stubText, $replacementText, $controllerStub);

        $controllerFileName = config('crud.modelName') . 'Controller.php';

        file_put_contents(app_path(config('crud.controllerPath') . '/' . $controllerFileName), $controllerStub);
    }

    private function generatePages()
    {
        $listPages = ['index', '_menu', 'create', 'edit'];

        mkdir(resource_path('views/' . config('crud.viewPath')), 0777, true);

        foreach ($listPages as $page) {
            $pageStub = $this->getStub('views/' . $page);
            
            $stubText = [
                '{{ modelInstance }}',
                '{{ resourceName }}',
                '{{ resourceNameSingular }}',
                '{{ route }}',
                '{{ formCreate }}',
                '{{ formEdit }}',
                '{{ tableData }}',
            ];
            $replacementText = [
                config('crud.modelInstance'),
                config('crud.resourceName'),
                config('crud.resourceNameSingular'),
                config('crud.route'),
                $this->generateForm(),
                $this->generateForm(true),
                $this->generateTable(),
            ];

            $pageStub = str_replace($stubText, $replacementText, $pageStub);

            $pageFileName = $page . '.blade.php';

            file_put_contents(resource_path('views/' . config('crud.viewPath') . '/' . $pageFileName), $pageStub);
        }
    }

    private function generateForm($isEdit = false)
    {
        $fields = config('crud.fields');
        $result = '';

        foreach ($fields as $field) {
            switch ($field['type']) {
                case 'text':
                    if ($isEdit) {
                        $result .= '<x-admin.forms.input-text name="' . $field['name'] . '" :label="__(\'' . $field['label'] . '\')" :value="old(\'' . $field['name'] . '\') ?? $' . config('crud.modelInstance') . '->' . $field['name'] . '" />';
                    }
                    else {
                        $result .= '<x-admin.forms.input-text name="' . $field['name'] . '" :label="__(\'' . $field['label'] . '\')" />';
                    }
                    break;
                case 'password':
                    if ($isEdit) {
                        $result .= '<x-admin.forms.password name="' . $field['name'] . '" :label="__(\'' . $field['label'] . '\')" :value="old(\''. $field['name'] . '\') ?? $' . config('crud.modelInstance') . '->' . $field['name'] . '" />';
                    }
                    else {
                        $result .= '<x-admin.forms.password name="' . $field['name'] . '" :label="__(\'' . $field['label'] . '\')" />';
                    }
                    break;
                case 'radio':
                    if ($isEdit) {
                        $result .= '<x-admin.forms.radio name="' . $field['name'] . '" :label="__(\'' . $field['label'] . '\')" value="' . $field['value'] . '" :selected="old(\'' . $field['name'] . '\') ?? $' . config('crud.modelInstance') . '->' . $field['name'] . '" />';
                    }
                    else {
                        $result .= '<x-admin.forms.radio name="' . $field['name'] . '" :label="__(\'' . $field['label'] . '\')" value="' . $field['value'] . '" :selected="old(\'' . $field['name'] . '\')" />';
                    }
                    break;
                case 'select':
                    if ($isEdit) {
                        $result .= '<x-admin.forms.select name="' . $field['name'] . '" :label="__(\'' . $field['label'] . '\')" :options="[]" :selected="old(\'' . $field['name'] . '\') ?? $' . config('crud.modelInstance') . '->' . $field['name'] . '" :default-option="__(\'Select option\')" />';
                    }
                    else {
                        $result .= '<x-admin.forms.select name="' . $field['name'] . '" :label="__(\'' . $field['label'] . '\')" :options="[]" :selected="old(\'' . $field['name'] . '\')" :default-option="__(\'Select option\')" />';
                    }
                    break;
                case 'textarea':
                    if ($isEdit) {
                        $result .= '<x-admin.forms.textarea name="' . $field['name'] . '" :label="__(\'' . $field['label'] . '\')">{{ old(\'' . $field['name'] . '\') ?? $' . config('crud.modelInstance') . '->' . $field['name'] . ' }}</x-admin.forms.textarea>';
                    }
                    else {
                        $result .= '<x-admin.forms.textarea name="' . $field['name'] . '" :label="__(\'' . $field['label'] . '\')">{{ old(\'' . $field['name'] . '\') }}</x-admin.forms.textarea>';
                    }
                    break;
                default:
                    break;
            }
            $result .= "\n";
        }

        return $result;
    }

    private function generateJSONData()
    {
        $data = config('crud.data');

        $result = '';
        foreach ($data as $item) {
            $result .= '\'' . $item['JSONName'] . '\' => $' . config('crud.modelInstance') . '->' . $item['databaseColumn'] . ',';
            $result .= "\n";
        }

        return $result;
    }

    private function generateTable()
    {
        $data = config('crud.data');

        $result = '';
        foreach ($data as $item) {
            $result .= '<th data-field="' . $item['JSONName'] . '" data-sortable="false" data-visible="true">{{ __(\'' . $item['tableName'] . '\') }}</th>';
            $result .= "\n";
        }

        return $result;
    }

    private function generateValidation($isEdit = false)
    {
        if ($isEdit) {
            $mode = 'edit_validation';
        }
        else {
            $mode = 'create_validation';
        }

        $fields = config('crud.fields');
        $result = '';

        foreach ($fields as $field) {
            if (isset($field[$mode])) {
                $result .= '\'' . $field['name'] . '\' => [\'' . implode("', '", $field[$mode]) . '\'],';
            }
            else {
                $result .= '\'' . $field['name'] . '\' => [],';
            }
            $result .= "\n";
        }

        return $result;
    }
}
