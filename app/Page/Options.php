<?php

namespace EmploybrandApply\Page;

use Carbon_Fields\Container;
use Carbon_Fields\Field;
use EmploybrandApply\EmploybrandApplyClient;
use EmploybrandApply\Exceptions\Http\Unauthenticated;
use EmploybrandApply\Sync\SyncCompany;


class Options
{

    public static function register()
    {
        add_action('carbon_fields_register_fields', [new self, 'settingsPage']);

        add_action('carbon_fields_theme_options_container_saved', [new self, 'saved']);
    }


    function settingsPage()
    {

        $text = '<h3>Employbrand Apply verbinden</h3><p>Om de plugin te verbinden aan jullie Employbrand Apply applicatie moet er een API Key opgegeven worden. Deze API Key kan opgehaald worden in de Hub via \'Geavanceerd > Employbrand API > Apply\'</p>';
        if(get_option('employbrand_apply_authenticated') == true) $text = '<h3>Employbrand Apply is verbonden</h3><p>De plugin is verbonden aan jullie Employbrand Apply applicatie. Je kunt eventueel de gegevens hieronder nog aanpassen om met een andere organisatie te verbinden.</p>';

        Container::make('theme_options', __('Employbrand Apply'))
            ->set_page_parent('options-general.php')
            ->add_fields([
                Field::make('html', 'info')
                    ->set_html($text),
                Field::make('text', 'employbrand_apply_company_id', 'Company ID'),
                Field::make('text', 'employbrand_apply_api_key', 'API Key'),
            ]);
    }


    function saved() {

        $parts = parse_url($_SERVER['REQUEST_URI']);
        parse_str($parts['query'], $query);

        /*
         * Different page
         */
        if($query['page'] !== 'crb_carbon_fields_container_employbrand_apply.php') return;

        /*
         * Not authenticated
         */
        if(empty(carbon_get_theme_option('employbrand_apply_company_id')) || empty(carbon_get_theme_option('employbrand_apply_api_key'))) {

            update_option('employbrand_apply_authenticated', false);
            return;
        }

        try {
            $employbrandApplyClient = new EmploybrandApplyClient(carbon_get_theme_option('employbrand_apply_company_id'), carbon_get_theme_option('employbrand_apply_api_key'));
            $employbrandApplyClient->company()->get();

            update_option('employbrand_apply_authenticated', true);
            if(!get_option('employbrand_apply_webhook_token'))
                update_option('employbrand_apply_webhook_token', $this->generateRandomString(16));

            /*
             * Configure the webhook
             */
            $createWebhook = true;

            $webhookUrl = get_site_url() . '/wp-json/employbrand-apply/v1/handle-webhook?token=' . get_option('employbrand_apply_webhook_token');

            $webhooks = $employbrandApplyClient->webhooks()->list()->all();
            foreach($webhooks as $webhook) {
                if($webhook->url == $webhookUrl) $createWebhook = false;
            }

            if($createWebhook) {
                $employbrandApplyClient->webhooks()->create([
                    'types' => ['all'],
                    'name' => 'wp-apply-plugin',
                    'url' => $webhookUrl
                ]);
            }

            /*
             * Sync company details
             */
            $syncCompany = new SyncCompany();
            $syncCompany->sync();
        }
        catch(\Exception $exception) {
            update_option('employbrand_apply_authenticated', false);
        }
    }


    protected function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
