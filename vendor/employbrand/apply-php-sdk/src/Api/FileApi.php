<?php

namespace EmploybrandApply\Api;


use EmploybrandApply\Entity\FileEntity;


class FileApi extends AbstractApi
{

    /**
     * Get files with pagination
     *
     * @return ApiPaginator
     */
    public function list(): ApiPaginator
    {
        return new ApiPaginator($this->client, 'files', FileEntity::class);
    }


    /**
     * Upload a file
     *
     * @param array|object $data
     * @return FileEntity
     */
    public function upload($fileName, $fileMime, $fileContent, $data): FileEntity
    {
        $multipart = [
            [
                'name' => 'file',
                'filename' => $fileName,
                'Mime-Type' => $fileMime,
                'contents' => $fileContent,
            ]
        ];

        foreach($data as $key => $value) {
            $multipart[] = [
                'name' => $key,
                'contents' => $value
            ];
        }

        $response = $this->client->makeAPICall($this->baseUri . 'files', 'POST', [
            'multipart' => $multipart
        ]);

        return new FileEntity($response);
    }


}
