<?php

namespace EmploybrandApply\Api;


use EmploybrandApply\Entity\WebhookEntity;


class WebhookApi extends AbstractApi
{

    /**
     * Get all webhooks
     *
     * @return ApiList
     */
    public function list(): ApiList
    {
        return new ApiList($this->client, 'webhooks', WebhookEntity::class);
    }


    /**
     * Create a new webhook
     *
     * @param array|object $data
     * @return WebhookEntity
     */
    public function create($data): WebhookEntity
    {
        return new WebhookEntity($this->postRequest('webhooks', $data));
    }


    /**
     * Delete a webhook
     *
     * @param $id
     * @return WebhookEntity
     */
    public function delete($id): WebhookEntity
    {
        return new WebhookEntity($this->deleteRequest('webhooks/' . $id));
    }


}
