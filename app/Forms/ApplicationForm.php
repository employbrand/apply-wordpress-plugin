<?php

namespace EmploybrandApply\Forms;

use Carbon_Fields\Container;
use Carbon_Fields\Field;


class ApplicationForm
{

    public static function register()
    {
        $instance = new self;

        add_shortcode('eb_application', [$instance, 'registerShortcode']);
    }


    public function registerShortcode($atts, $content = null)
    {

        $fields = [];

        global $post;

        if( is_singular('eb_vacancies') || is_singular('eb_vacancy_previews') ) {

            $fields = get_post_meta($post->ID, '_form_fields', true);
            if($fields == null) $fields = [];
        }
        else if(isset($atts['id'])) {

            $fields = get_post_meta($atts['id'], '_form_fields', true);
            if($fields == null) $fields = [];
        }
        else {

            $isEnvironment = false;

            $environmentTypes = get_option('employbrand_apply_environment_types');

            if($environmentTypes != false) {

                foreach($environmentTypes as $environmentType) {
                    if(is_singular('eb_et_' . $environmentType['id'])) $isEnvironment = true;
                }
            }

            if(!$isEnvironment) {
                return 'Shortcode only works on singular vacancy of environment pages.';
            }

            $fields = get_option('employbrand_apply_application_form');
        }

        if($fields == null || !is_array($fields)) return;

        /*
         * id
         */
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $id = '';
        for ($i = 0; $i < 10; $i++) {
            $id = $characters[rand(0, strlen($characters))];
        }

        ?>
        <div class="eb-application">
            <div class="eb-application-sent">
                Bedankt voor je sollicitatie! We nemen zo spoedig mogelijk contact met je op.
            </div>
            <form class="eb-application-form-<?php echo $id; ?>">
                <?php
                foreach ( $fields as $field ) {
                    ?>
                    <div class="eb-form-field-holder">
                        <div class="eb-form-field-label">
                            <?php echo $field[ 'name' ] ?>
                            <?php
                            if( $field[ 'required' ] ) {
                                ?>
                                <div class="eb-form-field-required">*</div><?php
                            }
                            ?>
                        </div>
                        <div class="eb-form-field">
                            <?php
                            $fieldName = 'eb-field-' . $field[ 'id' ];
                            $required = $field[ 'required' ] ?? false;

                            if( $field[ 'type' ] === 'text' ) {

                                $type = $field[ 'options' ][ 'type' ] ?? 'text';
                                $max = $field[ 'options' ][ 'max' ] ?? '';

                                ?>
                                <input <?php echo ($required ? 'required' : ''); ?> id="<?php echo $fieldName; ?>"
                                       type="<?php echo $type; ?>" max="<?php echo $max; ?>" class="eb-form-input"/>
                                <?php
                            }
                            else if( $field[ 'type' ] === 'textarea' ) {
                                ?>
                                <textarea <?php echo ($required ? 'required' : ''); ?> id="<?php echo $fieldName; ?>"
                                          class="eb-form-textarea"></textarea>
                                <?php
                            }
                            else if( $field[ 'type' ] === 'checkbox' ) {
                                ?>
                                <label>
                                    <input type="checkbox" <?php echo ($required ? 'required' : ''); ?> id="<?php echo $fieldName; ?>" class="eb-form-checkbox" />
                                    <?php echo $field[ 'name' ] ?>
                                </label>
                                <?php
                            }
                            else if( $field[ 'type' ] === 'file' ) {
                                ?>
                                <input <?php echo ($required ? 'required' : ''); ?> id="<?php echo $fieldName; ?>"
                                       class="eb-form-file" type="file"/>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>

                <label>
                    <input type="checkbox" id="gdpr_store_year" class="eb-form-checkbox" />
                    Mijn gegevens mogen 12 maanden in portefeuille bewaard blijven
                </label>

                <div class="eb-application-button">
                    <button class="eb-application-submit">Solliciteren</button>
                </div>
            </form>
        </div>

        <script>
            window.onload = (event) => {
                jQuery(function($) {
                    let isSubmitting = false;

                    $(document).on('submit', ".eb-application-form-<?php echo $id; ?>", function(event) {
                        event.preventDefault();

                        if(isSubmitting) return;
                        isSubmitting = true;

                        var formData = new FormData();

                        <?php

                        if( is_singular('eb_vacancies') || is_singular('eb_vacancy_previews') ) {
                            ?>
                            formData.append('vacancy_id', '<?php echo get_post_meta($post->ID, '_eb_vacancy_id', true); ?>');
                            <?php
                        }
                        else if(isset($atts['id'])) {
                        ?>
                        formData.append('vacancy_id', '<?php echo get_post_meta($atts['id'], '_eb_vacancy_id', true); ?>');
                        <?php
                        }
                        ?>

                        formData.append('environment_id', '<?php echo get_post_meta($post->ID, '_eb_environment_id', true); ?>');
                        formData.append('gdpr_store_year', $(this).find('#gdpr_store_year:checked').length > 0);

                        <?php

                        foreach ( $fields as $field ) {

                            $fieldName = 'eb-field-' . $field[ 'id' ];
                            $required = $field[ 'required' ] ?? false;
                            $max = $field[ 'options' ][ 'max' ] ?? null;

                            if($max != null) {
                        ?>
                        if($(this).find('#<?php echo $fieldName; ?>').val().length > <?php echo $max; ?>) {
                            alert('<?php echo $field['name'] . ' max maximaal ' . $max . ' tekens zijn.'; ?>');
                            return;
                        }
                        <?php
                            }

                            if($required) {
                        ?>
                        if($(this).find('#<?php echo $fieldName; ?>').val() === '') {
                            alert('Niet alle vereiste velden zijn ingevuld.')
                            return;
                        }
                        <?php
                            }

                        if( $field[ 'type' ] === 'file' ) {
                        ?>
                        if($(this).find('#<?php echo $fieldName; ?>')[0].files.length > 0) {
                            formData.append('<?php echo 'field-' . $field[ 'id' ]; ?>', $(this).find('#<?php echo $fieldName; ?>')[0].files[0]);
                        }
                        <?php
                        }
                        else if( $field[ 'type' ] === 'checkbox' ) {
                        ?>
                        formData.append('<?php echo 'field-' . $field[ 'id' ]; ?>', $(this).find('#<?php echo $fieldName; ?>:checked').length > 0);
                        <?php
                        }
                        else {
                        ?>
                        formData.append('<?php echo 'field-' . $field[ 'id' ]; ?>', $(this).find('#<?php echo $fieldName; ?>').val());
                        <?php
                        }

                        }
                        ?>

                        $.ajax({
                            url : "/wp-json/employbrand-apply/v1/apply",
                            type: "POST",
                            data: formData,
                            enctype: 'multipart/form-data',
                            contentType: false,
                            processData: false
                        }).done(function() {

                            $(".eb-application-sent").css('display', 'block');
                            $(".eb-application-form-<?php echo $id; ?>").css('display', 'none');
                            $(".eb-application-form-intro").css('display', 'none');

                        }).fail(function() {
                            isSubmitting = false;
                            alert('Er ging helaas wat fout. Probeer het nogmaals, of neem contact met ons op via het contactformulier. Excuses voor het ongemak!');
                        });
                    });
                });
            };
        </script>

        <style>
            .eb-application-sent {
                display: none;
            }

            .eb-form-field-label {
                display: flex;
                flex-direction: row;
                margin-bottom: 4px;
            }

            .eb-form-field-required {
                margin-left: 4px;
                color: red;
            }

            .eb-form-field-holder {
                margin-bottom: 15px;
            }

            .eb-form-input {
                width: 100%;
            }

            .eb-application-button {
                margin-top: 15px;
                display: flex;
            }

            .eb-application-submit {
                margin-left: auto;
            }
        </style>
        <?php
    }

}
