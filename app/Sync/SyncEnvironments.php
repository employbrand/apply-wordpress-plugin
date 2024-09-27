<?php

namespace EmploybrandApply\Sync;


use EmploybrandApply\Exceptions\Http\NotFound;


class SyncEnvironments extends SyncBase
{

    public function syncAll()
    {
        $environments = $this->employbrandApplyClient->environments()->list()->all();

        $ids = [1];

        foreach ( $environments as $environment ) {

            /*
             * Do not sync main environment
             */
            if( $environment->environmentType == null )
                continue;

            $ids[] = $environment->id;

            /*
             * Skip when version matches
             */
            global $wpdb;
            $query = $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_version' AND meta_value = %s AND `post_id` IN (SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_eb_environment_id' AND `meta_value` = %s)", $environment->version, $environment->id);
            $exists = $wpdb->get_results($query);
            if( count($exists) !== 0 )
                continue;

            $this->syncSingle($environment->id);
        }

        /*
         * Delete old
         */
        $query = $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_eb_environment_id' AND meta_value NOT IN (" . implode(',', $ids) . ") ");
        $WpToDelete = $wpdb->get_results($query);
        foreach($WpToDelete as $toDelete) {
            wp_delete_post($toDelete->post_id);
        }
    }


    public function syncSingle($id)
    {
        global $wpdb;
        $wpId = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_eb_environment_id' AND `meta_value` = %s LIMIT 0,1", $id));
        $wpId = (count($wpId) == 0 ? null : $wpId[ 0 ]->post_id);

        try {
            $environment = $this->employbrandApplyClient->environments()->getById($id);

            /*
             * Do not sync main environment
             */
            if( $environment->environmentType == null )
                return;

            if( $wpId != null ) {
                /*
                 * update
                 */


                /*
                 * Only update when there are changes
                 */
                $version = get_post_meta($wpId, '_version', true);
                if( $version == $environment->version )
                    return;


                wp_update_post([
                    'ID' => $wpId,
                    'post_title' => $environment->name,
                    'post_name' => $environment->slug,
                    'post_status' => 'publish'
                ]);
            }
            else {
                /*
                 * create
                 */

                $data = [
                    'post_title' => $environment->name,
                    'post_name' => $environment->slug,
                    'post_status' => 'publish',
                    'post_type' => 'eb_et_' . $environment->environmentType->id
                ];
                $wpId = wp_insert_post($data);
            }

            /*
             * Define meta fields
             */
            $metaData = [
                '_eb_environment_id' => $environment->id,
                '_version' => $environment->version,
                '_address' => $environment->address,
                '_postal_code' => $environment->postalCode,
                '_city' => $environment->city,
                '_country' => $environment->country,
                '_lat' => $environment->lat,
                '_lng' => $environment->lng,
                '_country_name' => $environment->country_name,
                '_lat_lng' => $environment->lat_lng,
                '_location' => $environment->location
            ];

            /*
             * Load meta
             */
            $metaData = array_merge($metaData, $this->getCustomFields($environment));

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
