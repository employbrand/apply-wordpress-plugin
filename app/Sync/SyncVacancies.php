<?php

namespace EmploybrandApply\Sync;


use EmploybrandApply\Exceptions\Http\NotFound;


class SyncVacancies extends SyncBase
{

    public function syncAll()
    {
        $vacancies = $this->employbrandApplyClient->vacancies()->list()->query(['all_environments' => 'true'])->all();

        $ids = [];

        foreach ( $vacancies as $vacancy ) {

            /*
             * Only show published vacancies
             */
            if( $vacancy->status !== 'published' )
                continue;

            $ids[] = $vacancy->id;

            /*
             * Skip when version matches
             */
            global $wpdb;
            $query = $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_version' AND meta_value = %s AND `post_id` IN (SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_eb_vacancy_id' AND `meta_value` = %s)", $vacancy->version, $vacancy->id);
            $exists = $wpdb->get_results($query);
            if( count($exists) !== 0 )
                continue;

            $this->syncSingle($vacancy->id);
        }

        /*
         * Delete old
         */
        $query = $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_eb_vacancy_id' AND meta_value NOT IN (" . implode(',', $ids) . ") ");
        $WpToDelete = $wpdb->get_results($query);
        foreach($WpToDelete as $toDelete) {
            wp_delete_post($toDelete->post_id);
        }
    }


    public function syncSingle($id)
    {
        global $wpdb;
        $wpId = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_eb_vacancy_id' AND `meta_value` = %s LIMIT 0,1", $id));
        $wpId = (count($wpId) == 0 ? null : $wpId[ 0 ]->post_id);

        try {
            $vacancy = $this->employbrandApplyClient->vacancies()->getById($id);

            /*
             * Only show published vacancies
             */
            if( $vacancy->status !== 'published' ) {
                if( $wpId != null ) {
                    wp_delete_post($wpId);
                }

                return;
            }


            if( $wpId != null ) {
                /*
                 * update
                 */


                /*
                 * Only update when there are changes
                 */
                $version = get_post_meta($wpId, '_version', true);
                if( $version == $vacancy->version )
                    return;


                wp_update_post([
                    'ID' => $wpId,
                    'post_title' => $vacancy->function,
                    'post_name' => $vacancy->slug,
                    'post_status' => 'publish'
                ]);
            }
            else {
                /*
                 * create
                 */

                $data = [
                    'post_title' => $vacancy->function,
                    'post_name' => $vacancy->slug,
                    'post_status' => 'publish',
                    'post_type' => 'eb_vacancies'
                ];
                $wpId = wp_insert_post($data);
            }

            /*
             * Define meta fields
             */
            $metaData = [
                '_eb_vacancy_id' => $vacancy->id,
                '_eb_environment_id' => $vacancy->environment->id,
                '_version' => $vacancy->version,
                '_publication_start_date' => $vacancy->publicationStartDate,
                '_publication_end_date' => $vacancy->publicationEndDate,
            ];

            /*
             * Load meta
             */
            $metaData = array_merge($metaData, $this->getCustomFields($vacancy));

            /*
             * Update meta
             */
            foreach ( $metaData as $key => $value ) {
                update_metadata('post', $wpId, $key, $value);
            }

        }
        catch ( NotFound $exception ) {

            if( $wpId != null ) {
                wp_delete_post($wpId);
            }
        }
    }

}
